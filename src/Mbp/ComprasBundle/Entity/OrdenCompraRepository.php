<?php

namespace Mbp\ComprasBundle\Entity;

/**
 * OrdenCompraRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OrdenCompraRepository extends \Doctrine\ORM\EntityRepository
{
	/* LISTA SEGUN UN CODIGO DE ARTICULO LAS ORDENES DE COMPRA PENDIENTES */
	public function articulosPendientesIngreso($codigoArt)
	{
		$em = $this->getEntityManager();
		$rep = $em->getRepository('MbpComprasBundle:OrdenCompra');
		$repArt = $em->getRepository('MbpArticulosBundle:Articulos');
		
		$art = $repArt->findOneByCodigo($codigoArt);	
		
		if(empty($art)) throw new \Exception("Artículo no encontrado", 1);
		$idArt=$art->getId();

		$idArt = $art->getId();
		$sql = "
		SELECT sub.* 
		FROM(
			SELECT DATE_FORMAT(o0_.fechaEmision,'%d/%m/%Y') AS fechaEmision,
			o1_.id AS idDetalleOrden,
			o0_.id AS idOc,
			DATE_FORMAT(o1_.fechaEntrega,'%d/%m/%Y') AS entrega,
			o1_.cant - IFNULL(SUM(d2_.cantidad), 0) AS pendiente,
			o1_.cant AS ordenCant,
			d2_.cantidad AS movCant,
			a3_.codigo AS codigo,
			o0_.anulada
			FROM OrdenCompra o0_
			INNER JOIN ordenCompra_detallesOrdenCompra o4_ ON o0_.id = o4_.orden_id
			INNER JOIN OrdenCompraDetalle o1_ ON o1_.id = o4_.ordencompradetalle_id
			LEFT JOIN DetalleMovArt d2_ ON o1_.id = d2_.ordenCompraDetalleId 
			INNER JOIN articulos a3_ ON o1_.articuloId = a3_.idArticulos 
			WHERE a3_.idArticulos = $idArt
			GROUP BY o1_.id) sub
		WHERE sub.pendiente > 0
			and sub.anulada=0
		";
			
		$stmt = $em->getConnection()->prepare($sql);
		$stmt->execute();
		$data = $stmt->fetchAll();

		return $data;
	}
	
}
