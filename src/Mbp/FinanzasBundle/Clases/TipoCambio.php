<?php

namespace Mbp\FinanzasBundle\Clases;
use Mbp\FinanzasBundle\Entity\ParametrosFinanzas;
use Doctrine\ORM\EntityManager;

class TipoCambio
{
	public $em;
		 
	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}
	
	public function getTipoCambio()
	{
		$repo = $this->em->getRepository('MbpFinanzasBundle:ParametrosFinanzas');
		$res = $repo->findAll();
		
		return $res[0]->getDolarOficial();
		//\Doctrine\Common\Util\Debug::dump($res);
	}
}
