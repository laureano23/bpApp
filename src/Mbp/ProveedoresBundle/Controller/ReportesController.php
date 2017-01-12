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
		
		try{
			/*
			 * PARAMETROS
			 */
			$idProv = $req->request->get('idProv');		
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
			
			$param->put('proveedorId', $idProv);
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
			     Proveedor.`localidad` AS Proveedor_localidad,
			     Proveedor.`provincia` AS Proveedor_provincia,
			     Proveedor.`rsocial` AS Proveedor_rsocial,
			     Proveedor.`denominacion` AS Proveedor_denominacion,
			     Proveedor.`direccion` AS Proveedor_direccion,
			     Proveedor.`cuit` AS Proveedor_cuit,
			     Proveedor.`cPostal` AS Proveedor_cPostal,
			     provincias.`id` AS provincias_id,
			     provincias.`nombre` AS provincias_nombre,
			     localidades.`id` AS localidades_id,
			     localidades.`departamento_id` AS localidades_departamento_id,
			     localidades.`nombre` AS localidades_nombre,
			     FormasPago.`id` AS FormasPago_id,
			     FormasPago.`descripcion` AS FormasPago_descripcion,
			     OrdenPago.`fechaEmision` AS OrdenPago_fechaEmision
			FROM
			     `Pago` Pago INNER JOIN `OrdenDePago_detallesPagos` OrdenDePago_detallesPagos ON Pago.`id` = OrdenDePago_detallesPagos.`pago_id`
			     INNER JOIN `OrdenPago` OrdenPago ON OrdenDePago_detallesPagos.`ordenPago_id` = OrdenPago.`id`
			     INNER JOIN `Proveedor` Proveedor ON OrdenPago.`proveedorId` = Proveedor.`id`
			     LEFT OUTER JOIN `provincias` provincias ON Proveedor.`provincia` = provincias.`id`
			     LEFT OUTER JOIN `localidades` localidades ON Proveedor.`localidad` = localidades.`id`
			     INNER JOIN `FormasPago` FormasPago ON Pago.`idFormaPago` = FormasPago.`id`
			WHERE
			     OrdenDePago_detallesPagos.`ordenPago_id` = $idOp";		     
		   	$param->put('SUBREPORT_DIR', $kernel->locateResource('@MbpProveedoresBundle/Reportes/'));
			
			$jru->runPdfFromSql($ruta, $destino, $param, $sql, $conn->getConnection());
			
			//$jru->runReportEmpty($ruta,$destino, $param);
			
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
			     SUM(TransaccionOPFC.`aplicado`) AS TransaccionOPFC_aplicado,
			     OrdenPago.`id` AS OrdenPago_id,
			     OrdenPago.`fechaEmision` AS OrdenPago_fechaEmision,
			     OrdenPago.`proveedorId` AS OrdenPago_proveedorId,
			     Pago_FacturaProveedores.`pago_id` AS Pago_FacturaProveedores_pago_id,
			     Pago_FacturaProveedores.`factura_id` AS Pago_FacturaProveedores_factura_id,
			     TransaccionOPFC.`id` AS TransaccionOPFC_id,
			     TransaccionOPFC.`facturaId` AS TransaccionOPFC_facturaId,
			     TransaccionOPFC.`ordenPagoId` AS TransaccionOPFC_ordenPagoId,
			     Factura.`id` AS Factura_id,
			     Factura.`sucursal` AS Factura_sucursal,
			     Factura.`numFc` AS Factura_numFc,
			     Factura.`totalFc` AS Factura_totalFc,
			     Factura.`tipo` AS Factura_tipo,
			     Factura.`proveedorId` AS Factura_proveedorId,
			     Proveedor.`id` AS Proveedor_id
			FROM
			     `OrdenPago` OrdenPago INNER JOIN `Pago_FacturaProveedores` Pago_FacturaProveedores ON OrdenPago.`id` = Pago_FacturaProveedores.`pago_id`
			     INNER JOIN `TransaccionOPFC` TransaccionOPFC ON OrdenPago.`id` = TransaccionOPFC.`ordenPagoId`
			     INNER JOIN `Proveedor` Proveedor ON OrdenPago.`proveedorId` = Proveedor.`id`
			     INNER JOIN `Factura` Factura ON Proveedor.`id` = Factura.`proveedorId`
			     AND TransaccionOPFC.`facturaId` = Factura.`id`
			     AND Factura.`id` = Pago_FacturaProveedores.`factura_id`
			WHERE
			     Factura.`id` = $idF AND
			     Proveedor.`id` = $proveedorId
			GROUP BY
			     OrdenPago.`id`";		     
			
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
}






















