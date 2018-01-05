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
							c.netoPercepcion,
							c.porcentajePercepcion,
							c.cuentaCerrada,
							prov.id as provincia,
							dep.id as localidad')
			->from('Mbp\ClientesBundle\Entity\Cliente', 'c')
		    ->where($qb->expr()->like('c.rsocial', $qb->expr()->literal($q.'%')))
			->leftjoin('c.provincia', 'prov')
			->leftjoin('c.departamento', 'dep')
			->leftjoin('c.iva', 'i')
		    ->getQuery()
		    ->getArrayResult();
		echo json_encode($res);
		//print_r($res);
	}
}
