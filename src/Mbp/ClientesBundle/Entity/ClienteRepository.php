<?php
namespace Mbp\ClientesBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ClienteRepository extends  EntityRepository
{
	public function searchCliente($q)
	{		
		$em = $this->getEntityManager();		
		$qb = $em->createQueryBuilder();
		$res = $qb->select('c.rsocial,
							c.id,
							c.denominacion,
							c.direccion,
							c.email,
							c.cuit,
							c.cPostal,
							i.id as iva,
							c.telefono1,
							c.contacto1,
							c.telefono2,
							c.contacto2,
							c.telefono3,
							c.contacto3,
							c.condVenta,
							c.vencimientoFc,
							c.cuentaCerrada,
							c.intereses,
							c.tasaInt as tasa,
							c.descuentoFijo,
							c.notasCC,
							prov.id as provincia,
							dep.id as departamento,
							l.id as localidad,
							t.id as transporte,
							t.nombre as transporteNombre')
			->from('Mbp\ClientesBundle\Entity\Cliente', 'c')
		    ->where($qb->expr()->like('c.rsocial', $qb->expr()->literal($q.'%')))
			->leftjoin('c.provincia', 'prov')
			->leftjoin('c.departamento', 'dep')
			->leftjoin('c.localidad', 'l')
			->leftjoin('c.iva', 'i')
			->leftjoin('c.transporteId', 't')
		    ->getQuery()
		    ->getArrayResult();
		echo json_encode($res);
	}
}
