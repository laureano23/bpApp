<?php

namespace Mbp\CalidadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class ControlRecepcionController extends Controller
{
    /**
     * @Route("/controlIngreso", name="mbp_calidad_controlIngreso", options={"expose"=true})
     */
    public function controlIngreso()
	{
		$em = $this->getDoctrine()->getManager();
		$response = new Response;
		$req = $this->get('request');
		
		try{
			$res=json_decode($req->request->get('data'));
			$rep = $em->getRepository('MbpArticulosBundle:DetalleMovArt');
			
			foreach ($res as $r) {				
				$item=$rep->find($r->id);
				
				if($r->estadoCalidad == "Aprobado"){
					$item->setEstadoCalidad(1);	
				}else{
					$item->setEstadoCalidad(0);
				}
				
				$item->setCertificadoNum($r->certificadoNum);
				$item->setDetalleControl($r->detalleControl);
				$em->persist($item);
			}
			
			$em->flush();	
			return $response->setContent(
				json_encode(array('success' => true))
			);
			
		}catch(\Exception $e){
			$msg = json_encode(array(
				'success' => false,
				'msg' => $e->getMessage()
			));
			
			return new Response($msg, 500);
		}	
	}
}



















