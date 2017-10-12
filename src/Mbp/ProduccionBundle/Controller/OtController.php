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
			
			//BUSCO ARTICULO
			$articulo = $repoArticulos->findByCodigo($stdObj->codigo);
						
			//BUSCO USUARIO
			$usuario = $this->get('security.context')->getToken()->getUser();
			
			//BUSCO SECTOR
			$sector = $repoSectores->find($stdObj->tipo);
			
			//BUSCO EL SECTOR DEL USUARIO QUE EMITE LA OT
			$sectorEmisor = $repoSectores->find($usuario->getSectorId());
			
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
			
			//SI EXISTEN ORDENES ASOCIADAS LAS AGREGO
			foreach ($ordenesAsociadas as $otA) {				
				$ot->addMisOrdenes($repoOt->find($otA->otNum));
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
				//var_dump($stock);
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
			
			/*if($sector->getDescripcion() == "PRODUCTO FINAL"){
				$dbfService = $this->get('DBF.class');
				
				$dbfService->initLoad("OTRABAJO.DBF");
				
				while(($record = $dbfService->GetNextRecord(true)) and !empty($record)) {
			        print_r($record);
					//exit;
			    }	
			}*/
			
			
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
			
			$resp = $repo->findByOt(33);
			
			
			
			//$ordenes = $resp->getMisOrdenes();
			//\Doctrine\Common\Util\Debug::dump($ordenes);
			
			$flag=0;
			$this->otRecursiva($resp, $flag);
			
			
		}catch(\Exception $e){
			$response->setContent(json_encode(array(
				'success' => false,
				'msg' => $e->getMessage())
			));
			
			return $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}
	
	private function otRecursiva($ot, &$flag){
		
		while($ot != null){
			$this->otRecursiva($ot);
			$ot=NULL;
		}
		
	}
}
