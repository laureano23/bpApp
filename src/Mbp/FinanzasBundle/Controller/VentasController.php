<?php

namespace Mbp\FinanzasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Mbp\FinanzasBundle\Entity\Facturas;
use Mbp\FinanzasBundle\Entity\FacturaDetalle;

class VentasController extends Controller
{		
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
		$repoClientes = $em->getRepository('MbpClientesBundle:Cliente');
		$req = $this->getRequest();
		
		/* RECIBO PARAMETROS */
		$idCliente = $req->query->get('idCliente');

		$clienteObj = $repoClientes->find($idCliente);
		if($clienteObj->getCuentaCerrada() == 1){
			echo json_encode(array("success"=>false, "msg"=>"El cliente tiene la cuenta cerrada"));
			return new Response;
		}
		
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
				$resp[$i]['emisionCalc'] = $factura->getFecha();//SOLO PARA ORDENAMIENTO POSTERIOR
				$resp[$i]['emision'] = $factura->getFecha()->format('d-m-Y');
				$resp[$i]['concepto'] = $factura->getConcepto()." N° ".$factura->getfcNro();
				$resp[$i]['vencimiento'] = $factura->getVencimiento()->format('d-m-Y'); 
				$resp[$i]['debe'] = $factura->getTipo() == 1 ? $subTotal[$i] : "";
				$resp[$i]['haber'] = $factura->getTipo() != 1 ? $subTotal[$i] : "";
				$resp[$i]['tipo'] = $factura->getTipo();
				$i++;
			}	
		}
		
		$respPagos = array();
		$i=0;
		
		if(!empty($resulPagos)){
			foreach ($resulPagos as $pagos) {
				$respPagos['id'] = $pagos->getId(); 
				$respPagos['emisionCalc'] = $pagos->getEmision();//SOLO PARA ORDENAMIENTO POSTERIOR
				$respPagos['emision'] = $pagos->getEmision()->format('d-m-Y');
				$respPagos['concepto'] = 'PAGO N° '.$pagos->getId();
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
	    return ($b['emisionCalc'] > $a['emisionCalc']) ? -1 : 1;
	}
	
	/**
     * @Route("/CCClientes/guardarFc", name="mbp_CCClientes_guardarFc", options={"expose"=true})
     */	    
    public function guardarFcAction()
	{
		//RECIBO PARAMETROS
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();

		try{
			$faele = $this->get('mbp.faele'); //FACTURA ELECTRONICA			
			
			$auxFinanzas = $this->get('AuxiliarFinanzas');
			$response = new Response();
			
			$data = $req->request->get('data');
			$fcData = $req->request->get('fcData');
			$decodeData = json_decode($data);
			$decodefcData = json_decode($fcData);
		
		
			//CREO OBJETO FACTURA
			$factura = new Facturas();
			$fechaFc = new \DateTime;
			$vencimiento = new \DateTime;
			
			
			//CLIENTE
			$repoCliente = $em->getRepository('MbpClientesBundle:Cliente');
			$cliente = $repoCliente->find($decodefcData->idCliente);
			if($cliente->getCuentaCerrada() == 1){
				echo json_encode(array("success"=>false, "msg"=>"Cuenta cerrada, no se puede realizar la operacion"));
				return new Response;
			}

    					
			//ARTICULO
			$repoArticulo = $em->getRepository('MbpArticulosBundle:Articulos');
			
			
			$factura->setPtoVta($faele->ptoVta);
			$factura->setFecha($fechaFc);
			$factura->setConcepto($auxFinanzas->TipoDeComprobante($decodefcData->tipo));
			$factura->setVencimiento($vencimiento->add(new \DateInterval('P'.$cliente->getVencimientoFc().'D'))); //ES LA FECHA DE FC + LA CONDICION DE VENTA DEL CLIENTE
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

			$ultimoComp = $faele->ultimoNroComp($decodefcData->tipo);

			//CONSULTA PERCEPCION DE IIBB
			$iibbService = $this->get('ServiceIIBB');	//SERVICIO PARA ALICUOTAS DE IIBB
			$iibbService->setOpts($cliente->getCuit());
			$alicuotaPercepcion = $iibbService->getAlicuotaPercepcion();			
			$percepcionIIBB=0;
			
			$repoFinanzas = $em->getRepository('MbpFinanzasBundle:ParametrosFinanzas');
			$parametrosFinanzas = $repoFinanzas->find(1);
			
			if($cliente->getLocalidad() == NULL || $cliente->getLocalidad()->getDepartamentoId()->getProvinciaId() == NULL){
				throw new \Exception("El cliente debe tener cargados localidad y provincia para calcular IIBB", 1);				
			}
			
			if($cliente->getLocalidad()->getDepartamentoId()->getProvinciaId()->getId() == $parametrosFinanzas->getProvincia()->getId() && $alicuotaPercepcion > 0){
				$percepcionIIBB = $netoGrabado * $alicuotaPercepcion / 100; 
				$percepcionIIBB = number_format($percepcionIIBB, 2);
			}	


			//REDONDEO IMPORTES A 2 DECIMALES
			$netoGrabado = number_format($netoGrabado, 2);
			$ivaLiquidado = number_format($ivaLiquidado, 2);

			$regfe['CbteTipo']=$decodefcData->tipo;
			$regfe['Concepto']=1;//ESTE DATO DEBE VENIR DEL CLIENTE 1=PRODUCTOS, 2=SERVICIOS, 3=PRODUCTOS Y SERVICIOS
			$regfe['DocTipo']=$faele->docTipo; //80=CUIT
			$regfe['DocNro']=$cliente->getCuit();
			$regfe['CbteDesde']= $ultimoComp['nro'] + 1;	// nro de comprobante desde (para cuando es lote)
			$regfe['CbteHasta']=$ultimoComp['nro'] + 1;	// nro de comprobante hasta (para cuando es lote)
			$regfe['CbteFch']=\date('Ymd'); 	// fecha emision de factura
			$regfe['ImpNeto']=$netoGrabado;			// neto gravado
			$regfe['ImpTotConc']=0;			// no gravado
			$regfe['ImpIVA']=$ivaLiquidado;			// IVA liquidado
			$regfe['ImpTrib']=$percepcionIIBB;			// otros tributos
			$regfe['ImpOpEx']=0;			// operacion exentas
			$regfe['ImpTotal']=$netoGrabado + $ivaLiquidado + $percepcionIIBB;			// total de la factura. ImpNeto + ImpTotConc + ImpIVA + ImpTrib + ImpOpEx
			$regfe['FchServDesde']=null;	// solo concepto 2 o 3
			$regfe['FchServHasta']=null;	// solo concepto 2 o 3
			$regfe['FchVtoPago']=null;		// solo concepto 2 o 3
			$regfe['MonId']='PES'; 			// Id de moneda 'PES'
			$regfe['MonCotiz']=1;			// Cotizacion moneda. Solo exportacion
			
			// Comprobantes asociados (solo notas de crédito y débito):
			$regfeasoc['Tipo'] = 91; //91; //tipo 91|5			
			$regfeasoc['PtoVta'] = 1;
			$regfeasoc['Nro'] = 0;
			
			// Detalle de otros tributos
			$regfetrib['Id'] = 2; 	//1: impuesto nacional, 2: imp. provincial, etc...		
			$regfetrib['Desc'] = 'impuesto IIBB';
			$regfetrib['BaseImp'] = $netoGrabado;
			$regfetrib['Alic'] = $alicuotaPercepcion; 
			$regfetrib['Importe'] = $percepcionIIBB;
			 
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

			//DATOS DEL CLIENTES
			$factura->setRSocial($cliente->getRSocial());
			$factura->setDomicilio($cliente->getDireccion());
			$factura->setLocalidad($cliente->getLocalidad()->getNombre());
			$factura->setCuit($cliente->getCuit());
			$factura->setIvaCond($cliente->getIva()->getPosicion());
			$factura->setCondVta($cliente->getCondVenta()); 
			$factura->setFcNro($ultimoComp['nro'] + 1);
			$factura->setPerIIBB($percepcionIIBB);
			$factura->setTotal($regfe['ImpTotal']);
			$factura->setIva21($ivaLiquidado);
			$factura->setporcentajeIIBB($alicuotaPercepcion);
			//$factura->setRtoNro(); COMPLETAR LOGICA PARA VINCULAR REMITO

			
			
				
			$em->persist($factura);
			$em->flush();	
			$cae["idFc"] = $factura->getId();
			$response->setContent(json_encode($cae));
			$response->setStatusCode(Response::HTTP_OK); 
			
			return $response;

		}catch(\Exception $e){
			throw $e;
			echo json_encode(array("success"=>false, "msg"=>$e->getMessage()));
			return new Response;
		}
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
     * @Route("/adm/CCClientes/EliminarComprobante", name="mbp_CCClientes_EliminarComprobante", options={"expose"=true})
     */	
    public function EliminarComprobanteAction()
	{
		$req = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		$response = new Response();
		
		$data = $req->request->get('data');
		$decData = json_decode($data);
		
		$repo = $em->getRepository('MbpFinanzasBundle:Cobranzas');
				
		try{
			$record = $repo->find($decData->id);
			if(empty($record)){
				throw new \Exception("No se encontró el registro", 1);			
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
}





















