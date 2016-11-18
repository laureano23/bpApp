<?php

namespace Mbp\CalidadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Connection;
use Mbp\CalidadBundle\Entity\Correlativos;
use Mbp\CalidadBundle\Entity\CorrelativosRepository;


class DefaultController extends Controller
{
    public function correlativosAction()
    {
        $em = $this->getDoctrine();
		$rep = $em->getRepository('MbpCalidadBundle:Correlativos');
		$res = $rep->listarCorrelativos();
		
		return new Response();		
    }
	
	public function newcorrelativoAction()
	{
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();
		$info = $request->request->get('data');
		
		try{
			$repo = $em->getRepository('MbpCalidadBundle:Correlativos')->newCorrelativo($info);	
			
		}catch(Exception $e){
			$em->getConnection()->rollback();
		    $em->close();
		    throw $e;
		}
		
		return new Response();		
	}
	
	public function updatecorrelativoAction()
	{
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();
		$info = $request->request->get('data');
		
		
		
		$repo = $em->getRepository('MbpCalidadBundle:Correlativos')->updateCorrelativo($info);
		
		return new Response();
	}
	
	public function destroycorrelativoAction()
	{
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();
		$info = $request->request->get('data');
		
		$repo = $em->getRepository('MbpCalidadBundle:Correlativos')->destroyCorrelativo($info);
		
		return new Response();
	}
	
	/*
	 * Formulario de control estanqueidad
	 */
	public function addEstanqueidadRegAction()
	{
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();
		
		$response = new Response(); 
		
		try{
			$data = $request->request->get('data');
			$idReg = $request->request->get('idReg');	
		}catch(\Exception $e){					
			$response->setContent(json_encode(array(
				'success' => false,
				'msg' => $e->getMessage()
			)));
			return $response;
		}
		
		
		$arrData = json_decode($data, true);
		$validator = $this->get('validator');
		
		$rep = $em->getRepository('MbpCalidadBundle:Estanqueidad')->addRegistro($arrData, $idReg, $validator);
		
		$response = new Response(); 		
		
		$response->setContent(json_encode($rep));
		
		if($rep['success'] == false && array_key_exists('tipo', $rep)){
			$response->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
		}elseif($rep['success'] == FALSE){
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
		}else{
			$response->setStatusCode(Response::HTTP_OK);
		}
		
		return $response;
	}
	
	
	public function listEstanqueidadAction()
	{
		$em = $this->getDoctrine()->getManager();
		
		$rep = $em->getRepository('MbpCalidadBundle:Estanqueidad')->listRegistro();

		return new Response();
	}
	
	public function deleteEstanqueidadRegAction()
	{
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();
		
		$idReg = $request->request->get('idReg');
		
		$reg = $em->getRepository('MbpCalidadBundle:Estanqueidad')->deleteReg($idReg);
		
		return new Response();
	}
}



















