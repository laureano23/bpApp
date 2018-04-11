<?php

namespace Mbp\ProduccionBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

use Mbp\ProduccionBundle\Entity\PedidoClientes;
use Mbp\ProduccionBundle\Entity\PedidoClientesDetalle;

class PedidoClientesController extends Controller
{
	public function nuevoPedidoAction()
	{
		$req = $this->getRequest();
		$response = new Response;
		
		$values = $req->request->get('data');
		$clienteId = $req->request->get('cliente');
		$oc = $req->request->get('oc');
		$autNum = $req->request->get('autNum');
		$esRepuesto = $req->request->get('esRepuesto');
				
		
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpProduccionBundle:PedidoClientes');
				
		$json = json_decode($values);
		
		$usuario = $this->get('security.context')->getToken()->getUser();
		
		try{
			$em = $this->getDoctrine()->getEntityManager();
						
			//BUSCO EL CLIENTE
			$clienteRepo = $em->getRepository('MbpClientesBundle:Cliente');
			$idCliente = $clienteRepo->findOneById($clienteId);

			$pedido = new PedidoClientes;
			$pedido->setOc($oc);
			$pedido->setCliente($idCliente);
			$pedido->setUsuarioId($usuario);
			$pedido->setFechaPedido(new \DateTime);
			$pedido->setAutEntrega($autNum);
			
			//print_r($esRepuesto);
			//exit;
			
			if($esRepuesto == "true"){
				$pedido->setEsRepuesto(TRUE);
			}
			
			foreach ($json as $val) {
				$pedidoDetalle = new PedidoClientesDetalle;
								
				//BUSCO EL CODIGO
				$articulosRepo = $em->getRepository('MbpArticulosBundle:Articulos');
				$codigoArticulo = $articulosRepo->findOneByCodigo($val->codigo);				
				
				//OBJETO FECHA				
				$date = new \DateTime;
				$date = $date->createFromFormat('d/m/Y', $val->fechaProgramacion); //FECHA TIPO 01/02/2015
				
				$pedidoDetalle->setCodigo($codigoArticulo);
				$pedidoDetalle->setCantidad($val->cantidad);
				$pedidoDetalle->setFechaProg($date);
				$pedidoDetalle->setDescripcion($val->descripcion);				
				$pedido->AddDetalleId($pedidoDetalle);
				
				$em->persist($pedido);
				$em->flush();
			}
			

			$response->setContent(
				json_encode(array(
					'success' => true,
					'msg' => 'El pedido se cargó exitosamente'
				))
			);

			return $response;
			
		}catch(\Exception $e){
			$response->setContent(
				json_encode(array(
					'success' => false,
					'msg' => $e->getMessage()
				))
			);
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			return $response;
		}		
	}
	
	public function listarPedidosAction() 
	{	
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$response = new Response;
		
		$cliente = $req->query->get('cliente');
		$codigo = $req->query->get('codigo');
		$repo = $em->getRepository('MbpProduccionBundle:PedidoClientes');
		
		$res;
		if(!empty($cliente) && !empty($codigo)){
			$res = $repo->listarPedidosClienteCodigo($cliente, $codigo);
		}else{
			if(!empty($codigo)){
				$res = $repo->listarPedidosCodigo($codigo);
			}else{
				//LISTA TODOS LOS PEDIDOS
				$res = $repo->listarPedidos();	
			}
		}
		
		return $response->setContent(json_encode($res));
		
	}
	
	public function reportePedidoAction()
	{
		$req = $this->getRequest();
		$response = new Response;
		
		$values = $req->request->get('data');
		$values = json_decode($values);
		
		$repo = $this->get('reporteador');
		$kernel = $this->get('kernel');
		
		try{
			
			//Configuro reporte			
			$jru = $repo->jru();
			
			//Ruta archivo Jasper			 
			$ruta = $kernel->locateResource('@MbpProduccionBundle/Reportes/Pedido.jrxml');
			
			//Ruta de destino del PDF			 
			$destino = $kernel->locateResource('@MbpProduccionBundle/Resources/public/pdf/').'Pedido.pdf';
			
						
			//Parametros de conexion
			$host = $this->container->getParameter('database_host');
			$dbName = $this->container->getParameter('database_name');
			$dbUser = $this->container->getParameter('database_user');
			$dbPass = $this->container->getParameter('database_password');
			
			$conn = $repo->getJdbc("com.mysql.jdbc.Driver","jdbc:mysql://".$host."/".$dbName, $dbUser, $dbPass);
						
			$codigoDesde = $values->articuloDesde;
			$codigoHasta = $values->articuloHasta;
			$clienteDesde = (int)$values->clienteDesde;
			$clienteHasta = (int)$values->clienteHasta;
			/*
			 * NUEVA FECHA FORMATO PHP 
			 */	
			$fechaDesde = \DateTime::createFromFormat('d/m/Y', $values->fechaDesde);
			$fechaHasta = \DateTime::createFromFormat('d/m/Y', $values->fechaHasta);
			
			//PARAMETROS
			$param = $repo->getJava('java.util.HashMap');
			$param->put('fechaDesde', $fechaDesde->format("d/m/Y"));
			$param->put('fechaHasta', $fechaHasta->format("d/m/Y"));
				
			/*
			 * FECHA OUTPUT FORMATO SQL PARA CONSULTA
			 */		
			$fechaDesdeSql = $fechaDesde->format('Y-m-d');
			$fechaHastaSql = $fechaHasta->format('Y-m-d');
						
			//PARAMETROS
			$param = $repo->getJava('java.util.HashMap');
			$param->put('fechaDesde', $fechaDesde->format("d/m/Y"));
			$param->put('fechaHasta', $fechaHasta->format("d/m/Y"));
			$param->put('codigoDesde', $codigoDesde);
			$param->put('codigoHasta', $codigoHasta);
			$param->put('clienteDesde', $clienteDesde);
			$param->put('clienteHasta', $clienteHasta);
			
			//CONSULTA SQL
			$sql = "SELECT
			     PedidoClientes.`id` AS PedidoClientes_id,
			     PedidoClientes.`fechaPedido` AS PedidoClientes_fechaPedido,
			     PedidoClientes.`oc` AS PedidoClientes_oc,
			     PedidoClientes.`cliente` AS PedidoClientes_cliente,
			     PedidoClientes.`inactivo` AS PedidoClientes_inactivo,
			     PedidoClientes.`usuarioId` AS PedidoClientes_usuarioId,
			     PedidoClientesDetalle.`id` AS PedidoClientesDetalle_id,
			     PedidoClientesDetalle.`codigo` AS PedidoClientesDetalle_codigo,
			     PedidoClientesDetalle.`cantidad` AS PedidoClientesDetalle_cantidad,
			     PedidoClientesDetalle.`fechaProg` AS PedidoClientesDetalle_fechaProg,
			     PedidoClientesDetalle.`entregado` AS PedidoClientesDetalle_entregado,
			     PedidoClientesDetalle.`inactivo` AS PedidoClientesDetalle_inactivo,
			     pedidoId_detalleId.`pedidoId` AS pedidoId_detalleId_pedidoId,
			     pedidoId_detalleId.`detalleId` AS pedidoId_detalleId_detalleId,
			     cliente.`idCliente` AS cliente_idCliente,
			     cliente.`rsocial` AS cliente_rsocial,
			     cliente.`denominacion` AS cliente_denominacion,
			     articulos.`idArticulos` AS articulos_idArticulos,
			     articulos.`codigo` AS articulos_codigo,
			     articulos.`descripcion` AS articulos_descripcion,
			     articulos.`unidad` AS articulos_unidad,
			     articulos.`costo` AS articulos_costo,
			     articulos.`precio` AS articulos_precio,
			     articulos.`moneda` AS articulos_moneda,
			     PedidoClientesDetalle.`descripcion` AS PedidoClientesDetalle_descripcion,
			     ParametrosFinanzas.dolarOficial
			FROM
			     `PedidoClientesDetalle` PedidoClientesDetalle INNER JOIN `pedidoId_detalleId` pedidoId_detalleId ON PedidoClientesDetalle.`id` = pedidoId_detalleId.`detalleId`
			     INNER JOIN `PedidoClientes` PedidoClientes ON pedidoId_detalleId.`pedidoId` = PedidoClientes.`id`
			     INNER JOIN `cliente` cliente ON PedidoClientes.`cliente` = cliente.`idCliente`
			     INNER JOIN `articulos` articulos ON PedidoClientesDetalle.`codigo` = articulos.`idArticulos`,
			     ParametrosFinanzas
			WHERE
			     PedidoClientes.`cliente` BETWEEN $clienteDesde AND $clienteHasta
			 AND articulos.`codigo` BETWEEN '$codigoDesde' AND '$codigoHasta'
			 AND PedidoClientes.`fechaPedido` BETWEEN '$fechaDesdeSql' AND '$fechaHastaSql'  
			 AND PedidoClientesDetalle.`inactivo` = 0
			ORDER BY cliente.`idCliente`";
			
						
			//Exportamos el reporte
			$jru->runPdfFromSql($ruta, $destino, $param, $sql, $conn->getConnection());
			
			$response->setContent(
				json_encode(
					array(
						'success'=> true,
						'reporte' => $destino,		
					)
				)
			);
		
			return $response;
			
		}catch(\JavaException $ex){
			$trace = new \Java('java.io.ByteArrayOutputStream');
			$ex->printStackTrace(new \Java('java.io.PrintStream', $trace));
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			$response->setContent(
				json_encode(array('success' => false, 'msg' => nl2br("java stack trace: Error al generar el reporte")))
			);
			return $response;
		}catch(\Exception $e){
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			$response->setContent(
				json_encode(array('success' => false, 'msg' => $e->getMessage()))
			);
			return $response;
		}		
	}
	
	public function reportePedidoPdfAction(){
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpProduccionBundle/Resources/public/pdf/').'Pedido.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'Pedido.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-type', 'application/pdf');

        return $response;
	}

	public function pedidosArticuloClienteAction()
	{
		$req = $this->getRequest();		
		$codigo = $req->request->get('codigo');
		$idCliente = $req->request->get('idCliente');
		$response = new Response;

		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpProduccionBundle:PedidoClientes');

		try{
			$res = $repo->pedidosPorArticuloCliente($codigo, $idCliente);	

			$response->setContent(json_encode($res));

		}catch(\Exception $e){
			$res = array('success' => false, 'msg' => $e->getMessage());

			$response->setContent(json_encode($res));
			$response->setStatusCode($response::HTTP_INTERNAL_SERVER_ERROR);

			return $response;
		}

		return $response;
		
	}
	
	public function ActualizarPedidoAction(){
		$req = $this->getRequest();		
		$pedidos = $req->request->get('pedidos');
		$response = new Response;

		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpProduccionBundle:PedidoClientesDetalle');
		
		try{
			$ped = json_decode($pedidos);
			
		
			$detalle = $repo->find($ped->idDetalle);
			if(empty($detalle)) throw new \Exception("No se encuentra el pedido a modificar", 1);
			$nuevaFecha = \DateTime::createFromFormat('d/m/Y', $ped->fechaProgramacion);
			$detalle->setFechaProg($nuevaFecha);
			$detalle->setCantidad($ped->cantidad);
			$em->persist($detalle);
			$em->flush();
			
			return $response->setContent(json_encode(array('success' => true)));	
		}catch(\Exception $e){
			throw $e;
			$res = array('success' => false, 'msg' => $e->getMessage());

			$response->setContent(json_encode($res));
			$response->setStatusCode($response::HTTP_INTERNAL_SERVER_ERROR);

			return $response;
		}        
	}
	
	public function BorrarPedidoAction(){
		$req = $this->getRequest();		
		$pedidos = $req->request->get('pedidos');
		$response = new Response;

		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpProduccionBundle:PedidoClientesDetalle');
		
		try{
			$ped = json_decode($pedidos);
			
		
			$detalle = $repo->find($ped->idDetalle);
			if(empty($detalle)) throw new \Exception("No se encuentra el pedido a modificar", 1);			
			$detalle->setInactivo(true);
			$em->persist($detalle);
			$em->flush();
			
			return $response->setContent(json_encode(array('success' => true)));	
		}catch(\Exception $e){
			$res = array('success' => false, 'msg' => $e->getMessage());

			$response->setContent(json_encode($res));
			$response->setStatusCode($response::HTTP_INTERNAL_SERVER_ERROR);

			return $response;
		}        
	}
}


































