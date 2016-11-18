<?php
namespace Mbp\ProduccionBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class OperacionesController extends Controller
{
	public function operacionesListAction()
	{	
		$em = $this->getDoctrine()->getEntityManager();
		$repo = $em->getRepository('MbpProduccionBundle:Operaciones');
		
		$repo->listarOperaciones();
		
		return new Response();
	}
	
	public function operacionesCreateAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$req = $this->getRequest();
		$data = $req->request->get('data');
		$repo = $em->getRepository('MbpProduccionBundle:Operaciones');
		
		$repo->saveOPeracion($data);
		
		
		return new Response();
	}
}

































