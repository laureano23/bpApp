<?php

namespace Mbp\PersonalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ReportesController extends Controller
{
	/**
     * @Route("/recibosLectura", name="mbp_personal_recibosLectura", options={"expose"=true})
     */    
	public function recibosLecturaAction()
	{
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$repo = $em->getRepository('MbpPersonalBundle:Recibos');
		
		/*
		 * PARAMETROS
		 */
		$mes = $req->query->get('mes');
		$anio = $req->query->get('anio');
		$periodo = $req->query->get('pagoTipo');
		$compensatorio = $req->query->get('compensatorio');
		
		$reporteador = $this->get('reporteador');
		$kernel = $this->get('kernel');
		
		/*
		 * Configuro reporte
		 */
		$jru = $reporteador->jru();
		
		/*
		 * Ruta archivo Jasper
		 */				
				
		$compensatorio == 'on' ? $ruta = $kernel->locateResource('@MbpPersonalBundle/Reportes/recibosCompensatorio.jrxml') : $ruta = $kernel->locateResource('@MbpPersonalBundle/Reportes/recibos.jrxml');
		
		/*
		 * Ruta de destino del PDF
		 */
		$destino = $kernel->locateResource('@MbpPersonalBundle/Resources/public/pdf/').'recibos.pdf';
		
		
		//Parametros HashMap
		$param = $reporteador->getJava('java.util.HashMap');
		$param->put('periodo', (int)$periodo);
		$param->put('anio', (int)$anio);
		$param->put('mes', (int)$mes);
		$param->put('dirFirma', $kernel->locateResource('@MbpPersonalBundle/Reportes/rob.jpg'));
		
		$conn = $reporteador->getJdbc();
		
				
		$sqlSimple = "SELECT
		     Recibos.`id` AS Recibos_id,
		     Recibos.`compensatorio` AS Recibos_compensatorio,
		     Recibos.`banco_id` AS Recibos_banco_id,
		     Recibos.`fechaPago` AS Recibos_fechaPago,
		     Recibos.`periodo` AS Recibos_periodo,
		     Recibos.`mes` AS Recibos_mes,
		     Recibos.`anio` AS Recibos_anio,
		     Recibos.`tipoPago` AS Recibos_tipoPago,
		     Recibos.`basicoHist` AS Recibos_basicoHist,
		     Recibos.`categoriaHist` AS Recibos_categoriaHist,
		     Recibos.`sindicatoHist` AS Recibos_sindicatoHist,
		     Recibos.`tarea` AS Recibos_tarea,
     		 Recibos.`antiguedad` AS Recibos_antiguedad,
		     RecibosDetalle.`id` AS RecibosDetalle_id,
		     RecibosDetalle.`cantConceptoVar` AS RecibosDetalle_cantConceptoVar,
		     RecibosDetalle.`valorConceptoHist` AS RecibosDetalle_valorConceptoHist,
		     RecibosDetalle.`remunerativo` AS RecibosDetalle_remunerativo,
		     RecibosDetalle.`exento` AS RecibosDetalle_exento,
		     RecibosDetalle.`descuento` AS RecibosDetalle_descuento,
		     RecibosDetalles_CodigoSueldos.`recibosDetalles_id` AS RecibosDetalles_CodigoSueldos_recibosDetalles_id,
		     RecibosDetalles_CodigoSueldos.`codigoSueldos_id` AS RecibosDetalles_CodigoSueldos_codigoSueldos_id,
		     RecibosPersonal.`recibos_id` AS RecibosPersonal_recibos_id,
		     RecibosPersonal.`personal_id` AS RecibosPersonal_personal_id,
		     recibo_detallesRecibos.`recibo_id` AS recibo_detallesRecibos_recibo_id,
		     recibo_detallesRecibos.`recibosdetalle_id` AS recibo_detallesRecibos_recibosdetalle_id,
		     CodigoSueldos.`id` AS CodigoSueldos_id,
		     CodigoSueldos.`descripcion` AS CodigoSueldos_descripcion,
		     CodigoSueldos.`remunerativo` AS CodigoSueldos_remunerativo,
		     CodigoSueldos.`noRemunerativo` AS CodigoSueldos_noRemunerativo,
		     CodigoSueldos.`descuento` AS CodigoSueldos_descuento,
		     CodigoSueldos.`asignacion` AS CodigoSueldos_asignacion,
		     CodigoSueldos.`fijo` AS CodigoSueldos_fijo,
		     CodigoSueldos.`porcentaje` AS CodigoSueldos_porcentaje,
		     CodigoSueldos.`importe` AS CodigoSueldos_importe,
		     Bancos.`id` AS Bancos_id,
		     Bancos.`nombre` AS Bancos_nombre,
		     Personal.`idP` AS Personal_idP,
		     Personal.`nombre` AS Personal_nombre,
		     Personal.`localidad` AS Personal_localidad,
		     Personal.`categoria` AS Personal_categoria,
		     Personal.`documentoNum` AS Personal_documentoNum,
		     Personal.`fechaIngreso` AS Personal_fechaIngreso,
		     Personal.`cuil` AS Personal_cuil,
		     Personal.`antPorcentaje` AS Personal_antPorcentaje,
		     Personal.`tarea` AS Personal_tarea,
		     Personal.`antiguedad` AS Personal_antiguedad,
		     Categorias.`id` AS Categorias_id,
		     Categorias.`categoria` AS Categorias_categoria,
		     Categorias.`salario` AS Categorias_salario,
		     Categorias.`idSindicato` AS Categorias_idSindicato,
		     Sindicatos.`id` AS Sindicatos_id,
		     Sindicatos.`sindicato` AS Sindicatos_sindicato,
		     Personal.`tipoContratacion` AS Personal_tipoContratacion,
		     Personal.`estado` AS Personal_estado
		FROM
		     `RecibosDetalle` RecibosDetalle INNER JOIN `RecibosDetalles_CodigoSueldos` RecibosDetalles_CodigoSueldos ON RecibosDetalle.`id` = RecibosDetalles_CodigoSueldos.`recibosDetalles_id`
		     INNER JOIN `recibo_detallesRecibos` recibo_detallesRecibos ON RecibosDetalle.`id` = recibo_detallesRecibos.`recibosdetalle_id`
		     INNER JOIN `Recibos` Recibos ON recibo_detallesRecibos.`recibo_id` = Recibos.`id`
		     INNER JOIN `RecibosPersonal` RecibosPersonal ON Recibos.`id` = RecibosPersonal.`recibos_id`
		     INNER JOIN `Bancos` Bancos ON Recibos.`banco_id` = Bancos.`id`
		     INNER JOIN `Personal` Personal ON RecibosPersonal.`personal_id` = Personal.`idP`
		     INNER JOIN `Categorias` Categorias ON Personal.`categoria` = Categorias.`id`
		     INNER JOIN `Sindicatos` Sindicatos ON Categorias.`idSindicato` = Sindicatos.`id`
		     INNER JOIN `CodigoSueldos` CodigoSueldos ON RecibosDetalles_CodigoSueldos.`codigoSueldos_id` = CodigoSueldos.`id`
		WHERE
		     Recibos.`mes` = $mes
		 AND Recibos.`anio` = $anio
		 AND Recibos.`periodo` = $periodo
		 AND Recibos.`compensatorio` = 0
		ORDER BY
		     Personal.`nombre` ASC,
		     CodigoSueldos.`remunerativo` DESC";
		
		$sqlCompensatorio = "SELECT
		     Recibos.`id` AS Recibos_id,
		     Recibos.`compensatorio` AS Recibos_compensatorio,
		     Recibos.`banco_id` AS Recibos_banco_id,
		     Recibos.`fechaPago` AS Recibos_fechaPago,
		     Recibos.`periodo` AS Recibos_periodo,
		     Recibos.`mes` AS Recibos_mes,
		     Recibos.`anio` AS Recibos_anio,
		     Recibos.`tipoPago` AS Recibos_tipoPago,
		     Recibos.`basicoHist` AS Recibos_basicoHist,
		     Recibos.`categoriaHist` AS Recibos_categoriaHist,
		     Recibos.`sindicatoHist` AS Recibos_sindicatoHist,
		     RecibosDetalle.`id` AS RecibosDetalle_id,
		     RecibosDetalle.`cantConceptoVar` AS RecibosDetalle_cantConceptoVar,
		     RecibosDetalle.`valorConceptoHist` AS RecibosDetalle_valorConceptoHist,
		     RecibosDetalle.`remunerativo` AS RecibosDetalle_remunerativo,
		     RecibosDetalle.`exento` AS RecibosDetalle_exento,
		     RecibosDetalle.`descuento` AS RecibosDetalle_descuento,
		     RecibosDetalles_CodigoSueldos.`recibosDetalles_id` AS RecibosDetalles_CodigoSueldos_recibosDetalles_id,
		     RecibosDetalles_CodigoSueldos.`codigoSueldos_id` AS RecibosDetalles_CodigoSueldos_codigoSueldos_id,
		     RecibosPersonal.`recibos_id` AS RecibosPersonal_recibos_id,
		     RecibosPersonal.`personal_id` AS RecibosPersonal_personal_id,
		     recibo_detallesRecibos.`recibo_id` AS recibo_detallesRecibos_recibo_id,
		     recibo_detallesRecibos.`recibosdetalle_id` AS recibo_detallesRecibos_recibosdetalle_id,
		     CodigoSueldos.`id` AS CodigoSueldos_id,
		     CodigoSueldos.`descripcion` AS CodigoSueldos_descripcion,
		     CodigoSueldos.`remunerativo` AS CodigoSueldos_remunerativo,
		     CodigoSueldos.`noRemunerativo` AS CodigoSueldos_noRemunerativo,
		     CodigoSueldos.`descuento` AS CodigoSueldos_descuento,
		     CodigoSueldos.`asignacion` AS CodigoSueldos_asignacion,
		     CodigoSueldos.`fijo` AS CodigoSueldos_fijo,
		     CodigoSueldos.`porcentaje` AS CodigoSueldos_porcentaje,
		     CodigoSueldos.`importe` AS CodigoSueldos_importe,
		     Bancos.`id` AS Bancos_id,
		     Bancos.`nombre` AS Bancos_nombre,
		     Personal.`idP` AS Personal_idP,
		     Personal.`nombre` AS Personal_nombre,
		     Personal.`localidad` AS Personal_localidad,
		     Personal.`categoria` AS Personal_categoria,
		     Personal.`documentoNum` AS Personal_documentoNum,
		     Personal.`fechaIngreso` AS Personal_fechaIngreso,
		     Personal.`cuil` AS Personal_cuil,
		     Personal.`antPorcentaje` AS Personal_antPorcentaje,
		     Personal.`tarea` AS Personal_tarea,
		     Personal.`antiguedad` AS Personal_antiguedad,
		     Personal.`compensatorio` AS Personal_compensatorio,
		     Categorias.`id` AS Categorias_id,
		     Categorias.`categoria` AS Categorias_categoria,
		     Categorias.`salario` AS Categorias_salario,
		     Categorias.`idSindicato` AS Categorias_idSindicato,
		     Sindicatos.`id` AS Sindicatos_id,
		     Sindicatos.`sindicato` AS Sindicatos_sindicato,
		     Personal.`tipoContratacion` AS Personal_tipoContratacion
		FROM
		     `RecibosDetalle` RecibosDetalle INNER JOIN `RecibosDetalles_CodigoSueldos` RecibosDetalles_CodigoSueldos ON RecibosDetalle.`id` = RecibosDetalles_CodigoSueldos.`recibosDetalles_id`
		     INNER JOIN `recibo_detallesRecibos` recibo_detallesRecibos ON RecibosDetalle.`id` = recibo_detallesRecibos.`recibosdetalle_id`
		     INNER JOIN `Recibos` Recibos ON recibo_detallesRecibos.`recibo_id` = Recibos.`id`
		     INNER JOIN `RecibosPersonal` RecibosPersonal ON Recibos.`id` = RecibosPersonal.`recibos_id`
		     INNER JOIN `Bancos` Bancos ON Recibos.`banco_id` = Bancos.`id`
		     INNER JOIN `Personal` Personal ON RecibosPersonal.`personal_id` = Personal.`idP`
		     INNER JOIN `Categorias` Categorias ON Personal.`categoria` = Categorias.`id`
		     INNER JOIN `Sindicatos` Sindicatos ON Categorias.`idSindicato` = Sindicatos.`id`
		     INNER JOIN `CodigoSueldos` CodigoSueldos ON RecibosDetalles_CodigoSueldos.`codigoSueldos_id` = CodigoSueldos.`id`
		WHERE
		     Recibos.`mes` = $mes
		 AND Recibos.`anio` = $anio
		 AND Recibos.`periodo` = $periodo
		 AND Recibos.`compensatorio` = 1
		ORDER BY
		     Personal.`nombre` ASC,
		     CodigoSueldos.`remunerativo` DESC";
		     
		//Exportamos el reporte
		$sql = '';
		$compensatorio == 'on' ? $sql = $sqlCompensatorio : $sql = $sqlSimple; 
		
		$jru->runPdfFromSql($ruta, $destino, $param, $sql, $conn->getConnection());
		
		echo json_encode(
			array(
				'success'=> true,	
			)
		);
		
		return new Response();
	}
	
	/**
     * @Route("/recibosPdf", name="mbp_personal_recibosPdf", options={"expose"=true})
     */
	public function recibosPdfAction()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpPersonalBundle/Resources/public/pdf/').'recibos.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'recibos.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-type', 'application/pdf');

        return $response;
	}
	
	/**
     * @Route("/reporteLibroSueldos", name="mbp_personal_reporteLibroSueldos", options={"expose"=true})
     */
	public function reporteLibroSueldosAction()
	{
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$repo = $em->getRepository('MbpPersonalBundle:Recibos');
		
		/*
		 * PARAMETROS
		 */
		$mesDesde = $req->request->get('mesDesde');
		$mesHasta = $req->request->get('mesHasta');
		$anioDesde = $req->request->get('anioDesde');
		$anioHasta = $req->request->get('anioHasta');
		$periodoDesde = (int)$req->request->get('pagoTipo');
		$periodoHasta;
		if($periodoDesde == 99){
			$periodoDesde = 1;
			$periodoHasta = 99;
		}else{
			$periodoHasta = $periodoDesde;
		}
		$compensatorio = $req->request->get('compensatorio');
		$compensatorio == 'on' ? $compensatorio = 1 : '';
		
		$reporteador = $this->get('reporteador');
		$kernel = $this->get('kernel');
		
		
		/*
		 * Configuro reporte
		 */
		$jru = $reporteador->jru();
		
		/*
		 * Ruta archivo Jasper
		 */				
				
		$ruta = $kernel->locateResource('@MbpPersonalBundle/Reportes/LibroSueldos.jrxml');
		
		/*
		 * Ruta de destino del PDF
		 */
		$destino = $kernel->locateResource('@MbpPersonalBundle/Resources/public/pdf/').'LibroSueldos.pdf';
		
		
		//Parametros HashMap
		$param = $reporteador->getJava('java.util.HashMap');
		$param->put('periodoDesde', $periodoDesde);
		$param->put('mesDesde', (int)$mesDesde);
		$param->put('anioHasta', (int)$anioHasta);
		
		$conn = $reporteador->getJdbc();
		
		$sql = "SELECT
		     Recibos.`id` AS Recibos_id,
		     Recibos.`compensatorio` AS Recibos_compensatorio,
		     Recibos.`banco_id` AS Recibos_banco_id,
		     Recibos.`fechaPago` AS Recibos_fechaPago,
		     Recibos.`periodo` AS Recibos_periodo,
		     Recibos.`mes` AS Recibos_mes,
		     Recibos.`anio` AS Recibos_anio,
		     Recibos.`tipoPago` AS Recibos_tipoPago,
		     RecibosDetalle.`id` AS RecibosDetalle_id,
		     RecibosDetalle.`cantConceptoVar` AS RecibosDetalle_cantConceptoVar,
		     RecibosDetalle.`valorConceptoHist` AS RecibosDetalle_valorConceptoHist,
		     RecibosDetalle.`remunerativo` AS RecibosDetalle_remunerativo,
		     RecibosDetalle.`exento` AS RecibosDetalle_exento,
		     RecibosDetalle.`descuento` AS RecibosDetalle_descuento,
		     RecibosPersonal.`recibos_id` AS RecibosPersonal_recibos_id,
		     RecibosPersonal.`personal_id` AS RecibosPersonal_personal_id,
		     recibo_detallesRecibos.`recibo_id` AS recibo_detallesRecibos_recibo_id,
		     recibo_detallesRecibos.`recibosdetalle_id` AS recibo_detallesRecibos_recibosdetalle_id,
		     Personal.`idP` AS Personal_idP,
		     Personal.`nombre` AS Personal_nombre,
		     Personal.`localidad` AS Personal_localidad,
		     Personal.`documentoNum` AS Personal_documentoNum,
		     Personal.`fechaIngreso` AS Personal_fechaIngreso,
		     Personal.`cuil` AS Personal_cuil,
		     CodigoSueldos.`id` AS CodigoSueldos_id,
		     CodigoSueldos.`descripcion` AS CodigoSueldos_descripcion,
		     RecibosDetalles_CodigoSueldos.`recibosDetalles_id` AS RecibosDetalles_CodigoSueldos_recibosDetalles_id,
		     RecibosDetalles_CodigoSueldos.`codigoSueldos_id` AS RecibosDetalles_CodigoSueldos_codigoSueldos_id,
		     Recibos.`basicoHist` AS Recibos_basicoHist,
		     Recibos.`categoriaHist` AS Recibos_categoriaHist,
		     Recibos.`sindicatoHist` AS Recibos_sindicatoHist,
		     Recibos.`domicilio` AS Recibos_domicilio,
		     Recibos.`eCivil` AS Recibos_eCivil,
		     Recibos.`obraSocial` AS Recibos_obraSocial,
		     Personal.`nacionalidad` AS Personal_nacionalidad,
		     Personal.`fechaNacimiento` AS Personal_fechaNacimiento,
		     Recibos.`tarea` AS Recibos_tarea,
		     Recibos.`antiguedad` AS Recibos_antiguedad
		FROM
		     `Recibos` Recibos INNER JOIN `RecibosPersonal` RecibosPersonal ON Recibos.`id` = RecibosPersonal.`recibos_id`
		     INNER JOIN `recibo_detallesRecibos` recibo_detallesRecibos ON Recibos.`id` = recibo_detallesRecibos.`recibo_id`
		     INNER JOIN `RecibosDetalle` RecibosDetalle ON recibo_detallesRecibos.`recibosdetalle_id` = RecibosDetalle.`id`
		     INNER JOIN `RecibosDetalles_CodigoSueldos` RecibosDetalles_CodigoSueldos ON RecibosDetalle.`id` = RecibosDetalles_CodigoSueldos.`recibosDetalles_id`
		     INNER JOIN `CodigoSueldos` CodigoSueldos ON RecibosDetalles_CodigoSueldos.`codigoSueldos_id` = CodigoSueldos.`id`
		     INNER JOIN `Personal` Personal ON RecibosPersonal.`personal_id` = Personal.`idP`
		     INNER JOIN `Categorias` Categorias ON Personal.`categoria` = Categorias.`id`
		WHERE
		     Recibos.`mes` BETWEEN $mesDesde AND $mesHasta
		 AND Recibos.`anio` BETWEEN $anioDesde AND $anioHasta
		 AND Recibos.`periodo` BETWEEN $periodoDesde AND $periodoHasta
		 AND Recibos.`compensatorio` = $compensatorio
		ORDER BY Personal.`nombre` ASC, RecibosDetalle.`remunerativo` DESC";
		
		$jru->runPdfFromSql($ruta, $destino, $param, $sql, $conn->getConnection());
		
		echo json_encode(
			array(
				'success'=> true,	
			)
		);
		
		return new Response();
	} 
	
	/**
     * @Route("/libroSueldosPdf", name="mbp_personal_libroSueldosPdf", options={"expose"=true})
     */ 
	public function libroSueldosPdfAction()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpPersonalBundle/Resources/public/pdf/').'LibroSueldos.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'LibroSueldos.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-type', 'application/pdf');

        return $response;
	}
	
	/**
     * @Route("/crearResumenAguinaldo", name="mbp_personal_crearResumenAguinaldo", options={"expose"=true})
     */ 
	public function crearResumenAguinaldoAction()
	{
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$repo = $em->getRepository('MbpPersonalBundle:Recibos');
		
		/*
		 * PARAMETROS
		 */
		$mesDesde = $req->request->get('mesDesde');
		$mesHasta = $req->request->get('mesHasta');
		$anioDesde = $req->request->get('anioDesde');
		$anioHasta = $req->request->get('anioHasta');
		$comp = $req->request->get('compensatorio');
		$comp == "on" ? $comp = 1 : "";
		
		$reporteador = $this->get('reporteador');
		$kernel = $this->get('kernel');
		
		/*
		 * Configuro reporte
		 */
		$jru = $reporteador->jru();
		
		/*
		 * Ruta archivo Jasper
		 */				
				
		$ruta = $kernel->locateResource('@MbpPersonalBundle/Reportes/resumenAguinaldo.jrxml');
		
		/*
		 * Ruta de destino del PDF
		 */
		$destino = $kernel->locateResource('@MbpPersonalBundle/Resources/public/pdf/').'resumenAguinaldo.pdf';
				
		//Parametros HashMap
		$param = $reporteador->getJava('java.util.HashMap');
		$param->put('mesInicio', (int)$mesDesde);
		$param->put('mesFin', (int)$mesHasta);
		$param->put('anioInicio', (int)$anioDesde);
		$param->put('anioFin', (int)$anioHasta);
		
		$conn = $reporteador->getJdbc();
		
				
		$sql = "
		SELECT
		     SUM(RecibosDetalle.`remunerativo`) AS RecibosDetalle_remunerativo,
		     recibo_detallesRecibos.`recibo_id` AS recibo_detallesRecibos_recibo_id,
		     recibo_detallesRecibos.`recibosdetalle_id` AS recibo_detallesRecibos_recibosdetalle_id,
		     RecibosPersonal.`recibos_id` AS RecibosPersonal_recibos_id,
		     RecibosPersonal.`personal_id` AS RecibosPersonal_personal_id,
		     RecibosDetalle.`id` AS RecibosDetalle_id,
		     RecibosDetalle.`cantConceptoVar` AS RecibosDetalle_cantConceptoVar,
		     RecibosDetalle.`valorConceptoHist` AS RecibosDetalle_valorConceptoHist,
		     Recibos.`id` AS Recibos_id,
		     Recibos.`compensatorio` AS Recibos_compensatorio,
		     Recibos.`periodo` AS Recibos_periodo,
		     Recibos.`mes` AS Recibos_mes,
		     Recibos.`anio` AS Recibos_anio,
		     Personal.`idP` AS Personal_idP,
		     Personal.`nombre` AS Personal_nombre
		FROM
		     `RecibosDetalle` RecibosDetalle INNER JOIN `recibo_detallesRecibos` recibo_detallesRecibos ON RecibosDetalle.`id` = recibo_detallesRecibos.`recibosdetalle_id`
		     INNER JOIN `Recibos` Recibos ON recibo_detallesRecibos.`recibo_id` = Recibos.`id`
		     INNER JOIN `RecibosPersonal` RecibosPersonal ON Recibos.`id` = RecibosPersonal.`recibos_id`
		     INNER JOIN `Personal` Personal ON RecibosPersonal.`personal_id` = Personal.`idP`
		WHERE
		     Recibos.mes BETWEEN '$mesDesde' AND '$mesHasta' AND
		     Recibos.anio BETWEEN '$anioDesde' AND '$anioHasta'  AND
		     Recibos.periodo != 7 AND
		     Recibos.periodo != 5 AND
		     Recibos.compensatorio = $comp
		GROUP BY
		     Personal.nombre, Recibos.mes
		ORDER BY
		     Personal_nombre ASC
		";
		
		
		$jru->runPdfFromSql($ruta, $destino, $param, $sql, $conn->getConnection());
		
		return new Response(json_encode(array(
				'success' => true
			))
		);
	}
	
	/**
     * @Route("/resumenAguinaldoPdf", name="mbp_personal_resumenAguinaldoPdf", options={"expose"=true})
     */ 
	public function resumenAguinaldoPdfAction()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpPersonalBundle/Resources/public/pdf/').'resumenAguinaldo.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'resumenAguianldo.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-type', 'application/pdf');

        return $response;
	}
}























