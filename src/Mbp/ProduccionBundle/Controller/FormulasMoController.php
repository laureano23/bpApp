<?php
namespace Mbp\ProduccionBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Mbp\ProduccionBundle\Entity\OperacionesFormula;

class FormulasMoController extends Controller
{
	public function formulasMoListAction()
	{		
		//TRAIGO CODIGO
		$req = $this->getRequest();
		$codigo = $req->query->get('codigo');

		//EM
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpProduccionBundle:OperacionesFormula');
		
		//CONTROLADOR AUXILIAR PARA PARAMETROS DE FINANZAS
		$auxFinanzas = $this->get('tipo_cambio');
		$tc = $auxFinanzas->getTipoCambio();
		
		
		$formula = $repo->formulaSegunArt($codigo, $tc);	
				
		return new Response();
	}
	
	public function formulasMoCreateAction()
	{
		//TRAIGO CODIGO
		$req = $this->getRequest();
		$codigo = $req->request->get('codigo');
		$data = $req->request->get('data');
				
		//EM
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpProduccionBundle:OperacionesFormula');
		$repo->nuevoItem($codigo, $data);
		
		
		return new Response();
	}
	
	public function formulasMoDeleteAction()
	{
		//EM		
		$em = $this->getDoctrine()->getManager();
		
		//TRAIGO CODIGO
		$req = $this->getRequest();		
		$data = $req->request->get('data');
		
		//TRAIGO CODIGO
		$req = $this->getRequest();		
		$data = $req->request->get('data');
		$info = json_decode($data);
		
		//REPO
		$repo = $em->getRepository('MbpProduccionBundle:OperacionesFormula');
		$item = $repo->find($info->id);
		$em->remove($item);
		$em->flush();
		
		
		echo json_encode(array(
			'success' => true
		));
		return new Response();
	}
}

































