<?php

namespace Mbp\ArticulosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Mbp\ArticulosBundle\Entity\RemitosClientes;
use Mbp\ArticulosBundle\Entity\RemitosClientesDetalles;
use Mbp\FinanzasBundle\Entity\ParametrosFinanzas;
use Mbp\ProduccionBundle\Entity\PedidoClientes;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;


class RemitosController extends Controller
{
    /**
     * @Route("/generarRemitoCliente", name="mbp_articulos_generarRemitoCliente", options={"expose"=true})
     */
    public function generarRemitoClienteAction()
    {
    	$em = $this->getDoctrine()->getManager();
        $req = $this->getRequest();
        $repo = $em->getRepository('MbpArticulosBundle:RemitosClientes');
        $repoClientes = $em->getRepository('MbpClientesBundle:Cliente');
		$repoProveedores = $em->getRepository('MbpProveedoresBundle:Proveedor');
        $repoArticulos = $em->getRepository('MbpArticulosBundle:Articulos');
        $repoParametros = $em->getRepository('MbpFinanzasBundle:ParametrosFinanzas');
        $repoPedidos = $em->getRepository('MbpProduccionBundle:PedidoClientesDetalle');
        $validador = $this->get('validator');	//VALIDADOR DE ENTIDADES SERVICIO
        $response = new Response;        

        try{
        	$items = json_decode($req->request->get('items'));
			$idCliente = json_decode($req->request->get('clienteId'));
			$errors = array();
			
			$origen = $req->get('origen') == "proveedor" ? $repoProveedores->find($idCliente) : $repoClientes->find($idCliente);
			$param = $repoParametros->find(1);


			$remito = new RemitosClientes;
			$remito->setFecha(new \DateTime);
			$remito->setRemitoNum($param->getRemitoNum());
			$req->get('origen') == "proveedor" ? $remito->setProveedorId($origen) : $remito->setClienteId($origen);

			

			foreach ($items as $item) {
				$remitoDetalle = new RemitosClientesDetalles;
				$remitoDetalle->setDescripcion($item->descripcion);
				$remitoDetalle->setCantidad($item->cantidad);
				$remitoDetalle->setUnidad($item->unidad);
				$remitoDetalle->setOc($item->oc);

				if($item->pedidoNum != ""){
					$pedido = $repoPedidos->find($item->pedidoNum);
					$remitoDetalle->setPedidoDetalleId($pedido);
					$pedido->setEntregado($pedido->getEntregado() + $item->cantidad);
					
					//SI LA CANTIDAD REMITADA + LAS PIEZAS YA ENTREGADAS ES MAYOR O IGUAL A LA CANTIDAD PEDIDA DAMOS DE BAJA EL PEDIDO
					if($pedido->getEntregado() >= $pedido->getCantidad()){
						$pedido->setInactivo(TRUE);
					}
					
					$em->persist($pedido);
				}
				

				$articulo = $repoArticulos->findByCodigo($item->codigo);
				$remitoDetalle->setArticuloId($articulo[0]);
				$remito->addDetalleRemito($remitoDetalle);


				$errors = $validador->validate($remito);
				
				if(count($errors) > 0){
					$resp = array('success' => false, 'tipo' => 'validacion');
					$errList = array();

					foreach ($errors as $error) {
						$errList[$error->getPropertyPath()] = $error->getMessage();
					}
					$resp['errors'] = $errList;


					$response->setContent(json_encode($resp));
					$response->setStatusCode($response::HTTP_BAD_REQUEST);

					return $response;
				}
			
				$em->persist($remito);
				//SE EJECUTA UN LISTENER PARA DESCONTAR STOCK DE CADA ITEM				
			}//EOF FOREACH ITEM
			$em->flush();

			$response->setContent(json_encode(array('success' => true, 'idRemito' => $remito->getId())));
			return $response;				
			
        }catch(\Exception $e){
        	$response->setContent(json_encode(array('success' => false, 'msg' => $e->getMessage())));
        	$response->setStatusCode($response::HTTP_INTERNAL_SERVER_ERROR);
        	return $response;        
        }
    }

	/**
     * @Route("/imprimirRemitoCliente", name="mbp_articulos_imprimirRemitoCliente", options={"expose"=true})
     */
    public function imprimirRemitoCliente()
    {
    	$em = $this->getDoctrine()->getManager();
        $req = $this->getRequest();
		$response = new Response;
        
		try{
			$idRemito = $req->request->get('idRemito');
			
			$reporteador = $this->get('reporteador');
			$kernel = $this->get('kernel');
			
			
			/*
			 * Configuro reporte
			 */
			$jru = $reporteador->jru();
			
			/*
			 * Ruta archivo Jasper
			 */				
					
			$ruta = $kernel->locateResource('@MbpArticulosBundle/Reportes/RemitoClientes.jrxml');
			$rutaLogo = $reporteador->getRutaLogo($kernel);
			
			/*
			 * Ruta de destino del PDF
			 */
			$destino = $kernel->locateResource('@MbpArticulosBundle/Resources/public/pdf/').'RemitoCliente.pdf';
			
			
			//Parametros HashMap
			$param = $reporteador->getJava('java.util.HashMap');
			$param->put('remitoId', $idRemito);
			$param->put('rutaLogo', $rutaLogo);
			
			$conn = $reporteador->getJdbc();
			
			$sql = "SELECT
			     RemitosClientes.`id` AS RemitosClientes_id,
			     RemitosClientes.`fecha` AS RemitosClientes_fecha,
			     RemitosClientes.`remitoNum` AS RemitosClientes_remitoNum,
			     RemitosClientes.`clienteId` AS RemitosClientes_clienteId,
			     RemitosClientes.`proveedorId` AS RemitosClientes_proveedorId,
			     RemitosClientesDetalles.`id` AS RemitosClientesDetalles_id,
			     RemitosClientesDetalles.`descripcion` AS RemitosClientesDetalles_descripcion,
			     RemitosClientesDetalles.`cantidad` AS RemitosClientesDetalles_cantidad,
			     RemitosClientesDetalles.`unidad` AS RemitosClientesDetalles_unidad,
			     RemitosClientesDetalles.`oc` AS RemitosClientesDetalles_oc,
			     RemitosClientesDetalles.`articuloId` AS RemitosClientesDetalles_articuloId,
			     RemitosClientesDetalles.`pedidoDetalleId` AS RemitosClientesDetalles_pedidoDetalleId,
			     RemitoClientes_detalle.`remitosclientes_id` AS RemitoClientes_detalle_remitosclientes_id,
			     RemitoClientes_detalle.`remitosclientesdetalles_id` AS RemitoClientes_detalle_remitosclientesdetalles_id,
			     cliente.`departamento` AS cliente_departamento,
			     cliente.`provincia` AS cliente_provincia,
			     cliente.`iva` AS cliente_iva,
			     cliente.`rsocial` AS cliente_rsocial,
			     cliente.`idCliente` AS cliente_idCliente,
			     cliente.`denominacion` AS cliente_denominacion,
			     cliente.`direccion` AS cliente_direccion,
			     cliente.`cuit` AS cliente_cuit,
			     cliente.`cPostal` AS cliente_cPostal,
			     PedidoClientesDetalle.`id` AS PedidoClientesDetalle_id,
			     PedidoClientesDetalle.`codigo` AS PedidoClientesDetalle_codigo,
			     PedidoClientesDetalle.`cantidad` AS PedidoClientesDetalle_cantidad,
			     PedidoClientesDetalle.`fechaProg` AS PedidoClientesDetalle_fechaProg,
			     PedidoClientesDetalle.`entregado` AS PedidoClientesDetalle_entregado,
			     PedidoClientesDetalle.`inactivo` AS PedidoClientesDetalle_inactivo,
			     PedidoClientesDetalle.`descripcion` AS PedidoClientesDetalle_descripcion,
			     Proveedor.`id` AS Proveedor_id,
			     Proveedor.`departamento` AS Proveedor_departamento,
			     Proveedor.`provincia` AS Proveedor_provincia,
			     Proveedor.`rsocial` AS Proveedor_rsocial,
			     Proveedor.`denominacion` AS Proveedor_denominacion,
			     Proveedor.`direccion` AS Proveedor_direccion,
			     Proveedor.`cuit` AS Proveedor_cuit,
			     PedidoClientes.`id` AS PedidoClientes_id,
			     PedidoClientes.`oc` AS PedidoClientes_oc,
			     PedidoClientes.`autEntrega` AS PedidoClientes_autEntrega,
			     pedidoId_detalleId.`pedidoId` AS pedidoId_detalleId_pedidoId,
			     pedidoId_detalleId.`detalleId` AS pedidoId_detalleId_detalleId,
			     PedidoClientes.`inactivo` AS PedidoClientes_inactivo,			     
				 articulos.`idArticulos` AS articulos_idArticulos,
				 articulos.`codigo` AS articulos_codigo
			FROM
			     `RemitosClientesDetalles` RemitosClientesDetalles INNER JOIN `RemitoClientes_detalle` RemitoClientes_detalle ON RemitosClientesDetalles.`id` = RemitoClientes_detalle.`remitosclientesdetalles_id`
			     INNER JOIN `RemitosClientes` RemitosClientes ON RemitoClientes_detalle.`remitosclientes_id` = RemitosClientes.`id`
			     LEFT OUTER JOIN `cliente` cliente ON RemitosClientes.`clienteId` = cliente.`idCliente`
			     LEFT OUTER JOIN `Proveedor` Proveedor ON RemitosClientes.`proveedorId` = Proveedor.`id`
			     LEFT OUTER JOIN `PedidoClientesDetalle` PedidoClientesDetalle ON RemitosClientesDetalles.`pedidoDetalleId` = PedidoClientesDetalle.`id`
			     LEFT OUTER JOIN `articulos` articulos ON RemitosClientesDetalles.`articuloId` = articulos.`idArticulos`
			     LEFT JOIN `pedidoId_detalleId` pedidoId_detalleId ON PedidoClientesDetalle.`id` = pedidoId_detalleId.`detalleId`
			     LEFT JOIN `PedidoClientes` PedidoClientes ON pedidoId_detalleId.`pedidoId` = PedidoClientes.`id`
			WHERE
			     RemitosClientes.`id` = $idRemito";
			
			$jru->runPdfFromSql($ruta, $destino, $param, $sql, $conn->getConnection());

			
			return $response->setContent(
					json_encode(
						array(
							'success'=> true,	
						)
					)
				);				
			
        }catch(\Exception $e){
        	//throw $e;
        	$response->setContent(json_encode(array('success' => false, 'msg' => $e->getMessage())));
        	$response->setStatusCode($response::HTTP_INTERNAL_SERVER_ERROR);
        	return $response;        
        }catch(\JavaException $ex){
			$trace = new \Java('java.io.ByteArrayOutputStream');
			$ex->printStackTrace(new \Java('java.io.PrintStream', $trace));
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			$response->setContent(
				json_encode(array('success' => false, 'msg' => nl2br("java stack trace: Error al generar el reporte")))
			);
			return $response;
		}
    }
    
    /**
     * @Route("/verRemitoCliente", name="mbp_articulos_verRemitoCliente", options={"expose"=true})
     */
	public function verRemitoCliente()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpArticulosBundle/Resources/public/pdf/').'RemitoCliente.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'RemitoCliente.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-Type', 'application/pdf');

        return $response;
	}
	
	 /**
     * @Route("/remitosPendientesFacturacion", name="mbp_articulos_remitosPendientesFacturacion", options={"expose"=true})
     */
	public function remitosPendientesFacturacion()
	{
		$response = new Response;
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();
		
		try{
			$repo = $em->getRepository('MbpArticulosBundle:RemitosClientes');
			$idCliente = $request->request->get('idCliente');
		
			$res = $repo->listarRemitosPendientesFacturacion($idCliente);	
		}catch(\Exception $e){
			throw $e;
			$response->setContent(json_encode(array('success' => false, 'msg' => $e->getMessage())));
			$response->setStatusCode($response::HTTP_INTERNAL_SERVER_ERROR);
			return $response;
		}		

        return $response->setContent(json_encode($res));
	}
	
	/**
     * @Route("/listarRemitos", name="mbp_articulos_listarRemitos", options={"expose"=true})
     */
	public function listarRemitos()
	{
		$response = new Response;
		$em = $this->getDoctrine()->getManager();
		
		try{
			$repo = $em->getRepository('MbpArticulosBundle:RemitosClientes');
		
			$res = $repo->listarRemitos();
			
			return $response->setContent(json_encode(array('success' => true, 'data' => $res)));	
		}catch(\Exception $e){
			throw $e;
			$response->setContent(json_encode(array('success' => false, 'msg' => $e->getMessage())));
			$response->setStatusCode($response::HTTP_INTERNAL_SERVER_ERROR);
			return $response;
		}		

        return $response->setContent(json_encode($res));
	}
}
















