<?php

namespace Mbp\FinanzasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Mbp\FinanzasBundle\Entity\Cobranzas;
use Mbp\FinanzasBundle\Entity\CobranzasDetalle;
use Mbp\FinanzasBundle\Entity\TransaccionCobranzaFactura;
use Mbp\FinanzasBundle\Entity\InteresesResarcitorios;
use Mbp\FinanzasBundle\Entity\CCClientes;

class CobranzasController extends Controller
{
    /**
     * @Route("/cobros/listarTipos", name="mbp_finanzas_listaTipos", options={"expose"=true})
     */
    public function listarTiposAction()
	{
		$em = $this->getDoctrine()->getEntityManager();		
		$repo = $em->getRepository('MbpFinanzasBundle:FormasPagos');
		$resu = $repo->createQueryBuilder('t')
			->select('t.id, t.descripcion, t.inactivo, t.retencionIIBB, t.retencionIVA21, t.chequeTerceros, t.esChequePropio, t.depositaEnCuenta, cb.concepto, cb.id as conceptoMov')
			->leftJoin('t.conceptoBancoId', 'cb')
			->where('t.inactivo = 0')
			->getQuery()
			->getArrayResult();
		
		
		echo json_encode(array(
			'items' => $resu
		));
		return new Response();
	}
	
	/**
     * @Route("/Cobranza/NuevaCobranza", name="mbp_Cobranza_NuevaCobranza", options={"expose"=true})
     */	
    public function NuevaCobranzaAction()
	{
		$req = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		$response = new Response;
		
		try{
			$data = $req->request->get('data');
			$fcImputadas = json_decode($req->request->get('aplicados'));
			$idCliente = json_decode($req->request->get('cliente'));
			$datosRecibo = json_decode($req->request->get('datosRecibo'));
			$repoCliente = $em->getRepository('MbpClientesBundle:Cliente');
			$repoTipoPago = $em->getRepository('MbpFinanzasBundle:FormasPagos');
			$repoFacturas = $em->getRepository('MbpFinanzasBundle:Facturas');
			$repoTransaccion = $em->getRepository('MbpFinanzasBundle:TransaccionCobranzaFactura');
			$repoCuentas = $em->getRepository('MbpFinanzasBundle:CuentasBancarias');
			
			$decodeData = json_decode($data);			
					
			$cobranza = new Cobranzas();		
			$cobranza->setEmision(new \DateTime);
			$cobranza->setFechaRecibo(\DateTime::createFromFormat('d/m/Y', $datosRecibo->fechaRecibo));
			$cliente = $repoCliente->find($idCliente);
			$cobranza->setClienteId($cliente);
			$cobranza->setPtoVenta((int)$datosRecibo->ptoVta);
			

			if($datosRecibo->paraPresupuesto=="on"){
				$cobranza->setParaPresupuesto(true);
				$cobranza->setNumRecibo(0);
			}else{
				$cobranza->setNumRecibo((int)$datosRecibo->reciboNum);
			}
			
			$importeTotal = 0;
			foreach($decodeData as $data){
				$detalleCob = new CobranzasDetalle();
				$formaPago = $repoTipoPago->findOneByDescripcion($data->formaPago);
				
				if($formaPago->getConceptoBancoId()){
					$cuenta = $repoCuentas->find($data->cuenta);
					
					if(!$cuenta) throw new \Exception("Debe asignar una cuenta bancaria al concepto ".$formaPago->getDescripcion(), 1);
					$detalleCob->setCuentaId($cuenta);
				}
				
				$detalleCob->setImporte($data->importe);
				$detalleCob->setNumero($data->numero);
				$detalleCob->setBanco($data->banco);
				$detalleCob->setVencimiento(\DateTime::createFromFormat('d/m/Y', $data->diferido));
				$detalleCob->setFormaPagoId($formaPago);
				
				$cobranza->addCobranzaDetalleId($detalleCob);
				$importeTotal += $detalleCob->getImporte();
			}
			
			$cobranza->setImporte($importeTotal);
			
			if(count($cobranza->getCobranzaDetalleId()) == 0) throw new \Exception("No es posible guardar una cobranza vacía", 1);
			
			//cc
			$cc = new CCClientes;
			$cc->setHaber($cobranza->getImporte());
			$cc->setFechaEmision($cobranza->getFechaRecibo());
			$cc->setFechaVencimiento($cobranza->getFechaRecibo());
			$cc->setCobranzaId($cobranza);
			$cc->setClienteId($cliente);
			
			$cobranza->setCcId($cc);
			
			$em->persist($cobranza);
			
			
			//SI SE APLICARON FACTURAS A LA COBRANZA
			if(!empty($fcImputadas)){
				foreach ($fcImputadas as $fc) {
					$factura = $repoFacturas->find($fc->id);
					//VALIDO QUE EL IMPORTE A APLICAR NO SUPERE EL TOTAL DE LA FC
					$aplicado = $repoTransaccion->getTotalAplicadoFactura($fc->id);
					$restante = round($factura->getTotal()*$factura->getTipoCambio(), 2)  - $aplicado;
					
					$epsilon=0.0001; //para comprar flotantes se usa este metodo
					if(($fc->aplicar - $restante) > $epsilon){
						throw new \Exception("El máximo imputable a esta factura es ".$restante);						
					}
					
					$transaccion = new TransaccionCobranzaFactura;
					$transaccion->setAplicado($fc->aplicar);
					
					$transaccion->setFacturaImputada($factura);
					$transaccion->setCobranzaImputada($cobranza);
					
					$em->persist($transaccion);
				}
				
				//SI SE CALCULAN INTERESES RESARCITORIOS POR PAGO FUERA DE TERMINO
				if($cliente->getIntereses()){
					$serviceIntereses = $this->get('InteresesResarcitorios');
					//ARMAMOS LOS ARRAYS DE FCS Y VALORES				
					if(!$fcImputadas) throw new \Exception("No se pueden calcular intereses porque no hay facturas imputadas", 1);
					$facturas = array();
					$valores = array();
					
					foreach ($fcImputadas as $f) {
						$fc = $repoFacturas->find($f->id);
						$reg['monto'] = $fc->getTotal();
						$reg['cbteNum'] = $fc->getPtoVta()."-".$fc->getFcNro();
						$reg['vencimiento'] = $fc->getVencimiento();
						
						array_push($facturas, $reg);
					}
					
					foreach ($decodeData as $d) {
						$reg['monto'] = $d->importe;
						$reg['numero'] = $d->numero;
						$reg['banco'] = $d->banco;
						$reg['vencimiento'] = \DateTime::createFromFormat('d/m/Y', $data->diferido);
						
						array_push($valores, $reg);
					}
					
					$serviceIntereses->calcularIntereses($facturas, $valores, $cliente->getTasaInt());
					
					$intereses = $serviceIntereses->getIntereses();
					if(!empty($intereses)){// si existen intereses los guardamos en la bd
						foreach ($intereses as $int) {
							$interes = new InteresesResarcitorios;
							$interes->setCbte($int['comprobante']);
							$interes->setClienteId($cliente);
							$interes->setInteres($int['interes']);
							$interes->setMonto($int['monto']);
							$interes->setTasa($cliente->getTasaInt());
							$interes->setChequeNum($int['numero']);
							$interes->setBanco($int['banco']);
							$interes->setDiferidoValor($int['diferidoValor']);
							$interes->setCobranzaId($cobranza);
							
							$em->persist($interes);							
						}					
					}
				}
			}
			
				

			//exit;
			
			$em->flush();
		}catch(\Exception $e){
			//throw $e;
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			return $response->setContent(
				json_encode(array(
					'success'=>false,
					'msg' => $e->getMessage()
				))
			);
		}
			
		return $response->setContent(
			json_encode(array(
				'success'=>true
			))
		);
	}

	/**
     * @Route("/Cobranza/VerCobranza", name="mbp_Cobranza_VerCobranza", options={"expose"=true})
     */	
    public function VerCobranzaAction()
	{
		
	}
	
	/**
     * @Route("/Cobranza/ListarFcParaImputar", name="mbp_CCClientes_ListarFcParaImputar", options={"expose"=true})
     */	
    public function ListarFcParaImputarAction()
	{
		$req = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		$response = new Response;
		
		try{
			$idCliente = $req->request->get('idCliente');
			
			$repoFacturas = $em->getRepository('MbpFinanzasBundle:Facturas');
			$data = $repoFacturas->ListarFcParaImputar($idCliente);
			
			$response->setContent(
				json_encode(array('success' => true, 'items' => $data))
			);
			
			return $response;
		}catch(\Exception $e){
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			return $response->setContent(
				json_encode(array(
					'success'=>false,
					'msg' => $e->getMessage()
				))
			);
		}
	} 
}





















