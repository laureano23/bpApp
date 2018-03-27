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
     * @Route("/etiquetaIngresoMaterial", name="mbp_formulas_etiquetaIngresoMaterial", options={"expose"=true})
     */
	public function etiquetaIngresoMaterial()
	{
		$repo = $this->get('reporteador');		
		$kernel = $this->get('kernel');	
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$response = new Response;
		
		try{
			/*
			 * Recibo parametros del request 
			 */
			
			$id = $req->request->get('id');
			$jru = $repo->jru();
					
			$ruta = $kernel->locateResource('@MbpArticulosBundle/Reportes/IngresoStock.jrxml');
			
			//Ruta de destino
			$destino = $kernel->locateResource('@MbpArticulosBundle/Resources/public/pdf/').'IngresoStock.pdf';
			
			//Parametros HashMap
			$param = $repo->getJava('java.util.HashMap');
			$param->put('ID_DETALLE_MOV', $id);
					
			$conn = $repo->getJdbc();
			
			/*
			 * SQL
			 * 
			 */
			$sql = "
				SELECT
			     movimientos_detalles.`movimientosarticulos_id` AS movimientos_detalles_movimientosarticulos_id,
			     movimientos_detalles.`detallemovart_id` AS movimientos_detalles_detallemovart_id,
			     MovimientosArticulos.`id` AS MovimientosArticulos_id,
			     MovimientosArticulos.`fechaMovimiento` AS MovimientosArticulos_fechaMovimiento,
			     MovimientosArticulos.`tipoMovimiento` AS MovimientosArticulos_tipoMovimiento,
			     MovimientosArticulos.`observaciones` AS MovimientosArticulos_observaciones,
			     MovimientosArticulos.`comprobanteNum` AS MovimientosArticulos_comprobanteNum,
			     MovimientosArticulos.`conceptoId` AS MovimientosArticulos_conceptoId,
			     MovimientosArticulos.`proveedorId` AS MovimientosArticulos_proveedorId,
			     MovimientosArticulos.`clienteId` AS MovimientosArticulos_clienteId,
			     MovimientosArticulos.`depositoId` AS MovimientosArticulos_depositoId,
			     DetalleMovArt.`id` AS DetalleMovArt_id,
			     DetalleMovArt.`cantidad` AS DetalleMovArt_cantidad,
			     DetalleMovArt.`loteNum` AS DetalleMovArt_loteNum,
			     DetalleMovArt.`descripcion` AS DetalleMovArt_descripcion,
			     DetalleMovArt.`ordenCompraId` AS DetalleMovArt_ordenCompraId,
			     DetalleMovArt.`articuloId` AS DetalleMovArt_articuloId,
			     cliente.`idCliente` AS cliente_idCliente,
			     cliente.`rsocial` AS cliente_rsocial,
			     cliente.`denominacion` AS cliente_denominacion,
			     Proveedor.`id` AS Proveedor_id,
			     Proveedor.`provincia` AS Proveedor_provincia,
			     Proveedor.`rsocial` AS Proveedor_rsocial,
			     articulos.`idArticulos` AS articulos_idArticulos,
			     articulos.`codigo` AS articulos_codigo
			FROM
			     `MovimientosArticulos` MovimientosArticulos LEFT OUTER JOIN `movimientos_detalles` movimientos_detalles ON MovimientosArticulos.`id` = movimientos_detalles.`movimientosarticulos_id`
			     RIGHT OUTER JOIN `DetalleMovArt` DetalleMovArt ON movimientos_detalles.`detallemovart_id` = DetalleMovArt.`id`
			     INNER JOIN `articulos` articulos ON DetalleMovArt.`articuloId` = articulos.`idArticulos`
			     LEFT OUTER JOIN `cliente` cliente ON MovimientosArticulos.`clienteId` = cliente.`idCliente`
			     LEFT OUTER JOIN `Proveedor` Proveedor ON MovimientosArticulos.`proveedorId` = Proveedor.`id`
			WHERE
			     DetalleMovArt.`id` = $id
			";
			
			//Exportamos el reporte
			$jru->runPdfFromSql($ruta, $destino, $param,$sql,$conn->getConnection());
						
			return $response->setContent(
				json_encode(array(
					'success' => true,
					'reporte' => $destino,	
				))
			);
		}catch(\Exception $e){
			return $response->setContent(
				json_encode(array(
					'success' => false,
					'msg' => $e->getMessage(),	
				))
			);
		}
			
	}
	
	/**
     * @Route("/etiquetaIngresoMaterial_pdf", name="mbp_formulas_etiquetaIngresoMaterial_pdf", options={"expose"=true})
     */
	public function etiquetaIngresoMaterial_pdf()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpArticulosBundle/Resources/public/pdf/').'IngresoStock.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'IngresoStock.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-type', 'application/pdf');

        return $response;
	}
	
		
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
			SELECT fechaMov, numComprobante, concepto,
				CASE WHEN TIPO_MOV = 0 THEN DetalleMovArt.cantidad ELSE null END AS entrada,
				CASE WHEN TIPO_MOV = 1 OR ISNULL(concepto) THEN RemitosClientesDetalles.cantidad ELSE null END AS salida,
				Proveedor.rsocial AS proveedorNombre,
				cliente.rsocial AS clienteNombre,
				CASE WHEN ISNULL(concepto) THEN articulosRem.codigo ELSE articulosMov.codigo END AS codigo,
				CASE WHEN ISNULL(concepto) THEN RemitosClientesDetalles.descripcion ELSE DetalleMovArt.descripcion END AS descripcion,
				CASE WHEN ISNULL(concepto) THEN RemitosClientesDetalles.id ELSE CONCAT('m', DetalleMovArt.id) END AS idFila
			FROM
			(SELECT
			     RemitosClientes.`id` AS remId,
			     RemitosClientes.`fecha` AS fechaMov,
			     RemitosClientes.`remitoNum` AS numComprobante,
			     RemitosClientes.`proveedorId` AS RemitosClientes_proveedorId,
			     RemitosClientes.`clienteId` AS RemitosClientes_clienteId,
			     RemitosClientes.`id` AS TIPO_MOV,
			     RemitosClientes.`id`=0 AS CONCEPTO_MOV
			FROM
			     `RemitosClientes` RemitosClientes
			UNION
			SELECT
			     MovimientosArticulos.`id` AS MovimientosArticulos_id,
			     MovimientosArticulos.`fechaMovimiento` AS MovimientosArticulos_fechaMovimiento,
			     MovimientosArticulos.`comprobanteNum` AS MovimientosArticulos_comprobanteNum,
			     MovimientosArticulos.`proveedorId` AS MovimientosArticulos_proveedorId,
			     MovimientosArticulos.`clienteId` AS MovimientosArticulos_clienteId,
			     MovimientosArticulos.`tipoMovimiento` AS MovimientosArticulos_tipoMovimiento,
			     MovimientosArticulos.`conceptoId` AS MovimientosArticulos_conceptoId
			FROM
			     `MovimientosArticulos` MovimientosArticulos) AS sub
			LEFT JOIN movimientos_detalles ON movimientos_detalles.movimientosarticulos_id = remId
			LEFT JOIN DetalleMovArt ON DetalleMovArt.id = movimientos_detalles.detallemovart_id
			LEFT JOIN RemitoClientes_detalle AS RemDetalle ON RemDetalle.remitosclientes_id = remId
			LEFT JOIN RemitosClientesDetalles ON RemDetalle.remitosclientesdetalles_id = RemitosClientesDetalles.id
			LEFT JOIN cliente ON cliente.idCliente = RemitosClientes_clienteId
			LEFT JOIN Proveedor ON Proveedor.id = RemitosClientes_proveedorId
			LEFT JOIN ConceptosStock ON ConceptosStock.id = CONCEPTO_MOV
			LEFT JOIN articulos AS articulosMov ON articulosMov.idArticulos = DetalleMovArt.articuloId
			LEFT JOIN articulos AS articulosRem ON articulosRem.idArticulos = RemitosClientesDetalles.articuloId
			WHERE fechaMov BETWEEN '$desde' AND '$hasta'
			AND articulosMov.codigo BETWEEN '$cod1' AND '$cod2' OR
			fechaMov BETWEEN '$desde' AND '$hasta' AND
			articulosRem.codigo BETWEEN '$cod1' AND '$cod2' AND ISNULL(concepto)
			GROUP BY idFila
			ORDER BY codigo
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