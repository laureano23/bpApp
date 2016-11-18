<?php

namespace Mbp\ProduccionBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class MaquinasController extends Controller
{
	public function listarMaquinaAction()
	{
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpProduccionBundle:Maquinas');
		
		$repo->listarMaquinas();
		
		return new Response();
	}
	
	public function maquinaSectorAction()
	{
		
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$sector = $req->query->get('sector');
		$repoMaquinas = $em->getRepository('MbpProduccionBundle:Maquinas');
		$repoSectores = $em->getRepository('MbpProduccionBundle:Sectores');
		$repoMaquinas->buscaxSector($sector);
		
		
		
		return new Response();
	}
}

































