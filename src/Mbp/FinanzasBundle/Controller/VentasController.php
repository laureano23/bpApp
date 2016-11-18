<?php

namespace Mbp\FinanzasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Mbp\FinanzasBundle\Entity\Facturas;
use Mbp\FinanzasBundle\Entity\FacturaDetalle;
use Mbp\FinanzasBundle\Entity\Cobranzas;
use Mbp\FinanzasBundle\Entity\CobranzasDetalle;

class VentasController extends Controller
{
	/**
     * @Route("/cc/listar", name="mbp_cc_listar", options={"expose"=true})
     */
    public function listarccAction()
    {
    	$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpFinanzasBundle:Facturas');
		$res = $repo->listarFcCC(945);
				
        return new Response();
    }
		
    /**
     * @Route("/bancos/listar", name="mbp_finanzas_listaBancos", options={"expose"=true})
     */
    public function listaBancosAction()
    {
    	$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpFinanzasBundle:Bancos');
		$res = $repo->findAll();
		
		$resu = array();
		
		$i=0;
		foreach ($res as $reg) {
			$resu[$i]['id'] = $reg->getId();
			$resu[$i]['nombre'] = $reg->getNombre();
			$i++;
		}
		echo json_encode(array(
			'success' => true,
			'items' => $resu
		));
		
        return new Response();
    }
	
	/**
     * @Route("/CCClientes/listar", name="mbp_CCClientes_listar", options={"expose"=true})
     */
    public function listarAction()
    {	
    	$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpFinanzasBundle:FacturaDetalle');
		$repoFacturas = $em->getRepository('MbpFinanzasBundle:Facturas');
		$repoCobranzas = $em->getRepository('MbpFinanzasBundle:Cobranzas');
		$req = $this->getRequest();
		
		/* RECIBO PARAMETROS */
		$idCliente = $req->query->get('idCliente');
		
		/* CONSULTA POR FACTURACION */
		$resulFacturas = $repoFacturas->listaFacturasClientes($idCliente);
				
		//METODO AUXILIAR PARA CALCULAR EL SUBTOTAL DE LAS FACTURAS
		$auxFinanzas = $this->get('AuxiliarFinanzas');		
		$subTotal = $auxFinanzas->SubTotalFacturaAction($resulFacturas);
		
		/* CONSULTA POR PAGOS */
		$resulPagos = $repoCobranzas->listaPagosClientes($idCliente);		
		
		//METODO AUXILIAR PARA CALCULAR EL TOTAL DE LAS COBRANZAS
		$subTotalCobranza = $auxFinanzas->SubTotalCobranzaAction($resulPagos);
		
		/*RESPUESTA*/
		$resp = array();
		$i=0;
		
		if(!empty($resulFacturas)){
			foreach ($resulFacturas as $factura) {
				$resp[$i]['id'] = $factura->getId();
				$resp[$i]['emision'] = $factura->getFecha()->format('d-m-Y');
				$resp[$i]['concepto'] = $factura->getConcepto();
				$resp[$i]['vencimiento'] = $factura->getVencimiento()->format('d-m-Y'); 
				$resp[$i]['debe'] = $subTotal[$i];
				$resp[$i]['haber'] = '';
				$resp[$i]['tipo'] = $factura->getTipo();
				$i++;
			}	
		}
		
		$respPagos = array();
		$i=0;
		
		if(!empty($resulPagos)){
			foreach ($resulPagos as $pagos) {
				$respPagos['id'] = $pagos->getId(); 
				$respPagos['emision'] = $pagos->getEmision()->format('d-m-Y');
				$respPagos['concepto'] = 'PAGO';
				$respPagos['vencimiento'] = $pagos->getEmision()->format('d-m-Y'); 
				$respPagos['debe'] = '';
				$respPagos['haber'] = $subTotalCobranza[$i];
				array_push($resp, $respPagos);
				$i++;
			}	
		}		
		/*EOF RESPUESTA*/
		
		if(!empty($resp)){
			//ORDENO LA SALIDA DEL ARRAY PARA MOSTRARLA POR FECHA DE EMISION DEL COMPROBANTE
			usort($resp, array($this, 'ordenar'));
			
			//UTILIZO ESTA CLASE AUXILIAR PARA CALCULAR EL SALDO DE LA CUENTA
			$auxFinanzas->calculaSaldo($resp, $haber='haber', $debe='debe');	
		}		
		
		echo json_encode(array(
			'items' => $resp
		));
        return new Response();
    }
	
	function ordenar($a, $b) {
	    return strtotime($a['emision']) - strtotime($b['emision']);
	}
	
	/**
     * @Route("/CCClientes/guardarFc", name="mbp_CCClientes_guardarFc", options={"expose"=true})
     */	    
    public function guardarFcAction()
	{
		//RECIBO PARAMETROS
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$faele = $this->get('mbp.faele'); //FACTURA ELECTRONICA
		$auxFinanzas = $this->get('AuxiliarFinanzas');
		$response = new Response();
		
		$data = $req->request->get('data');
		$fcData = $req->request->get('fcData');
		$decodeData = json_decode($data);
		$decodefcData = json_decode($fcData);
		
		
		//CREO OBJETO FACTURA
		$factura = new Facturas();
		
		//CLIENTE
		$repoCliente = $em->getRepository('MbpClientesBundle:Cliente');
		$cliente = $repoCliente->find($decodefcData->idCliente);
		
		//ARTICULO
		$repoArticulo = $em->getRepository('MbpArticulosBundle:Articulos');
		$fechaFc = \DateTime::createFromFormat('d/m/Y', $decodefcData->fecha);
		
		$factura->setPtoVta($faele->ptoVta);
		$factura->setFecha($fechaFc);
		$factura->setConcepto($auxFinanzas->TipoDeComprobante($decodefcData->tipo));
		$factura->setVencimiento($fechaFc->add(new \DateInterval('P'.$cliente->getVencimientoFc().'D'))); //ES LA FECHA DE FC + LA CONDICION DE VENTA DEL CLIENTE
		$factura->setClienteId($cliente);
		$factura->setTipo($decodefcData->tipo);
		
		$netoGrabado = 0;
		$ivaLiquidado = 0;
		foreach($decodeData as $items){
			$detalleFc = new FacturaDetalle();
			$detalleFc->setDescripcion($items->descripcion);
			$detalleFc->setCantidad($items->cantidad);
			$detalleFc->setPrecio($items->precio);
			$articulo = $repoArticulo->findByCodigo($items->codigo);
			$detalleFc->setArticuloId($articulo[0]);			
			$factura->addFacturaDetalleId($detalleFc);
			$em->persist($detalleFc);
			
			//NETO GRABADO
			$netoGrabado += $items->cantidad * $items->precio;					
		}
		$ivaLiquidado = $netoGrabado * 0.21;
						
		$regfe['CbteTipo']=$decodefcData->tipo;
		$regfe['Concepto']=$faele->concepto;
		$regfe['DocTipo']=$faele->docTipo; //80=CUIT
		$regfe['DocNro']=$faele->docNro;
		$regfe['CbteDesde']=1; 	// nro de comprobante desde (para cuando es lote)
		$regfe['CbteHasta']=1;	// nro de comprobante hasta (para cuando es lote)
		$regfe['CbteFch']=\date('Ymd'); 	// fecha emision de factura
		$regfe['ImpNeto']=$netoGrabado;			// neto gravado
		$regfe['ImpTotConc']=0;			// no gravado
		$regfe['ImpIVA']=$ivaLiquidado;			// IVA liquidado
		$regfe['ImpTrib']=0;			// otros tributos
		$regfe['ImpOpEx']=0;			// operacion exentas
		$regfe['ImpTotal']=$netoGrabado + $ivaLiquidado;			// total de la factura. ImpNeto + ImpTotConc + ImpIVA + ImpTrib + ImpOpEx
		$regfe['FchServDesde']=null;	// solo concepto 2 o 3
		$regfe['FchServHasta']=null;	// solo concepto 2 o 3
		$regfe['FchVtoPago']=null;		// solo concepto 2 o 3
		$regfe['MonId']='PES'; 			// Id de moneda 'PES'
		$regfe['MonCotiz']=1;			// Cotizacion moneda. Solo exportacion
		
		// Comprobantes asociados (solo notas de crédito y débito):
		$regfeasoc['Tipo'] = 91; //91; //tipo 91|5			
		$regfeasoc['PtoVta'] = 1;
		$regfeasoc['Nro'] = 1;
		
		// Detalle de otros tributos
		$regfetrib['Id'] = 1; 			
		$regfetrib['Desc'] = 'impuesto';
		$regfetrib['BaseImp'] = 0;
		$regfetrib['Alic'] = 0; 
		$regfetrib['Importe'] = 0;
		 
		// Detalle de iva
		$regfeiva['Id'] = 5; 
		$regfeiva['BaseImp'] = $netoGrabado; 
		$regfeiva['Importe'] = $ivaLiquidado;
		
		$cae = $faele->generarFc($regfe, $regfeasoc, $regfetrib, $regfeiva);	//GENERO FC ELECTRONICA
		
		if($cae['success'] == FALSE){
			$response->setContent(json_encode($cae));
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR); 
			return $response;
		}
		
		
		$factura->setCae($cae['cae']['cae']);
		$factura->setVtoCae(new \DateTime($cae['cae']['fecha_vencimiento']));
		$response->setContent(json_encode($cae));
		$response->setStatusCode(Response::HTTP_OK); 
		
		try{
			$validador = $this->get('validator');
			
			$em->persist($factura);
			$em->flush();	
			return $response;
		}catch(\Exception $e){
			print_r($e->getMessage());
		}
		
		echo json_encode(array(
			'success' => true
		));
		
	}

	
	
	/**
     * @Route("/CCClientes/MailComprobante", name="mbp_CCClientes_MailComprobante", options={"expose"=true})
     */	
    public function MailComprobanteAction()
	{
		$this->generarFcAction();
		$req = $this->getRequest();
		
		/* DEFINO SI BUSCAR UNA FACTURA O COBRANZA */
		$tipoComprobante = $req->request->get('tipo');
		$ruta;
		$rootDir = $this->get('kernel')->getRootDir();
		
		$tipoComprobante == 'fa' ? $ruta = $rootDir.'/../src/Mbp/FinanzasBundle/Resources/public/pdf/factura.pdf' : $ruta =  $rootDir.'/../src/Mbp/FinanzasBundle/Resources/public/pdf/ReciboCobranzas.pdf';
		
		$mensaje = \Swift_Message::newInstance()
			->setSubject($req->request->get('asunto'))
			->setFrom('administracion@metalurgicabp.com.ar')
			->setTo($req->request->get('destinatario'))
			->setBody($req->request->get('mensaje'));
		
		$adjunto = \Swift_Attachment::fromPath($ruta);
		$mensaje->attach($adjunto);
			
		$this->get('mailer')->send($mensaje);
		
		return new Response();
	}
	
	/**
     * @Route("/Cobranza/NuevaCobranza", name="mbp_Cobranza_NuevaCobranza", options={"expose"=true})
     */	
    public function NuevaCobranzaAction()
	{
		$req = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		$data = $req->request->get('data');
		$repoCliente = $em->getRepository('MbpClientesBundle:Cliente');
		$repoTipoPago = $em->getRepository('MbpFinanzasBundle:FormasPago');
		
		$decodeData = json_decode($data);
		$size = count($decodeData);
		
		$cobranza = new Cobranzas();		
		$cobranza->setEmision(\DateTime::createFromFormat('d/m/Y', $decodeData[$size-1]->emisionFecha));
		$cliente = $repoCliente->find($decodeData[$size-1]->idCliente);
		$cobranza->setClienteId($cliente);
		$cobranza->setPtoVenta((int)$decodeData[$size-1]->ptoVta);
		$cobranza->setNumRecibo((int)$decodeData[$size-1]->reciboNum);
		
		for($i=0; $i < $size - 1; $i++){
			$detalleCob = new CobranzasDetalle();
			$formaPago = $repoTipoPago->findByDescripcion($decodeData[$i]->formaPago);
			
			$detalleCob->setImporte($decodeData[$i]->importe);
			$detalleCob->setNumero($decodeData[$i]->numero);
			$detalleCob->setBanco($decodeData[$i]->banco);
			$detalleCob->setImporte($decodeData[$i]->importe);
			$detalleCob->setVencimiento(\DateTime::createFromFormat('d/m/Y', $decodeData[$i]->diferido));
			$detalleCob->setFormaPagoId($formaPago[0]);
			
			$cobranza->addCobranzaDetalleId($detalleCob);
		}
		$em->persist($cobranza);		
		$em->flush();
		
		echo json_encode(array(
			'success'=>true
		));
		
		return new Response();
	}

	/**
     * @Route("/adm/CCClientes/EliminarComprobante", name="mbp_CCClientes_EliminarComprobante", options={"expose"=true})
     */	
    public function EliminarComprobanteAction()
	{
		$req = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		
		$data = $req->request->get('data');
		$decData = json_decode($data);
		
		$repo=0;
		if($decData->tipo == 'cobranza'){
			$repo = $em->getRepository('MbpFinanzasBundle:Cobranzas');
		}else{
			$repo = $em->getRepository('MbpFinanzasBundle:Facturas');
		}
		
		$record = $repo->find($decData->id);
		$em->remove($record);
		$em->flush();
		
		echo json_encode(array(
			'success' => true,
		));
		return new Response();
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
}





















