<?php

namespace Mbp\FinanzasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/bancos/listar", name="mbp_finanzas_listaBancos", options={"expose"=true})
     */
    public function listaBancosAction()
    {
    	$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpFinanzasBundle:Bancos');
		$res = $repo->findAll();
		
		$resu = array();
		
		$i=0;
		foreach ($res as $reg) {
			$resu[$i]['id'] = $reg->getId();
			$resu[$i]['nombre'] = $reg->getNombre();
			$i++;
		}
		echo json_encode(array(
			'success' => true,
			'items' => $resu
		));
		
        return new Response();
    }
}
