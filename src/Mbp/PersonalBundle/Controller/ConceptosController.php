<?php

namespace Mbp\PersonalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ConceptosController extends Controller
{

	/**
     * @Route("/borrarAntiguedad", name="mbp_personal_borrarAntiguedad", options={"expose"=true})
     */
    public function borrarAntiguedadAction()
	{
		$em = $this->getDoctrine()->getManager(); 
		$req = $this->getRequest();
		$repo = $em->getRepository('MbpPersonalBundle:Recibos');
		$repoDetalle = $em->getRepository('MbpPersonalBundle:RecibosDetalle');
		
		$res = $repo->createQueryBuilder('r')
			->select('rd')
			->join('r.reciboDetalleId', 'd')
			->join('d.codigoSueldos', 'cod')
			->where('cod.id = :cod')
			->andWhere('r.periodo = :per')
			->setParameter('cod', 5)
			->setParameter('per', 8)
			->getQuery()
			->getResult();

			//var_dump($res);

		\Doctrine\Common\Util\Debug::dump($user);

		
		return new Response();
	}

	/**
     * @Route("/conceptosRead", name="mbp_personal_conceptosRead", options={"expose"=true})
     */
    public function conceptosReadAction()
	{
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$esVariable = $req->query->get('variable');
		$idPersonal = $req->query->get('idP');
		
		$repo = $em->getRepository('MbpPersonalBundle:CodigoSueldos');		
		$repo->listarCodigos($esVariable, $idPersonal);
		
		return new Response();
	}
	
	/**
     * @Route("/conceptosCreate", name="mbp_personal_conceptosCreate", options={"expose"=true})
     */
	public function conceptosCreateAction()
	{
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$data = $req->request->get('data');
		$jsonData = json_decode($data);
				
		$repo = $em->getRepository('MbpPersonalBundle:CodigoSueldos');		
		$repo->crearConcepto($jsonData);
		
		return new Response();
	}
	
	/**
     * @Route("/conceptosDelete", name="mbp_personal_conceptosDelete", options={"expose"=true})
     */
	public function conceptosDeleteAction()
	{
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$data = $req->request->get('data');
		$jsonData = json_decode($data);
				
		$repo = $em->getRepository('MbpPersonalBundle:CodigoSueldos');		
		$repo->eliminaConcepto($jsonData);
		
		return new Response();
	}
}





















