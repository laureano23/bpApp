<?php

namespace Mbp\ArticulosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Mbp\ArticulosBundle\Entity\PosEnfriadores;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
//use Mbp\ArticulosBundle\Entity\FormulasRepository;

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
			->select('e.codigo, e.descripcion, e.nombreImagen')
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
		
		$res = $rep->formulasList(7, 17.1);
		
		//print_r($res);
		echo json_encode($res);
		
		return new Response;
    }
}