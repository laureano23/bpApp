<?php

namespace Mbp\ProduccionBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Mbp\ProduccionBundle\Clases\Programacion;


class ProgramacionController extends Controller
{
	public function programacionSelectAction()
	{
		return new Response();
	}
	
	public function programacionCalculoAction()
	{
		$req = $this->getRequest();
		
		$data = $req->request->all();		
		
		$info = json_decode($data['data']);	//DATA DEL CLIENTE
		
		$codigo = $info[0][0]->codigo; //CODIGO DEL ARTICULO A PROGRAMAR
		$cantidad = $info[3]; //CANTIDAD DEL ARTICULO A PROGRAMAR
		
		$objProg = $this->get('programacion'); // OBJETO PROGRAMACION
		
		$fechaProg = $objProg->paramsProg($info, $cantidad);
		$fechasProgramadas = $objProg->tiempoOpe();
		
		
		/*
		 * PREPARO RESPUESTA PARA LA GRILLA DE PROGRAMACION
		 */
				
		$items = array();
		for($i=0; $i<count($info[0]); $i++){
			$items[$i] = array(
				'id' => $info[0][$i]->id,
				'codigo' => $codigo,
				'idOperacion' => array(					
					'descripcion' => $info[0][$i]->idOperacion->descripcion,
					'sector' => array(
						'descripcion' => $info[0][$i]->idOperacion->sector->descripcion
					)						
				),
				'tiempo' => $info[0][$i]->tiempo,
				'idProgramacion' => array(
					'fechaInicio' => $fechasProgramadas['fechaInicio'][$i]->format('Y-m-d H:i:s:u'),
					'fechaFin' => $fechasProgramadas['fechaFin'][$i]->format('Y-m-d H:i:s:u')
				)
			);
		}		
		
		echo json_encode($items);
		
		return new Response();
	}
	
	public function programacionControlRecursoAction()
	{
		$req = $this->getRequest();
		$data = $req->request->all();		
		
		$info = $data['data'];
		$decodeData = json_decode($info);
				
		//EM
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpProduccionBundle:Programacion');
		
		$repo->insertPrograma($decodeData);
		
		return new Response();
	}
	
	public function programacionReporteAction()
	{
		//REQUEST
		$req = $this->getRequest();
		
		$data = $req->request->get('data');
		$decoded = json_decode($data);
		
		//REPORTEADOR
		$repo = $this->get('reporteador');
		$kernel = $this->get('kernel');
		
		try{
			//Configuro reporte			
			$jru = $repo->jru();
			
			//Ruta archivo Jasper			 
			$ruta = $kernel->locateResource('@MbpProduccionBundle/Reportes/Programacion.jrxml');
			
			//Ruta de destino del PDF			 
			$destino = $kernel->locateResource('@MbpProduccionBundle/Resources/public/pdf/').'Programacion.pdf';
			
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
			$sql = "";
			
			
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
		print_r($decoded);
		return new Response();
	}
}

































