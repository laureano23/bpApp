<?php

namespace Mbp\ComprasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ReportesController extends Controller
{
    /**
     * @Route("/ordenCompra/reporteOrden", name="mbp_compras_reporteOrden", options={"expose"=true})
     */
    public function reporteOrdenAction()
    {
        /*
		 * PARAMETROS
		 */
		$idOC = $this->getRequest()->request->get('idOC');
		$response = new Response;

		try {
			$reporteador = $this->get('reporteador');
			$kernel = $this->get('kernel');
			
			
			/*
			 * Configuro reporte
			 */
			$jru = $reporteador->jru();
			
			/*
			 * Ruta archivo Jasper
			 */				
					
			$ruta = $kernel->locateResource('@MbpComprasBundle/Reportes/OrdenDeCompra.jrxml');
			$rutaLogo = $reporteador->getRutaLogo($kernel);
			
			/*
			 * Ruta de destino del PDF
			 */
			$destino = $kernel->locateResource('@MbpComprasBundle/Resources/public/pdf/').'OC.pdf';
			
			
			//Parametros HashMap
			$param = $reporteador->getJava('java.util.HashMap');
			$param->put('idOC', $idOC);
			$param->put('rutaLogo', $rutaLogo);
			
			$conn = $reporteador->getJdbc();
			
			$sql = "SELECT
			     OrdenCompra.`id` AS OrdenCompra_id,
			     OrdenCompra.`fechaEmision` AS OrdenCompra_fechaEmision,
			     OrdenCompra.`condicionCompra` AS OrdenCompra_condicionCompra,
			     OrdenCompra.`lugarEntrega` AS OrdenCompra_lugarEntrega,
			     OrdenCompra.`observaciones` AS OrdenCompra_observaciones,
			     OrdenCompra.`descuentoGral` AS OrdenCompra_descuentoGral,
			     OrdenCompra.`proveedorId` AS OrdenCompra_proveedorId,
			     OrdenCompra.`usuario` AS OrdenCompra_usuario,
			     OrdenCompraDetalle.`id` AS OrdenCompraDetalle_id,
			     OrdenCompraDetalle.`unidad` AS OrdenCompraDetalle_unidad,
			     OrdenCompraDetalle.`precio` AS OrdenCompraDetalle_precio,
			     OrdenCompraDetalle.`cant` AS OrdenCompraDetalle_cant,
			     OrdenCompraDetalle.`fechaEntrega` AS OrdenCompraDetalle_fechaEntrega,
			     OrdenCompraDetalle.`iva` AS OrdenCompraDetalle_iva,
			     OrdenCompraDetalle.`moneda` AS OrdenCompraDetalle_moneda,
			     OrdenCompraDetalle.`articuloId` AS OrdenCompraDetalle_articuloId,
			     ordenCompra_detallesOrdenCompra.`orden_id` AS ordenCompra_detallesOrdenCompra_orden_id,
			     ordenCompra_detallesOrdenCompra.`ordencompradetalle_id` AS ordenCompra_detallesOrdenCompra_ordencompradetalle_id,
			     articulos.`idArticulos` AS articulos_idArticulos,
			     articulos.`codigo` AS articulos_codigo,
			     articulos.`descripcion` AS articulos_descripcion,
			     OrdenCompra.`monedaOC` AS OrdenCompra_monedaOC,
			     OrdenCompra.`tc` AS OrdenCompra_tc,
			     OrdenCompraDetalle.`ivaCalculado` AS OrdenCompraDetalle_ivaCalculado
			FROM
			     `OrdenCompraDetalle` OrdenCompraDetalle INNER JOIN `ordenCompra_detallesOrdenCompra` ordenCompra_detallesOrdenCompra ON OrdenCompraDetalle.`id` = ordenCompra_detallesOrdenCompra.`ordencompradetalle_id`
			     INNER JOIN `OrdenCompra` OrdenCompra ON ordenCompra_detallesOrdenCompra.`orden_id` = OrdenCompra.`id`
			     INNER JOIN `articulos` articulos ON OrdenCompraDetalle.`articuloId` = articulos.`idArticulos`
			WHERE
			     OrdenCompra.`id` = $idOC";
			
			$jru->runPdfFromSql($ruta, $destino, $param, $sql, $conn->getConnection());

			
			return $response->setContent(
					json_encode(
						array(
							'success'=> true,	
						)
					)
				);
		} catch (\Exception $e) {
			$response->setContent(
				json_encode(
					array(
						'success'=> false,	
						'msg' => $e->getMessage()
					)
				)
			);

			return $response->setStatusCode($response::HTTP_INTERNAL_SERVER_ERROR);
		}	
    }

    /**
     * @Route("/verOC", name="mbp_personal_verOC", options={"expose"=true})
     */
	public function verOCAction()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpComprasBundle/Resources/public/pdf/').'OC.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'OC.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-Type', 'application/pdf');

        return $response;
	}
}
