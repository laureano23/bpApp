<?php

namespace Mbp\ProduccionBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class SectoresController extends Controller
{
	public function seleccionSectorAction()
	{
		$req = $this->getRequest();
		$sector = $req->query->get('sector');
		
		$em = $this->getDoctrine()->getManager();
		$repoSectores = $em->getRepository('MbpProduccionBundle:Sectores');
		
		if($sector){
			$repoSectores->buscaSector($sector);	
		}else{
			$repoSectores->listarSectores();
		}		
		
		return new Response();
	}
	
	public function personalSectorAction()
	{
		$req = $this->getRequest();
		$sector = $req->query->get('sector');
		
		$em = $this->getDoctrine()->getManager();
		$repoSectores = $em->getRepository('MbpProduccionBundle:Sectores');
		
		
		$repoSectores->personalEnSector($sector);	
			
		
		return new Response();
	}
}

































