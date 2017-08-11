<?php

namespace Mbp\ArticulosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Mbp\ArticulosBundle\Entity\ArticulosRepository;
use Mbp\ArticulosBundle\Clases\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;



class StockController extends Controller
{
	/**
     * @Route("/listarConceptosStock", name="mbp_articulos_listarConceptosStock", options={"expose"=true})
     */
    public function listarConceptosStock()
	{
		$em = $this->getDoctrine()->getManager();
		$response = new Response;
		
		try{
			$rep = $em->getRepository('MbpArticulosBundle:ConceptosStock');
			
			$data = $rep->createQueryBuilder('s')
				->select('')
				->getQuery()
				->getArrayResult();	
				
			$resp = json_encode(array(
				'success' => true,
				'data' => $data
			));
			
			$response = new Response($resp, 200);
			
			return $response;
		}catch(\Exception $e){
			$msg = json_encode(array(
				'success' => false,
				'msg' => 'Error al obtener los conceptos de Stock'
			));
			
			return new Response($msg, 500);
		}		
	}
    
	/**
     * @Route("/listarDepositos", name="mbp_articulos_listarDepositos", options={"expose"=true})
     */
    public function listarDepositos()
	{
		$em = $this->getDoctrine()->getManager();
		$response = new Response;
		
		try{
			$rep = $em->getRepository('MbpArticulosBundle:DepositoArticulos');
			
			$data = $rep->createQueryBuilder('d')
				->select('')
				->getQuery()
				->getArrayResult();	
				
			$resp = json_encode(array(
				'success' => true,
				'data' => $data
			));
			
			$response = new Response($resp, 200);
			
			return $response;
		}catch(\Exception $e){
			$msg = json_encode(array(
				'success' => false,
				'msg' => 'Error al obtener los depósitos de artículos'
			));
			
			return new Response($msg, 500);
		}		
	}
	
	/**
     * @Route("/pendientesDeIngreso", name="mbp_articulos_pendientesDeIngreso", options={"expose"=true})
     */
    public function pendientesDeIngreso()
	{
		$em = $this->getDoctrine()->getManager();
		$req = $this->get('request');
		$response = new Response;
		
		try{
			$rep = $em->getRepository('MbpComprasBundle:OrdenCompra');
			$codigo = $req->request->get('codigo');
			
			$data = $rep->articulosPendientesIngreso($codigo);			
			
			$i=0;
			foreach ($data as $row) {
				//TERMINAR DE PLANTEAR EL FILTRO SACANDO LOS PENDIENTES <= 0
				if($row['pendiente'] <= 0){
					unset($data[$i]);	
				}				
				$i++;
			}
							
			$resp = json_encode(array(
				'success' => true,
				'data' => $data
			));
			
			$response = new Response($resp, 200);
			
			return $response;
		}catch(\Exception $e){
			$msg = json_encode(array(
				'success' => false,
				'msg' => $e->getMessage()
			));
			
			return new Response($msg, 500);
		}		
	}
}
















