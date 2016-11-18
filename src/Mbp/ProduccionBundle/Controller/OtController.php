<?php

namespace Mbp\ProduccionBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Mbp\ProduccionBundle\Entity\Ot;

class OtController extends Controller
{
	public function readOtPanelesAction()
	{
		$em = $this->getDoctrine()->getManager();
		$rep = $em->getRepository('MbpProduccionBundle:Ot');
        $rep->readOtPaneles();
       
		
		
		return new Response();
	}
    public function nuevaOtPanelAction()
    {
       $em = $this->getDoctrine()->getManager();
       $request = $this->getRequest();
		
		$data = $request->request->get('data');		
		$stdObj = json_decode($data);
		
        $rep = $em->getRepository('MbpProduccionBundle:Ot');
        $rep->NewOtPanel($stdObj);
        
        return new Response();
    }
	
	public function verificaOtAction()
	{
		$em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
		
		$ot = $request->request->get('ot');
		$rep = $em->getRepository('MbpProduccionBundle:Ot');
        $rep->verificaOt($ot);
		
		return new Response();
	}
	
	public function verificaPanelCalculadoAction()
	{
		$em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
		
		$data = $request->request->get('idCodigo');
		$rep = $em->getRepository('MbpProduccionBundle:Ot');
        $rep->VerificaPanel($data);
		
		return new Response();
	}
}
