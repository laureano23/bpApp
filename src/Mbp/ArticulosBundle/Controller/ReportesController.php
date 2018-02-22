<?php
namespace Mbp\ArticulosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Mbp\ArticulosBundle\Entity\FormulasRepository;
use Mbp\ArticulosBundle\Entity\Formulas;


class ReportesController extends Controller
{	
	 /**
     * @Route("/extranet/generaReporteEstructura", name="mbp_formulas_generaReporte", options={"expose"=true})
     */
	public function generaReporteEstructuraAction()
	{
		$repo = $this->get('reporteador');		
		$kernel = $this->get('kernel');	
		
		/*
		 * Recibo parametros del request 
		 */
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$idNodo = (int)$req->request->get('idNodo');
		$tipoCambio = $this->get('TipoCambio');
		$tc = (float)$tipoCambio->getTipoCambio();
		
		$jru = $repo->jru();
				
		$ruta = $kernel->locateResource('@MbpArticulosBundle/Reportes/EstructuraDeMateriales.jrxml');
		$rutaLogo = $repo->getRutaLogo($kernel);
		
		//Ruta de destino
		$destino = $kernel->locateResource('@MbpArticulosBundle/Resources/public/pdf/').'Estructura_materiales.pdf';
		
		//Parametros HashMap
		$param = $repo->getJava('java.util.HashMap');
		$param->put('idNodo', $idNodo);
		$param->put('rutaLogo', $rutaLogo);
		$param->put('tc', $tc);
				
		$conn = $repo->getJdbc();
		
		/*
		 * SQL
		 * 
		 */
		$sql = "
			SELECT *
			FROM
			(SELECT 	node.id,
				node.lft,
				node.rgt,
				node.cant,
				(COUNT(parent.id) - (sub_tree.depth + 1)) AS depth,
				articulos.descripcion,
				articulos.codigo,
				articulos.moneda,
				articulos.costo as costo
				FROM `articulos` articulos INNER JOIN `Formulas` node ON articulos.`idArticulos` = node.`idArt`,
			        Formulas AS parent,
			        Formulas AS sub_parent,
			        (
						SELECT node.id, (COUNT(parent.id) - 1) AS depth
						FROM Formulas AS node,
						Formulas AS parent
						WHERE node.lft BETWEEN parent.lft AND parent.rgt
						AND node.id = $idNodo
						GROUP BY node.id
						ORDER BY node.lft
			        )AS sub_tree
			WHERE node.lft BETWEEN parent.lft AND parent.rgt
			        AND node.lft BETWEEN sub_parent.lft AND sub_parent.rgt
			        AND sub_parent.id = sub_tree.id
			GROUP BY node.id
			ORDER BY node.lft) AS x
			
			INNER JOIN
			
			(SELECT 	node.id,
				SUM(
					CASE
						WHEN articulos.moneda = 0
							THEN articulos.costo * node.cant
						ELSE articulos.costo * node.cant * $tc
						END) AS sumCosto,
				SUM(
					CASE
						WHEN articulos.moneda = 0
							THEN articulos.costo * node.cant
						ELSE articulos.costo * node.cant * $tc
						END) * parent.cant AS sumCostoPadre
				FROM `articulos` articulos INNER JOIN `Formulas` node ON articulos.`idArticulos` = node.`idArt`,
			        Formulas AS parent,
			        Formulas AS sub_parent,
			        (
						SELECT node.id, (COUNT(parent.id) - 1) AS depth
						FROM Formulas AS node,
						Formulas AS parent
						WHERE node.lft BETWEEN parent.lft AND parent.rgt
						AND node.id = $idNodo
						GROUP BY node.id
						ORDER BY node.lft
			        )AS sub_tree
			WHERE node.lft BETWEEN parent.lft AND parent.rgt
			        AND node.lft BETWEEN sub_parent.lft AND sub_parent.rgt
			        AND sub_parent.id = sub_tree.id
			GROUP BY parent.id
			ORDER BY node.lft) AS y ON x.id = y.id
		";
		 /*
		  * FIN SQL
		  */
		
		//Exportamos el reporte
		$jru->runPdfFromSql($ruta, $destino, $param,$sql,$conn->getConnection());
		
		
		return new Response(
			json_encode(array(
				'success' => true,
				'reporte' => $destino,	
			))
		);
	}
	
	/**
     * @Route("/extranet/muestraReporteEstructura", name="mbp_formulas_muestraReporte", options={"expose"=true})
     */
	public function muestraReporteEstructuraAction()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpArticulosBundle/Resources/public/pdf/').'Estructura_materiales.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'Estructura_materiales.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-type', 'application/pdf');

        return $response;
	}
	
	
	 /**
     * @Route("/reporteHistoricoMov", name="mbp_reportes_historicoMov", options={"expose"=true})
     */
	public function reporteHistoricoMov()
	{
		$repo = $this->get('reporteador');		
		$kernel = $this->get('kernel');	
		
		/*
		 * Recibo parametros del request 
		 */
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		
		$jru = $repo->jru();
				
		$ruta = $kernel->locateResource('@MbpArticulosBundle/Reportes/MovimientosArticulos.jrxml');
		$rutaLogo = $repo->getRutaLogo($kernel);
		
		//Ruta de destino
		$destino = $kernel->locateResource('@MbpArticulosBundle/Resources/public/pdf/').'MovimientosArticulos.pdf';
		
		//Fechas
		$desde = \DateTime::createFromFormat('d/m/Y', $req->request->get('desde'));
		$desde = $desde->format('Y-m-d');
		
		
		$hasta = \DateTime::createFromFormat('d/m/Y', $req->request->get('hasta'));
		$hasta = $hasta->format('Y-m-d');
		
		$cod1 = $req->request->get('codigo1');
		$cod2 = $req->request->get('codigo2');
		
		//Parametros HashMap
		$param = $repo->getJava('java.util.HashMap');
		$param->put('rutaLogo', $rutaLogo);
		$param->put('fechaDesde', $desde);
		$param->put('fechaHasta', $hasta);
		$param->put('codigoDesde', $cod1);
		$param->put('codigoHasta', $cod2);
		
				
		$conn = $repo->getJdbc();
		
		/*
		 * SQL
		 * 
		 */
		$sql = "
			SELECT
				RemitosClientes.fecha AS fechaRemito,
				RemitosClientes.remitoNum AS remitoNum,
				RemitosClientesDetalles.cantidad AS remitoCant,
				RemitosClientesDetalles.descripcion AS detalleRem,
				ProveedorRem.rsocial AS proveedorRem,
				ClienteRem.rsocial AS clienteRem,
			
				MovimientosArticulos.fechaMovimiento,
				MovimientosArticulos.comprobanteNum AS movimientoNum,
				MovimientosArticulos.tipoMovimiento,
				ConceptosStock.concepto AS conceptoMovimiento,
				ProveedorMov.rsocial AS proveedorMov,
				ClienteMov.rsocial AS clienteMov,
				DetalleMovArt.cantidad AS cantMov,
				DetalleMovArt.descripcion AS descripcionMov,
			
				articulos.codigo,
				articulos.descripcion AS descArt
			FROM (SELECT
			     DetalleMovArt.`id` AS MOVIMIENTO_REMITO_ID,
			     DetalleMovArt.`descripcion` AS DESCRIPCION_MOV,
			     DetalleMovArt.`articuloId` AS ARTICULO_ID
			FROM
			     `DetalleMovArt` DetalleMovArt
			UNION
			SELECT
			     RemitosClientesDetalles.`id` AS RemitosClientesDetalles_id,
			     RemitosClientesDetalles.`descripcion` AS RemitosClientesDetalles_descripcion,
			     RemitosClientesDetalles.`articuloId` AS RemitosClientesDetalles_articuloId
			FROM
			     `RemitosClientesDetalles` RemitosClientesDetalles) AS SUB
			LEFT JOIN movimientos_detalles ON MOVIMIENTO_REMITO_ID = movimientos_detalles.detallemovart_id
			LEFT JOIN MovimientosArticulos ON movimientos_detalles.movimientosarticulos_id = MovimientosArticulos.id
			LEFT JOIN Proveedor AS ProveedorMov ON ProveedorMov.id = MovimientosArticulos.proveedorId
			LEFT JOIN cliente AS ClienteMov ON ClienteMov.idCliente = MovimientosArticulos.clienteId
			LEFT JOIN ConceptosStock ON ConceptosStock.id = MovimientosArticulos.conceptoId
			LEFT JOIN DetalleMovArt ON DetalleMovArt.id = movimientos_detalles.detallemovart_id
			
			LEFT JOIN RemitoClientes_detalle ON RemitoClientes_detalle.remitosclientes_id = MOVIMIENTO_REMITO_ID
			LEFT JOIN RemitosClientes ON RemitosClientes.id = RemitoClientes_detalle.remitosclientes_id
			LEFT JOIN RemitosClientesDetalles ON RemitosClientesDetalles.id = RemitoClientes_detalle.remitosclientesdetalles_id
			LEFT JOIN Proveedor AS ProveedorRem ON ProveedorRem.id = RemitosClientes.proveedorId
			LEFT JOIN cliente AS ClienteRem ON ClienteRem.idCliente = RemitosClientes.clienteId
			
			LEFT JOIN articulos ON articulos.idArticulos = ARTICULO_ID
			
			WHERE			
				RemitosClientes.fecha BETWEEN '$desde' AND '$hasta' AND
				codigo BETWEEN '$cod1' AND '$cod2' OR
				fechaMovimiento BETWEEN '$desde' AND '$hasta' AND
				codigo BETWEEN '$cod1' AND '$cod2'
		";
		 /*
		  * FIN SQL
		  */
		
		//Exportamos el reporte
		$jru->runPdfFromSql($ruta, $destino, $param,$sql,$conn->getConnection());
		
		
		return new Response(
			json_encode(array(
				'success' => true,
				'reporte' => $destino,	
			))
		);
	}
	
	/**
     * @Route("/ReporteHistoricoMov_PDF", name="mbp_reportes_historicoMov_PDF", options={"expose"=true})
     */
	public function ReporteHistoricoMov_PDF()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpArticulosBundle/Resources/public/pdf/').'MovimientosArticulos.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'MovimientosArticulos.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-type', 'application/pdf');

        return $response;
	}
}