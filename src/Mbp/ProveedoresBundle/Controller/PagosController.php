<?php

namespace Mbp\ProveedoresBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Mbp\ProveedoresBundle\Entity\Proveedor;
use Mbp\ProveedoresBundle\Entity\Factura;
use Mbp\ProveedoresBundle\Entity\Pago;
use Mbp\ProveedoresBundle\Entity\OrdenPago;
use Mbp\ProveedoresBundle\Entity\TransaccionOPFC;
use Mbp\FinanzasBundle\Entity\MovimientosBancos;
use Mbp\FinanzasBundle\Entity\DetalleMovimientosBancos;
use Mbp\FinanzasBundle\Entity\FormasPago;
use Mbp\FinanzasBundle\Entity\ConceptosBanco;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class PagosController extends Controller
{
    /**
     * @Route("/proveedores/pagos/verificarRetencion", name="mbp_proveedores_verificarRetencion", options={"expose"=true})
     */
    public function verificarRetencion()
    {
    	$em = $this->getDoctrine()->getManager();
    	$req = $this->getRequest();
		$repoProv = $em->getRepository('MbpProveedoresBundle:Proveedor');
		$idProv = $req->request->get('idProv');
		$imputado = $req->request->get('totalImputado');
		$response = new Response;
		
		try{
			$proveedor = $repoProv->find($idProv);
			$repoFinanzas = $em->getRepository('MbpFinanzasBundle:ParametrosFinanzas');
			$parametrosFinanzas = $repoFinanzas->find(1);			
			$iibbService = $this->get('ServiceIIBB');	//SERVICIO PARA ALICUOTAS DE IIBB
			$iibbService->setOpts($proveedor->getCuit());
			$alicuotaRetencion = $iibbService->getAlicuotaRetencion();
			
			$resp = array();
			if($proveedor->getProvincia()->getId() == $parametrosFinanzas->getProvincia()->getId()
			 && $alicuotaRetencion > 0
			 && $parametrosFinanzas->getTopeRetencionIIBB() <= $imputado){
				$retencion=0;
				$resp['success'] = true;
				$resp['aplicaRetencion'] = true;
				
				if($imputado){
					$retencion = $imputado * $alicuotaRetencion / 100;
					$resp['retencion'] = number_format($retencion, 2);
				}
				
				return $response->setContent(json_encode($resp));			
			}else{				
				return $response->setContent(json_encode(array('success' => false, 'aplicaRetencion' => true, 'importe' => 0)));
			}
			
		}catch(\Exception $e){
			//throw $e;
			$response->setContent(
				json_encode(array(
					'success' => false,
					'msg' => $e->getMessage()
				))
			);
			
			return $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}
	
	/**
     * @Route("/proveedores/pagos/nuevoPago", name="mbp_proveedores_nuevoPago", options={"expose"=true})
     */
    public function nuevoPagoAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$req = $this->getRequest();
		$data = $req->request->get('data');
		$idOP = $req->request->get('idOP');
		$idProv = $req->request->get('idProv');
		$fcImputar = $req->request->get('fcImputar'); 
		$decData = json_decode($data);
		$fcImputarDec = json_decode($fcImputar);
		$repoTipoPago = $em->getRepository('MbpFinanzasBundle:FormasPagos');
		$repoBancos = $em->getRepository('MbpFinanzasBundle:Bancos');
		$repoProv = $em->getRepository('MbpProveedoresBundle:Proveedor');
		$repoFc = $em->getRepository('MbpProveedoresBundle:Factura');
		$repoOP = $em->getRepository('MbpProveedoresBundle:OrdenPago');
		$repoTrans = $em->getRepository('MbpProveedoresBundle:TransaccionOPFC');
		$response = new Response;
		
		try{
			$proveedor = $repoProv->find($idProv); //PROVEEDOR ASOCIADO
			$ordenPago = 0;
			if($idOP == 0){
				$ordenPago = new OrdenPago(); //CREO UNA NUEVA ORDEN DE PAGO
				$ordenPago->setEmision(new \DateTime());
				$ordenPago->setProveedorId($proveedor);	
				
				
				foreach ($decData as $rec) {
					if($rec->retencionIIBB == true) continue;
					$tipoPago = $repoTipoPago->findByDescripcion($rec->formaPago);
					$pago = new Pago(); //NUEVO DETALLE DE PAGO
					$pago->setEmision(new \DateTime());
					$pago->setNumero($rec->numero);
					$pago->setImporte($rec->importe);
					empty($rec->diferido) ? $pago->setDiferido(new \DateTime) : $pago->setDiferido(\DateTime::createFromFormat('d/m/Y', $rec->diferido));
					$pago->setBanco($rec->banco);
					empty($tipoPago) ? "" : $pago->setIdFormaPagos($tipoPago[0]);
					
					//SI ES UN CHEQUE PROPIO DEBO REGISTRAR LA OPERACION COMO MOVIMIENTO BANCARIO
					if($rec->conceptoBancario == true){
						$banco = $repoBancos->findByNombre($rec->banco);
						$pago->setBancoChequePropioId($banco[0]);
						
						$this->NuevoChequePropio($rec, $proveedor);
					}
					
											
					$ordenPago->addPagoDetalleId($pago);	
				}
			}else{
				$ordenPago = $repoOP->find($idOP);
			}


			
			//FACTURAS A IMPUTAR
			$factura;
			$transOpFc;	//OBEJTO QUE GUARDA EL MONTO APLICADO A CADA FC EN UN MOMENTO DADO
			$acumImputado=0;
			$valorAplicado=0;
			foreach ($fcImputarDec as $fc) {
				$factura = $repoFc->find($fc->id);	
				$valorAplicado += $fc->aplicar;

				//VALIDAR QUE NO SE IMPUTE MAS DEL TOTAL DE UNA FACTURA
				$imputaciones = $repoTrans->selectSumImputacionByFc($factura);
				$saldoImputar = $factura->getTotalFc() - $imputaciones['total'];

				if($fc->aplicar > $saldoImputar){
					throw new \Exception("Esta fc tiene un saldo menor al imputado", 1);					
				}
				//FIN DE LA VALIDACION

				$factura->setImputado($factura->getImputado() + $fc->aplicar);	
				$acumImputado += $factura->getImputado();
			}
			
			//CALCULO DE RETENCION
			$retencion = $this->calculoRentecion($valorAplicado, $proveedor);
			if($retencion > 0){
				$pago = new Pago;
				$tipoPago = $repoTipoPago->findOneByRetencionIIBB(true);
				$pago->setIdFormaPago($tipoPago);
				$pago->setEmision(new \DateTime);
				$pago->setImporte($retencion);
				$ordenPago->addPagoDetalleId($pago);
			}
			
				
			$em->persist($ordenPago);
			
			foreach ($fcImputarDec as $fc) {
				$factura = $repoFc->find($fc->id);
				$transOpFc = new TransaccionOPFC();	//NUEVO OBJETO
				$transOpFc->setFacturaImputada($factura);
				$transOpFc->setAplicado($fc->aplicar);				
				$transOpFc->setOrdenPagoImputada($ordenPago);
				$ordenPago->addFacturasImputada($transOpFc);
				$em->persist($transOpFc);				
			}		
			
			$em->flush();
			
			$response->setContent(
				json_encode(array(
					'success' => true,
					'idOrdenPago' => $ordenPago->getId()
				))
			);
			
			return $response;
		}catch(\Exception $e){
			$response->setContent(
				json_encode(array(
					'success' => false,
					'msg' => $e->getMessage()
				))
			);
			
			return $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
		}
    }

	private function calculoRentecion($acumImputado, $proveedor)
	{
		if($acumImputado == 0) return;
		
		$em = $this->getDoctrine()->getManager();
		$importe = 0;
		//CONSULTA RETENCION DE IIBB
		$repoFinanzas = $em->getRepository('MbpFinanzasBundle:ParametrosFinanzas');
		$parametrosFinanzas = $repoFinanzas->find(1);
		$iibbService = $this->get('ServiceIIBB');	//SERVICIO PARA ALICUOTAS DE IIBB
		$iibbService->setOpts($proveedor->getCuit());
		$alicuotaRetencion = $iibbService->getAlicuotaRetencion();
		
		if($proveedor->getProvincia()->getId() == $parametrosFinanzas->getProvincia()->getId()
			&& $alicuotaRetencion > 0
			&& $parametrosFinanzas->getTopeRetencionIIBB() <= $acumImputado){
			$importe = $acumImputado * $alicuotaRetencion / 100;
			return number_format($importe, 2);;			
		}
		return $importe;
	}

	private function NuevoChequePropio($data, $proveedor)
	{
		$em = $this->getDoctrine()->getManager();
		$repoBancos = $em->getRepository('MbpFinanzasBundle:Bancos');
		$repoConceptos = $em->getRepository('MbpFinanzasBundle:ConceptosBanco');
		
		$banco = $repoBancos->findByNombre($data->banco);
		$conceptoMov = $repoConceptos->find($data->conceptoBancario);
		
		$movBancario = new MovimientosBancos;
		$movBancario->setFechaMovimiento(new \DateTime);
		$movBancario->setBanco($banco[0]);
		$movBancario->setConceptoMovimiento($conceptoMov);
		
		$detalleMov = new DetalleMovimientosBancos;
		$detalleMov->setNumComprobante($data->numero);
		$detalleMov->setFechaDiferida(\DateTime::createFromFormat('d/m/Y', $data->diferido));
		$detalleMov->setImporte($data->importe);
		$detalleMov->setProveedorId($proveedor);
		
		
		$movBancario->addDetallesMovimiento($detalleMov);
		
		$em->persist($movBancario);
		$em->flush();
		
		return;
	}
}






















