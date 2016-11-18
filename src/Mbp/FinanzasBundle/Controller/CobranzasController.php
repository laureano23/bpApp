<?php

namespace Mbp\FinanzasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CobranzasController extends Controller
{
    /**
     * @Route("/cobros/listarTipos", name="mbp_finanzas_listaTipos", options={"expose"=true})
     */
    public function listarTiposAction()
	{
		$em = $this->getDoctrine()->getEntityManager();		
		$repo = $em->getRepository('MbpFinanzasBundle:FormasPago');
		$resu = $repo->findAll();
		
		$resp = array();
		$i=0;
		foreach ($resu as $res) {
			$resp[$i]['id'] = $res->getId();
			$resp[$i]['descripcion'] = $res->getDescripcion();
			$i++;
		}
		echo json_encode(array(
			'items' => $resp
		));
		return new Response();
	}
}





















