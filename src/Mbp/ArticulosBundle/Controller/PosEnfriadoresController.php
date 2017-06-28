<?php

namespace Mbp\ArticulosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Mbp\ArticulosBundle\Entity\PosEnfriadores;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ArticulosController extends Controller
{
	
	/**
     * @Route("/nuevoPosEnfriador", name="mbp_articulos_nuevoPosEnfriador", options={"expose"=true})
     */
    public function nuevoPosEnfriador()
    {
    	$em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
		
		
	}
}