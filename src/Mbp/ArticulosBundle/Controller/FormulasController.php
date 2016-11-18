<?php
namespace Mbp\ArticulosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Mbp\ArticulosBundle\Entity\FormulasRepository;
use Mbp\ArticulosBundle\Entity\Formulas;
use Mbp\ArticulosBundle\Clases\FormulasClass;


class FormulasController extends Controller
{	
	/*
	 * RECIBE ID DE ARTICULO Y TRAE LE FORMULA DEL MISMO SOLO EN NIVEL DE PROFUNDIDAD 1
	 * */
	public function formulaslistAction()
	{
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpArticulosBundle:Formulas');
		$req = $this->getRequest();
		$tipoCambio = $this->get('TipoCambio');
		$tc = (float)$tipoCambio->getTipoCambio();
		
		$res = $repo->formulasList($req->query->get('art'), $tc);
		
		echo json_encode(array(
			'success' => true,
			'data' => $res
		));
		return new Response();
	}
	
	public function formulasBorrarNodoAction()
	{
		$req = $this->getRequest();
		$data = json_decode($req->request->get('data'));
		$em = $this->getDoctrine()->getManager();
		$tipoCambio = $this->get('TipoCambio');
		$tc = (float)$tipoCambio->getTipoCambio();
		
		$formula = new FormulasClass($data->idFormula, $data->id, $req->request->get('art'), $data->cant, $em, $tc);
		$delete = $formula->borrarNodo();
		
		$success;
		$msg = "";
		if($delete != 1){
			$success = false;
			$msg = $delete;
		}else{
			$success = true;
		}
				
		$resp = new Response(
			json_encode(array(
				'success' => $success,
				'msg' => $msg
			))
		);
					
		return $resp;	
	}
	
	public function formulasUpdateNodoAction()
	{
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpArticulosBundle:Formulas');		
		$req = $this->getRequest();
		$data = json_decode($req->request->get('data'));
		$tipoCambio = $this->get('TipoCambio');
		$tc = (float)$tipoCambio->getTipoCambio();
		
		$formula = new FormulasClass($data->idFormula, $data->id, $req->request->get('art'), $data->cant, $em, $tc);	
		$update = $formula->formulasUpdate();
		
		$success;
		$msg = "";
		if($update != 1){
			$success = false;
			$msg = $update;
		}else{
			$success = true;
		}
				
		$resp = new Response(
			json_encode(array(
				'success' => $success,
				'msg' => $msg
			))
		);
					
		return $resp;		
	}
	
	public function formulasInsertNodoAction()
	{
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpArticulosBundle:Formulas');
		$req = $this->getRequest();
		$data = json_decode($req->request->get('data'));
		$tipoCambio = $this->get('TipoCambio');
		$tc = (float)$tipoCambio->getTipoCambio();
		
		$formula = new FormulasClass($req->request->get('idFormula'), $data->id, $req->request->get('art'), $data->cant, $em, $tc);
		
		$idF = $formula->formulasInsert();
		
		$success;
		$msg="";
		if(!is_int($idF)){
			$success = false;
			$msg = $formula;
		}else{
			$success = true;
		}
		
		return new Response(
			json_encode(array(
				'success' => $success,
				'idFormula' => $idF,
				'msg' => $msg
			))
		);
	}
	
	/*
	 * RECIBE EL ID DE UN ARTICULO Y DEVULEVE EL ID DE SU FORMULA
	 * */
	public function formulasIdNodoAction()
	{
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpArticulosBundle:Formulas');
		$repoArt = $em->getRepository('MbpArticulosBundle:Articulos');
		
		$req = $this->getRequest();
		$idArticulo = $req->request->get('idArt');
		
		$articulo = $repoArt->find($idArticulo);
		
		$qb = $repo->createQueryBuilder('f')
			->select('f.id')
			->where('f.idArt = :art')
			->andWhere('f.cant = 0')
			->setParameter('art', $idArticulo)
			->getQuery()
			->getResult();
		
		$resp;
		if(empty($qb)){
			$resp = "";
		}else{
			$resp = $qb[0]['id'];
		}
		
		return new Response(
			json_encode(array(
				'success' => true,
				'idNodo' => $resp
			))
		);
	}	
}



















