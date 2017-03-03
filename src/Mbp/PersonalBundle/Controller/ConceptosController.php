<?php

namespace Mbp\PersonalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ConceptosController extends Controller
{
	/**
     * @Route("/conceptosRead", name="mbp_personal_conceptosRead", options={"expose"=true})
     */
    public function conceptosReadAction()
	{
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$esVariable = $req->query->get('variable');
		$idPersonal = $req->query->get('idP');
		
		$repo = $em->getRepository('MbpPersonalBundle:CodigoSueldos');		
		$repo->listarCodigos($esVariable, $idPersonal);
		
		return new Response();
	}
	
	/**
     * @Route("/conceptosCreate", name="mbp_personal_conceptosCreate", options={"expose"=true})
     */
	public function conceptosCreateAction()
	{
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$data = $req->request->get('data');
		$jsonData = json_decode($data);
				
		$repo = $em->getRepository('MbpPersonalBundle:CodigoSueldos');		
		$repo->crearConcepto($jsonData);
		
		return new Response();
	}
	
	/**
     * @Route("/conceptosDelete", name="mbp_personal_conceptosDelete", options={"expose"=true})
     */
	public function conceptosDeleteAction()
	{
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$data = $req->request->get('data');
		$jsonData = json_decode($data);
				
		$repo = $em->getRepository('MbpPersonalBundle:CodigoSueldos');		
		$repo->eliminaConcepto($jsonData);
		
		return new Response();
	}
}





















