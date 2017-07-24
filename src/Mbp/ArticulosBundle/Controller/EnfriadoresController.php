<?php

namespace Mbp\ArticulosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Mbp\ArticulosBundle\Entity\PosEnfriadores;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class EnfriadoresController extends ArticulosController
{
	
	/**
     * @Route("/listarEnfriadores", name="mbp_articulos_enfriadores_leer", options={"expose"=true})
     */
    public function listarEnfriadores()
    {
    	$em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('MbpArticulosBundle:Enfriadores');
		
		$enf = $rep->createQueryBuilder('e')
			->select('e.id, e.codigo, e.descripcion, e.nombreImagen')
			->getQuery()
			->getArrayResult();
			
		$response = new Response;
		
		$response->setContent(
			json_encode(
				array(
					'success' => true,
					'data' => $enf
				)
			)
		);
		
		return $response;
		
	}
	
	/**
     * @Route("/formulaEnfriadores", name="mbp_articulos_enfriadores_formula", options={"expose"=true})
     */
    public function formulaEnfriadores()
    {
    	$em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('MbpArticulosBundle:Formulas');
		$req = $this->getRequest();
		$response = new Response;
		
		try{
			$idNodo = $req->request->get('idNodo');
		
			$res = $rep->estructuraCompleta($idNodo, 17.1);
			
			//print_r($res);
			//echo json_encode($res);
			return	$response->setContent(
					json_encode(
						array('success' => true, 'items' => $res)
					)
				);
		}catch(\Exception $e){
			$response->setContent(Response::HTTP_INTERNAL_SERVER_ERROR);
			return json_encode(
				$response->setContent(
					array('success' => false, 'msg' => $e->getMessage())
				)
			);	
		}
		
		
		return new Response;
    }
}