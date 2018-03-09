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
     * @Route("/finanzas/guardarParametros", name="mbp_finanzas_guardarParametros", options={"expose"=true})
     */
    public function guardarParametros()
    {
    	$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$repo = $em->getRepository('MbpFinanzasBundle:ParametrosFinanzas');
		$repoProv = $em->getRepository('MbpPersonalBundle:Provincia');
		$response = new Response;
		
		try{
			$res = $repo->find(1);
			$res->setIva($req->request->get('iva'));
			$res->setDolarOficial($req->request->get('dolarOficial'));
			$provincia = $repoProv->find($req->request->get('provincia'));
			$res->setProvincia($provincia);
			$res->setRemitoNum($req->request->get('remitoNum'));
			$res->setTopeRetencionIIBB($req->request->get('topeRetencionIIBB'));
			$res->setTopePercepcionIIBB($req->request->get('topePercepcionIIBB'));
			$res->setIndiceCorrelativos($req->request->get('indiceCorrelativos'));
			
			$validador = $this->get('validator');
			$errors = $validador->validate($res);
			if(count($errors) > 0){
				$errList = array();
				foreach ($errors as $error) {
					$errList[$error->getPropertyPath()] = $error->getMessage();
				}
				
				return $response->setContent(json_encode(array('success' => false, 'errors' => $errList, 'tipo' => 'validacion')));
				
			}
			
			$em->persist($res);
			$em->flush();
			
			
			
		}catch(\Exception $e){
			return $response->setContent(json_encode(array('success' => false, 'msg' => $e->getMessage())));
		}
		
		return $response->setContent(json_encode(array('success' => true)));
    }
	
	/**
     * @Route("/finanzas/listarParametros", name="mbp_finanzas_listarParametros", options={"expose"=true})
     */
    public function listarParametros()
    {
    	$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpFinanzasBundle:ParametrosFinanzas');
		$response = new Response;
		
		try{
			$qb = $repo->createQueryBuilder('p')
				->select('p.iva, p.dolarOficial, p.remitoNum, p.topeRetencionIIBB, p.topePercepcionIIBB, prov.id as provincia, p.indiceCorrelativos')
				->join('p.provincia', 'prov')
				->getQuery()
				->getArrayResult();	
		}catch(\Exception $e){
			return $response->setContent(json_encode(array('success' => false, 'msg' => $e->getMessage())));
		}
		
			
		return $response->setContent(json_encode(array('success' => true, 'data' => $qb[0])));
    }
	
	
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




















