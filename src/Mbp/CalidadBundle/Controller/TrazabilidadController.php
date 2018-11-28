<?php

namespace Mbp\CalidadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class TrazabilidadController extends Controller
{
    /**
     * @Route("/buscarCorrelativo", name="mbp_calidad_buscarCorrelativo", options={"expose"=true})
     */
    public function buscarCorrelativo()
	{
		$em = $this->getDoctrine()->getManager();
		$repo=$em->getRepository('MbpCalidadBundle:Correlativos');
		$response = new Response;
		$req = $this->get('request');
		
		try{
			$numCorrelativo=$req->request->get('numCorrelativo');
			$data=$repo->buscarCorrelativo($numCorrelativo);
			if(!empty($data)){
				$data=$data[0];
			}
			return $response->setContent(
				json_encode(array('success'=>true, 'data'=>$data))
			);
		}catch(\Exception $e){
			$msg = json_encode(array(
				'success' => false,
				'msg' => $e->getMessage()
			));
			
			return new Response($msg, 500);
		}	
	}

	/**
     * @Route("/asociarCorrelativo", name="mbp_calidad_asociarCorrelativo", options={"expose"=true})
     */
    public function asociarCorrelativo()
	{
		$em = $this->getDoctrine()->getManager();
		$repo=$em->getRepository('MbpCalidadBundle:Correlativos');
		$repoRemito=$em->getRepository('MbpArticulosBundle:RemitosClientes');
		$response = new Response;
		$req = $this->get('request');
		
		try{
			$correlativo=explode('-', $req->request->get('numCorrelativo'));
			$remito=$repoRemito->findOneByRemitoNum($req->request->get('remitoNum'));

			if($remito==null){
				throw new \Exception("No se encontró el número de remito ingresado", 1);				
			}

			foreach ($correlativo as $c) {
				$reg=$repo->findOneByNumCorrelativo($c);
				if($reg==null){
					throw new \Exception("No se encontró el número correlativo ".$c, 1);					
				}

				$reg->setRemitoId($remito);
				$em->persist($reg);
			}
			
			$em->flush();


			return $response->setContent(
				json_encode(array('success'=>true))
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



















