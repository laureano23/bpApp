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
        $repoPedidos = $em->getRepository('MbpProduccionBundle:PedidoClientes');
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
					$remitoDetalle->setPedidoId($repoPedidos->find($item->pedidoNum));
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
			     RemitosClientesDetalles.`id` AS RemitosClientesDetalles_id,
			     RemitosClientesDetalles.`descripcion` AS RemitosClientesDetalles_descripcion,
			     RemitosClientesDetalles.`cantidad` AS RemitosClientesDetalles_cantidad,
			     RemitosClientesDetalles.`unidad` AS RemitosClientesDetalles_unidad,
			     RemitosClientesDetalles.`oc` AS RemitosClientesDetalles_oc,
			     RemitosClientesDetalles.`articuloId` AS RemitosClientesDetalles_articuloId,
			     RemitoClientes_detalle.`remitosclientes_id` AS RemitoClientes_detalle_remitosclientes_id,
			     RemitoClientes_detalle.`remitosclientesdetalles_id` AS RemitoClientes_detalle_remitosclientesdetalles_id,
			     cliente.`rsocial` AS cliente_rsocial,
			     cliente.`idCliente` AS cliente_idCliente,
			     cliente.`denominacion` AS cliente_denominacion,
			     cliente.`direccion` AS cliente_direccion,
			     cliente.`cuit` AS cliente_cuit,
			     cliente.`localidad` AS cliente_localidad,
			     cliente.`iva` AS cliente_iva,
			     Proveedor.`id` AS Proveedor_id,
			     Proveedor.`localidad` AS Proveedor_localidad,
			     Proveedor.`provincia` AS Proveedor_provincia,
			     Proveedor.`rsocial` AS Proveedor_rsocial,
			     Proveedor.`denominacion` AS Proveedor_denominacion,
			     Proveedor.`direccion` AS Proveedor_direccion,
			     Proveedor.`cuit` AS Proveedor_cuit,
			     RemitosClientes.`proveedorId` AS RemitosClientes_proveedorId
			FROM
			     `RemitosClientesDetalles` RemitosClientesDetalles INNER JOIN `RemitoClientes_detalle` RemitoClientes_detalle ON RemitosClientesDetalles.`id` = RemitoClientes_detalle.`remitosclientesdetalles_id`
			     INNER JOIN `RemitosClientes` RemitosClientes ON RemitoClientes_detalle.`remitosclientes_id` = RemitosClientes.`id`
			     LEFT JOIN `cliente` cliente ON RemitosClientes.`clienteId` = cliente.`idCliente`
			     RIGHT JOIN `Proveedor` Proveedor ON RemitosClientes.`proveedorId` = Proveedor.`id`
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
        	$response->setContent(json_encode(array('success' => false, 'msg' => $e->getMessage())));
        	$response->setStatusCode($response::HTTP_INTERNAL_SERVER_ERROR);
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
		
		try{
			$repo = $em->getRepository('MbpArticulosBundle:RemitosClientes');
		
			$res = $repo->listarRemitosPendientesFacturacion();	
		}catch(\Exception $e){
			throw $e;
			$response->setContent(json_encode(array('success' => false, 'msg' => $e->getMessage())));
			$response->setStatusCode($response::HTTP_INTERNAL_SERVER_ERROR);
			return $response;
		}		

        return $response->setContent(json_encode($res));
	}
}
















