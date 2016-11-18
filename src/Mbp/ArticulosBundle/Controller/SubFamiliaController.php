<?php
namespace Mbp\ArticulosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Mbp\ArticulosBundle\Entity\FamiliaRepository;
use Mbp\ArticulosBundle\Entity\Familia;
use \Doctrine\ORM\Query;

class SubFamiliaController extends Controller
{
	/**
     * @Route("/listarSubFamilias", name="mbp_articulos_listarSubFamilias", options={"expose"=true})
     */
    public function listarSubFamiliasAction()
	{
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpArticulosBundle:SubFamilia');
		
		$qb = $repo->createQueryBuilder('f')
			->select('f.id AS idSubFamilia, f.subFamilia')
			->where('f.isActive = 1')
			->getQuery()
			->getResult();
		
		
		return new Response(json_encode(array(
			'success' => true,
			'data' => $qb
		)));
	}
	
	/**
     * @Route("/crearSubFamilia", name="mbp_articulos_crearSubFamilia", options={"expose"=true})
     */
    public function crearSubFamiliaAction()
	{
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpArticulosBundle:SubFamilia');		
		$req = $this->getRequest();
		$data = json_decode($req->request->get('data'));
		
		$res = $repo->nuevaSubFamilia($data->subFamilia);	
		
		return new Response(json_encode(array(
			'success' => $res >= 1 ? true : false,
			'msg' => $res >= 1 ? '' : $res,
			'data' => $res >= 1 ? array('idSubFamilia' => $res) : '',
		)));
	}
	
	/**
     * @Route("/actualizarSubFamilia", name="mbp_articulos_actualizarSubFamilia", options={"expose"=true})
     */
    public function actualizarSubFamiliaAction()
	{
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpArticulosBundle:SubFamilia');		
		$req = $this->getRequest();
		$data = json_decode($req->request->get('data'));
		
		$res = $repo->actualizarReg($data);		
		
		return new Response(json_encode(array(
			'success' => $res == 1 ? true : false,
			'msg' => $res == 1 ? '' : $res,
			'data' => $res >= 1 ? array('idSubFamilia' => $res) : '',
		)));
	}
	
	/**
     * @Route("/eliminarSubFamilia", name="mbp_articulos_eliminarSubFamilia", options={"expose"=true})
     */
    public function eliminarSubFamiliaAction()
	{
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpArticulosBundle:SubFamilia');		
		$req = $this->getRequest();
		$data = json_decode($req->request->get('data'));
		
		$res = $repo->eliminarReg($data);		
		
		return new Response(json_encode(array(
			'success' => $res == 1 ? true : false,
			'msg' => $res == 1 ? '' : $res,
		)));
	}
}
