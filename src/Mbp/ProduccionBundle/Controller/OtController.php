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
			$stdObj = json_decode($data);
			
			$repoArticulos = $em->getRepository('MbpArticulosBundle:Articulos');
			$repoClientes = $em->getRepository('MbpClientesBundle:Cliente');
			
			//BUSCO ARTICULO
			$articulo = $repoArticulos->findByCodigo($stdObj->codigo);
			
			//BUSCO CLIENTE
			$cliente = $repoClientes->find($stdObj->id);
			
			//BUSCO USUARIO
			$usuario = $this->get('security.context')->getToken()->getUser();
			
	        $ot = new Ot;
			$ot->setFechaEmision(new \DateTime);
			$ot->setIdCodigo($articulo[0]);
			$ot->setIdCliente($cliente);
			$ot->setCantidad($stdObj->cantidad);
			$fechaProg = \DateTime::createFromFormat('d/m/Y', $stdObj->fechaProg);
			$ot->setFechaProg($fechaProg);
			$ot->setObservaciones($stdObj->observaciones);
			$ot->setIdUsuario($usuario);
			$ot->setTipo($stdObj->tipo);
			
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
				json_encode(array('success' => true))
			);
		}catch(\Exception $e){
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR); 
			return $response->setContent(
					json_encode(array('success' => false, 'msg' => $e->getMessage()))
				);
		}		
    }	
}
