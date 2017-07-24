<?php
namespace Mbp\CalidadBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;


use Mbp\ProduccionBundle\Clases\Calculo;

class ReportesController extends Controller
{
	/*
	 * RG-010 Estanqueidad Form
	 */
	public function generateFormRg010Action()
	{
		$repo = $this->get('reporteador');		
		$kernel = $this->get('kernel');		
		
		$jru = $repo->jru();
				
		$ruta = $kernel->locateResource('@MbpCalidadBundle/Reportes/RG-010 Control Estanqueidad.jasper');
		
		//Ruta de destino
		$destino = $kernel->locateResource('@MbpCalidadBundle/Resources/public/pdf/').'RG-010 Estanqueidad.pdf';
		
		//Parametros
		$param = null;
		
		//Exportamos el reporte
		$jru->runReportEmpty($ruta, $destino, $param);
		
		
		echo json_encode(
				array(
					'success'=> true,
					'reporte' => $destino,		
				)
			);
		
		return new Response();
	}
	
	public function showFormRg010Action()
	{		
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpCalidadBundle/Resources/public/pdf/').'RG-010 Estanqueidad.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'RG-010 Estanqueidad.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-type', 'application/pdf');

        return $response;
	}
    
	/*
	 * RG-010 Estanqueidad Reporte
	 */
	public function generateReporteRg010Action()
	{
		$repo = $this->get('reporteador');		
		$kernel = $this->get('kernel');	
		
		/*
		 * Recibo parametros del request 
		 */
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$ot = (int)$req->request->get('ot');
		
		$jru = $repo->jru();
				
		$ruta = $kernel->locateResource('@MbpCalidadBundle/Reportes/RG010-01.jrxml');
		
		//Ruta de destino
		$destino = $kernel->locateResource('@MbpCalidadBundle/Resources/public/pdf/').'RG010-01.pdf';
		
		//Parametros HashMap
		$param = $repo->getJava('java.util.HashMap');
		$param->put('ot', $ot);
		
		$conn = $repo->getJdbc();
		
		/*
		 * SQL
		 * 
		 */
		$sql = "
			SELECT
			     Estanqueidad.`fechaPrueba` AS Estanqueidad_fechaPrueba,
			     Estanqueidad.`ot` AS Estanqueidad_ot,
			     Estanqueidad.`pruebaNum` AS Estanqueidad_pruebaNum,
			     Estanqueidad.`estado` AS Estanqueidad_estado,
			     Estanqueidad.`mChapa` AS Estanqueidad_mChapa,
			     Estanqueidad.`mBagueta` AS Estanqueidad_mBagueta,
			     Estanqueidad.`mPerfil` AS Estanqueidad_mPerfil,
			     Estanqueidad.`mPisoDesp` AS Estanqueidad_mPisoDesp,
			     Estanqueidad.`tRosca` AS Estanqueidad_tRosca,
			     Estanqueidad.`tPoros` AS Estanqueidad_tPoros,
			     Estanqueidad.`sConector` AS Estanqueidad_sConector,
			     Estanqueidad.`sTapaPanel` AS Estanqueidad_sTapaPanel,
			     Estanqueidad.`sPlanchuelas` AS Estanqueidad_sPlanchuelas,
			     Estanqueidad.`soldador` AS Estanqueidad_soldador,
			     Estanqueidad.`probador` AS Estanqueidad_probador,
			     Estanqueidad.`presion` AS Estanqueidad_presion,
			     Personal.`idP` AS Personal_idP,
			     Personal.`nombre` AS Personal_nombre,
			     Personal_A.`idP` AS Personal_A_idP,
			     Personal_A.`nombre` AS Personal_A_nombre,
			     Estanqueidad.`mAnulado` AS Estanqueidad_mAnulado,
			     Estanqueidad.`mCiba` AS Estanqueidad_mCiba,
			     Estanqueidad.`tFijacion` AS Estanqueidad_tFijacion,
			     Estanqueidad.`mChapaColectora` AS Estanqueidad_mChapaColectora,
			     Estanqueidad.`sPuntera` AS Estanqueidad_sPuntera
			FROM
			     `Personal` Personal RIGHT JOIN `Estanqueidad` Estanqueidad ON Personal.`idP` = Estanqueidad.`soldador`
			     LEFT JOIN `Personal` Personal_A ON Estanqueidad.`probador` = Personal_A.`idP`
			WHERE
			     Estanqueidad.`ot` = $ot
		";
		 /*
		  * FIN SQL
		  */
		
		//Exportamos el reporte
		$jru->runPdfFromSql($ruta, $destino, $param,$sql,$conn->getConnection());
		
		
		echo json_encode(
				array(
					'success'=> true,
					'reporte' => $destino,		
				)
			);
		
		return new Response();
	}
	
	public function showReporteRg010Action()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpCalidadBundle/Resources/public/pdf/').'RG010-01.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'RG010-01.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-type', 'application/pdf');

        return $response;
	}

	/*
	 * RG-010 Estanqueidad Reporte ENTRE FECHAS
	 */
	public function generateReporteRg010FechasAction()
	{
		$repo = $this->get('reporteador');		
		$kernel = $this->get('kernel');	
		
		/*
		 * Recibo parametros del request 
		 */
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$desde = $req->request->get('fechaDesde'); 
		$hasta = $req->request->get('fechaHasta');
		
		$fechaDesde = explode('T', $desde);
		$fechaHasta = explode('T', $hasta);				
		$jru = $repo->jru();
				
		$ruta = $kernel->locateResource('@MbpCalidadBundle/Reportes/RG010-01Fechas.jrxml');
		
		//Ruta de destino
		$destino = $kernel->locateResource('@MbpCalidadBundle/Resources/public/pdf/').'RG010-01Fechas.pdf';
		
		//Parametros HashMap
		$param = $repo->getJava('java.util.HashMap');
						
		$param->put('fechaDesde', $fechaDesde[0]);
		$param->put('fechaHasta', $fechaHasta[0]);
		
		$conn = $repo->getJdbc();
		
		/*
		 * SQL
		 * 
		 */
		$sql = "SELECT
			     Estanqueidad.`fechaPrueba` AS Estanqueidad_fechaPrueba,
			     Estanqueidad.`ot` AS Estanqueidad_ot,
			     Estanqueidad.`pruebaNum` AS Estanqueidad_pruebaNum,
			     Estanqueidad.`estado` AS Estanqueidad_estado,
			     Estanqueidad.`mChapa` AS Estanqueidad_mChapa,
			     Estanqueidad.`mBagueta` AS Estanqueidad_mBagueta,
			     Estanqueidad.`mPerfil` AS Estanqueidad_mPerfil,
			     Estanqueidad.`mPisoDesp` AS Estanqueidad_mPisoDesp,
			     Estanqueidad.`tRosca` AS Estanqueidad_tRosca,
			     Estanqueidad.`tPoros` AS Estanqueidad_tPoros,
			     Estanqueidad.`sConector` AS Estanqueidad_sConector,
			     Estanqueidad.`sTapaPanel` AS Estanqueidad_sTapaPanel,
			     Estanqueidad.`sPlanchuelas` AS Estanqueidad_sPlanchuelas,
			     Estanqueidad.`soldador` AS Estanqueidad_soldador,
			     Estanqueidad.`probador` AS Estanqueidad_probador,
			     Estanqueidad.`presion` AS Estanqueidad_presion,
			     Personal.`idP` AS Personal_idP,
			     Personal.`nombre` AS Personal_nombre,
			     Personal_A.`idP` AS Personal_A_idP,
			     Personal_A.`nombre` AS Personal_A_nombre,
			     Estanqueidad.`mAnulado` AS Estanqueidad_mAnulado,
			     Estanqueidad.`mCiba` AS Estanqueidad_mCiba,
			     Estanqueidad.`tFijacion` AS Estanqueidad_tFijacion,
			     Estanqueidad.`mChapaColectora` AS Estanqueidad_mChapaColectora,
     			 Estanqueidad.`sPuntera` AS Estanqueidad_sPuntera
				FROM
				     `Personal` Personal RIGHT JOIN `Estanqueidad` Estanqueidad ON Personal.`idP` = Estanqueidad.`soldador`
				     LEFT JOIN `Personal` Personal_A ON Estanqueidad.`probador` = Personal_A.`idP`
				WHERE
    		 		fechaPrueba BETWEEN '$fechaDesde[0]' AND '$fechaHasta[0]'";
		 /*
		  * FIN SQL
		  */
		
		//Exportamos el reporte
		$jru->runPdfFromSql($ruta, $destino, $param,$sql,$conn->getConnection());
		
		
		echo json_encode(
				array(
					'success'=> true,
					'reporte' => $destino,		
				)
			);
		
		return new Response();
	}
	
	public function showReporteRg010FechasAction()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpCalidadBundle/Resources/public/pdf/').'RG010-01Fechas.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'RG010-01Fechas.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-type', 'application/pdf');

        return $response;
	}
	
   public function generateRepoFallasSoldaduraAction()
   {
   		$repo = $this->get('reporteador');		
		$kernel = $this->get('kernel');	
		
		/*
		 * Recibo parametros del request 
		 */
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$desde = $req->request->get('desde'); 
		$hasta = $req->request->get('hasta');
								
		$jru = $repo->jru();
				
		$ruta = $kernel->locateResource('@MbpCalidadBundle/Reportes/fallasSoldadura.jrxml');
		$rutaLogo = $repo->getRutaLogo($kernel);
		
		//Ruta de destino
		$destino = $kernel->locateResource('@MbpCalidadBundle/Resources/public/pdf/').'fallasSoldadura.pdf';
		
		//Parametros HashMap
		$param = $repo->getJava('java.util.HashMap');
		
		/*
		 * NUEVA FECHA FORMATO PHP 
		 */	
		$fechaDesde = \DateTime::createFromFormat('d/m/Y', $desde);
		$fechaHasta = \DateTime::createFromFormat('d/m/Y', $hasta);		
		
		/*
		 * FECHA OUTPUT FORMATO SQL PARA CONSULTA
		 */		
		$fechaDesdeSql = $fechaDesde->format('Y-m-d');
		$fechaHastaSql = $fechaHasta->format('Y-m-d');
		
		try{
			$param->put('desde', $fechaDesde->format('d/m/y'));
			$param->put('hasta', $fechaHasta->format('d/m/y'));
			$param->put('rutaLogo', $rutaLogo);
			
			$conn = $repo->getJdbc();
			
			/*
			 * SQL
			 * 
			 */
			$sql = "SELECT
			     SUM(Estanqueidad.`sConector`) AS Estanqueidad_sConector,
			     SUM(Estanqueidad.`sTapaPanel`) AS Estanqueidad_sTapaPanel,
			     SUM(Estanqueidad.`sPlanchuelas`) AS Estanqueidad_sPlanchuelas,
			     SUM(Estanqueidad.`sPuntera`) AS Estanqueidad_sPuntera,
			     Estanqueidad.`fechaPrueba` AS Estanqueidad_fechaPrueba,
			     Estanqueidad.`id` AS Estanqueidad_id,
			     Estanqueidad.`soldador` AS Estanqueidad_soldador,
			     Estanqueidad.`estado` AS Estanqueidad_estado,
			     Personal.`nombre` AS Personal_nombre,
			     Personal.`idP` AS Personal_idP
			FROM
			     `Personal` Personal INNER JOIN `Estanqueidad` Estanqueidad ON Personal.`idP` = Estanqueidad.`soldador`
			WHERE
			     Estanqueidad.`fechaPrueba` BETWEEN '$fechaDesdeSql' AND '$fechaHastaSql'
			GROUP BY
			     Estanqueidad_soldador";
			 /*
			  * FIN SQL
			  */
			
			//Exportamos el reporte
			$jru->runPdfFromSql($ruta, $destino, $param,$sql,$conn->getConnection());
			
			
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
   
   public function showRepoFallasSoldaduraAction()
   {
   		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpCalidadBundle/Resources/public/pdf/').'fallasSoldadura.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'fallasSoldadura.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-type', 'application/pdf');

        return $response;
   } 
   
   public function generateRepoEstanqueidad3DAction()
   {
   		$repo = $this->get('reporteador');		
		$kernel = $this->get('kernel');	
		
		/*
		 * Recibo parametros del request 
		 */
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$desde = $req->request->get('desde'); 
		$hasta = $req->request->get('hasta');
								
		$jru = $repo->jru();
		$ruta = $kernel->locateResource('@MbpCalidadBundle/Reportes/ReporteGraficoEstanqueidad.jrxml');		
		
		//Ruta de destino
		$destino = $kernel->locateResource('@MbpCalidadBundle/Resources/public/pdf/').'ReporteGraficoEstanqueidad.pdf';
		
		//Parametros HashMap
		$param = $repo->getJava('java.util.HashMap');
		
		/*
		 * NUEVA FECHA FORMATO PHP 
		 */	
		$fechaDesde = \DateTime::createFromFormat('d/m/Y', $desde);
		$fechaHasta = \DateTime::createFromFormat('d/m/Y', $hasta);	
		
		/*
		 * FECHA OUTPUT FORMATO SQL PARA CONSULTA
		 */		
		$fechaDesdeSql = $fechaDesde->format('Y-m-d');
		$fechaHastaSql = $fechaHasta->format('Y-m-d');
		
		try{
			$param->put('FECHA_DESDE', $fechaDesde->format('d/m/Y'));
			$param->put('FECHA_HASTA', $fechaHasta->format('d/m/Y'));
			
			$conn = $repo->getJdbc();
			
						
			$sql = "SELECT
			     SUM(if(Estanqueidad.`mChapa`=1,1,0)) AS Estanqueidad_mChapa,
			     SUM(if(Estanqueidad.`mBagueta`=1,1,0)) AS Estanqueidad_mBagueta,
			     SUM(if( Estanqueidad.`mPerfil`=1,1,0)) AS Estanqueidad_mPerfil,
			     SUM(if(Estanqueidad.`mPisoDesp`=1,1,0)) AS Estanqueidad_mPisoDesp,
			     SUM(if(Estanqueidad.`tRosca`=1,1,0)) AS Estanqueidad_tRosca,
			     SUM(if(Estanqueidad.`tPoros`=1,1,0)) AS Estanqueidad_tPoros,
			     SUM(if(Estanqueidad.`sConector`=1,1,0)) AS Estanqueidad_sConector,
			     SUM(if(Estanqueidad.`sTapaPanel`=1,1,0)) AS Estanqueidad_sTapaPanel,
			     SUM(if(Estanqueidad.`sPlanchuelas`=1,1,0)) AS Estanqueidad_sPlanchuelas,
			     SUM(if(Estanqueidad.`mAnulado`=1,1,0)) AS Estanqueidad_mAnulado,
			     SUM(if(Estanqueidad.`mCiba`=1,1,0)) AS Estanqueidad_mCiba,
			     SUM(if(Estanqueidad.`tFijacion`=1,1,0)) AS Estanqueidad_tFijacion,
			     COUNT(Estanqueidad.`id`) AS total,
			     Estanqueidad.`fechaPrueba` AS Estanqueidad_fechaPrueba
			FROM
			     `Estanqueidad` Estanqueidad
			WHERE
			     Estanqueidad.`fechaPrueba` BETWEEN '$fechaDesdeSql' AND '$fechaHastaSql'			
			";
						
			//Exportamos el reporte
			$jru->runPdfFromSql($ruta, $destino, $param,$sql,$conn->getConnection());
			
			
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
			return array(
						'success'=> false,
						'msg' => 'Error al generar el reporte',		
					);
		}	
   }
   
   public function showRepoEstanqueidad3DAction()
   {
   		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpCalidadBundle/Resources/public/pdf/').'ReporteGraficoEstanqueidad.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'ReporteGraficoEstanqueidad.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-type', 'application/pdf');

        return $response;
   } 
}









