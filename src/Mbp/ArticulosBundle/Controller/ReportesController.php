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
     * @Route("/etiquetaArticulo", name="mbp_formulas_etiquetaArticulo", options={"expose"=true})
     */
	public function etiquetaArticulo()
	{
		$repo = $this->get('reporteador');		
		$kernel = $this->get('kernel');	
		$req = $this->getRequest();
		$response = new Response;
		
		try{			
			$codigo = $req->request->get('codigo');
			$cliente = $req->request->get('cliente');
			$numSerie = $req->request->get('numSerie');
			$jru = $repo->jru();
					
			$ruta = $kernel->locateResource('@MbpArticulosBundle/Reportes/EtiquetaArticulo.jasper');
			
			//Ruta de destino
			$destino = $kernel->locateResource('@MbpArticulosBundle/Resources/public/pdf/').'EtiquetaArticulo.pdf';
			$rutaLogo = $repo->getRutaLogo($kernel);

			//Parametros HashMap
			$param = $repo->getJava('java.util.HashMap');
			$param->put('codigo', $codigo);
			$param->put('cliente', $cliente);
			$param->put('numSerie', $numSerie);
			$param->put('rutaLogo', $rutaLogo);

					
			$conn = $repo->getJdbc();
			
			
			//Exportamos el reporte
			$jru->runReportToPdfFile($ruta, $destino, $param, $conn->getConnection());
						
			return $response->setContent(
				json_encode(array(
					'success' => true,
					'reporte' => $destino,	
				))
			);
		}catch(\Exception $e){
			print($e);
			return $response->setContent(
				json_encode(array(
					'success' => false,
					'msg' => $e->getMessage(),	
				))
			);
		}
			
	}

	/**
     * @Route("/etiquetaArticuloPDF", name="mbp_formulas_etiquetaArticuloPDF", options={"expose"=true})
     */
	public function etiquetaArticuloPDF()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpArticulosBundle/Resources/public/pdf/').'EtiquetaArticulo.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'EtiquetaArticulo.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-type', 'application/pdf');

        return $response;
	}
	
	/**
     * @Route("/enQueFormulas", name="mbp_formulas_enQueFormulas", options={"expose"=true})
     */
	public function enQueFormulas()
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
			
			$id = $req->request->get('idArt');
			$jru = $repo->jru();
					
			$ruta = $kernel->locateResource('@MbpArticulosBundle/Reportes/EnQueFormulas.jrxml');
			
			//Ruta de destino
			$destino = $kernel->locateResource('@MbpArticulosBundle/Resources/public/pdf/').'EnQueFormulas.pdf';
			
			//Parametros HashMap
			$param = $repo->getJava('java.util.HashMap');
			$param->put('ID_ART', $id);
					
			$conn = $repo->getJdbc();
			
			/*
			 * SQL
			 * 
			 */
			$sql = "
				SELECT sub.*, art.codigo as codigoHijo, art.descripcion as descripcionHijo
				from
				(SELECT art.codigo, art.descripcion, f.cantidad
				FROM FormulasC as f
				JOIN FormulasC parent on parent.id = f.parent_id
				JOIN articulos art on art.idArticulos = parent.idArt
				WHERE f.idArt = $id
				GROUP BY art.codigo
				ORDER BY art.codigo ASC) as sub,
				articulos art
				where art.idArticulos = $id
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
     * @Route("/enQueFormulasPDF", name="mbp_formulas_enQueFormulas_pdf", options={"expose"=true})
     */
	public function enQueFormulasPDF()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpArticulosBundle/Resources/public/pdf/').'EnQueFormulas.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'EnQueFormulas.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-type', 'application/pdf');

        return $response;
	}

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
			     DetalleMovArt.`ordenCompraDetalleId` AS DetalleMovArt_ordenCompraDetalleId,
			     DetalleMovArt.`articuloId` AS DetalleMovArt_articuloId,
			     cliente.`idCliente` AS cliente_idCliente,
			     cliente.`rsocial` AS cliente_rsocial,
			     cliente.`denominacion` AS cliente_denominacion,
			     Proveedor.`id` AS Proveedor_id,
			     Proveedor.`provincia` AS Proveedor_provincia,
			     Proveedor.`rsocial` AS Proveedor_rsocial,
			     articulos.`idArticulos` AS articulos_idArticulos,
			     articulos.`codigo` AS articulos_codigo,
			     OrdenCompraDetalle.`id` AS OrdenCompraDetalle_id,
			     OrdenCompraDetalle.`unidad` AS OrdenCompraDetalle_unidad,
			     OrdenCompraDetalle.`precio` AS OrdenCompraDetalle_precio,
			     OrdenCompraDetalle.`cant` AS OrdenCompraDetalle_cant,
			     OrdenCompraDetalle.`fechaEntrega` AS OrdenCompraDetalle_fechaEntrega,
			     OrdenCompraDetalle.`iva` AS OrdenCompraDetalle_iva,
			     OrdenCompraDetalle.`ivaCalculado` AS OrdenCompraDetalle_ivaCalculado,
			     OrdenCompraDetalle.`moneda` AS OrdenCompraDetalle_moneda,
			     OrdenCompraDetalle.`articuloId` AS OrdenCompraDetalle_articuloId,
			     OrdenCompraDetalle.`descripcion` AS OrdenCompraDetalle_descripcion,
			     ordenCompra_detallesOrdenCompra.`orden_id` AS ordenCompra_detallesOrdenCompra_orden_id,
			     ordenCompra_detallesOrdenCompra.`ordencompradetalle_id` AS ordenCompra_detallesOrdenCompra_ordencompradetalle_id,
			     OrdenCompra.`id` AS OrdenCompra_id,
			     OrdenCompra.`usuario` AS OrdenCompra_usuario,
			     OrdenCompra.`fechaEmision` AS OrdenCompra_fechaEmision,
			     OrdenCompra.`monedaOC` AS OrdenCompra_monedaOC,
			     OrdenCompra.`condicionCompra` AS OrdenCompra_condicionCompra,
			     OrdenCompra.`lugarEntrega` AS OrdenCompra_lugarEntrega,
			     OrdenCompra.`observaciones` AS OrdenCompra_observaciones,
			     OrdenCompra.`descuentoGral` AS OrdenCompra_descuentoGral,
			     OrdenCompra.`tc` AS OrdenCompra_tc,
			     OrdenCompra.`proveedorId` AS OrdenCompra_proveedorId,
			     OrdenCompra.`anulada` AS OrdenCompra_anulada
			FROM
			     `MovimientosArticulos` MovimientosArticulos LEFT OUTER JOIN `movimientos_detalles` movimientos_detalles ON MovimientosArticulos.`id` = movimientos_detalles.`movimientosarticulos_id`
			     RIGHT OUTER JOIN `DetalleMovArt` DetalleMovArt ON movimientos_detalles.`detallemovart_id` = DetalleMovArt.`id`
			     INNER JOIN `articulos` articulos ON DetalleMovArt.`articuloId` = articulos.`idArticulos`
			     LEFT JOIN `OrdenCompraDetalle` OrdenCompraDetalle ON DetalleMovArt.`ordenCompraDetalleId` = OrdenCompraDetalle.`id`
			     LEFT JOIN `ordenCompra_detallesOrdenCompra` ordenCompra_detallesOrdenCompra ON OrdenCompraDetalle.`id` = ordenCompra_detallesOrdenCompra.`ordencompradetalle_id`
			     LEFT JOIN `OrdenCompra` OrdenCompra ON ordenCompra_detallesOrdenCompra.`orden_id` = OrdenCompra.`id`
			     LEFT OUTER JOIN `cliente` cliente ON MovimientosArticulos.`clienteId` = cliente.`idCliente`
			     LEFT OUTER JOIN `Proveedor` Proveedor ON MovimientosArticulos.`proveedorId` = Proveedor.`id`
			WHERE
			     DetalleMovArt.`id` = $id
			";
			
			//Exportamos el reporte
			$jru->runPdfFromSql($ruta, $destino, $param,$sql,$conn->getConnection());
						
			return new Response(
				json_encode(array(
					'success' => true,
					'reporte' => $destino,	
				))
			);
		}catch(\Exception $e){
			print($e);
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			return $response->setContent(
				json_encode(array(
					'success' => false,
					'msg' => $e->getMessage(),	
				))
			);
		}
			
	}

	/**
     * @Route("/muestraReporteEtiquetaIngreso", name="mbp_formulas_muestraReporteEtiquetaIngreso", options={"expose"=true})
     */
	public function muestraReporteEtiquetaIngreso()
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
		$em=$this->getDoctrine()->getEntityManager();
		$repoFormulas=$em->getRepository('MbpArticulosBundle:FormulasC');
		$repoArt=$em->getRepository('MbpArticulosBundle:Articulos');
		$response=new Response;
		
		/*
		 * Recibo parametros del request 
		 */
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$idArt=$req->request->get('idArt');
		$art=$repoArt->find($idArt);
		$nodo = $repoFormulas->findOneByIdArt($art);
		
		if($nodo==null) return $response->setContent(json_encode(array('success'=>true, 'reporte'=>null)));
		$idNodo=$nodo->getId();
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
		
		$sql = "call estructuraFormulas($idNodo, $tc);";
		
		
		//Exportamos el reporte
		$jru->runPdfFromSql($ruta, $destino, $param,$sql,$conn->getConnection());
		
		
		return $response->setContent(
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