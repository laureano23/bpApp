<?php

namespace Mbp\FinanzasBundle\Controller;	

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class VendedorController extends Controller
{
	/**
     * @Route("/vendedor/listarVendedores", name="mbp_finanzas_listarVendedores", options={"expose"=true})
     */
    public function listarVendedores()
    {
    	$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpFinanzasBundle:Vendedor');
		$response = new Response;
		
		try{
			
			$res=$repo->createQueryBuilder('v')
				->select('')
				->getQuery()
				->getArrayResult();
			
			return $response->setContent(json_encode(array('success' => true, 'data' => $res)));
		}catch(\Exception $e){
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			return $response->setContent(json_encode(array('success' => false, 'msg' => $e->getMessage())));
		}
    } 
}





















