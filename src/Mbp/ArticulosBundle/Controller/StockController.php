<?php

namespace Mbp\ArticulosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Mbp\ArticulosBundle\Entity\ArticulosRepository;
use Mbp\ArticulosBundle\Clases\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Mbp\ArticulosBundle\Entity\MovimientosArticulos;
use Mbp\ArticulosBundle\Entity\DetalleMovArt;



class StockController extends Controller
{
	/**
     * @Route("/listarIngresos", name="mbp_articulos_listarIngresos", options={"expose"=true})
     */
    public function listarIngresos()
	{
		$em = $this->getDoctrine()->getManager();
		$response = new Response;
		$req = $this->get('request');
		
		try{
			$rep = $em->getRepository('MbpArticulosBundle:MovimientosArticulos');
			$id = $req->request->get('idOrigen');
			$origen = $req->request->get('origen2');
			
			
			
				
			$qb = $rep->createQueryBuilder('m');
			
			
			$data = $qb->select('art.codigo, d.descripcion, d.id, d.cantidad AS cant, d.loteNum AS lote, oc.id AS idOc')
				->join('m.movDetalleId', 'd')
				->leftJoin('m.proveedorId', 'prov')
				->leftJoin('m.clienteId', 'cliente')
				->leftJoin('d.articuloId', 'art')
				->leftJoin('d.ordenCompraId', 'oc')
				->where('prov.id = :id')
				->orWhere('cliente = :id')
				->setParameter('id', $id)
				->orderBy('m.fechaMovimiento', 'DESC')
				->getQuery()
				->getArrayResult();	
		
			
				
			return $response->setContent(
				json_encode(array('success' => true, 'data' => $data))
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
	
	/**
     * @Route("/nuevoMovArt", name="mbp_articulos_nuevoMovArt", options={"expose"=true})
     */
    public function nuevoMovArt()
	{
		$em = $this->getDoctrine()->getManager();
		$req = $this->get('request');
		$response = new Response;
		$repoConcepto = $em->getRepository('MbpArticulosBundle:ConceptosStock');
		$repoArt = $em->getRepository('MbpArticulosBundle:Articulos');
		$repoProveedor = $em->getRepository('MbpProveedoresBundle:Proveedor');
		$repoCliente = $em->getRepository('MbpClientesBundle:Cliente');
		$repoDeposito = $em->getRepository('MbpArticulosBundle:DepositoArticulos');
		$repoOC = $em->getRepository('MbpComprasBundle:OrdenCompra');
		
		try{
			$data = json_decode($req->request->get('data'));
			$articulos = json_decode($req->request->get('articulos'));
			
			$concepto;
			try{
				$concepto = $repoConcepto->find($data->conceptoId);
				if($concepto == ""){
					throw new \Exception("No existe el concepto", 1);					
				}
			}catch(\Exception $e){
				$msg = json_encode(array(
					'success' => false,
					'msg' => $e->getMessage()
				));
				
				return $response($msg, 500);
			}
			
			$fuente;
			try{
				if($data->origen == "proveedor"){
					$fuente = $repoProveedor->find($data->idOrigen);	
				}else{
					$fuente = $repoCliente->find($data->idOrigen);
				}
				
				if($fuente == ""){
					throw new \Exception("No existe el cliente/proveedor", 1);					
				}
			}catch(\Exception $e){
				$msg = json_encode(array(
					'success' => false,
					'msg' => $e->getMessage()
				));
				
				return $response($msg, 500);
			}
			
			$deposito=null;
			try{
				$deposito = $repoDeposito->find($data->depositoId);
			}catch(\Exception $e){
				$msg = json_encode(array(
					'success' => false,
					'msg' => $e->getMessage()
				));
				
				return $response($msg, 500);
			}
			
			$movimiento = new MovimientosArticulos;
			$movimiento->setFechaMovimiento(new \DateTime);
			$data->tipoMovimiento == "Entrada" ? $movimiento->setTipoMovimiento(0) : $movimiento->setTipoMovimiento(1);
			$movimiento->setObservaciones($data->observaciones);
			$movimiento->setComprobanteNum($data->comprobanteNum);
			$movimiento->setConceptoId($concepto);
			$data->origen == "proveedor" ? $movimiento->setProveedorId($fuente) : $movimiento->setClienteId($fuente);
			$movimiento->setDepositoId($deposito);
			
			
			/* AGREGAR DETALLES AL MOVIMIENTO */	
			$validator = $this->get('validator');//VALIDADOR		
			foreach ($articulos as $art) {
				//BUSCAMOS OC
				$oc = $repoOC->find($art->idOc);	
				$objArt = $repoArt->findOneByCodigo($art->codigo);
				
				
				$detalle = new DetalleMovArt;
				$detalle->setCantidad($art->cant);
				$art->loteNum == 0 ? $detalle->setLoteNum(null) : $detalle->setLoteNum($art->loteNum);
				$detalle->setDescripcion($art->descripcion);
				$oc == "" ? $detalle->setOrdenCompraId(null) : $detalle->setOrdenCompraId($oc);
				$detalle->setArticuloId($objArt);
				
				$movimiento->addMovDetalleId($detalle);
				
				//VALIDAMOS LA ENTIDAD				
				$error = $validator->validate($detalle);
				
				if(count($error) != 0){
					throw new \Exception("error de validacion", 1);
					
				}
			}
			
			$error = $validator->validate($movimiento);				
			if(count($error) != 0){
				throw new \Exception("error de validacion", 1);					
			}
			
			$em->persist($movimiento);
			$em->flush();
			
			$msg = array(
				'success' => true
			);
			
			return $response->setContent(json_encode($msg));
			
		}catch(\Exception $e){
			$msg = json_encode(array(
				'success' => false,
				'msg' => $e->getMessage()
			));
			
			return new Response($msg, 500);
		}
	}
}
















