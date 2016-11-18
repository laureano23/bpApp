<?php

namespace Mbp\ArticulosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Mbp\ArticulosBundle\Entity\ArticulosRepository;
use Mbp\ArticulosBundle\Entity\Articulos;

class ArticulosController extends Controller
{
    public function articuloslistAction()
    {    	
    	$em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
				
		$rep = $em->getRepository('MbpArticulosBundle:Articulos');
		$res = $rep->listarArticulos();
		
		return new Response();
    }
	
	public function articuloConCostoAction()
	{
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();
		$idArt = $request->request->get('id');
				
		$repoFormula = $em->getRepository('MbpArticulosBundle:Formulas');
		$repoArt = $em->getRepository('MbpArticulosBundle:Articulos');
		$tipoCambio = $this->get('TipoCambio');
		
		$articulo = $repoArt->find($idArt);
		
		$existeEnFormula = $repoFormula->tieneFormula($articulo);
						
		$costo = 0;
		
		if($existeEnFormula){
			$qb = $repoFormula->costoArticuloConFormula($existeEnFormula);
			
			$costo=0.0;
			foreach ($qb as $rec) {
				if($rec['moneda'] == 1){
					$dolar = $tipoCambio->getTipoCambio();
				}else{
					$dolar = 1;
				}
				$costo = $costo + (float)$rec['costo'] * (float)$rec['cantidad'] * $dolar;
				
			}			
		}else{
			$costo = $articulo->getCosto();
		}
		
		$art = 	$repoArt->detalleArticulo($idArt);
		
		$art[0]['costo'] = $costo;
		
		echo json_encode(array(
			'success' => true,
			'data' => $art
		));
		return new Response();
	}
	
	public function articulosCreateAction()
	{
		$em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
		
		$data = $request->request->get('data');
				
		$rep = $em->getRepository('MbpArticulosBundle:Articulos');
		$validator = $this->get('validator');
		$res = $rep->crearArticulo($data, $validator);
		
		$response = new Response();
		$response->setContent(json_encode($res));
		
		if(array_key_exists('tipo', $res)){
			$response->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
		}elseif($res['success'] == false){
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
		}else{
			$response->setStatusCode(Response::HTTP_OK);
		}
		
		return $response;
	}
	
	public function articulosdestroyAction()
	{
		$em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
		
		$data = $request->request->get('data');
		
		$rep = $em->getRepository('MbpArticulosBundle:Articulos');
		$res = $rep->deleteArticulo($data);
		
		return new Response();
	}	
	
	public function validartAction()
	{
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();
		
		$cod = $request->request->get('codigo');
		
		
		$rep = $em->getRepository('MbpArticulosBundle:Articulos');
		$res = $rep->validArt($cod);
		
		return new Response();
	}
}
















