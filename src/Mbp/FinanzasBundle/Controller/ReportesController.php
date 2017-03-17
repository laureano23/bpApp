<?php

namespace Mbp\FinanzasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Mbp\FinanzasBundle\Entity\Facturas;
use Mbp\FinanzasBundle\Entity\FacturaDetalle;
use Mbp\FinanzasBundle\Entity\Cobranzas;
use Mbp\FinanzasBundle\Entity\CobranzasDetalle;

class ReportesController extends Controller
{
	/**
     * @Route("/CCClientes/Reportes/generarFc", name="mbp_CCClientes_generateFc", options={"expose"=true})
     */	    
    public function generarFcAction()
	{
		//RECIBO PARAMETROS
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		
		/*
		 * PARAMETROS
		 */
		$tipo = $req->request->get('tipo');
		$idCliente = $req->request->get('idCliente');		
		$idFactura = $req->request->get('idFactura');	
						
		$reporteador = $this->get('reporteador');
		$kernel = $this->get('kernel');
		
		/*
		 * Configuro reporte
		 */
		$jru = $reporteador->jru();
		
		/*
		 * Ruta archivo Jasper
		 */				
				
		$ruta = $kernel->locateResource('@MbpFinanzasBundle/Reportes/factura.jrxml');
		
		/*
		 * Ruta de destino del PDF
		 */
		$destino = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/pdf/').'factura.pdf';		
		
		//Parametros HashMap
		$param = $reporteador->getJava('java.util.HashMap');
		$rutaLogo = $reporteador->getRutaLogo($kernel);
		
		$param->put('tipo', $tipo);
		$param->put('rutaLogo', $rutaLogo);
		$param->put('idFactura', $idFactura);
		
		$conn = $reporteador->getJdbc();
		
		$sql = "SELECT
		     CASE WHEN Facturas.`vtoCae`=0 THEN NULL ELSE Facturas.`vtoCae` END AS Facturas_vtoCae,
		     factura_detallesFacturas.`factura_id` AS factura_detallesFacturas_factura_id,
		     factura_detallesFacturas.`facturadetalle_id` AS factura_detallesFacturas_facturadetalle_id,
		     Facturas.`id` AS Facturas_id,
		     Facturas.`fecha` AS Facturas_fecha,
		     Facturas.`concepto` AS Facturas_concepto,
		     Facturas.`vencimiento` AS Facturas_vencimiento,
		     Facturas.`clienteId` AS Facturas_clienteId,
		     Facturas.`tipo` AS Facturas_tipo,
		     FacturaDetalle.`id` AS FacturaDetalle_id,
		     FacturaDetalle.`descripcion` AS FacturaDetalle_descripcion,
		     FacturaDetalle.`cantidad` AS FacturaDetalle_cantidad,
		     FacturaDetalle.`precio` AS FacturaDetalle_precio,
		     FacturaDetalle.`articuloId` AS FacturaDetalle_articuloId,
		     articulos.`idArticulos` AS articulos_idArticulos,
		     articulos.`codigo` AS articulos_codigo,
		     articulos.`descripcion` AS articulos_descripcion,
		     articulos.`unidad` AS articulos_unidad,
		     articulos.`precio` AS articulos_precio,
		     cliente.`idCliente` AS cliente_idCliente,
		     Facturas.`cae` AS Facturas_cae,
		     Facturas.`ptoVta` AS Facturas_ptoVta,
		     Facturas.`ivaCond` AS Facturas_ivaCond,
		     Facturas.`rSocial` AS Facturas_rSocial,
		     Facturas.`domicilio` AS Facturas_domicilio,
		     Facturas.`localidad` AS Facturas_localidad,
		     Facturas.`cuit` AS Facturas_cuit,
		     Facturas.`condVta` AS Facturas_condVta,
		     Facturas.`rtoNro` AS Facturas_rtoNro,
		     Facturas.`perIIBB` AS Facturas_perIIBB,
		     Facturas.`iva21` AS Facturas_iva21,
		     Facturas.`dtoTotal` AS Facturas_dtoTotal,
		     Facturas.`vtoCae` AS Facturas_vtoCae,
		     Facturas.`fcNro` AS Facturas_fcNro
		FROM
		     `Facturas` Facturas INNER JOIN `factura_detallesFacturas` factura_detallesFacturas ON Facturas.`id` = factura_detallesFacturas.`factura_id`
		     INNER JOIN `FacturaDetalle` FacturaDetalle ON factura_detallesFacturas.`facturadetalle_id` = FacturaDetalle.`id`
		     INNER JOIN `articulos` articulos ON FacturaDetalle.`articuloId` = articulos.`idArticulos`
		     INNER JOIN `cliente` cliente ON Facturas.`clienteId` = cliente.`idCliente`
		WHERE
		     Facturas.`id` = $idFactura";
		
		$res = $jru->runPdfFromSql($ruta, $destino, $param, $sql, $conn->getConnection());
		
		//ARMO RESPUESTA
		$response = new Response();
		$content;
		if($res == FALSE){
			$response->setContent(
				json_encode(array(
					'success' =>false,		
					'msg' => 'Error al procesor el reporte'			
					)
				)
			);
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);			
		}else{
			$response->setContent(
				json_encode(array(
					'success' =>true,					
					)
				)
			);
			$response->setStatusCode(Response::HTTP_OK);
		}
		
		return $response;
	}
	
	/**
     * @Route("/CCClientes/Reportes/verFc", name="mbp_CCClientes_verFc", options={"expose"=true})
     */	    
    public function verFcAction()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/pdf/').'factura.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'factura.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-type', 'application/pdf');

        return $response;
	}

	/**
     * @Route("/CCClientes/Reportes/generarCobranza", name="mbp_CCClientes_generateCobranza", options={"expose"=true})
     */	
    public function generarCobranzaAction()
	{
		//RECIBO PARAMETROS
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$response = new Response;
		
		try{
			/*
			 * PARAMETROS
			 */
			$idCobranza = $req->request->get('idCobranza');
							
			$reporteador = $this->get('reporteador');
			$kernel = $this->get('kernel');
			
			/*
			 * Configuro reporte
			 */
			$jru = $reporteador->jru();
			
			/*
			 * Ruta archivo Jasper
			 */				
					
			$ruta = $kernel->locateResource('@MbpFinanzasBundle/Reportes/ReciboCobranzas.jrxml');
			
			/*
			 * Ruta de destino del PDF
			 */
			$destino = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/pdf/').'ReciboCobranzas.pdf';		
			
			//Parametros HashMap
			$param = $reporteador->getJava('java.util.HashMap');
			$rutaLogo = $reporteador->getRutaLogo($kernel);
			
			$param->put('rutaLogo', $rutaLogo);
			$param->put('cobranzaId', $idCobranza);
			$param->put('SUBREPORT_DIR', $kernel->locateResource('@MbpFinanzasBundle/Reportes/'));
			
			$conn = $reporteador->getJdbc();
			
			$sql = "SELECT
			     Cobranzas.`id` AS Cobranzas_id,
			     Cobranzas.`emision` AS Cobranzas_emision,
			     Cobranzas.`clienteId` AS Cobranzas_clienteId,
			     CobranzasDetalle.`id` AS CobranzasDetalle_id,
			     CobranzasDetalle.`importe` AS CobranzasDetalle_importe,
			     CobranzasDetalle.`vencimiento` AS CobranzasDetalle_vencimiento,
			     cobranza_detallesCobranzas.`cobranza_id` AS cobranza_detallesCobranzas_cobranza_id,
			     cobranza_detallesCobranzas.`cobranzasdetalle_id` AS cobranza_detallesCobranzas_cobranzasdetalle_id,
			     cliente.`idCliente` AS cliente_idCliente,
			     cliente.`rsocial` AS cliente_rsocial,
			     FormasPago.`id` AS FormasPago_id,
			     FormasPago.`descripcion` AS FormasPago_descripcion,
			     Cobranzas.`numRecibo` AS Cobranzas_numRecibo,
			     CobranzasDetalle.`numero` AS CobranzasDetalle_numero,
			     CobranzasDetalle.`banco` AS CobranzasDetalle_banco
			FROM
			     `Cobranzas` Cobranzas INNER JOIN `cobranza_detallesCobranzas` cobranza_detallesCobranzas ON Cobranzas.`id` = cobranza_detallesCobranzas.`cobranza_id`
			     INNER JOIN `cliente` cliente ON Cobranzas.`clienteId` = cliente.`idCliente`
			     INNER JOIN `CobranzasDetalle` CobranzasDetalle ON cobranza_detallesCobranzas.`cobranzasdetalle_id` = CobranzasDetalle.`id`
			     INNER JOIN `FormasPago` FormasPago ON CobranzasDetalle.`formaPagoId` = FormasPago.`id`
			WHERE
			     Cobranzas.`id` = $idCobranza";
			
			$jru->runPdfFromSql($ruta, $destino, $param, $sql, $conn->getConnection());	
		}catch(\Exception $e){
			$response->setStatusCode($response::HTTP_INTERNAL_SERVER_ERROR);
			return $response->setContent(
				json_encode(array('success' => false, 'msg' => $e->getMessage()))
				);
		}
		
		return $response->setContent(
			json_encode(array('success' => true))
			);
	}	
	
	/**
     * @Route("/CCClientes/verCobranza", name="mbp_CCClientes_verCobranza", options={"expose"=true})
     */	
    public function verCobranzaAction()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/pdf/').'ReciboCobranzas.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'ReciboCobranzas.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-type', 'application/pdf');
		
		return $response;
	}
	
	/**
     * @Route("/CCClientes/Reportes/LibroIVAVentas", name="mbp_CCClientes_LibroIVAVentas", options={"expose"=true})
     */	
    public function LibroIVAVentasAction()
	{
		$response = new Response;
				
		try{
			/*
			 * PARAMETROS
			 */
			$desde = $this->get('request')->get('desde');
			$hasta = $this->get('request')->get('hasta');
							
			$reporteador = $this->get('reporteador');
			$kernel = $this->get('kernel');
			
			/*
			 * Configuro reporte
			 */
			$jru = $reporteador->jru();
			
			/*
			 * Ruta archivo Jasper
			 */				
					
			$ruta = $kernel->locateResource('@MbpFinanzasBundle/Reportes/LibroIVAVentas.jrxml');
			
			/*
			 * Ruta de destino del PDF
			 */
			$destino = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/pdf/').'LibroIVAVentas.pdf';		
			
			//Parametros HashMap
			$param = $reporteador->getJava('java.util.HashMap');
			$rutaLogo = $reporteador->getRutaLogo($kernel);
			
			$param->put('fechaDesde', $desde);
			$param->put('fechaHasta', $hasta); 
			
			$conn = $reporteador->getJdbc();
			
			$desde = \DateTime::createFromFormat('d/m/Y', $desde);
			$hasta = \DateTime::createFromFormat('d/m/Y', $hasta);
			
			$desde = $desde->format('Y-m-d');
			$hasta = $hasta->format('Y-m-d');
							
			
			$sql = "SELECT
			     Facturas.`id` AS Facturas_id,
			     Facturas.`fecha` AS Facturas_fecha,
			     Facturas.`concepto` AS Facturas_concepto,
			     Facturas.`vencimiento` AS Facturas_vencimiento,
			     Facturas.`clienteId` AS Facturas_clienteId,
			     Facturas.`tipo` AS Facturas_tipo,
			     Facturas.`cae` AS Facturas_cae,
			     Facturas.`vtoCae` AS Facturas_vtoCae,
			     Facturas.`ptoVta` AS Facturas_ptoVta,
			     Facturas.`dtoTotal` AS Facturas_dtoTotal,
			     Facturas.`perIIBB` AS Facturas_perIIBB,
			     Facturas.`iva21` AS Facturas_iva21,
			     Facturas.`rSocial` AS Facturas_rSocial,
			     Facturas.`domicilio` AS Facturas_domicilio,
			     Facturas.`localidad` AS Facturas_localidad,
			     Facturas.`cuit` AS Facturas_cuit,
			     Facturas.`condVta` AS Facturas_condVta,
			     Facturas.`rtoNro` AS Facturas_rtoNro,
			     Facturas.`ivaCond` AS Facturas_ivaCond,
			     Facturas.`fcNro` AS Facturas_fcNro,
			     Facturas.`total` AS Facturas_total,
			     cliente.`idCliente` AS cliente_idCliente,
			     cliente.`rsocial` AS cliente_rsocial,
			     Facturas.`porcentajeIIBB` AS Facturas_porcentajeIIBB
			FROM
			     `cliente` cliente INNER JOIN `Facturas` Facturas ON cliente.`idCliente` = Facturas.`clienteId`
			WHERE Facturas.`fecha` BETWEEN '$desde' AND '$hasta'";
			
			$jru->runPdfFromSql($ruta, $destino, $param, $sql, $conn->getConnection());	
		}catch(\Exception $e){
			$response->setStatusCode($response::HTTP_INTERNAL_SERVER_ERROR);
			return $response->setContent(
				json_encode(array('success' => false, 'msg' => $e->getMessage()))
				);
		}
		
		return $response->setContent(
			json_encode(array('success' => true))
			);
	}
	
	/**
     * @Route("/CCClientes/Reportes/VerLibroIVAVentas", name="mbp_CCClientes_VerLibroIVAVentas", options={"expose"=true})
     */	
    public function VerLibroIVAVentasAction()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/pdf/').'LibroIVAVentas.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'ReciboCobranzas.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-type', 'application/pdf');
		
		return $response;
	}
}





















