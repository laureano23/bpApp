<?php

namespace Mbp\WebBundle\Entity;

/**
 * TiposRadiadoresRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TiposRadiadoresRepository extends \Doctrine\ORM\EntityRepository
{
	public function listarAplicaciones()
	{
		$em = $this->getEntityManager();
		$repo = $em->getRepository('MbpWebBundle:AplicacionesRadiadores');

		$res = $repo->createQueryBuilder('apli')		
			->select('apli')
			->getQuery()
			->getArrayResult();

		return $res;
	}
}
