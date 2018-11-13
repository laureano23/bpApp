<?php

namespace Mbp\FinanzasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Mbp\FinanzasBundle\Entity\Facturas;
use Mbp\FinanzasBundle\Entity\FacturaDetalle;
use Mbp\FinanzasBundle\Entity\CCClientes;
use Mbp\FinanzasBundle\Entity\TipoComprobante;
use Mbp\FinanzasBundle\Clases\Facturacion\FacturaA;
use Mbp\FinanzasBundle\Clases\Facturacion\NotaCreditoA;
use Mbp\FinanzasBundle\Clases\Facturacion\NotaDebitoA;

class VentasController extends Controller
{	
	


	/**
     * @Route("/CCClientes/calcularPercepcion", name="mbp_CCClientes_calcularPercepcion", options={"expose"=true})
     */	    
    public function calcularPercepcion()
	{
		//RECIBO PARAMETROS
		$em = $this->getDoctrine()->getManager();
		$response=new Response;
		$req = $this->getRequest();
		$response = new Response;
		$netoGrabado = $req->request->get('subTotal');
		$idCliente = $req->request->get('clienteId');
		//PARAMETROS FINANCIEROS
		$repoFinanzas = $em->getRepository('MbpFinanzasBundle:ParametrosFinanzas');
		
		try{
			$parametrosFinanzas = $repoFinanzas->find(1);
			$percepcionIIBB=0;
			$alicuotaPercepcion=0;
			
			$repoCliente = $em->getRepository('MbpClientesBundle:Cliente');
			$cliente = $repoCliente->find($idCliente);
			if($cliente->getProvincia() == NULL){
				throw new \Exception("El cliente debe tener cargados localidad y provincia para calcular IIBB", 1);				
			}elseif($cliente->getDepartamento()->getProvinciaId()->getId() == $parametrosFinanzas->getProvincia()->getId()){
				$iibbService = $this->get('ServiceIIBB');	//SERVICIO PARA ALICUOTAS DE IIBB
				$iibbService->setOpts($cliente->getCuit());
				$alicuotaPercepcion = $iibbService->getAlicuotaPercepcion();
				
				if($alicuotaPercepcion > 0
					&& $netoGrabado > $parametrosFinanzas->getTopePercepcionIIBB()
					&& $cliente->getNoAplicaPercepcion() == false){
					$percepcionIIBB = $netoGrabado * $alicuotaPercepcion / 100; 
					$percepcionIIBB = number_format($percepcionIIBB, 2, ".", "");
				}			
			}
			
			return $response->setContent(json_encode(array('success'=>true, 'percepcion'=>$percepcionIIBB)));
		}catch(\Exception $e){
			return $response->setContent(json_encode(array('success'=>false, 'msg'=>$e->getMessage())));
		}
		
	}

	/**
     * @Route("/CCClientes/recuperarComp", name="mbp_CCClientes_recuperarComp", options={"expose"=true})
     */	    
    public function recuperarComp()
	{

		$response = new Response;

		$faele = $this->get('mbp.faele'); //FACTURA ELECTRONICA			
		
		$cae=$faele->consultarCaeEmitido(1, 5034, 2);

		print_r($cae);
		$dig=$faele->digitoVerificador(1, $cae->FECompConsultarResult->ResultGet->CodAutorizacion, $cae->FECompConsultarResult->ResultGet->FchVto);
		print_r('dig verificador: '.$dig);
		return $response;
	}


	/**
     * @Route("/CCClientes/listarTiposComprobantes", name="mbp_CCClientes_listarTiposComprobantes", options={"expose"=true})
     */
    public function listarTiposComprobantes()
    {
    	$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpFinanzasBundle:TipoComprobante');
		$response = new Response;
		
		try{
			$res = $repo->createQueryBuilder('t')
				->select('')
				->getQuery()
				->getArrayResult();
			
			
		return $response->setContent(json_encode(array('success' => true, 'data' => $res)));	
				
		}catch(\Exception $e){
			$response->setContent(json_encode(array("success"=>false, "msg"=>$e->getMessage())));
			return $response;
		}
    }
			
	/**
     * @Route("/CCClientes/listar", name="mbp_CCClientes_listar", options={"expose"=true})
     */
    public function listarAction()
    {	
    	$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpFinanzasBundle:CCClientes');
		
		$req = $this->getRequest();
		$idCliente = $req->query->get('idCliente');
		
		$resp = $repo->listarCCClientes($idCliente);
		
		echo json_encode(array(
			'items' => $resp
		));
        return new Response();
    }
	
	function ordenar($a, $b) {
	    return ($b['emisionCalc'] > $a['emisionCalc']) ? -1 : 1;
	}

	/**
     * @Route("/CCClientes/crearComprobanteVenta", name="mbp_CCClientes_crearComprobanteVenta", options={"expose"=true})
     */	    
    public function crearComprobanteVenta()
	{
		//RECIBO PARAMETROS
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$response = new Response;
		$repoFc = $em->getRepository('MbpFinanzasBundle:Facturas');
		$repoCliente = $em->getRepository('MbpClientesBundle:Cliente');
		$repoFinanzas = $em->getRepository('MbpFinanzasBundle:ParametrosFinanzas');

		try{
			$faele = $this->get('mbp.faele'); //FACTURA ELECTRONICA
			$data = $req->request->get('data');
			$fcData = $req->request->get('fcData');
			$descuento = $req->request->get('descuentoFijo');
			$percepcionIIBB = $req->request->get('percepcion');
			$decodeData = json_decode($data);
			$decodefcData = json_decode($fcData);
			

			$comprobante=null;
			$idCbte=null;
			switch($decodefcData->tipo){
				case 1:
					$comprobante=new FacturaA(
						$decodefcData->tipoCambio, $decodefcData->moneda,
						$decodefcData->idCliente, $decodeData, $descuento, $percepcionIIBB,
						$faele, $repoFc, $repoCliente, null
					);
					$idCbte=$repoFc->crearComprobante($comprobante);
					break;
				case 2:
					$fcsAsociadas=explode(',', $decodefcData->compAsociados);
					$comprobante=new NotaCreditoA(
						$decodefcData->tipoCambio, $decodefcData->moneda,
						$decodefcData->idCliente, $decodeData, $descuento, $percepcionIIBB,
						$faele, $repoFc, $repoCliente, $fcsAsociadas
					);
					$idCbte=$repoFc->crearComprobante($comprobante);
					break;
				case 4:
					$comprobante=new NotaDebitoA(
						$decodefcData->tipoCambio, $decodefcData->moneda,
						$decodefcData->idCliente, $decodeData, $descuento, $percepcionIIBB,
						$faele, $repoFc, $repoCliente, null
					);
					$idCbte=$repoFc->crearComprobante($comprobante);
					break;
			}
			
			
			$cae['cae']=array(
				'cae'=>$comprobante->getCAE(),
				'fecha_vencimiento'=>$comprobante->getFechaVencimiento(),
				'success'=>true
			);

			$cae["idFc"] = $idCbte;
			$cae["success"]=true;
			$cae["digitoVerificador"]=$comprobante->getDigitoVerificador();
			$response=new Response;
			$response->setContent(json_encode($cae));
			
			return $response;
				
		}catch(\Exception $e){
			$logger=$this->get('monolog.logger.facturacion');
			$logger->err($e->getMessage());
			$msg=json_decode($e->getMessage());
			if($msg==null){
				$msg=$e->getMessage();
			}
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			$response->setContent(json_encode(array("success"=>false, "msg"=>$msg, 'code' => $e->getCode())));
			return $response;
		}
	}
	
	
	
	
	/**
     * @Route("/CCClientes/MailComprobante", name="mbp_CCClientes_MailComprobante", options={"expose"=true})
     */	
    public function MailComprobanteAction()
	{
		$req = $this->getRequest();
		$kernel = $this->get('kernel');
		$response = new Response;
		
		try{
			/* DEFINO SI BUSCAR UNA FACTURA O COBRANZA */
			$idFactura = $req->request->get('idFactura');
			
			$ruta="";
			if($idFactura > 0){
				$ruta = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/pdf/').'factura.pdf';
			}else{
				$ruta = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/pdf/').'ReciboCobranzas.pdf';
			}
			
			$mensaje = \Swift_Message::newInstance()
				->setSubject($req->request->get('asunto'))
				->setFrom('administracion@metalurgicabp.com.ar')
				->setTo($req->request->get('destinatario'))
				->setBody($req->request->get('mensaje'));
			
			$adjunto = \Swift_Attachment::fromPath($ruta);
			$mensaje->attach($adjunto);
				
			$this->get('mailer')->send($mensaje);
			
			return $response->setContent(
				json_encode(array('success' => true))
			);
		}catch(\Exception $e){
			$response->setStatusCode($response::HTTP_INTERNAL_SERVER_ERROR);
			return $response->setContent(
				json_encode(array('success' => false, 'msg' => $e->getMessage()))
				);
		}
		
			
	}	
	

	/**
     * @Route("/adm/CCClientes/EliminarComprobante", name="mbp_CCClientes_EliminarComprobante", options={"expose"=true})
     */	
    public function EliminarComprobanteAction()
	{
		$req = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		$response = new Response();
		
		$idCobranza = $req->request->get('idCobranza');
		$idBalance = $req->request->get('idBalance');
		
		$repo = $em->getRepository('MbpFinanzasBundle:Cobranzas');
		$repoFc = $em->getRepository('MbpFinanzasBundle:Facturas');
		$record="";
			
		try{
			if($idBalance > 0){
				$record = $repoFc->find($idBalance);
			}else{
				$record = $repo->find($idCobranza);
				if(empty($record)){
					throw new \Exception("No se encontró el registro", 1);			
				}	
				
				//SI HAY INTERESES ASOCIADOS LOS BORRAMOS
				$repoIntereses = $em->getRepository('MbpFinanzasBundle:InteresesResarcitorios');
				$intereses = $repoIntereses->findByCobranzaId($record);
				
				foreach ($intereses as $i) {
					$em->remove($i);
				}

				//si la cobranza tiene algun detalle asociado a un mov. bancario x ej deposito de cheque no se puede borrar
				$detallesCobranza=$record->getCobranzaDetalleId();
				foreach ($detallesCobranza as $det) {
					if($det->getEstado() != 0){ //Si el estado es distinto de cero es poque el cheque fue depositado o entregado a terceros y no puede borrarse la operacion
						return $response->setContent(json_encode(array("success"=>false, "msg"=>"La cobranza tiene un movimiento bancario asociado")));
					}					
				}
			}				
			
			$em->remove($record);
			$em->flush();
			
			$response->setContent(json_encode(array("success" => true)));
			return $response;
			
		}catch(\Exception $e){
			$response->setContent(json_encode(array("success" => false, "msg" => $e->getMessage())));
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			return $response;
		}
	}
	
	/**
     * @Route("/adm/CCClientes/ValidarRecibo", name="mbp_CCClientes_ValidarRecibo", options={"expose"=true})
     */	
    public function ValidarReciboAction()
	{
		$req = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		$numRecibo = $req->request->get('reciboNum');
		$ptoVta = $req->request->get('ptoVta');
		
		$repoCobranzas = $em->getRepository('MbpFinanzasBundle:Cobranzas');
		$query = $repoCobranzas->createQueryBuilder('c')
					->select('')
					->join('c.clienteId', 'cliente')
					->where('c.ptoVenta =:puntoVenta')
					->andWhere('c.numRecibo =:numRecibo')
					->setParameter('puntoVenta', $ptoVta)
					->setParameter('numRecibo', $numRecibo)					
					->getQuery();
		$resu = $query->getResult();
		
		if(empty($resu)){
			echo json_encode(array(
				'success' => true,
			));
			return new Response();
		}
		
		
		echo json_encode(array(
			'success' => false,
			'msg' => 'Este recibo pertenece al cliente '.$resu[0]->getClienteId()->getRsocial()
		));
		return new Response();
	}
	
	/**
     * @Route("/adm/CCClientes/CrearBalance", name="mbp_CCClientes_CrearBalance", options={"expose"=true})
     */	
    public function CrearBalance()
	{
		
		//RECIBO PARAMETROS
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$response = new Response;
		$repoTipos = $em->getRepository('MbpFinanzasBundle:TipoComprobante');
		$repoCliente = $em->getRepository('MbpClientesBundle:Cliente');

		try{
			$neto = $req->request->get('neto');
			$obs = $req->request->get('obs');
			$idCliente = $req->request->get('idCliente');	
			$fechaEmision=\DateTime::createFromFormat("d/m/Y", $req->request->get('fecha'));
		
			//CREO OBJETO FACTURA
			$factura = new Facturas;
			//Creo obj cc
			$cc = new CCClientes;
			
			$total;
			if($neto < 0){
				$total = $neto * -1;
			}else{
				$total = $neto;
			}
			
			$factura->setTotal($total);
			$factura->setFecha($fechaEmision);
			$factura->setVencimiento($fechaEmision);
			$factura->setConcepto("BALANCE");
			$factura->setPtoVta(0);
			$factura->setFcNro(0);
			$factura->setCae(0);
			$factura->setVtoCae($fechaEmision);
			$factura->setRSocial("BALANCE");
			$factura->setDomicilio("BALANCE");
			$factura->setCuit(0);
			$factura->setIvaCond("BALANCE");
			$factura->setDigitoVerificador(0);			
			
			
			$cliente = $repoCliente->find($idCliente);
			$factura->setClienteId($cliente);
			$factura->setTipoIva($cliente->getIva());
			
			
			$tipo = $repoTipos->findOneByEsBalance(true);
			if(empty($tipo)) throw new \Exception("No existe el tipo de comprobante balance en la BD", 1);
			$factura->setTipoId($tipo);
						
			//cc
			if($neto < 0){
				$cc->setHaber($factura->getTotal());	
			}else{
				$cc->setDebe($factura->getTotal());
			}
			$cc->setFechaEmision($factura->getFecha());
			$cc->setFechaVencimiento($factura->getVencimiento());
			$cc->setFacturaId($factura);
			$cc->setClienteId($cliente);
			
			$factura->setCcId($cc);
			
			$em->persist($factura);
			$em->flush();
			
			return $response->setContent(
				json_encode(array('success' => true))
			);
			
		}catch(\Exception $e){
			throw $e;
			$response->setContent(json_encode(array("success" => false, "msg" => $e->getMessage())));
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			return $response;
		}
	}
	
	/**
     * @Route("/adm/listarPosicionesIva", name="mbp_finanzas_posicionesIva", options={"expose"=true})
     */	
    public function listarPosicionesIva()
	{
		$em = $this->getDoctrine()->getManager();
		$response = new Response;
		$repoIva = $em->getRepository('MbpFinanzasBundle:PosicionIVA');
		
		try{
			$res = $repoIva->createQueryBuilder('p')
				->select('')
				->getQuery()
				->getArrayResult();
			
			return $response->setContent(
				json_encode(array('success' => true, 'data' => $res))
			);
		}catch(\Exception $e){
			$response->setContent(json_encode(array("success" => false, "msg" => $e->getMessage())));
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			return $response;
		}
	}
}





















