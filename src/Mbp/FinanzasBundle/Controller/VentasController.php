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

class VentasController extends Controller
{	

	/**
     * @Route("/CCClientes/listarFacturasParaAsociar", name="mbp_CCClientes_listarFacturasParaAsociar", options={"expose"=true})
     */	    
    public function listarFacturasParaAsociar()
	{
		//RECIBO PARAMETROS
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$response = new Response;
		$idCliente = $req->request->get('idCliente');
		
		try{
			$repoFc = $em->getRepository('MbpFinanzasBundle:Facturas');

			$resp=$repoFc->listarFacturasParaAsociar($idCliente);
			
			return $response->setContent(json_encode(array('success'=>true, 'items'=>$resp)));
		}catch(\Exception $e){
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			return $response->setContent(json_encode(array('success'=>false, 'msg'=>$e->getMessage())));
		}
		
	}


	/**
     * @Route("/CCClientes/calcularPercepcion", name="mbp_CCClientes_calcularPercepcion", options={"expose"=true})
     */	    
    public function calcularPercepcion()
	{
		//RECIBO PARAMETROS
		$em = $this->getDoctrine()->getManager();
		$response=new Response;
		$req = $this->getRequest();
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
			$fcsAsociadas=explode(',', $decodefcData->compAsociados);

			//\print_r($decodefcData->idCliente);
			$factura_a=new FacturaA(
				$decodefcData->tipoCambio, $decodefcData->moneda,
				$decodefcData->idCliente, $decodeData, $descuento, $percepcionIIBB,
				$faele, $repoFc, $repoCliente, $repoFinanzas 
			);
			
			return new Response;

				
		}catch(\Exception $e){
			throw $e;
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			$response->setContent(json_encode(array("success"=>false, "msg"=>$e->getMessage(), 'code' => $e->getCode())));
			return $response;
		}
	}
	
	/**
     * @Route("/CCClientes/guardarFc", name="mbp_CCClientes_guardarFc", options={"expose"=true})
     */	    
    public function guardarFcAction()
	{
		//RECIBO PARAMETROS
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$response = new Response;

		try{
			$faele = $this->get('mbp.faele'); //FACTURA ELECTRONICA
				
			
			//$faele->analizarCertificado();
			
			$auxFinanzas = $this->get('AuxiliarFinanzas');
			
						
			$data = $req->request->get('data');
			$fcData = $req->request->get('fcData');
			$descuento = $req->request->get('descuentoFijo');
			$percepcionIIBB = $req->request->get('percepcion');
			$decodeData = json_decode($data);
			$decodefcData = json_decode($fcData);
			$fcsAsociadas=explode(',', $decodefcData->compAsociados);

			
			//PARAMETROS FINANCIEROS
			$repoFinanzas = $em->getRepository('MbpFinanzasBundle:ParametrosFinanzas');
			$repoFc = $em->getRepository('MbpFinanzasBundle:Facturas');
			$parametrosFinanzas = $repoFinanzas->find(1);
						
			if(empty($parametrosFinanzas)) throw new \Exception("No estan definidos los parámetros financieros", 1);
		
		
			//CREO OBJETO FACTURA
			$factura = new Facturas();
			//Creo obj cc
			$cc = new CCClientes;
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
			$factura->setVencimiento($vencimiento->add(new \DateInterval('P'.$cliente->getVencimientoFc().'D'))); //ES LA FECHA DE FC + LA CONDICION DE VENTA DEL CLIENTE
			$factura->setClienteId($cliente);
			
			//tipo
			$repoTipo = $em->getRepository('MbpFinanzasBundle:TipoComprobante');
			$tipo = $repoTipo->find($decodefcData->tipo);		
			$factura->setTipoId($tipo);
			$factura->setConcepto($tipo->getDescripcion());

			
			//tipo de iva	
			$factura->setTipoIva($cliente->getIva());
			
			$netoGrabado = 0;
			$ivaLiquidado = 0;
			$netoNoGrabado = 0;
			$cbteTipo;
			
			if($tipo->getEsFactura() && $tipo->getSubTipoA()){
				$cbteTipo=1;	
			}
			
			if($tipo->getEsNotaDebito() && $tipo->getSubTipoA()){
				$cbteTipo=2;	
			}
			
			if($tipo->getEsNotaCredito() && $tipo->getSubTipoA()){
				$cbteTipo=3;
			}
			
			foreach($decodeData as $items){
				$detalleFc = new FacturaDetalle();
				$detalleFc->setDescripcion($items->descripcion);
				$detalleFc->setCantidad($items->cantidad);
				$detalleFc->setPrecio($items->precio);
				$articulo = $repoArticulo->findByCodigo($items->codigo);
				$detalleFc->setArticuloId($articulo[0]);
				$detalleFc->setIvaGrabado($items->ivaGrabado);
				
				//BUSCO EL REMITO
				$repoRemito = $em->getRepository('MbpArticulosBundle:RemitosClientesDetalles');
				$remitoDetalle = $repoRemito->find($items->remitoNum);			
				if(!empty($remitoDetalle)){
					$detalleFc->setRemitoDetalleId($remitoDetalle);
					$remitoDetalle->setFacturado(TRUE);	
				}					
							
				$factura->addFacturaDetalleId($detalleFc);
				$em->persist($detalleFc);
				
				//NETO GRABADO
				if($items->ivaGrabado){
					$netoGrabado += $items->cantidad * $items->precio;		
				}else{
					$netoNoGrabado+=$items->cantidad * $items->precio;		
				}			
			}
			
			//CALCULO LOS DESCUENTOS ANTES DE CALCULAR IMPUESTOS
			$descuentoTotal = ($netoGrabado+$netoNoGrabado) * $descuento / 100; 
			$descuentoNetoGrabado=$netoGrabado * $descuento / 100; 
			$descuentoNetoNoGrabado=$netoNoGrabado * $descuento / 100; 

			$netoGrabado -= $descuentoNetoGrabado;
			$netoNoGrabado -= $descuentoNetoNoGrabado;
			
			$factura->setDtoTotal($descuentoTotal);
			
			//SIN IVA PARA EJ NOTA DE DEBITO DE CHEQUES RECHAZADOS
			$ivaLiquidado=$netoGrabado*$parametrosFinanzas->getIva();
			/*if($decodefcData->sinIva == "on"){
				$ivaLiquidado = 0;
				$netoNoGrabado = $netoGrabado;
				$netoGrabado = 0;
			}else{
				$ivaLiquidado = $netoGrabado * $parametrosFinanzas->getIva();	
			}*/
						
			$ultimoComp = $faele->ultimoNroComp($cbteTipo);
			
			
			//CONSULTA PERCEPCION DE IIBB
			$alicuotaPercepcion = 0;
			if($netoGrabado > 0){
				$alicuotaPercepcion = $percepcionIIBB * 100 / $netoGrabado;
			}

			//REDONDEO IMPORTES A 2 DECIMALES
			$netoGrabado = number_format($netoGrabado, 2, ".", "");
			$ivaLiquidado = number_format($ivaLiquidado, 2, ".", "");
			$netoNoGrabado = number_format($netoNoGrabado, 2, ".", "");
			$alicuotaPercepcion = number_format($alicuotaPercepcion, 2, ".", "");
			
			
			
			$regfe['CbteTipo']=$cbteTipo;		
			$regfe['Concepto']=1;//ESTE DATO DEBE VENIR DEL CLIENTE 1=PRODUCTOS, 2=SERVICIOS, 3=PRODUCTOS Y SERVICIOS
			$regfe['DocTipo']=$faele->docTipo; //80=CUIT
			$regfe['DocNro']=$cliente->getCuit();
			$regfe['CbteDesde']= $ultimoComp['nro'] + 1;	// nro de comprobante desde (para cuando es lote)
			$regfe['CbteHasta']=$ultimoComp['nro'] + 1;	// nro de comprobante hasta (para cuando es lote)
			$regfe['CbteFch']=\date('Ymd'); 	// fecha emision de factura
			$regfe['ImpNeto']=$netoGrabado;			// neto gravado
			$regfe['ImpTotConc']=$netoNoGrabado;			// no gravado
			$regfe['ImpIVA']=$ivaLiquidado;			// IVA liquidado
			$regfe['ImpTrib']=$percepcionIIBB;			// otros tributos
			$regfe['ImpOpEx']=0;			// operacion exentas
			$regfe['ImpTotal']=$netoGrabado + $netoNoGrabado + $ivaLiquidado + $percepcionIIBB;// total de la factura. ImpNeto + ImpTotConc + ImpIVA + ImpTrib + ImpOpEx
			$regfe['FchServDesde']=null;	// solo concepto 2 o 3
			$regfe['FchServHasta']=null;	// solo concepto 2 o 3
			$regfe['FchVtoPago']=null;		// solo concepto 2 o 3
			$regfe['MonCotiz'] = 1; //solo si la operacion es en otra moneda diferente al peso ARS
			
			//evaluamos moneda a facturar
			if($decodefcData->moneda == 0){
				$regfe['MonId']='PES'; 			// Id de moneda 'PES'-------para dolares 'DOL'	
			}else{
				$regfe['MonId']='DOL';
				
				//cotizacion de moneda
				//$coti = $faele->FEParamGetCotizacion('DOL');
				//$regfe['MonCotiz'] = $coti->FEParamGetCotizacionResult->ResultGet->MonCotiz;
				$regfe['MonCotiz'] = $decodefcData->tipoCambio;
			}
			
			//$regfe['MonCotiz']=1;			// Cotizacion moneda. Solo exportacion
			
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
			if($ivaLiquidado==0){
				$regfeiva['Id'] = 3; //5 codigo IVA 21...4 codigo IVA 10.5...3 codigo IVA 0		
			}else{
				$regfeiva['Id'] = 5;
			}
							
			$regfeiva['BaseImp'] = $netoGrabado;
			$regfeiva['Importe'] = $ivaLiquidado;

			//SI ES UNA NC DEBO ASOCIAR LAS FCS CORRESPONDIENTES
			//SI LA NC ES PARCIAL SOLO PUEDE ESTAR APLICADA A UNA FACTURA
			if($tipo->getEsNotaCredito()){
				$total=0;
				if(empty($fcsAsociadas)){
					throw new \Exception("La nota de crédito debe estar asociada al menos una factura", 1);					
				}else{
					//SI ES UNA NOTA DE CREDITO POR VARIOS COMPROBANTES SE ANULA NOTO
					if(count($fcsAsociadas) > 1){
						foreach($fcsAsociadas as $fc){
							$fcAsociada=$repoFc->find($fc);
							if($fcAsociada->getMoneda() != $decodefcData->moneda){
								throw new \Exception("La moneda del comprobante a cancelar es distinta a la moneda de la nota de crédito", 1);							
							}else{
								$factura->addFacturasAsociada($fcAsociada);
								$fcAsociada->setAnulaImporteNC($fcAsociada->getTotal());
								$em->persist($fcAsociada);
								$total+=$fcAsociada->getTotal();
							}						
						}	
					}else{// SI ES SOBRE UN UNICO COMPROBANTE SE PUEDE ANULAR PARCIALMENTE
						foreach($fcsAsociadas as $fc){
							$fcAsociada=$repoFc->find($fc);
							if($fcAsociada->getMoneda() != $decodefcData->moneda){
								throw new \Exception("La moneda del comprobante a cancelar es distinta a la moneda de la nota de crédito", 1);							
							}else{
								$factura->addFacturasAsociada($fcAsociada);
								$fcAsociada->setAnulaImporteNC($regfe['ImpTotal']);
								$em->persist($fcAsociada);
								$total+=$fcAsociada->getTotal();
							}						
						}	
					}						
				}		
				$epsilon=0.0001;
				if(($regfe['ImpTotal']-$total) > $epsilon && count($fcsAsociadas) > 1){ //validacion para varios comprobantes
					throw new \Exception("El total de la NC no coincide con las facturas imputadas", 1);					
				}

				if(($total-$regfe['ImpTotal']) < $epsilon && count($fcsAsociadas) == 1){ //validacion para unico comprobante
					throw new \Exception("El total de la NC debe ser menor o igual a la factura imputada", 1);					
				}
			}
			
			
			$cae = $faele->generarFc($regfe, $regfeasoc, $regfetrib, $regfeiva);	//GENERO FC ELECTRONICA
			
			
			
			if($cae['success'] == FALSE){
				throw new \Exception($cae["msg"]["msg"][0], $cae["msg"]["code"][0]);
			}
			
			$factura->setDigitoVerificador($cae['digitoVerificador']);
			$factura->setCae($cae['cae']['cae']);
			$factura->setVtoCae(new \DateTime($cae['cae']['fecha_vencimiento']));

			//DATOS DEL CLIENTES
			$factura->setRSocial($cliente->getRSocial());
			$factura->setDomicilio($cliente->getDireccion());
			$factura->setDepartamento($cliente->getDepartamento()->getNombre());
			$factura->setCuit($cliente->getCuit());
			$factura->setIvaCond($cliente->getIva()->getPosicion());
			$factura->setCondVta($cliente->getCondVenta()); 
			$factura->setFcNro($ultimoComp['nro'] + 1);
			$factura->setPerIIBB($percepcionIIBB);
			$factura->setTotal($regfe['ImpTotal']);
			$factura->setIva21($ivaLiquidado);
			$factura->setporcentajeIIBB($alicuotaPercepcion);			
			$decodefcData->moneda == 0 ? $factura->setMoneda(0) : $factura->setMoneda(1);
			$decodefcData->moneda == 0 ? $factura->setTipoCambio(1) : $factura->setTipoCambio($regfe['MonCotiz']);
			
			if($decodefcData->tipoCambio > 0){
				$factura->setTipoCambioRefFac($decodefcData->tipoCambio);
			}
			
			//cc
			$totalCC=0;
			if($decodefcData->moneda == 1){
				$totalCC=$factura->getTotal()*$decodefcData->tipoCambio;
			}else{
				$totalCC=$factura->getTotal();
			} 
			if($tipo->getEsNotaCredito()){
				$cc->setHaber($totalCC);	
			}else{
				$cc->setDebe($totalCC);
			}
			$cc->setFechaEmision($factura->getFecha());
			$cc->setFechaVencimiento($factura->getVencimiento());
			$cc->setFacturaId($factura);
			$cc->setClienteId($cliente);
			
			$factura->setCcId($cc);
			
			
			
			$em->persist($factura);
			$em->flush();	
			$cae["idFc"] = $factura->getId();
			$response->setContent(json_encode($cae));
			$response->setStatusCode(Response::HTTP_OK); 
			
			return $response;

		}catch(\Exception $e){
			//throw $e;
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			$response->setContent(json_encode(array("success"=>false, "msg"=>$e->getMessage(), 'code' => $e->getCode())));
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
			}

			//si la cobranza tiene algun detalle asociado a un mov. bancario x ej deposito de cheque no se puede borrar
			$detallesCobranza=$record->getCobranzaDetalleId();
			foreach ($detallesCobranza as $det) {
				if($det->getEstado() != 0){ //Si el estado es distinto de cero es poque el cheque fue depositado o entregado a terceros y no puede borrarse la operacion
					return $response->setContent(json_encode(array("success"=>false, "msg"=>"La cobranza tiene un movimiento bancario asociado")));
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





















