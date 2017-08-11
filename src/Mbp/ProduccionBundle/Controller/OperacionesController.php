<?php
namespace Mbp\ProduccionBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class OperacionesController extends Controller
{
	/**
     * @Route("/operacionesList", name="mbp_produccion_operaciones_list", options={"expose"=true})
     */
	public function operacionesListAction()
	{	
		$em = $this->getDoctrine()->getEntityManager();
		$repo = $em->getRepository('MbpProduccionBundle:Operaciones');
		$req = $this->getRequest();
		
		try{
			$sector = $req->query->get('sector');
			
			//var_dump($sector);
			
			$data = "";
			if($sector != ""){
				$data = json_encode(array('success' => true, 'items' => $repo->listarPorSector($sector)));
				
				return new Response($data);
			}else{
				return new Response($repo->listarOperaciones());
			}
		}catch(\Exception $e){
			$resp = json_encode(array('success' => false, 'msg' => $e->getMessage()));
			
			return new Response($resp, 500);
		}
		
			
		
		
		
	}
	
	/**
     * @Route("/operacionesCreate", name="mbp_produccion_operaciones_create", options={"expose"=true})
     */
	public function operacionesCreateAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$req = $this->getRequest();
		$data = $req->request->get('data');
		$repo = $em->getRepository('MbpProduccionBundle:Operaciones');
		
		$repo->saveOPeracion($data);
		
		
		return new Response();
	}
	
	/**
     * @Route("/operacionesSoldadura", name="mbp_produccion_operacionesCreate", options={"expose"=true})
     */
	public function operacionesListSoldadura()
	{	
		$em = $this->getDoctrine()->getEntityManager();
		$repo = $em->getRepository('MbpProduccionBundle:Operaciones');
		
		try{
			//$repo->listarOperaciones();
			$query = $repo->createQueryBuilder('o')
				->select('')
				->join('o.centroCosto', 'c')
				->where('c.descripcion = :desc')
				->setParameter('desc', 'SOLDADO')
				->getQuery()
				->getArrayResult();
			
			$resp = json_encode(array('success' => true, 'data' => $query));
			
			return new Response($resp);
			
		}catch(\Exception $e){
			
		}
			
	}
}

































