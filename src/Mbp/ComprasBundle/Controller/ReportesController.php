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
			
			$sql = "
			SELECT
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
			     OrdenCompraDetalle.`ivaCalculado` AS OrdenCompraDetalle_ivaCalculado,
			     OrdenCompraDetalle.`descripcion` AS OrdenCompraDetalle_descripcion,
			     Proveedor.`id` AS Proveedor_id,
			     Proveedor.`provincia` AS Proveedor_provincia,
			     Proveedor.`rsocial` AS Proveedor_rsocial,
			     provincia.`id` AS provincia_id,
			     provincia.`nombre` AS provincia_nombre,
			     localidades.`id` AS localidades_id,
			     localidades.`departamento_id` AS localidades_departamento_id,
			     localidades.`provincia_id` AS localidades_provincia_id,
			     localidades.`nombre` AS localidades_nombre
			FROM
			     `OrdenCompraDetalle` OrdenCompraDetalle INNER JOIN `ordenCompra_detallesOrdenCompra` ordenCompra_detallesOrdenCompra ON OrdenCompraDetalle.`id` = ordenCompra_detallesOrdenCompra.`ordencompradetalle_id`
			     INNER JOIN `OrdenCompra` OrdenCompra ON ordenCompra_detallesOrdenCompra.`orden_id` = OrdenCompra.`id`
			     LEFT OUTER JOIN `Proveedor` Proveedor ON OrdenCompra.`proveedorId` = Proveedor.`id`
			     LEFT JOIN `provincia` provincia ON Proveedor.`provincia` = provincia.`id`
			     LEFT JOIN `localidades` localidades ON Proveedor.`localidad` = localidades.`id`
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
	
	/**
     * @Route("/ordenCompra/articulosComprados", name="mbp_compras_articulosComprados", options={"expose"=true})
     */
    public function articulosComprados()
    {
		$req= $this->getRequest();
		$proveedorDesde=$req->request->get('proveedor1');
		$proveedorHasta=$req->request->get('proveedor2');
		$fechaDesde=$req->request->get('desde');
		$fechaHasta=$req->request->get('hasta');
		$codigoDesde=$req->request->get('codigo1');
		$codigoHasta=$req->request->get('codigo2');
		$response = new Response;

		try {
			$reporteador = $this->get('reporteador');
			$kernel = $this->get('kernel');
			
			$jru = $reporteador->jru();
					
			$ruta = $kernel->locateResource('@MbpComprasBundle/Reportes/ComprasPendientes.jrxml');
			$rutaLogo = $reporteador->getRutaLogo($kernel);
			
			$destino = $kernel->locateResource('@MbpComprasBundle/Resources/public/pdf/').'ComprasPendientes.pdf';
			
			$param = $reporteador->getJava('java.util.HashMap');
			$param->put('PROVEEDOR_ID_DESDE', $proveedorDesde);
			$param->put('PROVEEDOR_ID_HASTA', $proveedorHasta);			
			$param->put('CODIGO_DESDE', $codigoDesde);
			$param->put('CODIGO_HASTA', $codigoHasta);
			$param->put('rutaLogo', $rutaLogo);
			
			$desde = \DateTime::createFromFormat('d/m/Y', $fechaDesde);
			$hasta = \DateTime::createFromFormat('d/m/Y', $fechaHasta);
			
			$param->put('FECHA_DESDE', $desde->format('d/m/Y'));
			$param->put('FECHA_HASTA', $hasta->format('d/m/Y'));
			
			$desde=$desde->format("Y-m-d");
			$hasta=$hasta->format("Y-m-d");
			
			$conn = $reporteador->getJdbc();
			$sql = "
				select sub.*, detMov.cantidad as cantidad, sum(ifnull(detMov.cantidad, 0)) as ingresado
				from
					(select oc.id as ordenId, art.codigo, detalleOrden.id as detalleOrdenId, detalleOrden.cant as comprado, detalleOrden.descripcion, detalleOrden.articuloId, oc.fechaEmision,oc.tc, oc.monedaOc, detalleOrden.unidad, detalleOrden.precio, sum(detalleOrden.cant) as compradoTotal, detalleOrden.fechaEntrega, prov.id as proveedorId, prov.rsocial
					from
						OrdenCompraDetalle as detalleOrden
						inner join ordenCompra_detallesOrdenCompra oc_det on oc_det.ordencompradetalle_id = detalleOrden.id
						left join OrdenCompra oc on oc.id = oc_det.orden_id
						left join Proveedor prov on oc.proveedorId = prov.id
						left join articulos art on art.idArticulos = detalleOrden.articuloId
					 where
					    oc.fechaEmision between '$desde' and '$hasta'
						and prov.id between $proveedorDesde and $proveedorHasta
						and art.codigo between '$codigoDesde' and '$codigoHasta'
				group by oc.id, art.idArticulos) as sub
				left join DetalleMovArt detMov on detMov.ordenCompraDetalleId = sub.detalleOrdenId
				group by sub.ordenId, sub.articuloId
			";
			
			$jru->runPdfFromSql($ruta, $destino, $param, $sql, $conn->getConnection());
			
			return $response->setContent(json_encode(array('success'=> true)));
		} catch (\Exception $e) {
			throw $e;
			$response->setContent(json_encode(array('success'=> false,'msg' => $e->getMessage())));
			return $response->setStatusCode($response::HTTP_INTERNAL_SERVER_ERROR);
		}	
    }

    /**
     * @Route("/verArticulosComprados", name="mbp_personal_verArticulosComprados", options={"expose"=true})
     */
	public function verArticulosComprados()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpComprasBundle/Resources/public/pdf/').'ComprasPendientes.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'ComprasPendientes.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-Type', 'application/pdf');		
		$response->deleteFileAfterSend(TRUE);

        return $response;
	}
}
