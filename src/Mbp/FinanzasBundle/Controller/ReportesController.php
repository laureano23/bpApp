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
			     Facturas.`cuit` AS Facturas_cuit,
			     Facturas.`condVta` AS Facturas_condVta,
			     Facturas.`perIIBB` AS Facturas_perIIBB,
			     Facturas.`iva21` AS Facturas_iva21,
			     Facturas.`dtoTotal` AS Facturas_dtoTotal,
			     Facturas.`vtoCae` AS Facturas_vtoCae,
			     Facturas.`fcNro` AS Facturas_fcNro,
			     Facturas.`departamento` AS Facturas_departamento,
			     FacturaDetalle.`remitoId` AS FacturaDetalle_remitoId,
			     RemitosClientes.`id` AS RemitosClientes_id,
			     RemitosClientes.`fecha` AS RemitosClientes_fecha,
			     RemitosClientes.`remitoNum` AS RemitosClientes_remitoNum,
			     RemitosClientes.`clienteId` AS RemitosClientes_clienteId,
			     RemitosClientes.`proveedorId` AS RemitosClientes_proveedorId,
			     RemitoClientes_detalle.`remitosclientes_id` AS RemitoClientes_detalle_remitosclientes_id,
			     RemitoClientes_detalle.`remitosclientesdetalles_id` AS RemitoClientes_detalle_remitosclientesdetalles_id,
			     RemitosClientesDetalles.`id` AS RemitosClientesDetalles_id,
			     RemitosClientesDetalles.`descripcion` AS RemitosClientesDetalles_descripcion,
			     RemitosClientesDetalles.`cantidad` AS RemitosClientesDetalles_cantidad,
			     RemitosClientesDetalles.`unidad` AS RemitosClientesDetalles_unidad,
			     RemitosClientesDetalles.`oc` AS RemitosClientesDetalles_oc,
			     RemitosClientesDetalles.`articuloId` AS RemitosClientesDetalles_articuloId,
			     RemitosClientesDetalles.`pedidoDetalleId` AS RemitosClientesDetalles_pedidoDetalleId,
			     RemitosClientesDetalles.`facturado` AS RemitosClientesDetalles_facturado,
			     Facturas.`moneda` AS Facturas_moneda,
			     TipoComprobante.`id` AS TipoComprobante_id,
			     TipoComprobante.`esFactura` AS TipoComprobante_esFactura,
			     TipoComprobante.`esNotaCredito` AS TipoComprobante_esNotaCredito,
			     TipoComprobante.`esNotaDebito` AS TipoComprobante_esNotaDebito,
			     TipoComprobante.`descripcion` AS TipoComprobante_descripcion,
			     TipoComprobante.`esBalance` AS TipoComprobante_esBalance,
			     TipoComprobante.`subTipoA` AS TipoComprobante_subTipoA,
			     TipoComprobante.`subTipoB` AS TipoComprobante_subTipoB,
			     TipoComprobante.`subTipoE` AS TipoComprobante_subTipoE
			FROM
			     `Facturas` Facturas LEFT OUTER JOIN `factura_detallesFacturas` factura_detallesFacturas ON Facturas.`id` = factura_detallesFacturas.`factura_id`
			     INNER JOIN `FacturaDetalle` FacturaDetalle ON factura_detallesFacturas.`facturadetalle_id` = FacturaDetalle.`id`
			     LEFT OUTER JOIN `articulos` articulos ON FacturaDetalle.`articuloId` = articulos.`idArticulos`
			     LEFT OUTER JOIN `RemitosClientesDetalles` RemitosClientesDetalles ON FacturaDetalle.`remitoId` = RemitosClientesDetalles.`id`
			     LEFT OUTER JOIN `RemitoClientes_detalle` RemitoClientes_detalle ON RemitosClientesDetalles.`id` = RemitoClientes_detalle.`remitosclientesdetalles_id`
			     LEFT OUTER JOIN `RemitosClientes` RemitosClientes ON RemitoClientes_detalle.`remitosclientes_id` = RemitosClientes.`id`
			     LEFT OUTER JOIN `cliente` cliente ON Facturas.`clienteId` = cliente.`idCliente`
			     INNER JOIN `TipoComprobante` TipoComprobante ON Facturas.`tipoId` = TipoComprobante.`id`
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
			     FormasPagos.`id` AS FormasPago_id,
			     FormasPagos.`descripcion` AS FormasPago_descripcion,
			     Cobranzas.`numRecibo` AS Cobranzas_numRecibo,
			     CobranzasDetalle.`numero` AS CobranzasDetalle_numero,
			     CobranzasDetalle.`banco` AS CobranzasDetalle_banco
			FROM
			     `Cobranzas` Cobranzas INNER JOIN `cobranza_detallesCobranzas` cobranza_detallesCobranzas ON Cobranzas.`id` = cobranza_detallesCobranzas.`cobranza_id`
			     INNER JOIN `cliente` cliente ON Cobranzas.`clienteId` = cliente.`idCliente`
			     INNER JOIN `CobranzasDetalle` CobranzasDetalle ON cobranza_detallesCobranzas.`cobranzasdetalle_id` = CobranzasDetalle.`id`
			     INNER JOIN `FormasPagos` FormasPagos ON CobranzasDetalle.`formaPagoId` = FormasPagos.`id`
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
				     cliente.`idCliente` AS cliente_idCliente,
				     cliente.`rsocial` AS cliente_rsocial,
				     Facturas.`id` AS Facturas_id,
				     Facturas.`fecha` AS Facturas_fecha,
				     Facturas.`concepto` AS Facturas_concepto,
				     Facturas.`clienteId` AS Facturas_clienteId,
				     Facturas.`tipo` AS Facturas_tipo,
				     Facturas.`ptoVta` AS Facturas_ptoVta,
				     CASE WHEN Facturas.`moneda` = 1 THEN Facturas.`perIIBB` * Facturas.`tipoCambio` ELSE Facturas.`perIIBB` END AS Facturas_perIIBB,
				     CASE WHEN Facturas.`moneda` = 1 THEN Facturas.`iva21` * Facturas.`tipoCambio` ELSE Facturas.`iva21` END  AS Facturas_iva21,
				     Facturas.`fcNro` AS Facturas_fcNro,
				     Facturas.`rSocial` AS Facturas_rSocial,
				     Facturas.`cuit` AS Facturas_cuit,
				     Facturas.`ivaCond` AS Facturas_ivaCond,
				     CASE WHEN Facturas.`moneda` = 1 THEN Facturas.`total` * Facturas.`tipoCambio` ELSE Facturas.`total` END AS Facturas_total,
				     Facturas.`porcentajeIIBB` AS Facturas_porcentajeIIBB,
				     Facturas.`moneda` AS Facturas_moneda,
				     Facturas.`tipoCambio` AS Facturas_tipoCambio
				FROM
				     `cliente` cliente INNER JOIN `Facturas` Facturas ON cliente.`idCliente` = Facturas.`clienteId`
				WHERE
				     Facturas.`fecha` BETWEEN '$desde' AND '$hasta'";
			
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
	
	/**
     * @Route("/Reportes/ArtVendidos", name="mbp_Reportes_ArtVendidos", options={"expose"=true})
     */	
    public function ArtVendidos()
	{
		$response = new Response;
				
		try{
			/*
			 * PARAMETROS
			 */
			$desde = $this->get('request')->get('desde');
			$hasta = $this->get('request')->get('hasta');
			$cod1 = $this->get('request')->get('codigo1');
			$cod2 = $this->get('request')->get('codigo2');
			$cliente1 = $this->get('request')->get('cliente1');
			$cliente2 = $this->get('request')->get('cliente2');
							
			$reporteador = $this->get('reporteador');
			$kernel = $this->get('kernel');
			
			/*
			 * Configuro reporte
			 */
			$jru = $reporteador->jru();
			
			/*
			 * Ruta archivo Jasper
			 */				
					
			$ruta = $kernel->locateResource('@MbpFinanzasBundle/Reportes/ArtClientePeriodo.jrxml');
			
			/*
			 * Ruta de destino del PDF
			 */
			$destino = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/pdf/').'ArtClientePeriodo.pdf';		
			
			//Parametros HashMap
			$param = $reporteador->getJava('java.util.HashMap');
			$rutaLogo = $reporteador->getRutaLogo($kernel);
			
			
			$param->put('CODIGO1', $cod1);
			$param->put('CODIGO2', $cod2);
			$param->put('CLIENTE1', $cliente1);
			$param->put('CLIENTE2', $cliente2); 
			
			$conn = $reporteador->getJdbc();
			
			$desde = \DateTime::createFromFormat('d/m/Y', $desde);
			$hasta = \DateTime::createFromFormat('d/m/Y', $hasta);
			$param->put('DESDE', $desde->format('d/m/Y'));
			$param->put('HASTA', $hasta->format('d/m/Y'));	
			
			
			$desde = $desde->format('Y-m-d');
			$hasta = $hasta->format('Y-m-d');
									
			
			$sql = "SELECT
			     Facturas.`id` AS Facturas_id,
			     Facturas.`fecha` AS Facturas_fecha,
			     Facturas.`concepto` AS Facturas_concepto,
			     Facturas.`vencimiento` AS Facturas_vencimiento,
			     Facturas.`clienteId` AS Facturas_clienteId,
			     Facturas.`tipo` AS Facturas_tipo,
			     Facturas.`ptoVta` AS Facturas_ptoVta,
			     Facturas.`cae` AS Facturas_cae,
			     Facturas.`vtoCae` AS Facturas_vtoCae,
			     Facturas.`dtoTotal` AS Facturas_dtoTotal,
			     Facturas.`perIIBB` AS Facturas_perIIBB,
			     Facturas.`iva21` AS Facturas_iva21,
			     Facturas.`fcNro` AS Facturas_fcNro,
			     Facturas.`rSocial` AS Facturas_rSocial,
			     Facturas.`domicilio` AS Facturas_domicilio,
			     Facturas.`departamento` AS Facturas_departamento,
			     Facturas.`cuit` AS Facturas_cuit,
			     Facturas.`ivaCond` AS Facturas_ivaCond,
			     Facturas.`condVta` AS Facturas_condVta,
			     Facturas.`porcentajeIIBB` AS Facturas_porcentajeIIBB,
			     Facturas.`total` AS Facturas_total,
			     cliente.`idCliente` AS cliente_idCliente,
			     cliente.`rsocial` AS cliente_rsocial,
			     cliente.`denominacion` AS cliente_denominacion,
			     factura_detallesFacturas.`factura_id` AS factura_detallesFacturas_factura_id,
			     factura_detallesFacturas.`facturadetalle_id` AS factura_detallesFacturas_facturadetalle_id,
			     FacturaDetalle.`id` AS FacturaDetalle_id,
			     FacturaDetalle.`descripcion` AS FacturaDetalle_descripcion,
			     FacturaDetalle.`cantidad` AS FacturaDetalle_cantidad,
			     FacturaDetalle.`precio` AS FacturaDetalle_precio,
			     FacturaDetalle.`articuloId` AS FacturaDetalle_articuloId,
			     FacturaDetalle.`remitoId` AS FacturaDetalle_remitoId,
			     articulos.`idArticulos` AS articulos_idArticulos,
			     articulos.`codigo` AS articulos_codigo
			FROM
			     `cliente` cliente INNER JOIN `Facturas` Facturas ON cliente.`idCliente` = Facturas.`clienteId`
			     INNER JOIN `factura_detallesFacturas` factura_detallesFacturas ON Facturas.`id` = factura_detallesFacturas.`factura_id`
			     INNER JOIN `FacturaDetalle` FacturaDetalle ON factura_detallesFacturas.`facturadetalle_id` = FacturaDetalle.`id`
			     INNER JOIN `articulos` articulos ON FacturaDetalle.`articuloId` = articulos.`idArticulos`
			WHERE
			     Facturas.`fecha` BETWEEN '$desde' AND '$hasta' AND
			     articulos.`codigo` BETWEEN '$cod1' AND '$cod2' AND
			     cliente.`idCliente` BETWEEN $cliente1 AND $cliente2
			ORDER BY cliente.`idCliente` ASC,
			         Facturas.`fecha` ASC";
			
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
     * @Route("/Reportes/VerArtVendidos", name="mbp_Reportes_VerArtVendidos", options={"expose"=true})
     */	
    public function VerArtVendidos()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/pdf/').'ArtClientePeriodo.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'ArtClientePeriodo.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-type', 'application/pdf');
		
		return $response;
	}
	
	/**
     * @Route("/Reportes/InteresesResarcitorios", name="mbp_Reportes_InteresesResarcitorios", options={"expose"=true})
     */	
    public function InteresesResarcitorios()
	{
		$response = new Response;
				
		try{
			/*
			 * PARAMETROS
			 */
			$desde = $this->get('request')->get('desde');
			$hasta = $this->get('request')->get('hasta');
			$cliente1 = $this->get('request')->get('cliente1');
			$cliente2 = $this->get('request')->get('cliente2');
							
			$reporteador = $this->get('reporteador');
			$kernel = $this->get('kernel');
			
			/*
			 * Configuro reporte
			 */
			$jru = $reporteador->jru();
			
			/*
			 * Ruta archivo Jasper
			 */				
					
			$ruta = $kernel->locateResource('@MbpFinanzasBundle/Reportes/InteresesResarcitorios.jrxml');
			$rutaLogo = $reporteador->getRutaLogo($kernel);
			
			/*
			 * Ruta de destino del PDF
			 */
			$destino = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/pdf/').'InteresesResarcitorios.pdf';		
			
			//Parametros HashMap
			$param = $reporteador->getJava('java.util.HashMap');
			$rutaLogo = $reporteador->getRutaLogo($kernel);
						
			$param->put('CLIENTE_DESDE', $cliente1);
			$param->put('CLIENTE_HASTA', $cliente2); 
			
			$conn = $reporteador->getJdbc();
			
			$desde = \DateTime::createFromFormat('d/m/Y', $desde);
			$hasta = \DateTime::createFromFormat('d/m/Y', $hasta);
			$param->put('COBRANZA_DESDE', $desde->format('d/m/Y'));
			$param->put('COBRANZA_HASTA', $hasta->format('d/m/Y'));	
			$param->put('rutaLogo', $rutaLogo);
			
			
			$desde = $desde->format('Y-m-d');
			$hasta = $hasta->format('Y-m-d');
									
			
			$sql = "SELECT
			     InteresesResarcitorios.`id` AS InteresesResarcitorios_id,
			     InteresesResarcitorios.`cbte` AS InteresesResarcitorios_cbte,
			     InteresesResarcitorios.`monto` AS InteresesResarcitorios_monto,
			     InteresesResarcitorios.`tasa` AS InteresesResarcitorios_tasa,
			     InteresesResarcitorios.`interes` AS InteresesResarcitorios_interes,
			     InteresesResarcitorios.`clienteId` AS InteresesResarcitorios_clienteId,
			     InteresesResarcitorios.`chequeNum` AS InteresesResarcitorios_chequeNum,
			     InteresesResarcitorios.`banco` AS InteresesResarcitorios_banco,
			     InteresesResarcitorios.`diferidoValor` AS InteresesResarcitorios_diferidoValor,
			     cliente.`idCliente` AS cliente_idCliente,
			     cliente.`rsocial` AS cliente_rsocial,
			     cliente.`denominacion` AS cliente_denominacion,
			     cliente.`direccion` AS cliente_direccion,
			     InteresesResarcitorios.`cobranzaId` AS InteresesResarcitorios_cobranzaId,
			     Cobranzas.`id` AS Cobranzas_id,
			     Cobranzas.`emision` AS Cobranzas_emision,
			     Cobranzas.`clienteId` AS Cobranzas_clienteId,
			     Cobranzas.`ptoVenta` AS Cobranzas_ptoVenta,
			     Cobranzas.`numRecibo` AS Cobranzas_numRecibo,
			     Cobranzas.`fechaRecibo` AS Cobranzas_fechaRecibo
			FROM
			     `cliente` cliente INNER JOIN `InteresesResarcitorios` InteresesResarcitorios ON cliente.`idCliente` = InteresesResarcitorios.`clienteId`
			     INNER JOIN `Cobranzas` Cobranzas ON InteresesResarcitorios.`cobranzaId` = Cobranzas.`id`
			WHERE cliente.`idCliente` BETWEEN $cliente1 AND $cliente2
				AND Cobranzas.`fechaRecibo` BETWEEN '$desde' AND '$hasta'";
			
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
     * @Route("/Reportes/VerReporteIntResarcitorios", name="mbp_Reportes_VerReporteIntResarcitorios", options={"expose"=true})
     */	
    public function VerReporteIntResarcitorios()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/pdf/').'InteresesResarcitorios.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'InteresesResarcitorios.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-type', 'application/pdf');
		
		return $response;
	}
	
	/**
     * @Route("/Reportes/Bancos/MovimientosBancos", name="mbp_Reportes_MovimientosBancos", options={"expose"=true})
     */	
    public function MovimientosBancos()
	{
		$response = new Response;
				
		try{
			/*
			 * PARAMETROS
			 */
			$desde = $this->get('request')->get('fecha1');
			$hasta = $this->get('request')->get('fecha2');
			$cuenta1 = $this->get('request')->get('cuenta1');
			$cuenta2 = $this->get('request')->get('cuenta2');
			$concepto1 = $this->get('request')->get('concepto1');
			$concepto2 = $this->get('request')->get('concepto2');
							
			$reporteador = $this->get('reporteador');
			$kernel = $this->get('kernel');
			
			/*
			 * Configuro reporte
			 */
			$jru = $reporteador->jru();
			
			/*
			 * Ruta archivo Jasper
			 */				
					
			$ruta = $kernel->locateResource('@MbpFinanzasBundle/Reportes/MovimientosBancarios.jrxml');
			$rutaLogo = $reporteador->getRutaLogo($kernel);
			
			/*
			 * Ruta de destino del PDF
			 */
			$destino = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/pdf/').'MovimientosBancarios.pdf';		
			
			//Parametros HashMap
			$param = $reporteador->getJava('java.util.HashMap');
			$rutaLogo = $reporteador->getRutaLogo($kernel);
						
			$param->put('CONCEPTO_DESDE', $concepto1);
			$param->put('CONCEPTO_HASTA', $concepto2);
			$param->put('CUENTA_DESDE', $cuenta1);
			$param->put('CUENTA_HASTA', $cuenta2);
			
			$conn = $reporteador->getJdbc();
			
			$desde = \DateTime::createFromFormat('d/m/Y', $desde);
			$hasta = \DateTime::createFromFormat('d/m/Y', $hasta);
			$param->put('DESDE', $desde->format('d/m/Y'));
			$param->put('HASTA', $hasta->format('d/m/Y'));	
			//$param->put('rutaLogo', $rutaLogo);
			
			
			$desde = $desde->format('Y-m-d');
			$hasta = $hasta->format('Y-m-d');
									
			
			$sql = "SELECT
			     MovimientosBancos.`id` AS MovimientosBancos_id,
			     MovimientosBancos.`fechaMovimiento` AS MovimientosBancos_fechaMovimiento,
			     MovimientosBancos.`ceonceptoBancoId` AS MovimientosBancos_ceonceptoBancoId,
			     MovimientoBanco_Detalle.`detallemovimientosbancos_id` AS MovimientoBanco_Detalle_detallemovimientosbancos_id,
			     MovimientoBanco_Detalle.`Movimiento_id` AS MovimientoBanco_Detalle_Movimiento_id,
			     DetalleMovimientosBancos.`id` AS DetalleMovimientosBancos_id,
			     DetalleMovimientosBancos.`numComprobante` AS DetalleMovimientosBancos_numComprobante,
			     DetalleMovimientosBancos.`fechaDiferida` AS DetalleMovimientosBancos_fechaDiferida,
			     DetalleMovimientosBancos.`importe` AS DetalleMovimientosBancos_importe,
			     DetalleMovimientosBancos.`observaciones` AS DetalleMovimientosBancos_observaciones,
			     DetalleMovimientosBancos.`ChequeTerceros_id` AS DetalleMovimientosBancos_ChequeTerceros_id,
			     DetalleMovimientosBancos.`Proveedor_id` AS DetalleMovimientosBancos_Proveedor_id,
			     ConceptosBanco.`id` AS ConceptosBanco_id,
			     ConceptosBanco.`concepto` AS ConceptosBanco_concepto,
			     ConceptosBanco.`imputaDebe` AS ConceptosBanco_imputaDebe,
			     ConceptosBanco.`inactivo` AS ConceptosBanco_inactivo,
			     DetalleMovimientosBancos.`idCliente` AS DetalleMovimientosBancos_idCliente,
			     cliente.`idCliente` AS cliente_idCliente,
			     cliente.`rsocial` AS cliente_rsocial,
			     Proveedor.`rsocial` AS Proveedor_rsocial,
			     Proveedor.`id` AS Proveedor_id,
			     MovimientosBancos.`cuentaId` AS MovimientosBancos_cuentaId,
			     CuentasBancarias.`id` AS CuentasBancarias_id,
			     CuentasBancarias.`tipo` AS CuentasBancarias_tipo,
			     CuentasBancarias.`numero` AS CuentasBancarias_numero,
			     CuentasBancarias.`cbu` AS CuentasBancarias_cbu,
			     CuentasBancarias.`bancoId` AS CuentasBancarias_bancoId,
			     Bancos.`id` AS Bancos_id,
			     Bancos.`nombre` AS Bancos_nombre
			FROM
			     `MovimientosBancos` MovimientosBancos INNER JOIN `MovimientoBanco_Detalle` MovimientoBanco_Detalle ON MovimientosBancos.`id` = MovimientoBanco_Detalle.`Movimiento_id`
			     INNER JOIN `DetalleMovimientosBancos` DetalleMovimientosBancos ON MovimientoBanco_Detalle.`detallemovimientosbancos_id` = DetalleMovimientosBancos.`id`
			     LEFT JOIN `Proveedor` Proveedor ON DetalleMovimientosBancos.`Proveedor_id` = Proveedor.`id`
			     LEFT JOIN `cliente` cliente ON DetalleMovimientosBancos.`idCliente` = cliente.`idCliente`
			     LEFT JOIN `ConceptosBanco` ConceptosBanco ON MovimientosBancos.`ceonceptoBancoId` = ConceptosBanco.`id`
			     LEFT JOIN `CuentasBancarias` CuentasBancarias ON MovimientosBancos.`cuentaId` = CuentasBancarias.`id`
			     LEFT JOIN `Bancos` Bancos ON CuentasBancarias.`bancoId` = Bancos.`id`
			WHERE CAST(MovimientosBancos.`fechaMovimiento` AS DATE) BETWEEN '$desde' AND '$hasta'
				AND ConceptosBanco.`id` BETWEEN $concepto1 AND $concepto2
				AND CuentasBancarias.`id` BETWEEN $cuenta1 AND $cuenta2
			     ";
			
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
     * @Route("/Reportes/Bancos/VerReporteMovBancos", name="mbp_Reportes_VerReporteMovBancos", options={"expose"=true})
     */	
    public function VerReporteMovBancos()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/pdf/').'MovimientosBancarios.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'MovimientosBancarios.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-type', 'application/pdf');
		
		return $response;
	}
	
	/**
     * @Route("/Reportes/Reportes/SaldoDeudor", name="mbp_Reportes_SaldoDeudor", options={"expose"=true})
     */	
    public function SaldoDeudor()
	{
		$response = new Response;
				
		try{
			/*
			 * PARAMETROS
			 */
			$vencimiento = $this->get('request')->get('vencimiento');
							
			$reporteador = $this->get('reporteador');
			$kernel = $this->get('kernel');
			
			/*
			 * Configuro reporte
			 */
			$jru = $reporteador->jru();
			
			/*
			 * Ruta archivo Jasper
			 */				
					
			$ruta = $kernel->locateResource('@MbpFinanzasBundle/Reportes/resumenSaldoDeudor.jrxml');
			$rutaLogo = $reporteador->getRutaLogo($kernel);
			
			/*
			 * Ruta de destino del PDF
			 */
			$destino = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/pdf/').'resumenSaldoDeudor.pdf';		
			
			//Parametros HashMap
			$param = $reporteador->getJava('java.util.HashMap');
			$rutaLogo = $reporteador->getRutaLogo($kernel);
						
			$param->put('VENCIMIENTO', $vencimiento);
			
			$conn = $reporteador->getJdbc();
			
			$vencimiento = \DateTime::createFromFormat('d/m/Y', $vencimiento);
			$vencimiento = $vencimiento->format('Y-m-d');
									
			
			$sql = "SELECT *, SUM(Facturas_total) AS SALDO
				FROM (SELECT
				     Facturas.`id` AS Facturas_id,
				     Facturas.`vencimiento` AS Facturas_vencimiento,
				     Facturas.`clienteId` AS Facturas_clienteId,
				     Facturas.`tipo` AS Facturas_tipo,
				     Facturas.`tipoCambio` AS Facturas_tipoCambio,
				     CASE WHEN Facturas.`tipo` != 1 && Facturas.`tipo` != 2 THEN
					Facturas.`total` * -1 ELSE
					Facturas.`total` END AS Facturas_total,
				     cliente.`idCliente` AS cliente_idCliente,
				     cliente.`rsocial` AS cliente_rsocial
				FROM
				     `cliente` cliente INNER JOIN `Facturas` Facturas ON cliente.`idCliente` = Facturas.`clienteId`
				WHERE Facturas.`vencimiento` <= '$vencimiento'
				UNION
				SELECT
				     Cobranzas.`id` AS Cobranzas_id,
				     Cobranzas.`fechaRecibo` AS Cobranzas_fechaRecibo,
				     Cobranzas.`clienteId` AS Cobranzas_clienteId,
				     Cobranzas.`clienteId`=NULL AS DUMMY_FC_TIPO,
				     Cobranzas.`totalCobranza`=NULL AS DUMMY_TIPO_CAMBIO,
				     Cobranzas.`totalCobranza` * -1 AS Cobranzas_totalCobranza,
				     cliente.`idCliente` AS cliente_idCliente,
				     cliente.`rsocial` AS cliente_rsocial
				FROM
				     `cliente` cliente INNER JOIN `Cobranzas` Cobranzas ON cliente.`idCliente` = Cobranzas.`clienteId`
				WHERE Cobranzas.`fechaRecibo` <= '$vencimiento'
				) AS SUB
				GROUP BY cliente_idCliente
				ORDER BY SALDO DESC
			     ";
			
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
     * @Route("/Reportes/Bancos/VerReporteSaldoDeudor", name="mbp_Reportes_VerReporteSaldoDeudor", options={"expose"=true})
     */	
    public function VerReporteSaldoDeudor()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/pdf/').'resumenSaldoDeudor.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'resumenSaldoDeudor.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-type', 'application/pdf');
		
		return $response;
	}
}





















