<?php

namespace Mbp\FinanzasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Mbp\FinanzasBundle\Entity\CentroCostos;

class ParametrosController extends Controller
{
    /**
     * @Route("/finanzas/initParams", name="mbp_finanzas_initParams", options={"expose"=true})
     */
    public function initParamsAction()
    {
    	$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpFinanzasBundle:ParametrosFinanzas');
		$params = $repo->findAll();
		$env = $this->container->get('kernel')->getEnvironment();
			
		return new Response(json_encode(
			array(
				'data' => array(
					'iva' => (float) $params[0]->getIva(),
					'dolarOficial' => (float) $params[0]->getDolarOficial(),
					'env' => $env
				),
				'success' => true
			)
		));
    }	
	
	/**
     * @Route("/finanzas/parametros/centroCostos", name="mbp_finanzas_centroCostos", options={"expose"=true})
     */
    public function centroCostosAction()
    {
    	$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpFinanzasBundle:CentroCostos');
		$params = $repo->listarCentroCostos();
						
		echo json_encode(array(
			'data' => $params
		));
		
		return new Response();
    }	
	
	/**
     * @Route("/finanzas/parametros/nuevoCentroCostos", name="mbp_finanzas_nuevoCentroCostos", options={"expose"=true})
     */
    public function nuevoCentroCostosAction()
    {
    	$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpFinanzasBundle:CentroCostos');
		$req = $this->getRequest();
		$data = $req->request->get('data');
		$decodeData = json_decode($data);
		
		try{
			$CentroCostos;
			if($decodeData->id > 0){
				$CentroCostos = $repo->find($decodeData->id);
			}else{
				$CentroCostos = new CentroCostos();	
			}
						
			$CentroCostos->setNombre($decodeData->nombre);
			$CentroCostos->setCosto($decodeData->costo);
			$CentroCostos->setMoneda($decodeData->moneda == 'p' ? 0 : 1);
			
			$em->persist($CentroCostos);
			$em->flush();
							
			echo json_encode(array(
				'success' => true,
				'id' => $CentroCostos->getId()
			));
			
			return new Response();	
		}catch(\Exception $e){
			echo json_encode(array(
				'success' => false,
				'msg' => $e->getMessage()
			));
		}			
    }	
	
	/**
     * @Route("/finanzas/parametros/borrarCentroCostos", name="mbp_finanzas_borrarCentroCostos", options={"expose"=true})
     */
    public function borrarCentroCostosAction()
    {
    	$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpFinanzasBundle:CentroCostos');
		$req = $this->getRequest();
		$data = $req->request->get('data');
		$decodeData = json_decode($data);
		
		try{
			
			$CentroCostos = $repo->find($decodeData->id);			
			$em->remove($CentroCostos);
			$em->flush();
							
			echo json_encode(array(
				'success' => true,
				'id' => $CentroCostos->getId()
			));
			
			return new Response();	
		}catch(\Exception $e){
			echo json_encode(array(
				'success' => false,
				'msg' => $e->getMessage()
			));
		}			
    }			
}




















