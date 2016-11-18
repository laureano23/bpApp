<?php
namespace Mbp\ArticulosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Mbp\ArticulosBundle\Entity\FamiliaRepository;
use Mbp\ArticulosBundle\Entity\Familia;
use \Doctrine\ORM\Query;

class FamiliaController extends Controller
{
	/**
     * @Route("/listarFamilias", name="mbp_articulos_listarFamilias", options={"expose"=true})
     */
    public function listarFamiliasAction()
	{
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpArticulosBundle:Familia');
		
		$qb = $repo->createQueryBuilder('f')
			->select('f.id AS idFamilia, f.familia')
			->where('f.isActive = 1')
			->getQuery()
			->getResult();
		
		
		return new Response(json_encode(array(
			'success' => true,
			'data' => $qb
		)));
	}
	
	/**
     * @Route("/crearFamilia", name="mbp_articulos_crearFamilia", options={"expose"=true})
     */
    public function crearFamiliaAction()
	{
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpArticulosBundle:Familia');		
		$req = $this->getRequest();
		$data = json_decode($req->request->get('data'));
		
		$res = $repo->nuevaFamilia($data->familia);	
		
		return new Response(json_encode(array(
			'success' => $res >= 1 ? true : false,
			'msg' => $res >= 1 ? '' : $res,
			'data' => $res >= 1 ? array('idFamilia' => $res) : '',
		)));
	}
	
	/**
     * @Route("/actualizarFamilia", name="mbp_articulos_actualizarFamilia", options={"expose"=true})
     */
    public function actualizarFamiliaAction()
	{
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpArticulosBundle:Familia');		
		$req = $this->getRequest();
		$data = json_decode($req->request->get('data'));
		
		$res = $repo->actualizarReg($data);		
		
		return new Response(json_encode(array(
			'success' => $res == 1 ? true : false,
			'msg' => $res == 1 ? '' : $res,
			'data' => $res >= 1 ? array('idFamilia' => $res) : '',
		)));
	}
	
	/**
     * @Route("/eliminarFamilia", name="mbp_articulos_eliminarFamilia", options={"expose"=true})
     */
    public function eliminarFamiliaAction()
	{
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpArticulosBundle:Familia');		
		$req = $this->getRequest();
		$data = json_decode($req->request->get('data'));
		
		$res = $repo->eliminarReg($data);		
		
		return new Response(json_encode(array(
			'success' => $res == 1 ? true : false,
			'msg' => $res == 1 ? '' : $res,
			//'data' => $res >= 1 ? array('idFamilia' => $res) : '',
		)));
	}
}
