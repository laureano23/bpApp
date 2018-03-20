<?php

namespace Mbp\ProveedoresBundle\Entity;

/**
 * CCProvRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CCProvRepository extends \Doctrine\ORM\EntityRepository
{
	public function listarCCProv($idProveedor){
		$em = $this->getEntityManager();
		$repo = $em->getRepository('MbpProveedoresBundle:CCProv');
		
				
		$res = $repo->createQueryBuilder('cc')
			->select(
				"DATE_FORMAT(cc.fechaEmision, '%d-%m-%Y %H:%i:%s') as fechaEmision,
				CASE WHEN cc.OrdenPagoId IS NOT NULL THEN CONCAT('ORDEN DE PAGO N° ', op.id) ELSE CONCAT(tipo.descripcion, ' N° ', fc.sucursal, '-', fc.numFc) END AS concepto, 
				CASE WHEN cc.OrdenPagoId IS NOT NULL THEN true ELSE false END AS detalle,
				DATE_FORMAT(cc.fechaVencimiento, '%d-%m-%Y %H:%i:%s') as fechaVencimiento,
				cc.debe,
				cc.haber,
				SUM(cc2.haber) - SUM(cc2.debe) AS saldo,
				fc.id as idF,
				op.id as idOP
				")
			->leftJoin('cc.facturaId', 'fc')
			->leftJoin('fc.tipoId', 'tipo')
			->leftJoin('cc.OrdenPagoId', 'op')
			->leftJoin('fc.proveedorId', 'provFc')
			->leftJoin('op.proveedorId', 'provOP')
			->leftJoin('Mbp\ProveedoresBundle\Entity\CCProv', 'cc2', \Doctrine\ORM\Query\Expr\Join::WITH, 'cc.id >= cc2.id')
			->where('provFc.id = :idProv')
			->orWhere('provOP.id = :idProv')
			->setParameter('idProv', $idProveedor)	
			->groupBy('cc.id')		
			->getQuery()
			->getArrayResult();
		
		return $res;
	}
}
