<?php

namespace Mbp\ProduccionBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Mbp\ProduccionBundle\Entity\Ot;

class OtController extends Controller
{	
	/** 
     * @Route("/nuevaot", name="mbp_produccion_nuevaot", options={"expose"=true})
     */
    public function nuevaOt()
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
		$response = new Response;
		
		try{
			$data = $request->request->get('data');		
			$ordenesAsociadas = $request->request->get('otAsociada');
			$stdObj = json_decode($data);
			$ordenesAsociadas = json_decode($ordenesAsociadas);
			
			
			$repoArticulos = $em->getRepository('MbpArticulosBundle:Articulos');
			$repoClientes = $em->getRepository('MbpClientesBundle:Cliente');
			$repoSectores = $em->getRepository('MbpProduccionBundle:Sectores');
			$repoOt = $em->getRepository('MbpProduccionBundle:Ot');
			$repoCliente = $em->getRepository('MbpClientesBundle:Cliente');
			$repoPedidosDetalle = $em->getRepository('MbpProduccionBundle:PedidoClientesDetalle');
			
			//BUSCO ARTICULO
			$articulo = $repoArticulos->findByCodigo($stdObj->codigo);
						
			//BUSCO USUARIO
			$usuario = $this->get('security.context')->getToken()->getUser();
			
			//BUSCO SECTOR
			$sector = $repoSectores->find($stdObj->tipo);
			
			//BUSCO CLIENTE
			$cliente = $repoCliente->find($stdObj->idCliente);
			
			//BUSCO EL SECTOR DEL USUARIO QUE EMITE LA OT
			$sectorEmisor = $repoSectores->find($usuario->getSectorId());
			
			//LANZAMOS ERRORES
			if(empty($articulo)) throw new \Exception("No existe el articulo ingresado", 1);
			if(empty($sector)) throw new \Exception("No existe el sector ingresado", 1);
			if(empty($cliente)) throw new \Exception("No existe el cliente ingresado", 1);
			
			
			
	        $ot = new Ot;
			$ot->setFechaEmision(new \DateTime);
			$ot->setIdCodigo($articulo[0]);
			$ot->setCantidad($stdObj->cantidad);
			$fechaProg = \DateTime::createFromFormat('d/m/Y', $stdObj->fechaProg);
			$ot->setFechaProg($fechaProg);
			$ot->setObservaciones($stdObj->observaciones);
			$ot->setIdUsuario($usuario);
			$ot->setSectorId($sector);
			$ot->setSectorEmisor($sectorEmisor);
			$ot->setClienteId($cliente);
			
			//SI EXISTEN ORDENES ASOCIADAS LAS AGREGO
			foreach ($ordenesAsociadas as $otA) {				
				$ot->addMisOrdenes($repoOt->find($otA->otNum));
			}
			
			//SI EXISTEN PEDIDOS ASOCIADOS LOS AGREGO
			if(!empty($stdObj->pedidosAsociados)){
				$asociados = explode(',', $stdObj->pedidosAsociados);
				
				foreach ($asociados as $pedido) {
					$pedId = $repoPedidosDetalle->find($pedido);
					$ot->addPedido($pedId);
				}
			}
			
			
			//VALIDACIONES
			$validador = $this->get('validator');			
			$errors = $validador->validate($ot);
			
			
				
			if(count($errors) > 0){
				$errList = array();
				foreach ($errors as $error) {
					$errList[$error->getPropertyPath()] = $error->getMessage();
				}
				
				$response->setContent(
					json_encode(
						array(
						'success' => false,
						'errors' => $errList,
						'tipo' => 'validacion'
						)
					)
				);
				
				return $response->setStatusCode(Response::HTTP_BAD_REQUEST); 
			}
			$em->persist($ot);
			$em->flush();
			
			//NOTIFICACION
			$pusher = $this->container->get('lopi_pusher.pusher');
			
		    $data=array(
				'message' => 'Se ingreso la OT: '.$ot->getOt().". Verificar panel de programacion",
				'sectorReceptor' => $sector->getDescripcion()
			);
			
		    $pusher->trigger('my-channel', 'my-event', json_encode($data));
				        
	        return $response->setContent(
				json_encode(array('success' => true, 'ot' => $ot->getOt()))
			);
		}catch(\Exception $e){
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR); 
			return $response->setContent(
					json_encode(array('success' => false, 'msg' => $e->getMessage()))
				);
		}		
    }	

	/**
     * @Route("/CerrarOtListado", name="mbp_produccion_CerrarOtListado", options={"expose"=true})
     */
    public function CerrarOtListadoAction()
	{
		$em = $this->getDoctrine()->getManager();
		$response = new Response;
		$repo = $em->getRepository('MbpProduccionBundle:Ot');
		
		try{
			
			
			$response->setContent(
				json_encode(array(
					'success' => true,
					'items' => $repo->listadoOtParaCerrar() 
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
	
	/**
     * @Route("/ActualizarCerradoOt", name="mbp_produccion_ActualizarCerradoOt", options={"expose"=true})
     */
    public function ActualizarCerradoOtAction()
	{
		$em = $this->getDoctrine()->getManager();
		$response = new Response;
		$repo = $em->getRepository('MbpProduccionBundle:Ot');
		$request = $this->getRequest();
		
		$items = $request->request->get('items');
		$decodeItem = json_decode($items);
		
		try{
			foreach ($decodeItem as $item) {				
				$ot = $repo->find($item->otNum);
				$oldValue= $ot->getAprobado();
				$ot->setAprobado($item->aprobado);
				$ot->setRechazado($item->rechazado);
				
				$em->persist($ot);
				
				/*ACTUALIZO EL STOCK DEL ARTICULO*/
				$repoArt = $em->getRepository('MbpArticulosBundle:Articulos');
				$articulo = $repoArt->findByCodigo($item->codigo);
				
				$stock = $articulo[0]->getStock() + $item->aprobado - $oldValue;
				$articulo[0]->setStock($stock);
				$em->persist($articulo[0]);
			}
			
			$em->flush();
			
			$response->setContent(json_encode(array(
				'success' => true,
			)));
			
			return $response;
		}catch(\Exception $e){
			$response->setContent(json_encode(array(
				'success' => false,
				'msg' => $e->getMessage())
			));
			
			return $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	/**
     * @Route("/listarOrdenes", name="mbp_produccion_ListarOrdenes", options={"expose"=true})
     */
    public function listarOrdenes()
	{
		$em = $this->getDoctrine()->getManager();
		$response = new Response;
		$repo = $em->getRepository('MbpProduccionBundle:Ot');
		$request = $this->getRequest();
		
		try{
			$listado = $repo->listarOrdenes();
			
			$response->setContent(json_encode(array(
				'data' => $listado,
				'success' => true,
			)));
			
			return $response;
		}catch(\Exception $e){
			$response->setContent(json_encode(array(
				'success' => false,
				'msg' => $e->getMessage())
			));
			
			return $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}
	
	/**
     * @Route("/listarOrdenesCompletas", name="mbp_produccion_ListarOrdenesCompletas", options={"expose"=true})
     */
    public function listarOrdenesCompletas()
	{
		$em = $this->getDoctrine()->getManager();
		$response = new Response;
		$repo = $em->getRepository('MbpProduccionBundle:Ot');
		$request = $this->getRequest();
		
		try{
			$listado = $repo->listarOrdenesCompletas();
			
			$response->setContent(json_encode(array(
				'data' => $listado,
				'success' => true,
			)));
			
			return $response;
		}catch(\Exception $e){
			$response->setContent(json_encode(array(
				'success' => false,
				'msg' => $e->getMessage())
			));
			
			return $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}
	
	/**
     * @Route("/listarOrdenesParaProgramacion", name="mbp_produccion_ListarOrdenesProg", options={"expose"=true})
     */
    public function listarOrdenesParaProgramacion()
	{
		$em = $this->getDoctrine()->getManager();
		$response = new Response;
		$repo = $em->getRepository('MbpProduccionBundle:Ot');
		$request = $this->getRequest();
		
		try{
			//BUSCO USUARIO PARA OBTENER SU SECTOR
			$usuario = $this->get('security.context')->getToken()->getUser();
			$sector = $usuario->getSectorId();
			$listado = $repo->listarOrdenesParaProgramacion($sector);
			
			
			$response->setContent(json_encode(array(
				'data' => $listado,
				'success' => true,
			)));
			
			
			return $response;
		}catch(\Exception $e){
			$response->setContent(json_encode(array(
				'success' => false,
				'msg' => $e->getMessage())
			));
			
			return $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	
	/**
     * @Route("/listarOrdenesParaProgramacionEmisor", name="mbp_produccion_ListarOrdenesProgEmisor", options={"expose"=true})
     */
    public function listarOrdenesParaProgramacionEmisor()
	{
		$em = $this->getDoctrine()->getManager();
		$response = new Response;
		$repo = $em->getRepository('MbpProduccionBundle:Ot');
		$request = $this->getRequest();
		
		try{
			//BUSCO USUARIO PARA OBTENER SU SECTOR
			$usuario = $this->get('security.context')->getToken()->getUser();
			$sector = $usuario->getSectorId();
			
			$listado = $repo->listarOrdenesParaProgramacionPorEmisor($sector);
			
			$response->setContent(json_encode(array(
				'data' => $listado,
				'success' => true,
			)));
			
			return $response;
		}catch(\Exception $e){
			$response->setContent(json_encode(array(
				'success' => false,
				'msg' => $e->getMessage())
			));
			
			return $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}
	
	/**
     * @Route("/ActualizarEstadoOt", name="mbp_produccion_ActualizarEstadoOt", options={"expose"=true})
     */
    public function ActualizarEstadoOt()
	{		
		$em = $this->getDoctrine()->getManager();
		$response = new Response;
		$repo = $em->getRepository('MbpProduccionBundle:Ot');
		$request = $this->getRequest();
		
		try{
			$data = json_decode($request->request->get('data'));
			
			
			$ot = $repo->find($data->otNum);
			$ot->setAprobado($data->aprobado);
			$ot->setRechazado($data->rechazado);
			
			switch ($data->estado) {
				case 'No comenzada':
					$ot->setEstado(0);
					break;
				case 'En proceso':
					$ot->setEstado(1);
					break;
				case 'Terminada':
					$ot->setEstado(2);
										
					/* Si la OT esta terminada enviamos una notificacion al sector que la solicitó */
					//NOTIFICACION
					$pusher = $this->container->get('lopi_pusher.pusher');
					
					$cliente = $ot->getClienteId();
					if(!empty($cliente)){
						$cliente = $ot->getClienteId()->getrSocial();
					}
					
				    $data=array(
						'message' => 'Se cerró la OT n°: '.$ot->getOt().
							" código: ".$ot->getIdCodigo()->getCodigo().
							" del cliente ".$cliente.
							" verifique el panel de programación.",
						'sectorReceptor' => $ot->getSectorEmisor()->getDescripcion()
					);
					
				    $pusher->trigger('my-channel', 'my-event', json_encode($data));
					
					break;
				case 'Generada':
					$ot->setEstado(3);
					break;
				default:
					$ot->setEstado(0);
					break;
			}
			
			$em->persist($ot);
			$em->flush();
			
			$response->setContent(json_encode(array(				
				'success' => true,
			)));
			
			return $response;
		}catch(\Exception $e){
			$response->setContent(json_encode(array(
				'success' => false,
				'msg' => $e->getMessage())
			));
			
			return $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}
	
	/**
     * @Route("/seguimientoOT", name="mbp_produccion_seguimientoOT", options={"expose"=true})
     */
    public function seguimientoOT()
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
		$response = new Response;
		
		try{
			$ot = $request->request->get('ot');	
			$repo = $em->getRepository('MbpProduccionBundle:Ot');
			
			$resp = $repo->findByOt($ot);
			
			$arrayResp = array();
			$datosOt;
			
			if(!empty($resp)){
				$datosOt['descripcion'] = $resp[0]->getIdCodigo()->getDescripcion();
				$datosOt['cantidad'] = $resp[0]->getCantidad();	
			}else{
				throw new \Exception("No existe la OT ingresada", 1);
				
			}
			
			$this->otRecursiva($resp, $arrayResp);
			
			return $response->setContent(json_encode(
				array('success' => true, 'data' => $arrayResp, 'datosOt' => $datosOt)
			));
		}catch(\Exception $e){
			$response->setContent(json_encode(array(
				'success' => false,
				'msg' => $e->getMessage())
			));
			
			return $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}
	
	private function otRecursiva($ot, &$arrayResp){
		
		if(empty($ot)) return;
		foreach ($ot as $orden) {			
			
			$raw['otNum'] = $orden->getOt();
			$raw['codigo'] = $orden->getIdCodigo()->getCodigo();
			$raw['descripcion'] = $orden->getIdCodigo()->getDescripcion();
			$raw['totalOt'] = $orden->getCantidad();
			$raw['programado'] = $orden->getFechaProg()->format('d/m/Y');
			$raw['aprobado'] = $orden->getAprobado();
			$raw['rechazado'] = $orden->getRechazado();
			$raw['estado'] = $orden->getEstado();
			$raw['sectorEmisor'] = $orden->getSectorEmisor()->getDescripcion();
			array_push($arrayResp, $raw);
			
			
			$this->otRecursiva($orden->getOrdenesConmigo(), $arrayResp);	
		}
	}
	
	/**
     * @Route("/verificarOT", name="mbp_produccion_verificarOT", options={"expose"=true})
     */
    public function verificarOT()
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
		$response = new Response;
		
		try{
			$codigo = $request->request->get('codigo');
			$sector = $request->request->get('sector');	
			$repo = $em->getRepository('MbpProduccionBundle:Ot');
			
			
			$resp = $repo->listarOTEnProceso($codigo, $sector);
			
			$arrayResp=array();
			$str="";
			$mensaje;
			
			if(!empty($resp)){
				foreach ($resp as $r) {
					$str = $str.$r["otNum"]."</br>";
					array_push($arrayResp, $str);
				}	
				$mensaje = array('success' => true, 'data' => $arrayResp, 'type' => 'info');			
			}else{
				$mensaje = array('success' => true);
			}
			
			
			return $response->setContent(json_encode($mensaje));
		}catch(\Exception $e){
			$response->setContent(json_encode(array(
				'success' => false,
				'msg' => $e->getMessage())
			));
			
			return $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}
	
	/**
     * @Route("/eliminarOT", name="mbp_produccion_eliminarOT", options={"expose"=true})
     */
    public function eliminarOT()
    {    	
		$em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
		$response = new Response;
		
		try{
			$ot = $request->request->get('ot');
			$obs = $request->request->get('observacion');
			$repo = $em->getRepository('MbpProduccionBundle:Ot');
			
			
			$resp = $repo->findByOt($ot);
			
			$arrayResp = array();
			
			$this->otRecursiva($resp, $arrayResp);
			
			
			$pusher = $this->container->get('lopi_pusher.pusher');//NOTIFICADOR
			
		    
			
			foreach ($arrayResp as $orden) {
				$o = $repo->find($orden['otNum']);
				$o->setAnulada(TRUE);
				$o->setObservaciones($o->getObservaciones()."----------MOTIVO DE ANULACIÓN: ".$obs);
				
				$data=array(
					'message' => 'Se ANULO la OT: '.$o->getOt(),
					'sectorReceptor' => $o->getSectorId()->getDescripcion()
				);
				
			    $pusher->trigger('my-channel', 'my-event', json_encode($data));
			}
			
			
			$em->flush();
			
			
			return $response->setContent(json_encode(array('success' => true)));
		}catch(\Exception $e){
			$response->setContent(json_encode(array(
				'success' => false,
				'msg' => $e->getMessage())
			));
			
			return $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
		}
    }
	
	/**
     * @Route("/validarAnulacionOT", name="mbp_produccion_validarAnulacionOT", options={"expose"=true})
     */
    public function validarAnulacionOT()
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
		$response = new Response;
		
		try{
			$ot = $request->request->get('ot');
			$obs = $request->request->get('observacion');
			$repo = $em->getRepository('MbpProduccionBundle:Ot');
			
			
			$resp = $repo->findByOt($ot);
			
			$arrayResp = array();
			
			
			$this->otRecursiva($resp, $arrayResp);			
						
			return $response->setContent(json_encode(array('success' => true, 'data' => $arrayResp)));
		}catch(\Exception $e){
			$response->setContent(json_encode(array(
				'success' => false,
				'msg' => $e->getMessage())
			));
			
			return $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}
	
	/**
     * @Route("/cambiarFechaEntrega", name="mbp_produccion_cambiarFechaEntrega", options={"expose"=true})
     */
    public function cambiarFechaEntrega()
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
		$response = new Response;
		
		try{
			$ot = $request->request->get('ot');
			$fecha = $request->request->get('fecha');
			$repo = $em->getRepository('MbpProduccionBundle:Ot');
			
			
			$row = $repo->find($ot);
			
			$row->setFechaProg(\DateTime::createFromFormat('d/m/Y', $fecha));
			$em->persist($row);
			$em->flush(); 
			
			
			return $response->setContent(json_encode(array('success' => true)));
		}catch(\Exception $e){
			$response->setContent(json_encode(array(
				'success' => false,
				'msg' => $e->getMessage())
			));
			
			return $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}
}
