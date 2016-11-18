<?php
namespace Mbp\ArticulosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Mbp\ArticulosBundle\Entity\FormulasRepository;
use Mbp\ArticulosBundle\Entity\Formulas;

class ReportesController extends Controller
{	
	/*
	 * RECIBE EL ID DE UNA FORMULA Y GENERA REPORTE DE LA ESTRUCTURA
	 * */
	 
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
		
		//Parametros de conexion
		$host = $this->container->getParameter('database_host');
		$dbName = $this->container->getParameter('database_name');
		$dbUser = $this->container->getParameter('database_user');
		$dbPass = $this->container->getParameter('database_password');
		
		$conn = $repo->getJdbc("com.mysql.jdbc.Driver","jdbc:mysql://".$host."/".$dbName, $dbUser, $dbPass);
		
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
}