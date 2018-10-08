<?php

namespace Mbp\FinanzasBundle\Controller;	

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Mbp\FinanzasBundle\Entity\Cotizacion;
use Mbp\FinanzasBundle\Entity\DetalleCotizacion;

class CotizacionController extends Controller
{
	
	/**
     * @Route("/cotizaciones/nuevaCotizacion", name="mbp_finanzas_cotizaciones_nueva", options={"expose"=true})
     */
    public function nuevaCotizacion()
    {
    	$response=new Response;
		$em = $this->getDoctrine()->getManager();
		$repoArticulos = $em->getRepository('MbpArticulosBundle:Articulos');
		$repoClientes = $em->getRepository('MbpClientesBundle:Cliente');
		
		try{
			$req = $this->getRequest();
			$coti=json_decode($req->request->get('data'));
			$detalles=json_decode($req->request->get('items'));
			//$total=$req->request->get('total');
			$descuento=$req->request->get('descuento');
			
			
			$cotizacion=new Cotizacion;
			$cotizacion->setCondVenta($coti->condVenta);
			$cotizacion->setCuit($coti->cuit);
			$cotizacion->setDireccion($coti->direccion);
			$cotizacion->setEmision(new \DateTime);
			$cotizacion->setMoneda($coti->monedaCoti == "ARS" ? 0 : 1);
			$cotizacion->setObservaciones($coti->observaciones);
			$cotizacion->setTc($coti->tc);
			
			$cliente=$repoClientes->find($coti->id);
			//if(empty($cliente)) throw new \Exception("Cliente no encontrado", 1);
			$cotizacion->setCliente($coti->cliente);
			
			$cotizacion->setClienteId($cliente);
			$cotizacion->setIdUsuario($this->get('security.context')->getToken()->getUser());
			
			$totalGral=0;
			foreach ($detalles as $d) {
				$detalle=new DetalleCotizacion;
				$detalle->setDescripcion($d->descripcion);
				$detalle->setCant($d->cant);
				$fechaEntrega=empty($d->entrega) ? null : \DateTime::createFromFormat("d/m/Y", $d->entrega); 
				$detalle->setEntrega($fechaEntrega);
				$detalle->setPrecio($d->precio);
				$detalle->setUnidad($d->unidad);
				
				$art=$repoArticulos->find($d->id);
				if(empty($art)) throw new \Exception("Articulo no encontrado", 1);
				$detalle->setArticuloId($art);
				$detalle->setCotizacion($cotizacion);
				
				$cotizacion->addDetalle($detalle);
				$totalGral+=($d->precio*$d->cant);
			}

			$calculoDescuento=$totalGral*$descuento/100;
			$cotizacion->setTotal($totalGral-$calculoDescuento);

			
			$cotizacion->setDescuento($calculoDescuento);
			
			$em->persist($cotizacion);
			$em->flush();
		
			return $response->setContent(json_encode(array('success' => true, 'idCoti' => $cotizacion->getId())));	
		}catch(\Exception $e){
			//throw $e;
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			return $response->setContent(json_encode(array('success' => FALSE, 'msg'=>$e->getMessage())));
		}	
	}

	/**
     * @Route("/cotizaciones/listarCotizaciones", name="mbp_finanzas_cotizaciones_listar", options={"expose"=true})
     */
    public function listarCotizaciones(Request $req)
    {
    	$response=new Response;
		$em = $this->getDoctrine()->getManager();
		$repoCoti = $em->getRepository('MbpFinanzasBundle:Cotizacion');
		
		try{
			$idCodigo = $req->query->get('idCodigo');
			
			if($idCodigo > 0){//filtramos por codigo el listado
				$cotizaciones=$repoCoti->listarCotizacionesPorCodigo($idCodigo);
			}else{
				$cotizaciones=$repoCoti->listarCotizaciones();
			}			
		
			return $response->setContent(json_encode(array('success' => true, 'data' => $cotizaciones)));	
		}catch(\Exception $e){
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			return $response->setContent(json_encode(array('success' => FALSE, 'msg'=>$e->getMessage())));
		}	
    }
	
	/**
     * @Route("/cotizaciones/eliminarCotizacion", name="mbp_finanzas_cotizaciones_eliminar", options={"expose"=true})
     */
    public function eliminarCotizacion()
    {
    	$response=new Response;
		$em = $this->getDoctrine()->getManager();
		$repoCoti = $em->getRepository('MbpFinanzasBundle:Cotizacion');
		
		try{
			$req = $this->getRequest();
			$coti=json_decode($req->request->get('data'));
			
			$cotizacion=$repoCoti->find($coti->id);
			
			$em->remove($cotizacion);
			$em->flush();
		
			return $response->setContent(json_encode(array('success' => true)));	
		}catch(\Exception $e){
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			return $response->setContent(json_encode(array('success' => FALSE, 'msg'=>$e->getMessage())));
		}	
    }
}





















