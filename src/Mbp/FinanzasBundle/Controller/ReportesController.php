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
     * @Route("/Reportes/generarComisiones", name="mbp_Reportes_generarComisiones", options={"expose"=true})
     */	    
    public function generarComisiones()
	{
		//RECIBO PARAMETROS
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		
		try{
			$desde = $req->request->get('desde');		
			$hasta = $req->request->get('hasta');


			$reporteador = $this->get('reporteador');
			$kernel = $this->get('kernel');
			
			$jru = $reporteador->jru();
			
			/*
			 * Ruta archivo Jasper
			 */				
					
			$ruta = $kernel->locateResource('@MbpFinanzasBundle/Reportes/ComisionesVendedores.jrxml');
			
			$destino = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/pdf/').'ComisionesVendedores.pdf';		
			
			//Parametros HashMap
			$param = $reporteador->getJava('java.util.HashMap');
			$rutaLogo = $reporteador->getRutaLogo($kernel);
			
			
			
			$conn = $reporteador->getJdbc();
			$desdeObj=\DateTime::createFromFormat('d/m/Y', $desde);
			$hastaObj=\DateTime::createFromFormat('d/m/Y', $hasta);
			//print_r($desdeObj);
			//exit;

			$desdeSql=$desdeObj->format('Y-m-d');
			$hastaSql=$hastaObj->format('Y-m-d');

			$param->put('FECHA_DESDE', $desdeSql);
			$param->put('FECHA_HASTA', $hastaSql);

			$sql = "SELECT
			     Facturas.`id` AS Facturas_id,
			     Facturas.`fecha` AS Facturas_fecha,
			     Facturas.`clienteId` AS Facturas_clienteId,
			     Facturas.`ptoVta` AS Facturas_ptoVta,
			     Facturas.`fcNro` AS Facturas_fcNro,
			     Facturas.`rSocial` AS Facturas_rSocial,
			     Facturas.`tipoId` AS Facturas_tipoId,
			     Facturas.`tipoCambio` AS Facturas_tipoCambio,
			     Facturas.`moneda` AS Facturas_moneda,
			     TipoComprobante.`id` AS TipoComprobante_id,
			     TipoComprobante.`esFactura` AS TipoComprobante_esFactura,
			     TipoComprobante.`esNotaCredito` AS TipoComprobante_esNotaCredito,
			     TipoComprobante.`esNotaDebito` AS TipoComprobante_esNotaDebito,
			     TipoComprobante.`esBalance` AS TipoComprobante_esBalance,
			     TipoComprobante.`descripcion` AS TipoComprobante_descripcion,
			     TipoComprobante.`subTipoA` AS TipoComprobante_subTipoA,
			     TipoComprobante.`subTipoB` AS TipoComprobante_subTipoB,
			     TipoComprobante.`subTipoE` AS TipoComprobante_subTipoE,
			     TipoComprobante.`codigoAfip` AS TipoComprobante_codigoAfip,
			     TipoComprobante.`esNegro` AS TipoComprobante_esNegro,
			     cliente.`idCliente` AS cliente_idCliente,
			     cliente.`rsocial` AS cliente_rsocial,
			     Vendedor.`id` AS Vendedor_id,
			     Vendedor.`apellido` AS Vendedor_apellido,
			     Vendedor.`nombre` AS Vendedor_nombre,
			     Vendedor.`telefono` AS Vendedor_telefono,
			     Vendedor.`inactivo` AS Vendedor_inactivo,
			     cliente.`vendedorId` AS cliente_vendedorId,
			     cliente.`comision` AS cliente_comision,
			     Facturas.`total` AS Facturas_total,
			     Facturas.`iva21` AS Facturas_iva21,
			     Facturas.`perIIBB` AS Facturas_perIIBB
			FROM
			     `TipoComprobante` TipoComprobante INNER JOIN `Facturas` Facturas ON TipoComprobante.`id` = Facturas.`tipoId`
			     INNER JOIN `cliente` cliente ON Facturas.`clienteId` = cliente.`idCliente`
			     INNER JOIN `Vendedor` Vendedor ON cliente.`vendedorId` = Vendedor.`id`
			WHERE TipoComprobante.`esBalance` = FALSE
				AND Facturas.`fecha` BETWEEN '$desdeSql' AND '$hastaSql'
			ORDER BY Vendedor.`id`, Facturas.`fecha` ASC";
			
			$res = $jru->runPdfFromSql($ruta, $destino, $param, $sql, $conn->getConnection());
			
			//ARMO RESPUESTA
			$response = new Response();
			return $response->setContent(json_encode(array('success'=>true)));		
		}catch(\Exception $e){
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			return $response->setContent(json_encode(array('success'=>false, 'msg'=>$e->getMessage())));
		}
	}

	/**
     * @Route("/Reportes/servirReporteComisiones", name="mbp_Reportes_servirReporteComisiones", options={"expose"=true})
     */	    
    public function servirReporteComisiones()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/pdf/').'ComisionesVendedores.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'ComisionesVendedores.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-type', 'application/pdf');
		$response->deleteFileAfterSend(TRUE);

        return $response;
	}


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
		
		$param->put('rutaLogo', $rutaLogo);
		$param->put('facturaId', $idFactura);
		
		$conn = $reporteador->getJdbc();
		
		//print_r($idFactura);
		//exit;
		
		$sql = "SELECT
		     Facturas.`id` AS Facturas_id,
		     Facturas.`fecha` AS Facturas_fecha,
		     Facturas.`concepto` AS Facturas_concepto,
		     Facturas.`vencimiento` AS Facturas_vencimiento,
		     Facturas.`clienteId` AS Facturas_clienteId,
		     Facturas.`ptoVta` AS Facturas_ptoVta,
		     Facturas.`cae` AS Facturas_cae,
		     Facturas.`vtoCae` AS Facturas_vtoCae,
		     DATE_FORMAT(Facturas.`vtoCae`, '%Y%m%d') AS vtoCaeFormateado,
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
		     Facturas.`moneda` AS Facturas_moneda,
		     Facturas.`tipoCambio` AS Facturas_tipoCambio,
		     Facturas.`tipoId` AS Facturas_tipoId,
		     Facturas.`ccId` AS Facturas_ccId,
		     Facturas.`tipoCambioRefFac` AS Facturas_tipoCambioRefFac,
		     Facturas.`digitoVerificador` AS Facturas_digitoVerificador,
		     FacturaDetalle.`id` AS FacturaDetalle_id,
		     FacturaDetalle.`descripcion` AS FacturaDetalle_descripcion,
		     FacturaDetalle.`cantidad` AS FacturaDetalle_cantidad,
		     FacturaDetalle.`precio` AS FacturaDetalle_precio,
		     FacturaDetalle.`articuloId` AS FacturaDetalle_articuloId,
		     FacturaDetalle.`remitoId` AS FacturaDetalle_remitoId,
		     factura_detallesFacturas.`factura_id` AS factura_detallesFacturas_factura_id,
		     factura_detallesFacturas.`facturadetalle_id` AS factura_detallesFacturas_facturadetalle_id,
		     RemitosClientes.`id` AS RemitosClientes_id,
		     RemitosClientes.`fecha` AS RemitosClientes_fecha,
		     RemitosClientes.`remitoNum` AS RemitosClientes_remitoNum,
		     RemitosClientes.`clienteId` AS RemitosClientes_clienteId,
		     RemitosClientes.`proveedorId` AS RemitosClientes_proveedorId,
		     RemitoClientes_detalle.`remitosclientes_id` AS RemitoClientes_detalle_remitosclientes_id,
		     RemitoClientes_detalle.`remitosclientesdetalles_id` AS RemitoClientes_detalle_remitosclientesdetalles_id,
		     RemitosClientesDetalles.`id` AS RemitosClientesDetalles_id,
		     RemitosClientesDetalles.`oc` AS RemitosClientesDetalles_oc,
		     RemitosClientesDetalles.`articuloId` AS RemitosClientesDetalles_articuloId,
		     RemitosClientesDetalles.`pedidoDetalleId` AS RemitosClientesDetalles_pedidoDetalleId,
		     pedidoId_detalleId.`pedidoId` AS pedidoId_detalleId_pedidoId,
		     pedidoId_detalleId.`detalleId` AS pedidoId_detalleId_detalleId,
		     PedidoClientes.`id` AS PedidoClientes_id,
		     PedidoClientes.`fechaPedido` AS PedidoClientes_fechaPedido,
		     PedidoClientes.`oc` AS PedidoClientes_oc,
		     PedidoClientes.`cliente` AS PedidoClientes_cliente,
		     PedidoClientes.`inactivo` AS PedidoClientes_inactivo,
		     PedidoClientes.`usuarioId` AS PedidoClientes_usuarioId,
		     PedidoClientes.`autEntrega` AS PedidoClientes_autEntrega,
		     PedidoClientesDetalle.`id` AS PedidoClientesDetalle_id,
		     PedidoClientesDetalle.`codigo` AS PedidoClientesDetalle_codigo,
		     PedidoClientesDetalle.`cantidad` AS PedidoClientesDetalle_cantidad,
		     PedidoClientesDetalle.`fechaProg` AS PedidoClientesDetalle_fechaProg,
		     PedidoClientesDetalle.`entregado` AS PedidoClientesDetalle_entregado,
		     PedidoClientesDetalle.`inactivo` AS PedidoClientesDetalle_inactivo,
		     PedidoClientesDetalle.`descripcion` AS PedidoClientesDetalle_descripcion,
		     TipoComprobante.`id` AS TipoComprobante_id,
		     TipoComprobante.`esFactura` AS TipoComprobante_esFactura,
		     TipoComprobante.`esNotaCredito` AS TipoComprobante_esNotaCredito,
		     TipoComprobante.`esNotaDebito` AS TipoComprobante_esNotaDebito,
		     TipoComprobante.`esBalance` AS TipoComprobante_esBalance,
		     TipoComprobante.`descripcion` AS TipoComprobante_descripcion,
		     TipoComprobante.`subTipoA` AS TipoComprobante_subTipoA,
		     TipoComprobante.`subTipoB` AS TipoComprobante_subTipoB,
		     TipoComprobante.`subTipoE` AS TipoComprobante_subTipoE,
		     TipoComprobante.`codigoAfip` AS TipoComprobante_codigoAfip,
		     articulos.`codigo` AS articulos_codigo,
		     articulos.`descripcion` AS articulos_descripcion,
		     articulos.`idArticulos` AS articulos_idArticulos,
		     articulos.`precio` AS articulos_precio,
		     articulos.`moneda` AS articulos_moneda,
		     articulos.`inactivo` AS articulos_inactivo
		FROM
		     `FacturaDetalle` FacturaDetalle INNER JOIN `factura_detallesFacturas` factura_detallesFacturas ON FacturaDetalle.`id` = factura_detallesFacturas.`facturadetalle_id`
		     RIGHT OUTER JOIN `Facturas` Facturas ON factura_detallesFacturas.`factura_id` = Facturas.`id`
		     LEFT OUTER JOIN `TipoComprobante` TipoComprobante ON Facturas.`tipoId` = TipoComprobante.`id`
		     LEFT OUTER JOIN `RemitosClientesDetalles` RemitosClientesDetalles ON FacturaDetalle.`remitoId` = RemitosClientesDetalles.`id`
		     INNER JOIN `articulos` articulos ON FacturaDetalle.`articuloId` = articulos.`idArticulos`
		     LEFT JOIN `PedidoClientesDetalle` PedidoClientesDetalle ON RemitosClientesDetalles.`pedidoDetalleId` = PedidoClientesDetalle.`id`
		     LEFT OUTER JOIN `RemitoClientes_detalle` RemitoClientes_detalle ON RemitosClientesDetalles.`id` = RemitoClientes_detalle.`remitosclientesdetalles_id`
		     LEFT OUTER JOIN `RemitosClientes` RemitosClientes ON RemitoClientes_detalle.`remitosclientes_id` = RemitosClientes.`id`
		     LEFT JOIN `pedidoId_detalleId` pedidoId_detalleId ON PedidoClientesDetalle.`id` = pedidoId_detalleId.`detalleId`
		     LEFT JOIN `PedidoClientes` PedidoClientes ON pedidoId_detalleId.`pedidoId` = PedidoClientes.`id`
			WHERE Facturas.`id` = $idFactura";
		
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
			
			//print_r($kernel->locateResource('@MbpFinanzasBundle/Reportes/'));

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
			     CASE WHEN Facturas.`moneda`=1 THEN Facturas.`perIIBB`*Facturas.`tipoCambio` ELSE Facturas.`perIIBB` END AS Facturas_perIIBB,
			     CASE WHEN Facturas.`moneda`=1 THEN Facturas.`iva21`*Facturas.`tipoCambio` ELSE Facturas.`iva21` END AS Facturas_iva21,
			     CASE WHEN Facturas.`moneda`=1 THEN Facturas.`total`*Facturas.`tipoCambio` ELSE Facturas.`total` END AS Facturas_total,
			     cliente.`idCliente` AS cliente_idCliente,
			     cliente.`rsocial` AS cliente_rsocial,
			     Facturas.`id` AS Facturas_id,
			     Facturas.`fecha` AS Facturas_fecha,
			     Facturas.`concepto` AS Facturas_concepto,
			     Facturas.`clienteId` AS Facturas_clienteId,
			     Facturas.`ptoVta` AS Facturas_ptoVta,
			     Facturas.`fcNro` AS Facturas_fcNro,
			     Facturas.`rSocial` AS Facturas_rSocial,
			     Facturas.`cuit` AS Facturas_cuit,
			     Facturas.`ivaCond` AS Facturas_ivaCond,
			     Facturas.`porcentajeIIBB` AS Facturas_porcentajeIIBB,
			     Facturas.`moneda` AS Facturas_moneda,
			     Facturas.`tipoCambio` AS Facturas_tipoCambio,
			     Facturas.`tipoId` AS Facturas_tipoId,
				 Facturas.`dtoTotal` AS Facturas_dtoTotal,
			     TipoComprobante.`id` AS TipoComprobante_id,
			     TipoComprobante.`esFactura` AS TipoComprobante_esFactura,
			     TipoComprobante.`esNotaCredito` AS TipoComprobante_esNotaCredito,
			     TipoComprobante.`esNotaDebito` AS TipoComprobante_esNotaDebito,
			     TipoComprobante.`esBalance` AS TipoComprobante_esBalance,
			     TipoComprobante.`descripcion` AS TipoComprobante_descripcion,
			     TipoComprobante.`subTipoA` AS TipoComprobante_subTipoA,
			     TipoComprobante.`subTipoB` AS TipoComprobante_subTipoB,
			     TipoComprobante.`subTipoE` AS TipoComprobante_subTipoE,
			     Facturas.`tipoIva` AS Facturas_tipoIva,
			     PosicionIVA.`id` AS PosicionIVA_id,
			     PosicionIVA.`posicion` AS PosicionIVA_posicion,
			     PosicionIVA.`esResponsableInscripto` AS PosicionIVA_esResponsableInscripto,
			     PosicionIVA.`esResponsableNoInscripto` AS PosicionIVA_esResponsableNoInscripto,
			     PosicionIVA.`esExento` AS PosicionIVA_esExento,
			     PosicionIVA.`esResponsableMonotributo` AS PosicionIVA_esResponsableMonotributo,
			     PosicionIVA.`esConsumidorFinal` AS PosicionIVA_esConsumidorFinal,
			     PosicionIVA.`esExportacion` AS PosicionIVA_esExportacion,
			     FacturaDetalle.`id` AS FacturaDetalle_id,
			     FacturaDetalle.`descripcion` AS FacturaDetalle_descripcion,
			     FacturaDetalle.`cantidad` AS FacturaDetalle_cantidad,
			     FacturaDetalle.`precio` AS FacturaDetalle_precio,
			     SUM(CASE WHEN FacturaDetalle.`ivaGrabado`= 1 THEN
				 FacturaDetalle.`cantidad` * FacturaDetalle.`precio`
				 ELSE 0 END) AS netoGrabado,
			     SUM(CASE WHEN FacturaDetalle.`ivaGrabado`= 0 THEN
				 FacturaDetalle.`cantidad` * FacturaDetalle.`precio`
				 ELSE 0 END) AS netoNoGrabado,
			     FacturaDetalle.`articuloId` AS FacturaDetalle_articuloId,
			     FacturaDetalle.`remitoId` AS FacturaDetalle_remitoId,
			     FacturaDetalle.`ivaGrabado` AS FacturaDetalle_ivaGrabado,
			     factura_detallesFacturas.`factura_id` AS factura_detallesFacturas_factura_id,
			     factura_detallesFacturas.`facturadetalle_id` AS factura_detallesFacturas_facturadetalle_id
			FROM
			     `cliente` cliente INNER JOIN `Facturas` Facturas ON cliente.`idCliente` = Facturas.`clienteId`
			     INNER JOIN `TipoComprobante` TipoComprobante ON Facturas.`tipoId` = TipoComprobante.`id`
			     INNER JOIN `PosicionIVA` PosicionIVA ON Facturas.`tipoIva` = PosicionIVA.`id`
			     INNER JOIN `factura_detallesFacturas` factura_detallesFacturas ON Facturas.`id` = factura_detallesFacturas.`factura_id`
			     RIGHT JOIN `FacturaDetalle` FacturaDetalle ON factura_detallesFacturas.`facturadetalle_id` = FacturaDetalle.`id`
			WHERE
			     Facturas.`fecha` BETWEEN '$desde' AND '$hasta'
			 AND TipoComprobante.`esBalance` = 0
			GROUP BY Facturas.`id`
			ORDER BY
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
     CASE WHEN Facturas.`moneda`=1 THEN FacturaDetalle.`precio`*Facturas.`tipoCambio` ELSE FacturaDetalle.`precio` END AS FacturaDetalle_precio,
     Facturas.`id` AS Facturas_id,
     Facturas.`fecha` AS Facturas_fecha,
     Facturas.`concepto` AS Facturas_concepto,
     Facturas.`vencimiento` AS Facturas_vencimiento,
     Facturas.`clienteId` AS Facturas_clienteId,
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
     Facturas.`moneda` AS Facturas_moneda,
     Facturas.`tipoCambio` AS Facturas_tipoCambio,
     cliente.`idCliente` AS cliente_idCliente,
     cliente.`rsocial` AS cliente_rsocial,
     cliente.`denominacion` AS cliente_denominacion,
     factura_detallesFacturas.`factura_id` AS factura_detallesFacturas_factura_id,
     factura_detallesFacturas.`facturadetalle_id` AS factura_detallesFacturas_facturadetalle_id,
     FacturaDetalle.`id` AS FacturaDetalle_id,
     FacturaDetalle.`descripcion` AS FacturaDetalle_descripcion,
     FacturaDetalle.`cantidad` AS FacturaDetalle_cantidad,
     FacturaDetalle.`articuloId` AS FacturaDetalle_articuloId,
     FacturaDetalle.`remitoId` AS FacturaDetalle_remitoId,
     articulos.`idArticulos` AS articulos_idArticulos,
     articulos.`codigo` AS articulos_codigo,
     TipoComprobante.`id` AS TipoComprobante_id,
     TipoComprobante.`esFactura` AS TipoComprobante_esFactura,
     TipoComprobante.`esNotaCredito` AS TipoComprobante_esNotaCredito,
     TipoComprobante.`esNotaDebito` AS TipoComprobante_esNotaDebito,
     TipoComprobante.`esBalance` AS TipoComprobante_esBalance,
     TipoComprobante.`descripcion` AS TipoComprobante_descripcion,
     TipoComprobante.`subTipoA` AS TipoComprobante_subTipoA,
     TipoComprobante.`subTipoB` AS TipoComprobante_subTipoB,
     TipoComprobante.`subTipoE` AS TipoComprobante_subTipoE,
     TipoComprobante.`codigoAfip` AS TipoComprobante_codigoAfip,
     TipoComprobante.`esNegro` AS TipoComprobante_esNegro,
     Facturas.`tipoId` AS Facturas_tipoId
FROM
     `cliente` cliente INNER JOIN `Facturas` Facturas ON cliente.`idCliente` = Facturas.`clienteId`
     INNER JOIN `factura_detallesFacturas` factura_detallesFacturas ON Facturas.`id` = factura_detallesFacturas.`factura_id`
     INNER JOIN `TipoComprobante` TipoComprobante ON Facturas.`tipoId` = TipoComprobante.`id`
     INNER JOIN `FacturaDetalle` FacturaDetalle ON factura_detallesFacturas.`facturadetalle_id` = FacturaDetalle.`id`
     INNER JOIN `articulos` articulos ON FacturaDetalle.`articuloId` = articulos.`idArticulos`
WHERE
     Facturas.`fecha` BETWEEN '$desde' AND '$hasta'
 AND articulos.`codigo` BETWEEN '$cod1' AND '$cod2'
 AND cliente.`idCliente` BETWEEN $cliente1 AND $cliente2
 AND TipoComprobante.`esNegro`=false
 AND TipoComprobante.`esBalance`=false
ORDER BY
     cliente.`idCliente` ASC,
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
					IFNULL(cliente.`rsocial`, '') AS cliente_rsocial,
					IFNULL(Proveedor.`rsocial`,'') AS Proveedor_rsocial,
					Proveedor.`id` AS Proveedor_id,
					MovimientosBancos.`cuentaId` AS MovimientosBancos_cuentaId,
					CuentasBancarias.`id` AS CuentasBancarias_id,
					CuentasBancarias.`tipo` AS CuentasBancarias_tipo,
					CuentasBancarias.`numero` AS CuentasBancarias_numero,
					CuentasBancarias.`cbu` AS CuentasBancarias_cbu,
					CuentasBancarias.`bancoId` AS CuentasBancarias_bancoId,
					Bancos.`id` AS Bancos_id,
					Bancos.`nombre` AS Bancos_nombre,
					IFNULL(clienteCob.rsocial,'') as clienteCobranza
			FROM
					`MovimientosBancos` MovimientosBancos INNER JOIN `MovimientoBanco_Detalle` MovimientoBanco_Detalle ON MovimientosBancos.`id` = MovimientoBanco_Detalle.`Movimiento_id`
					INNER JOIN `DetalleMovimientosBancos` DetalleMovimientosBancos ON MovimientoBanco_Detalle.`detallemovimientosbancos_id` = DetalleMovimientosBancos.`id`
					LEFT JOIN `Proveedor` Proveedor ON DetalleMovimientosBancos.`Proveedor_id` = Proveedor.`id`
					LEFT JOIN `cliente` cliente ON DetalleMovimientosBancos.`idCliente` = cliente.`idCliente`
					LEFT JOIN `ConceptosBanco` ConceptosBanco ON MovimientosBancos.`ceonceptoBancoId` = ConceptosBanco.`id`
					LEFT JOIN `CuentasBancarias` CuentasBancarias ON MovimientosBancos.`cuentaId` = CuentasBancarias.`id`
					LEFT JOIN `Bancos` Bancos ON CuentasBancarias.`bancoId` = Bancos.`id`
					LEFT JOIN `cobranza_detallesCobranzas` cob_det ON cob_det.`cobranzasdetalle_id` = DetalleMovimientosBancos.`ChequeTerceros_id`
					LEFT JOIN `Cobranzas` cob ON cob.`id` = cob_det.`cobranza_id`
					LEFT JOIN `cliente` clienteCob ON clienteCob.`idCliente` = cob.`clienteId`
			WHERE CAST(DetalleMovimientosBancos.`fechaDiferida` AS DATE) BETWEEN '$desde' AND '$hasta'
				AND ConceptosBanco.`id` BETWEEN $concepto1 AND $concepto2
				AND CuentasBancarias.`id` BETWEEN $cuenta1 AND $cuenta2
				ORDER BY DetalleMovimientosBancos.`fechaDiferida` ASC
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
									
			
			$sql = "SELECT
			     SUM(CCClientes.`debe`)-SUM(CCClientes.`haber`) AS saldo,
			     CCClientes.`id` AS CCClientes_id,
			     CCClientes.`fechaEmision` AS CCClientes_fechaEmision,
			     CCClientes.`fechaVencimiento` AS CCClientes_fechaVencimiento,
			     CCClientes.`facturaId` AS CCClientes_facturaId,
			     CCClientes.`cobranzaId` AS CCClientes_cobranzaId,
			     CCClientes.`clienteId` AS CCClientes_clienteId,
			     cliente.`idCliente` AS cliente_idCliente,
			     cliente.`rsocial` AS cliente_rsocial
			FROM
			     `cliente` cliente INNER JOIN `CCClientes` CCClientes ON cliente.`idCliente` = CCClientes.`clienteId`
			WHERE
				CCClientes.`fechaVencimiento` <= '$vencimiento'				
			GROUP BY
			     cliente.`idCliente`
			HAVING
				saldo > 0
			ORDER BY
			     saldo DESC
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
	
	/**
     * @Route("/Reportes/Reportes/CbtesNoPagados", name="mbp_Reportes_CbtesNoPagados", options={"expose"=true})
     */	
    public function CbtesNoPagados()
	{
		$response = new Response;
				
		try{
			/*
			 * PARAMETROS
			 */
			$request = $this->get('request');
			$desde = $request->get('desde');
			$hasta = $request->get('hasta');
			$cliente1 = $request->get('cliente1');
			$cliente2 = $request->get('cliente2');
							
			$reporteador = $this->get('reporteador');
			$kernel = $this->get('kernel');
			
			/*
			 * Configuro reporte
			 */
			$jru = $reporteador->jru();
			
			/*
			 * Ruta archivo Jasper
			 */				
					
			$ruta = $kernel->locateResource('@MbpFinanzasBundle/Reportes/ComprobantesPendientesPago.jrxml');
			$rutaLogo = $reporteador->getRutaLogo($kernel);
			
			/*
			 * Ruta de destino del PDF
			 */
			$destino = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/pdf/').'ComprobantesPendientesPago.pdf';		
			
			//Parametros HashMap
			$param = $reporteador->getJava('java.util.HashMap');
			$rutaLogo = $reporteador->getRutaLogo($kernel);
						
			
			$param->put('CLIENTE_DESDE', $cliente1);
			$param->put('CLIENTE_HASTA', $cliente2);
			$param->put('FECHA_DESDE', $desde);
			$param->put('FECHA_HASTA', $hasta);
			$conn = $reporteador->getJdbc();
			
			$desde = \DateTime::createFromFormat('d/m/Y', $desde);
			$hasta = \DateTime::createFromFormat('d/m/Y', $hasta);
			
			
			$desde = $desde->format('Y-m-d');
			$hasta = $hasta->format('Y-m-d');
									
			
			$sql = "SELECT * FROM(
			SELECT
			     CASE WHEN TransaccionCobranzaFactura.`aplicado` IS NULL THEN 0 ELSE SUM(TransaccionCobranzaFactura.`aplicado`) END AS TransaccionCobranzaFactura_aplicado,
			     Cobranzas.`id` AS Cobranzas_id,
			     Cobranzas.`emision` AS Cobranzas_emision,
			     TransaccionCobranzaFactura.`id` AS TransaccionCobranzaFactura_id,
			     TransaccionCobranzaFactura.`facturaId` AS TransaccionCobranzaFactura_facturaId,
			     TransaccionCobranzaFactura.`cobranzaId` AS TransaccionCobranzaFactura_cobranzaId,
			     Facturas.`id` AS Facturas_id,
			     Facturas.`fecha` AS Facturas_fecha,
			     Facturas.`concepto` AS Facturas_concepto,
			     Facturas.`fcNro` AS fcNro,
			     Facturas.`vencimiento` AS Facturas_vencimiento,
			     Facturas.`clienteId` AS Facturas_clienteId,
			     Facturas.`ptoVta` AS Facturas_ptoVta,
			     Facturas.`total` AS Facturas_total,
			     Facturas.`moneda` AS Facturas_moneda,
			     Facturas.`tipoCambio` AS Facturas_tipoCambio,
			     CASE WHEN tipo.esFactura = true then 'FA N° '
				when tipo.esNotaDebito=true then 'NC N° '
				else 'ND N° ' end as concepto,
			     cliente.`rsocial` AS cliente_rsocial,
			     cliente.`idCliente` AS cliente_idCliente,
			     tipo.esFactura as esFactura,
			     tipo.esNotaDebito as esNotaDebito,
				 fn.fc_id as ncAnula
			FROM
			     `Cobranzas` Cobranzas INNER JOIN `TransaccionCobranzaFactura` TransaccionCobranzaFactura ON Cobranzas.`id` = TransaccionCobranzaFactura.`cobranzaId`
			     RIGHT JOIN `Facturas` Facturas ON TransaccionCobranzaFactura.`facturaId` = Facturas.`id`
			     INNER JOIN `cliente` cliente ON Facturas.`clienteId` = cliente.`idCliente`
			     INNER JOIN `TipoComprobante` tipo ON tipo.`id` = Facturas.`tipoId`
				 left join Facturas_NotasCredito fn on fn.nc_id = Facturas.id
			WHERE
				cliente.`idCliente` BETWEEN $cliente1 AND $cliente2 AND
				Facturas.`fecha` BETWEEN '$desde' AND '$hasta'
			GROUP BY Facturas.`id`, TransaccionCobranzaFactura.`facturaId`) AS sub
			WHERE
				Facturas_total > TransaccionCobranzaFactura_aplicado AND
				esFactura = true OR 
				Facturas_total > TransaccionCobranzaFactura_aplicado AND
				esNotaDebito = true OR
				ncAnula > 0
			ORDER BY cliente_idCliente, Facturas_fecha";
			
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
     * @Route("/Reportes/Bancos/VerCbptesNoPagados", name="mbp_Reportes_VerCbptesNoPagados", options={"expose"=true})
     */	
    public function VerCbptesNoPagados()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/pdf/').'ComprobantesPendientesPago.pdf';
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
	
	/**
     * @Route("/Reportes/InventarioCheques", name="mbp_Reportes_InventarioCheques", options={"expose"=true})
     */	
    public function InventarioCheques()
	{
		$response = new Response;
				
		try{
			/*
			 * PARAMETROS
			 */
			$request = $this->get('request');
			$desde = $request->get('desde');
			$hasta = $request->get('hasta');
							
			$reporteador = $this->get('reporteador');
			$kernel = $this->get('kernel');
			
			/*
			 * Configuro reporte
			 */
			$jru = $reporteador->jru();
			
			/*
			 * Ruta archivo Jasper
			 */				
					
			$ruta = $kernel->locateResource('@MbpFinanzasBundle/Reportes/InventarioCheques.jrxml');
			$rutaLogo = $reporteador->getRutaLogo($kernel);
			
			/*
			 * Ruta de destino del PDF
			 */
			$destino = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/pdf/').'InventarioCheques.pdf';		
			
			//Parametros HashMap
			$param = $reporteador->getJava('java.util.HashMap');
					
			
			$param->put('FECHA_DESDE', $desde);
			$param->put('FECHA_HASTA', $hasta);
			$conn = $reporteador->getJdbc();
			
			$desde = \DateTime::createFromFormat('d/m/Y', $desde);
			$hasta = \DateTime::createFromFormat('d/m/Y', $hasta);
			
			
			$desde = $desde->format('Y-m-d');
			$hasta = $hasta->format('Y-m-d');
									
			
			$sql = "SELECT
			     Cobranzas.`id` AS Cobranzas_id,
			     Cobranzas.`emision` AS Cobranzas_emision,
			     Cobranzas.`clienteId` AS Cobranzas_clienteId,
			     Cobranzas.`ptoVenta` AS Cobranzas_ptoVenta,
			     Cobranzas.`numRecibo` AS Cobranzas_numRecibo,
			     Cobranzas.`fechaRecibo` AS Cobranzas_fechaRecibo,
			     Cobranzas.`totalCobranza` AS Cobranzas_totalCobranza,
			     Cobranzas.`ccId` AS Cobranzas_ccId,
			     CobranzasDetalle.`id` AS CobranzasDetalle_id,
			     CobranzasDetalle.`importe` AS CobranzasDetalle_importe,
			     CobranzasDetalle.`numero` AS CobranzasDetalle_numero,
			     CobranzasDetalle.`banco` AS CobranzasDetalle_banco,
			     CobranzasDetalle.`vencimiento` AS CobranzasDetalle_vencimiento,
			     CobranzasDetalle.`estado` AS CobranzasDetalle_estado,
			     CobranzasDetalle.`formaPagoId` AS CobranzasDetalle_formaPagoId,
			     CobranzasDetalle.`cuentaId` AS CobranzasDetalle_cuentaId,
			     CobranzasDetalle.`movBancoId` AS CobranzasDetalle_movBancoId,
			     cobranza_detallesCobranzas.`cobranza_id` AS cobranza_detallesCobranzas_cobranza_id,
			     cobranza_detallesCobranzas.`cobranzasdetalle_id` AS cobranza_detallesCobranzas_cobranzasdetalle_id,
			     FormasPagos.`id` AS FormasPagos_id,
			     FormasPagos.`descripcion` AS FormasPagos_descripcion,
			     FormasPagos.`inactivo` AS FormasPagos_inactivo,
			     FormasPagos.`retencionIIBB` AS FormasPagos_retencionIIBB,
			     FormasPagos.`retencionIVA21` AS FormasPagos_retencionIVA21,
			     FormasPagos.`chequeTerceros` AS FormasPagos_chequeTerceros,
			     FormasPagos.`esChequePropio` AS FormasPagos_esChequePropio,
			     FormasPagos.`ceonceptoBancoId` AS FormasPagos_ceonceptoBancoId,
			     FormasPagos.`depositaEnCuenta` AS FormasPagos_depositaEnCuenta,
			     cliente.`idCliente` AS cliente_idCliente,
			     cliente.`rsocial` AS cliente_rsocial,
			     cliente.`denominacion` AS cliente_denominacion
			FROM
			     `CobranzasDetalle` CobranzasDetalle INNER JOIN `cobranza_detallesCobranzas` cobranza_detallesCobranzas ON CobranzasDetalle.`id` = cobranza_detallesCobranzas.`cobranzasdetalle_id`
			     INNER JOIN `Cobranzas` Cobranzas ON cobranza_detallesCobranzas.`cobranza_id` = Cobranzas.`id`
			     INNER JOIN `cliente` cliente ON Cobranzas.`clienteId` = cliente.`idCliente`
			     INNER JOIN `FormasPagos` FormasPagos ON CobranzasDetalle.`formaPagoId` = FormasPagos.`id`
			WHERE
			     FormasPagos.`chequeTerceros` = 1
			 AND CobranzasDetalle.`estado` = 0
			 AND CobranzasDetalle.`vencimiento` BETWEEN '$desde' AND '$hasta'
			ORDER BY CobranzasDetalle.`vencimiento` ASC";
			
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
     * @Route("/Reportes/VerInventarioCheques", name="mbp_Reportes_VerInventarioCheques", options={"expose"=true})
     */	
    public function VerInventarioCheques()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/pdf/').'InventarioCheques.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'InventarioCheques.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-type', 'application/pdf');
		
		return $response;
	}
	
	/**
     * @Route("/CCClientes/Reportes/reporteCC", name="mbp_CCClientes_reporteCC", options={"expose"=true})
     */	    
    public function reporteCC()
	{
		//RECIBO PARAMETROS
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$response = new Response();	
		
		try{
			/*
			 * PARAMETROS
			 */
			$idCliente = $req->request->get('cliente1');
			$desde = $req->request->get('desde');		
							
			$reporteador = $this->get('reporteador');
			$kernel = $this->get('kernel');
			
			/*
			 * Configuro reporte
			 */
			$jru = $reporteador->jru();
			
			/*
			 * Ruta archivo Jasper
			 */				
					
			$ruta = $kernel->locateResource('@MbpFinanzasBundle/Reportes/ResumenCuenta.jrxml');
			
			/*
			 * Ruta de destino del PDF
			 */
			$destino = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/pdf/').'ccCliente.pdf';		
			
			//Parametros HashMap
			$param = $reporteador->getJava('java.util.HashMap');
			$rutaLogo = $reporteador->getRutaLogo($kernel);
			
			$simpleDateFormat = new \Java("java.text.SimpleDateFormat", 'd/M/yyyy');
			$javaDate = $simpleDateFormat->parse($desde); // This is a Java date
			$param->put('ID_CLIENTE', $idCliente);
			$param->put('FECHA_DESDE', $javaDate);

			$desde=\DateTime::createFromFormat("d/m/Y", $desde);
			$desdeSql=$desde->format("Y/m/d");

			$conn = $reporteador->getJdbc();
			
			$sql = "call listarCCClienteDesde($idCliente, '$desdeSql')";
			
			$res = $jru->runPdfFromSql($ruta, $destino, $param, $sql, $conn->getConnection());
			
			//ARMO RESPUESTA	
			$response->setContent(
					json_encode(array(
						'success' =>true,					
						)
					)
				);
			
			return $response;
		}catch(\Exception $e){
			throw $e;
			$response->setStatusCode($response::HTTP_INTERNAL_SERVER_ERROR);
			return $response->setContent(
				json_encode(array('success' => false, 'msg' => $e->getMessage()))
				);
		}
			
	}
	
	/**
     * @Route("/CCClientes/Reportes/verCCCliente", name="mbp_CCClientes_verCCCliente", options={"expose"=true})
     */	    
    public function verCCCliente()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/pdf/').'ccCliente.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'ccCliente.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-type', 'application/pdf');
		
		$response->deleteFileAfterSend(TRUE);
		
        return $response;
	}
	
	/**
     * @Route("/Cotizaciones/Reportes/cotizacionReporte", name="mbp_Cotizaciones_reporteCoti", options={"expose"=true})
     */	    
    public function cotizacionReporte()
	{
		//RECIBO PARAMETROS
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$response = new Response();	
		
		try{
			/*
			 * PARAMETROS
			 */
			$idCoti = $req->request->get('idCoti');	
							
			$reporteador = $this->get('reporteador');
			$kernel = $this->get('kernel');
			
			/*
			 * Configuro reporte
			 */
			$jru = $reporteador->jru();
			
			/*
			 * Ruta archivo Jasper
			 */				
					
			$ruta = $kernel->locateResource('@MbpFinanzasBundle/Reportes/Cotizacion.jrxml');
			
			/*
			 * Ruta de destino del PDF
			 */
			$destino = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/pdf/').'Cotizacion.pdf';		
			
			//Parametros HashMap
			$param = $reporteador->getJava('java.util.HashMap');
			$rutaLogo = $reporteador->getRutaLogo($kernel);
			
			
			$param->put('cotizacionId', $idCoti);
			$param->put('rutaLogo', $rutaLogo);				
			$conn = $reporteador->getJdbc();
			
			$sql = "
			SELECT
						DetalleCotizacion.`id` AS DetalleCotizacion_id,
						DetalleCotizacion.`descripcion` AS DetalleCotizacion_descripcion,
						DetalleCotizacion.`cant` AS DetalleCotizacion_cant,
						DetalleCotizacion.`unidad` AS DetalleCotizacion_unidad,
						DetalleCotizacion.`precio` AS DetalleCotizacion_precio,
						DetalleCotizacion.`entrega` AS DetalleCotizacion_entrega,
						DetalleCotizacion.`cotizacionId` AS DetalleCotizacion_cotizacionId,
						DetalleCotizacion.`articuloId` AS DetalleCotizacion_articuloId,
						cliente.`idCliente` AS cliente_idCliente,
						cliente.`rsocial` AS cliente_rsocial,
						Cotizacion.`id` AS Cotizacion_id,
						Cotizacion.`emision` AS Cotizacion_emision,
						Cotizacion.`direccion` AS Cotizacion_direccion,
						Cotizacion.`cuit` AS Cotizacion_cuit,
						Cotizacion.`condVenta` AS Cotizacion_condVenta,
						Cotizacion.`moneda` AS Cotizacion_moneda,
						Cotizacion.`cliente` AS Cotizacion_cliente,
						Cotizacion.`tc` AS Cotizacion_tc,
						Cotizacion.`observaciones` AS Cotizacion_observaciones,
						Cotizacion.`clienteId` AS Cotizacion_clienteId,
						Cotizacion.`idUsuario` AS Cotizacion_idUsuario,
						Cotizacion.`total` AS Cotizacion_total,
						Cotizacion.`descuento` AS Cotizacion_descuento,
						Cotizacion.`inactiva` AS Cotizacion_inactiva,
						Cotizacion.`esExportacion` AS Cotizacion_esExportacion,
						articulos.`idArticulos` AS articulos_idArticulos,
						articulos.`descripcion` AS articulos_descripcion,
						articulos.`codigo` AS articulos_codigo,
						articulos.`unidad` AS articulos_unidad,
						articulos.`costo` AS articulos_costo,
						articulos.`precio` AS articulos_precio,
						articulos.`moneda` AS articulos_moneda
				FROM
						`Cotizacion` Cotizacion
						LEFT JOIN `cliente` cliente  ON cliente.`idCliente` = Cotizacion.`clienteId`
						INNER JOIN `DetalleCotizacion` DetalleCotizacion ON Cotizacion.`id` = DetalleCotizacion.`cotizacionId`
						INNER JOIN `articulos` articulos ON DetalleCotizacion.`articuloId` = articulos.`idArticulos`
				WHERE Cotizacion.`id` = $idCoti
			";
			
			$res = $jru->runPdfFromSql($ruta, $destino, $param, $sql, $conn->getConnection());
			
			//ARMO RESPUESTA	
			$response->setContent(
					json_encode(array(
						'success' =>true,					
						)
					)
				);
			
			return $response;
		}catch(\Exception $e){
			throw $e;
			$response->setStatusCode($response::HTTP_INTERNAL_SERVER_ERROR);
			return $response->setContent(
				json_encode(array('success' => false, 'msg' => $e->getMessage()))
				);
		}
	}
	
	/**
     * @Route("/Cotizaciones/Reportes/verCoti", name="mbp_Cotizaciones_verCoti", options={"expose"=true})
     */	    
    public function verCoti()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/pdf/').'Cotizacion.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'Cotizacion.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-type', 'application/pdf');
		
		$response->deleteFileAfterSend(TRUE);
		
        return $response;
	}
}





















