<?php

namespace Mbp\ProduccionBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class PedidoClientesController extends Controller
{
	public function nuevoPedidoAction()
	{
		$req = $this->getRequest();
		
		$values = $req->request->get('data');
		
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpProduccionBundle:PedidoClientes');
				
		$json = json_decode($values);
		
		//CARGA EL ARRAY DE ARTICULOS A LA BD
		$repo->cargaPedidos($json);
		
		
		return new Response();
	}
	
	public function listarPedidosAction()
	{	
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpProduccionBundle:PedidoClientes');
		
		//LISTA TODOS LOS PEDIDOS
		$repo->listarPedidos();
		
		
		return new Response();
		
	}
	
	public function reportePedidoAction()
	{
		$req = $this->getRequest();
		
		$values = $req->request->get('data');
		$values = json_decode($values);
		
		$repo = $this->get('reporteador');
		$kernel = $this->get('kernel');
		
		try{
			
			//Configuro reporte			
			$jru = $repo->jru();
			
			//Ruta archivo Jasper			 
			$ruta = $kernel->locateResource('@MbpProduccionBundle/Reportes/Pedidos.jrxml');
			
			//Ruta de destino del PDF			 
			$destino = $kernel->locateResource('@MbpProduccionBundle/Resources/public/pdf/').'Pedidos.pdf';
			
			//PARAMETROS
			$param = $repo->getJava('java.util.HashMap');
			
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
				
			/*
			 * FECHA OUTPUT FORMATO SQL PARA CONSULTA
			 */		
			$fechaDesdeSql = $fechaDesde->format('Y-m-d');
			$fechaHastaSql = $fechaHasta->format('Y-m-d');
			
			//CONSULTA SQL
			$sql = "SELECT
			     PedidoClientes.`codigo` AS PedidoClientes_codigo,
			     PedidoClientes.`cantidad` AS PedidoClientes_cantidad,
			     PedidoClientes.`fechaProg` AS PedidoClientes_fechaProg,
			     PedidoClientes.`oc` AS PedidoClientes_oc,
			     articulos.`codigo` AS articulos_codigo,
			     articulos.`descripcion` AS articulos_descripcion,
			     articulos.`idArticulos` AS articulos_idArticulos,
			     articulos.`unidad` AS articulos_unidad,
			     PedidoClientes.`id` AS PedidoClientes_id,
			     PedidoClientes.`cliente` AS PedidoClientes_cliente,
			     cliente.`idCliente` AS cliente_idCliente,
			     cliente.`rsocial` AS cliente_rsocial,
			     articulos.`costo` AS articulos_costo
			FROM
			     `articulos` articulos INNER JOIN `PedidoClientes` PedidoClientes ON articulos.`idArticulos` = PedidoClientes.`codigo`
			     INNER JOIN `cliente` cliente ON PedidoClientes.`cliente` = cliente.`idCliente`
			WHERE
			     PedidoClientes.`cliente` BETWEEN $clienteDesde AND $clienteHasta
 				 AND articulos.`codigo` BETWEEN '$codigoDesde' AND '$codigoHasta' 				 
 				 AND PedidoClientes.`fechaProg` BETWEEN '$fechaDesdeSql' AND '$fechaHastaSql'";
			
			
			//Exportamos el reporte
			$jru->runPdfFromSql($ruta, $destino, $param, $sql, $conn->getConnection());
			
		
			echo json_encode(
					array(
						'success'=> true,
						'reporte' => $destino,		
					)
				);
			
			return new Response();
			
		}catch(JavaException $ex){
			$trace = new Java('java.io.ByteArrayOutputStream');
			$ex->printStackTrace(new Java('java.io.PrintStream', $trace));
			print nl2br("java stack trace: $trace\n");
			return false;
		}		
	}
	
	public function reportePedidoPdfAction(){
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpProduccionBundle/Resources/public/pdf/').'Pedidos.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'Pedidos.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-type', 'application/pdf');

        return $response;
	}
}


































