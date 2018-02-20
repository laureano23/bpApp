<?php

namespace Mbp\ComprasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Mbp\ComprasBundle\Entity\OrdenCompra;
use Mbp\ComprasBundle\Entity\OrdenCompraDetalle;
use Mbp\ComprasBundle\Entity\PedidosInternos;
use Mbp\ComprasBundle\Entity\PedidoInternoDetalle;

class ComprasController extends Controller
{
    /**
     * @Route("/ordenCompra/nuevaOrdenCompra", name="mbp_compras_nuevaOrden", options={"expose"=true})
     */
    public function nuevaOrdenCompraAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$response = new Response;
		$repo = $em->getRepository('MbpComprasBundle:OrdenCompra');
    	$req = $this->getRequest();
		$data = $req->request->get('detalle');
		$orden = $req->request->get('orden');
		$detalleData = json_decode($data);
		$ordenData = json_decode($orden);
		$usuario = $this->getUser()->getUserName();

		$repoArt = $em->getRepository('MbpArticulosBundle:Articulos');
		$repoProv = $em->getRepository('MbpProveedoresBundle:Proveedor');
		$repoParametros = $em->getRepository('MbpFinanzasBundle:ParametrosFinanzas');
		$tc = $repoParametros->find(1)->getDolarOficial();
		
		$orden = new OrdenCompra();		
		$detalle;
		$fechaEntrega;

		try{			
			foreach ($detalleData as $det) {
				$detalle = new OrdenCompraDetalle();
				//BUSCO ARTICULO
				$articulo = $repoArt->find($det->id);
				//FECHA DE ENTREGA
				$det->entrega == "" ? $fechaEntrega = new \DateTime : $fechaEntrega = \DateTime::createFromFormat('d/m/Y', $det->entrega);
				//CARGO DATOS DEL DETALLE DE OC			
				$detalle->setArticuloId($articulo);
				$detalle->setDescripcion($det->descripcion);
				$detalle->setUnidad($det->unidad);
				$detalle->setPrecio($det->costo);
				$detalle->setCant($det->cant);
				$detalle->setFechaEntrega($fechaEntrega);
				$detalle->setIva($det->iva);
				$det->iva == 21 ? $detalle->setIvaCalculado($det->cant * $det->costo * 0.21) : $detalle->setIvaCalculado($det->cant * $det->costo * 0.105); 
				$det->moneda == 'p' ? $detalle->setMoneda(0) : $detalle->setMoneda(1);

				//ACTUALIZO EL COSTO DEL ARTICULO SI ES NECESARIO
				if($det->actCosto == true){
					if($articulo->getMoneda() == 1 && $det->moneda == "d"){
						$articulo->setCosto($det->costo);
					}
					elseif($articulo->getMoneda() == 0 && $det->moneda == "d"){
						$articulo->setCosto($det->costo * $tc);
					}
					elseif($articulo->getMoneda() == 1 && $det->moneda == "p"){
						$articulo->setCosto($det->costo / $tc);
					}else{
						$articulo->setCosto($det->costo);
					}
				}
				$em->persist($articulo);
				
				$orden->addOrdenDetalleId($detalle);
			}
			
			//BUSCO PROVEEDOR
			$proveedor = $repoProv->find($ordenData->id);
			$orden->setProveedorId($proveedor);
			$orden->setUsuario($usuario);
			$orden->setFechaEmision(new \DateTime);
			$ordenData->monedaOC == "ARS" ? $orden->setMonedaOC(0) : $orden->setMonedaOC(1);
			$ordenData->monedaOC == "U\$D" ? $orden->setTc($ordenData->tc) : $orden->setTc($tc);  
			$orden->setCondicionCompra($ordenData->condCompra);
			$orden->setLugarEntrega($ordenData->lugar);
			$orden->setObservaciones($ordenData->observaciones);
			$orden->setDescuentoGral($ordenData->descuentoGral);
			
			//VALIDO LA ORDEN
			$validador = $this->get('validator');
			$errors = $validador->validate($orden); 
			$arrayError = array();
			foreach ($errors as $error) {
				array_push($arrayError, $error->getMessage());
			}


			if(count($errors) > 0){
				$response->setContent(
					json_encode(array('success' => false, 'msg' => $arrayError))
				);
				$response->setStatusCode($response::HTTP_BAD_REQUEST);
				return $response;				
			}
			
			$em->persist($orden);
			$em->flush();
			
	        return $response->setContent(
				json_encode(array(
					'success' => true,
					'idOC' => $orden->getId()
				))
			);
		}catch(\Exception $e){
			$response->setContent(
				json_encode(array(
					'success' => false,
					'msg' => $e->getMessage()
				))
			);
			return $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
		}	
    }

    /**
     * @Route("/ordenCompra/listarOrdenes", name="mbp_compras_listarOrdenes", options={"expose"=true})
     */
    public function listarOrdenesaAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$response = new Response;
		$repo = $em->getRepository('MbpComprasBundle:OrdenCompra');

		try{
			$data = $repo->createQueryBuilder('oc')
				->select("oc.id idOc, p.rsocial proveedor, DATE_FORMAT(oc.fechaEmision, '%d/%m/%Y') fecha")
				->join('oc.proveedorId', 'p')
				->orderBy('fecha', 'DESC')
				->getQuery()
				->getResult();

			return $response->setContent(
				json_encode(array('success' => true, 'data'=>$data)) 
			);
		}catch(\Exception $e){
			$response->setContent(
				json_encode(array('success' => false, 'msg'=>$e->getMessage())) 
			);
			return $response->setStatusCode($response::HTTP_INTERNAL_SERVER_ERROR);
    	}
	}
    
    /**
     * @Route("/ordenCompra/eliminarOrden", name="mbp_compras_eliminarOrden", options={"expose"=true})
     */
    public function eliminarOrdenAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$response = new Response;
    	$req = $this->getRequest();
		$repo = $em->getRepository('MbpComprasBundle:OrdenCompra');

		try{
			$ocId = $req->request->get('idOc');
			$orden = $repo->find($ocId);

			$em->remove($orden);
			$em->flush();			

			return $response->setContent(
				json_encode(array('success' => true)) 
			);
		}catch(\Exception $e){
			$response->setContent(
				json_encode(array('success' => false, 'msg'=>$e->getMessage())) 
			);
			return $response->setStatusCode($response::HTTP_INTERNAL_SERVER_ERROR);
    	}
	}

	/**
     * @Route("/ordenCompra/historicoCompra", name="mbp_compras_historicoCompra", options={"expose"=true})
     */
    public function historicoCompra()
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$response = new Response;
    	$req = $this->getRequest();
    	$codigo = $req->request->get('codigo');
		$repo = $em->getRepository('MbpComprasBundle:OrdenCompra');
		
		$query = $repo->createQueryBuilder('c')
			->select("DATE_FORMAT(c.fechaEmision, '%d/%m/%Y') fecha,	c.id idOc, a.codigo, a.descripcion, d.cant, d.precio, (CASE WHEN d.moneda=false THEN 'ARS' ELSE 'USD' END) moneda, p.rsocial as proveedor")
			->join('c.proveedorId', 'p')
			->join('c.ordenDetalleId', 'd')
			->join('d.articuloId', 'a')
			->where('a.codigo = :cod')
			->setParameter('cod', $codigo)
			->getQuery()
			->getResult();
			
		return $response->setContent(
				json_encode(array('success' => true, 'data' => $query)) 
			);
		
    }
	
	/**
     * @Route("/pedidosInternos/nuevoPedido", name="mbp_pedidosInternos_nuevoPedido", options={"expose"=true})
     */
    public function nuevoPedido()
    {
    	$req = $this->getRequest();
		$em = $this->getDoctrine()->getEntityManager();
		$response = new Response;
		
		try{
			$articulos = $req->request->get('articulos');
			$articulos = json_decode($articulos);
			
					
	    	$pedido = new PedidosInternos;
			$pedido->setEmision(new \DateTime);
			$pedido->setUsuarioId($this->getUser());
			
			//CARGAMOS LOS DETALLES
			foreach ($articulos as $art) {
				$detalle = new PedidoInternoDetalle;
				$detalle->setDescripcion($art->descripcion);
				$detalle->setCantidad($art->cant);
				$detalle->setUnidad($art->unidad);
				$detalle->setEntrega(\DateTime::createFromFormat("d/m/Y", $art->entrega));
				
				$pedido->addDetalleId($detalle);			
			}
			
			
			$em->persist($pedido);
			$em->flush();
				
	    	return $response->setContent(
				json_encode(array('success' => true))
			);
		}catch(\Exception $e){
			$response->setContent(
				json_encode(array('success' => false, 'msg'=>$e->getMessage())) 
			);
			return $response->setStatusCode($response::HTTP_INTERNAL_SERVER_ERROR);
    	}
		
		
    }

	/**
     * @Route("/pedidosInternos/listarPedidos", name="mbp_pedidosInternos_listarPedidos", options={"expose"=true})
     */
    public function listarPedidos()
    {
    	$req = $this->getRequest();
		$em = $this->getDoctrine()->getEntityManager();
		$response = new Response;
		
		try{
			$repo = $em->getRepository('MbpComprasBundle:PedidosInternos');
			
			$data = $repo->listarPendientes();
				
	    	return $response->setContent(
				json_encode(array('success' => true, 'data' => $data))
			);
		}catch(\Exception $e){
			$response->setContent(
				json_encode(array('success' => false, 'msg'=>$e->getMessage())) 
			);
			return $response->setStatusCode($response::HTTP_INTERNAL_SERVER_ERROR);
    	}
    }
	
	/**
     * @Route("/pedidosInternos/actualizarPedido", name="mbp_pedidosInternos_actualizarPedido", options={"expose"=true})
     */
    public function actualizarPedido()
    {
    	$req = $this->getRequest();
		$em = $this->getDoctrine()->getEntityManager();
		$response = new Response;
		
		try{
			$pedido = $req->request->get('data');
			$data = json_decode($pedido);
			$repo = $em->getRepository('MbpComprasBundle:PedidoInternoDetalle');
			
			$row = $repo->find($data->id);
			
			$row->setPedido($data->pedido);
			$row->setCumplido($data->cumplido);
			
			if($row->getCumplido() >= $row->getCantidad()){
				$row->setInactivo(TRUE);
			}
			
			$em->persist($row);
			$em->flush();			
				
	    	return $response->setContent(
				json_encode(array('success' => true))
			);
		}catch(\Exception $e){
			$response->setContent(
				json_encode(array('success' => false, 'msg'=>$e->getMessage())) 
			);
			return $response->setStatusCode($response::HTTP_INTERNAL_SERVER_ERROR);
    	}
    }
}
