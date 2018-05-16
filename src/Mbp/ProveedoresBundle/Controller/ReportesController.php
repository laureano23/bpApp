<?php

namespace Mbp\ProveedoresBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ReportesController extends Controller
{
	/**
     * @Route("/proveedores/reporteDetallePago", name="mbp_proveedores_reporteDetallePago", options={"expose"=true})
     */
    public function reporteDetallePagoAction()
    {
    	//RECIBO PARAMETROS
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$response = new Response;
		
		try{
			/*
			 * PARAMETROS
			 */	
			$idOp = (int)$req->request->get('idOp');	
							
			$reporteador = $this->get('reporteador');
			$kernel = $this->get('kernel');
			
			/*
			 * Configuro reporte
			 */
			$jru = $reporteador->jru();
			
			/*
			 * Ruta archivo Jasper
			 */				
					
			$ruta = $kernel->locateResource('@MbpProveedoresBundle/Reportes/Detalle_Pago_Proveedor.jrxml');
			
			/*
			 * Ruta de destino del PDF
			 */
			$destino = $kernel->locateResource('@MbpProveedoresBundle/Resources/public/pdf/').'Detalle_Pago_Proveedor.pdf';		
			
			//Parametros HashMap
			$param = $reporteador->getJava('java.util.HashMap');
			$rutaLogo = $reporteador->getRutaLogo($kernel);
			
			$param->put('ordenPagoId', $idOp);
			
			$conn = $reporteador->getJdbc();
						
			$sql = "SELECT
			     Pago.`id` AS Pago_id,
			     Pago.`emision` AS Pago_emision,
			     Pago.`numero` AS Pago_numero,
			     Pago.`diferido` AS Pago_diferido,
			     Pago.`idFormaPago` AS Pago_idFormaPago,
			     Pago.`banco` AS Pago_banco,
			     Pago.`importe` AS Pago_importe,
			     OrdenPago.`id` AS OrdenPago_id,
			     OrdenPago.`proveedorId` AS OrdenPago_proveedorId,
			     OrdenDePago_detallesPagos.`pago_id` AS OrdenDePago_detallesPagos_pago_id,
			     OrdenDePago_detallesPagos.`ordenPago_id` AS OrdenDePago_detallesPagos_ordenPago_id,
			     Proveedor.`id` AS Proveedor_id,
			     Proveedor.`provincia` AS Proveedor_provincia,
			     Proveedor.`rsocial` AS Proveedor_rsocial,
			     Proveedor.`denominacion` AS Proveedor_denominacion,
			     Proveedor.`direccion` AS Proveedor_direccion,
			     Proveedor.`cuit` AS Proveedor_cuit,
			     Proveedor.`cPostal` AS Proveedor_cPostal,
			     OrdenPago.`fechaEmision` AS OrdenPago_fechaEmision,
			     Proveedor.`departamento` AS Proveedor_departamento,
			     localidades.`id` AS localidades_id,
			     localidades.`departamento_id` AS localidades_departamento_id,
			     localidades.`provincia_id` AS localidades_provincia_id,
			     localidades.`nombre` AS localidades_nombre,
			     Proveedor.`localidad` AS Proveedor_localidad,
			     provincia.`id` AS provincia_id,
			     provincia.`nombre` AS provincia_nombre,
			     FormasPagos.`id` AS FormasPagos_id,
			     FormasPagos.`descripcion` AS FormasPagos_descripcion,
			     FormasPagos.`inactivo` AS FormasPagos_inactivo,
			     FormasPagos.`retencionIIBB` AS FormasPagos_retencionIIBB,
			     FormasPagos.`retencionIVA21` AS FormasPagos_retencionIVA21,
			     FormasPagos.`chequeTerceros` AS FormasPagos_chequeTerceros,
			     FormasPagos.`esChequePropio` AS FormasPagos_esChequePropio,
			     FormasPagos.`ceonceptoBancoId` AS FormasPagos_ceonceptoBancoId
			FROM
			     `Pago` Pago INNER JOIN `OrdenDePago_detallesPagos` OrdenDePago_detallesPagos ON Pago.`id` = OrdenDePago_detallesPagos.`pago_id`
			     INNER JOIN `OrdenPago` OrdenPago ON OrdenDePago_detallesPagos.`ordenPago_id` = OrdenPago.`id`
			     INNER JOIN `Proveedor` Proveedor ON OrdenPago.`proveedorId` = Proveedor.`id`
			     LEFT OUTER JOIN `localidades` localidades ON Proveedor.`localidad` = localidades.`id`
			     LEFT JOIN `provincia` provincia ON localidades.`provincia_id` = provincia.`id`
			     LEFT JOIN `FormasPagos` FormasPagos ON Pago.`idFormaPago` = FormasPagos.`id`
			WHERE
			     OrdenDePago_detallesPagos.`ordenPago_id` = $idOp";		     
		   	$param->put('SUBREPORT_DIR', $kernel->locateResource('@MbpProveedoresBundle/Reportes/'));
			
			$jru->runPdfFromSql($ruta, $destino, $param, $sql, $conn->getConnection());
			
			
			return $response->setContent(json_encode(
				array(
					'success'=> true,	
				)
			));
		}catch(\Exception $e){
			return $response->setContent(json_encode(array(
				'success' => false,
				'msg' => $e->getMessage()
			)));
		}		
	}
	
	/**
     * @Route("/proveedores/verReporteDetallePago", name="mbp_proveedores_verReporteDetallePago", options={"expose"=true})
     */	    
    public function verReporteDetallePagoAction()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpProveedoresBundle/Resources/public/pdf/').'Detalle_Pago_Proveedor.pdf';	
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'Detalle_Pago_Proveedor.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-type', 'application/pdf');

        return $response;
	}
	
	/**
     * @Route("/proveedores/reporteImputacionAfc", name="mbp_proveedores_reporteImputacionAfc", options={"expose"=true})
     */
    public function reporteImputacionAfcAction(){
    	//RECIBO PARAMETROS
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$response = new Response;
		
		try{
			/*
			 * PARAMETROS
			 */
			$idF = $req->request->get('idF');		
			$proveedorId = $req->request->get('proveedorId');
			
			$reporteador = $this->get('reporteador');
			$kernel = $this->get('kernel');
			
			/*
			 * Configuro reporte
			 */
			$jru = $reporteador->jru();
			
			/*
			 * Ruta archivo Jasper
			 */				
					
			$ruta = $kernel->locateResource('@MbpProveedoresBundle/Reportes/PagoAsociado.jrxml');
			
			/*
			 * Ruta de destino del PDF
			 */
			$destino = $kernel->locateResource('@MbpProveedoresBundle/Resources/public/pdf/').'PagoAsociado.pdf';		
			
			//Parametros HashMap
			$param = $reporteador->getJava('java.util.HashMap');
			$rutaLogo = $reporteador->getRutaLogo($kernel);
			
			$param->put('ProveedorId', $proveedorId);
			$param->put('FacturaId', $idF);
			
			$conn = $reporteador->getJdbc();
						
			$sql = "SELECT
			     FacturaProveedor.`id` AS FacturaProveedor_id,
			     FacturaProveedor.`fechaEmision` AS FacturaProveedor_fechaEmision,
			     FacturaProveedor.`sucursal` AS FacturaProveedor_sucursal,
			     FacturaProveedor.`numFc` AS FacturaProveedor_numFc,
			     FacturaProveedor.`totalFc` AS FacturaProveedor_totalFc,
			     FacturaProveedor.`proveedorId` AS FacturaProveedor_proveedorId,
			     TransaccionOPFC.`id` AS TransaccionOPFC_id,
			     TransaccionOPFC.`aplicado` AS TransaccionOPFC_aplicado,
			     TransaccionOPFC.`facturaId` AS TransaccionOPFC_facturaId,
			     TransaccionOPFC.`ordenPagoId` AS TransaccionOPFC_ordenPagoId,
			     OrdenPago.`id` AS OrdenPago_id,
			     OrdenPago.`fechaEmision` AS OrdenPago_fechaEmision,
			     OrdenPago.`proveedorId` AS OrdenPago_proveedorId
			FROM
			     `FacturaProveedor` FacturaProveedor INNER JOIN `TransaccionOPFC` TransaccionOPFC ON FacturaProveedor.`id` = TransaccionOPFC.`facturaId`
			     INNER JOIN `OrdenPago` OrdenPago ON TransaccionOPFC.`ordenPagoId` = OrdenPago.`id`
			WHERE
			     TransaccionOPFC.`facturaId` = $idF
			 AND OrdenPago.`proveedorId` = $proveedorId";		     
			
			$jru->runPdfFromSql($ruta, $destino, $param, $sql, $conn->getConnection());
			
			echo json_encode(
				array(
					'success'=> true,	
				)
			);
			
	    	return $response;	
		}catch(\Exception $e){
			$response->setStatusCode($response::HTTP_INTERNAL_SERVER_ERROR);
			return $response->setContent(
				json_encode(array('success' => false, 'msg' => $e->getMessage()))
				);
		}		
    }
    
    /**
     * @Route("/proveedores/verReporteImputacionFc", name="mbp_proveedores_verReporteImputacionFc", options={"expose"=true})
     */	    
    public function verReporteImputacionFcAction()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpProveedoresBundle/Resources/public/pdf/').'PagoAsociado.pdf';	
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'PagoAsociado.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-type', 'application/pdf');

        return $response;
	}   
	
	/**
     * @Route("/proveedores/ReporteLibroIVACompras", name="mbp_proveedores_ReporteLibroIVACompras", options={"expose"=true})
     */
    public function ReporteLibroIVACompras(){
    	//RECIBO PARAMETROS
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$response = new Response;
		
		
		try{
			/*
			 * PARAMETROS
			 */
			$desde = \DateTime::createFromFormat('d/m/Y', $req->request->get('desde'));		
			$hasta = \DateTime::createFromFormat('d/m/Y', $req->request->get('hasta'));	
			
			$desde = $desde->format('Y-m-d');
			$hasta = $hasta->format('Y-m-d');
			
			$reporteador = $this->get('reporteador');
			$kernel = $this->get('kernel');
			
			/*
			 * Configuro reporte
			 */
			$jru = $reporteador->jru();
			
			/*
			 * Ruta archivo Jasper
			 */				
					
			$ruta = $kernel->locateResource('@MbpProveedoresBundle/Reportes/LibroIVACompras.jrxml');
			
			/*
			 * Ruta de destino del PDF
			 */
			$destino = $kernel->locateResource('@MbpProveedoresBundle/Resources/public/pdf/').'LibroIVACompras.pdf';		
			
			//Parametros HashMap
			$param = $reporteador->getJava('java.util.HashMap');
			$rutaLogo = $reporteador->getRutaLogo($kernel);
			
			$param->put('fechaDesde', $desde);
			$param->put('fechaHasta', $hasta);
			
			
			$conn = $reporteador->getJdbc();
						
			$sql = "SELECT
			     Proveedor.`id` AS Proveedor_id,
			     Proveedor.`provincia` AS Proveedor_provincia,
			     Proveedor.`rsocial` AS Proveedor_rsocial,
			     Proveedor.`denominacion` AS Proveedor_denominacion,
			     Proveedor.`direccion` AS Proveedor_direccion,
			     Proveedor.`cuit` AS Proveedor_cuit,
			     Proveedor.`vencimientoFc` AS Proveedor_vencimientoFc,
			     Proveedor.`imputacionGastos` AS Proveedor_imputacionGastos,
			     FacturaProveedor.`proveedorId` AS FacturaProveedor_proveedorId,
			     FacturaProveedor.`totalFc` AS FacturaProveedor_totalFc,
			     FacturaProveedor.`concepto` AS FacturaProveedor_concepto,
			     FacturaProveedor.`vencimiento` AS FacturaProveedor_vencimiento,
			     FacturaProveedor.`iibbCf` AS FacturaProveedor_iibbCf,
			     FacturaProveedor.`perIva3` AS FacturaProveedor_perIva3,
			     FacturaProveedor.`perIva5` AS FacturaProveedor_perIva5,
			     FacturaProveedor.`iva10_5` AS FacturaProveedor_iva10_5,
			     FacturaProveedor.`iva21` AS FacturaProveedor_iva21,
			     FacturaProveedor.`netoNoGrabado` AS FacturaProveedor_netoNoGrabado,
			     FacturaProveedor.`neto` AS FacturaProveedor_neto,
			     FacturaProveedor.`iva27` AS FacturaProveedor_iva27,
			     FacturaProveedor.`numFc` AS FacturaProveedor_numFc,
			     FacturaProveedor.`sucursal` AS FacturaProveedor_sucursal,
			     FacturaProveedor.`fechaEmision` AS FacturaProveedor_fechaEmision,
			     FacturaProveedor.`id` AS FacturaProveedor_id,
			     TipoComprobante.`id` AS TipoComprobante_id,
			     TipoComprobante.`esFactura` AS TipoComprobante_esFactura,
			     TipoComprobante.`esNotaCredito` AS TipoComprobante_esNotaCredito,
			     TipoComprobante.`esNotaDebito` AS TipoComprobante_esNotaDebito,
			     TipoComprobante.`esBalance` AS TipoComprobante_esBalance,
			     TipoComprobante.`descripcion` AS TipoComprobante_descripcion,
			     TipoComprobante.`subTipoA` AS TipoComprobante_subTipoA,
			     TipoComprobante.`subTipoB` AS TipoComprobante_subTipoB,
			     TipoComprobante.`subTipoE` AS TipoComprobante_subTipoE,
			     FacturaProveedor.`tipoId` AS FacturaProveedor_tipoId
			FROM
			     `Proveedor` Proveedor INNER JOIN `FacturaProveedor` FacturaProveedor ON Proveedor.`id` = FacturaProveedor.`proveedorId`
			     INNER JOIN `TipoComprobante` TipoComprobante ON FacturaProveedor.`tipoId` = TipoComprobante.`id`
			WHERE
			     FacturaProveedor.`fechaEmision` BETWEEN '$desde' AND '$hasta'";		     
			
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
     * @Route("/proveedores/VerReporteLibroIVACompras", name="mbp_proveedores_VerReporteLibroIVACompras", options={"expose"=true})
     */	    
    public function VerReporteLibroIVACompras()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpProveedoresBundle/Resources/public/pdf/').'LibroIVACompras.pdf';	
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'LibroIVACompras.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-type', 'application/pdf');

        return $response;
	}   
	
	/**
     * @Route("/proveedores/ChequesTercerosEntregados", name="mbp_proveedores_ChequesTercerosEntregados", options={"expose"=true})
     */
    public function ChequesTercerosEntregados(){
    	//RECIBO PARAMETROS
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$response = new Response;
		
		
		try{
			/*
			 * PARAMETROS
			 */
			$desde = \DateTime::createFromFormat('d/m/Y', $req->request->get('desde'));		
			$hasta = \DateTime::createFromFormat('d/m/Y', $req->request->get('hasta'));	
			
			$desde = $desde->format('Y-m-d');
			$hasta = $hasta->format('Y-m-d');
			
			$reporteador = $this->get('reporteador');
			$kernel = $this->get('kernel');
			
			/*
			 * Configuro reporte
			 */
			$jru = $reporteador->jru();
			
			/*
			 * Ruta archivo Jasper
			 */				
					
			$ruta = $kernel->locateResource('@MbpProveedoresBundle/Reportes/ChequeTerceros_Entregados.jrxml');
			
			/*
			 * Ruta de destino del PDF
			 */
			$destino = $kernel->locateResource('@MbpProveedoresBundle/Resources/public/pdf/').'ChequeTerceros_Entregados.pdf';		
			
			//Parametros HashMap
			$param = $reporteador->getJava('java.util.HashMap');
			$rutaLogo = $reporteador->getRutaLogo($kernel);
			
			$param->put('fechaDesde', $desde);
			$param->put('fechaHasta', $hasta);
			
			
			$conn = $reporteador->getJdbc();
						
			$sql = "SELECT
			     Pago.`id` AS Pago_id,
			     Pago.`banco` AS Pago_banco,
			     Pago.`emision` AS Pago_emision,
			     Pago.`numero` AS Pago_numero,
			     Pago.`importe` AS Pago_importe,
			     Pago.`diferido` AS Pago_diferido,
			     Pago.`idFormaPago` AS Pago_idFormaPago,
			     Pago.`cuentaId` AS Pago_cuentaId,
			     Pago.`movBancoId` AS Pago_movBancoId,
			     OrdenPago.`id` AS OrdenPago_id,
			     OrdenPago.`fechaEmision` AS OrdenPago_fechaEmision,
			     OrdenPago.`proveedorId` AS OrdenPago_proveedorId,
			     OrdenPago.`importe` AS OrdenPago_importe,
			     OrdenPago.`ccId` AS OrdenPago_ccId,
			     OrdenDePago_detallesPagos.`pago_id` AS OrdenDePago_detallesPagos_pago_id,
			     OrdenDePago_detallesPagos.`ordenPago_id` AS OrdenDePago_detallesPagos_ordenPago_id,
			     FormasPagos.`id` AS FormasPagos_id,
			     FormasPagos.`descripcion` AS FormasPagos_descripcion,
			     FormasPagos.`inactivo` AS FormasPagos_inactivo,
			     FormasPagos.`retencionIIBB` AS FormasPagos_retencionIIBB,
			     FormasPagos.`retencionIVA21` AS FormasPagos_retencionIVA21,
			     FormasPagos.`esChequePropio` AS FormasPagos_esChequePropio,
			     FormasPagos.`chequeTerceros` AS FormasPagos_chequeTerceros,
			     FormasPagos.`ceonceptoBancoId` AS FormasPagos_ceonceptoBancoId,
			     FormasPagos.`depositaEnCuenta` AS FormasPagos_depositaEnCuenta,
			     Proveedor.`id` AS Proveedor_id,
			     Proveedor.`rsocial` AS Proveedor_rsocial,
			     CobranzasDetalle.`id` AS CobranzasDetalle_id,
			     CobranzasDetalle.`importe` AS CobranzasDetalle_importe,
			     CobranzasDetalle.`numero` AS CobranzasDetalle_numero,
			     CobranzasDetalle.`banco` AS CobranzasDetalle_banco,
			     CobranzasDetalle.`vencimiento` AS CobranzasDetalle_vencimiento,
			     CobranzasDetalle.`estado` AS CobranzasDetalle_estado,
			     CobranzasDetalle.`formaPagoId` AS CobranzasDetalle_formaPagoId,
			     CobranzasDetalle.`cuentaId` AS CobranzasDetalle_cuentaId,
			     CobranzasDetalle.`movBancoId` AS CobranzasDetalle_movBancoId,
			     Cobranzas.`id` AS Cobranzas_id,
			     Cobranzas.`ptoVenta` AS Cobranzas_ptoVenta,
			     Cobranzas.`numRecibo` AS Cobranzas_numRecibo,
			     Cobranzas.`emision` AS Cobranzas_emision,
			     Cobranzas.`fechaRecibo` AS Cobranzas_fechaRecibo,
			     Cobranzas.`clienteId` AS Cobranzas_clienteId,
			     Cobranzas.`totalCobranza` AS Cobranzas_totalCobranza,
			     Cobranzas.`ccId` AS Cobranzas_ccId,
			     cobranza_detallesCobranzas.`cobranza_id` AS cobranza_detallesCobranzas_cobranza_id,
			     cobranza_detallesCobranzas.`cobranzasdetalle_id` AS cobranza_detallesCobranzas_cobranzasdetalle_id,
			     cliente.`rsocial` AS cliente_rsocial,
			     cliente.`idCliente` AS cliente_idCliente
			FROM
			     `Pago` Pago INNER JOIN `OrdenDePago_detallesPagos` OrdenDePago_detallesPagos ON Pago.`id` = OrdenDePago_detallesPagos.`pago_id`
			     INNER JOIN `OrdenPago` OrdenPago ON OrdenDePago_detallesPagos.`ordenPago_id` = OrdenPago.`id`
			     INNER JOIN `Proveedor` Proveedor ON OrdenPago.`proveedorId` = Proveedor.`id`
			     LEFT OUTER JOIN `FormasPagos` FormasPagos ON Pago.`idFormaPago` = FormasPagos.`id`
			     INNER JOIN `CobranzasDetalle` CobranzasDetalle ON Pago.`numero` = CobranzasDetalle.`numero`
			     AND CobranzasDetalle.`banco` = Pago.`banco`
			     AND FormasPagos.`id` = CobranzasDetalle.`formaPagoId`
			     INNER JOIN `cobranza_detallesCobranzas` cobranza_detallesCobranzas ON CobranzasDetalle.`id` = cobranza_detallesCobranzas.`cobranzasdetalle_id`
			     INNER JOIN `Cobranzas` Cobranzas ON cobranza_detallesCobranzas.`cobranza_id` = Cobranzas.`id`
			     INNER JOIN `cliente` cliente ON Cobranzas.`clienteId` = cliente.`idCliente`
			WHERE
			     	FormasPagos.`chequeTerceros` = TRUE AND
				Cobranzas.`emision` BETWEEN '$desde' AND '$hasta'
			ORDER BY Pago.`diferido` ASC";		     
			
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
     * @Route("/proveedores/VerChequesTercerosEntregados", name="mbp_proveedores_VerChequesTercerosEntregados", options={"expose"=true})
     */	    
    public function VerChequesTercerosEntregados()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpProveedoresBundle/Resources/public/pdf/').'ChequeTerceros_Entregados.pdf';	
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'ChequeTerceros_Entregados.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-type', 'application/pdf');

        return $response;
	} 
	
	/**
     * @Route("/proveedores/CertificadoRetencion", name="mbp_proveedores_CertificadoRetencion", options={"expose"=true})
     */
    public function CertificadoRetencion(){
    	//RECIBO PARAMETROS
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$response = new Response;
		
		
		try{
			/*
			 * PARAMETROS
			 */
			$idOp = (int)$req->request->get('idOp');
			
			$reporteador = $this->get('reporteador');
			$kernel = $this->get('kernel');
			
			/*
			 * Configuro reporte
			 */
			$jru = $reporteador->jru();
			
			/*
			 * Ruta archivo Jasper
			 */				
					
			$ruta = $kernel->locateResource('@MbpProveedoresBundle/Reportes/Comprobante_Retencion.jrxml');
			
			/*
			 * Ruta de destino del PDF
			 */
			$destino = $kernel->locateResource('@MbpProveedoresBundle/Resources/public/pdf/').'Comprobante_Retencion.pdf';		
			
			//Parametros HashMap
			$param = $reporteador->getJava('java.util.HashMap');
			$rutaLogo = $reporteador->getRutaLogo($kernel);
			
			$param->put('OP_ID', $idOp);
			$param->put('dirFirma', $kernel->locateResource('@MbpPersonalBundle/Reportes/rob.jpg'));
			
			
			$conn = $reporteador->getJdbc();
						
			$sql = "SELECT
			     Pago.`id` AS Pago_id,
			     Pago.`banco` AS Pago_banco,
			     Pago.`emision` AS Pago_emision,
			     Pago.`numero` AS Pago_numero,
			     Pago.`importe` AS Pago_importe,
			     Pago.`diferido` AS Pago_diferido,
			     Pago.`idFormaPago` AS Pago_idFormaPago,
			     Pago.`cuentaId` AS Pago_cuentaId,
			     Pago.`movBancoId` AS Pago_movBancoId,
			     FormasPagos.`id` AS FormasPagos_id,
			     FormasPagos.`descripcion` AS FormasPagos_descripcion,
			     FormasPagos.`inactivo` AS FormasPagos_inactivo,
			     FormasPagos.`retencionIIBB` AS FormasPagos_retencionIIBB,
			     FormasPagos.`retencionIVA21` AS FormasPagos_retencionIVA21,
			     FormasPagos.`chequeTerceros` AS FormasPagos_chequeTerceros,
			     FormasPagos.`esChequePropio` AS FormasPagos_esChequePropio,
			     FormasPagos.`ceonceptoBancoId` AS FormasPagos_ceonceptoBancoId,
			     FormasPagos.`depositaEnCuenta` AS FormasPagos_depositaEnCuenta,
			     OrdenDePago_detallesPagos.`pago_id` AS OrdenDePago_detallesPagos_pago_id,
			     OrdenDePago_detallesPagos.`ordenPago_id` AS OrdenDePago_detallesPagos_ordenPago_id,
			     OrdenPago.`id` AS OrdenPago_id,
			     OrdenPago.`fechaEmision` AS OrdenPago_fechaEmision,
			     OrdenPago.`proveedorId` AS OrdenPago_proveedorId,
			     TransaccionOPFC.`id` AS TransaccionOPFC_id,
			     TransaccionOPFC.`aplicado` AS TransaccionOPFC_aplicado,
			     TransaccionOPFC.`facturaId` AS TransaccionOPFC_facturaId,
			     TransaccionOPFC.`ordenPagoId` AS TransaccionOPFC_ordenPagoId,
			     FacturaProveedor.`id` AS FacturaProveedor_id,
			     FacturaProveedor.`fechaEmision` AS FacturaProveedor_fechaEmision,
			     FacturaProveedor.`sucursal` AS FacturaProveedor_sucursal,
			     FacturaProveedor.`numFc` AS FacturaProveedor_numFc,
			     FacturaProveedor.`neto` AS FacturaProveedor_neto,
			     FacturaProveedor.`tipoId` AS FacturaProveedor_tipoId,
			     TipoComprobante.`id` AS TipoComprobante_id,
			     TipoComprobante.`esFactura` AS TipoComprobante_esFactura,
			     TipoComprobante.`esNotaCredito` AS TipoComprobante_esNotaCredito,
			     TipoComprobante.`esNotaDebito` AS TipoComprobante_esNotaDebito,
			     TipoComprobante.`descripcion` AS TipoComprobante_descripcion,
			     Proveedor.`id` AS Proveedor_id,
			     Proveedor.`rsocial` AS Proveedor_rsocial,
			     Proveedor.`direccion` AS Proveedor_direccion,
			     Proveedor.`localidad` AS Proveedor_localidad,
			     Proveedor.`provincia` AS Proveedor_provincia,
			     Proveedor.`cuit` AS Proveedor_cuit,
			     provincia.`id` AS provincia_id,
			     provincia.`nombre` AS provincia_nombre,
			     localidades.`id` AS localidades_id,
			     localidades.`departamento_id` AS localidades_departamento_id,
			     localidades.`provincia_id` AS localidades_provincia_id,
			     localidades.`nombre` AS localidades_nombre,
			     baseImponible
			FROM
			     `FormasPagos` FormasPagos INNER JOIN `Pago` Pago ON FormasPagos.`id` = Pago.`idFormaPago`
			     INNER JOIN `OrdenDePago_detallesPagos` OrdenDePago_detallesPagos ON Pago.`id` = OrdenDePago_detallesPagos.`pago_id`
			     INNER JOIN `OrdenPago` OrdenPago ON OrdenDePago_detallesPagos.`ordenPago_id` = OrdenPago.`id`
			     INNER JOIN `TransaccionOPFC` TransaccionOPFC ON OrdenPago.`id` = TransaccionOPFC.`ordenPagoId`
			     INNER JOIN `Proveedor` Proveedor ON OrdenPago.`proveedorId` = Proveedor.`id`
			     INNER JOIN `FacturaProveedor` FacturaProveedor ON Proveedor.`id` = FacturaProveedor.`proveedorId`
			     LEFT JOIN `provincia` provincia ON Proveedor.`provincia` = provincia.`id`
			     LEFT JOIN `localidades` localidades ON Proveedor.`localidad` = localidades.`id`
			     INNER JOIN `TipoComprobante` TipoComprobante ON FacturaProveedor.`tipoId` = TipoComprobante.`id`,
				(select SUM(tr.aplicado) as baseImponible
				from TransaccionOPFC as tr
				where tr.ordenPagoId = $idOp ) as sub
			WHERE
			     OrdenPago.`id` = $idOp AND TransaccionOPFC.`facturaId` = FacturaProveedor.`id`
			 AND FormasPagos.`retencionIIBB` = TRUE";		     
			
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
     * @Route("/proveedores/VerCertificadoRetencion", name="mbp_proveedores_VerCertificadoRetencion", options={"expose"=true})
     */	    
    public function VerCertificadoRetencion()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpProveedoresBundle/Resources/public/pdf/').'Comprobante_Retencion.pdf';	
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'Comprobante_Retencion.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-type', 'application/pdf');

        return $response;
	}     
}






















