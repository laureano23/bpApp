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
			     INNER JOIN `provincia` provincia ON localidades.`provincia_id` = provincia.`id`
			     INNER JOIN `FormasPagos` FormasPagos ON Pago.`idFormaPago` = FormasPagos.`id`
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
			     FacturaProveedor.`tipo` AS FacturaProveedor_tipo,
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
			
	    	return new Response();	
		}catch(\Exception $e){
			echo json_encode(array(
				'success' => false,
				'msg' => $e->getMessage()
			));
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
			     FacturaProveedor.`tipo` AS FacturaProveedor_tipo,
			     FacturaProveedor.`fechaEmision` AS FacturaProveedor_fechaEmision,
			     FacturaProveedor.`id` AS FacturaProveedor_id
			FROM
			     `Proveedor` Proveedor INNER JOIN `FacturaProveedor` FacturaProveedor ON Proveedor.`id` = FacturaProveedor.`proveedorId`
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
}






















