<?php

namespace Mbp\ProduccionBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CalculoRadRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CalculoRadRepository extends EntityRepository
{
	public function saveCalculo($info)
	{
		$em = $this->getEntityManager();
		
		$repo = $em->getRepository('MbpProduccionBundle:CalculoRad');
		$repoArticulo = $em->getRepository('MbpArticulosBundle:Articulos');
        
		$obj = $repo->findOneBycod($info->id);
		$art = $repoArticulo->findOneById($info->id);
		
		if(!$obj){
			$calc = new CalculoRad();
			
			$calc->setCod($art);
			$calc->setapoyoTapas($info->apoyoTapas);
			$calc->setprof($info->prof);
			$calc->setancho($info->ancho);
			$calc->setchapaPiso($info->chapaPiso);
			$calc->setcantAdic($info->cantAdicional);
			$calc->setperfilInt($info->perfilIntermedio);
			$calc->setaletaTipo($info->aletaTipo);
			$calc->setpisosManual($info->pisosManual);
			$calc->setpisosManual7($info->pisosManual7);
			$calc->settipo($info->tipo);
			$calc->setaletaFluA($info->aletaFluA);
			$calc->setaletaVenA($info->aletaVenA);
            $calc->setmaxAlt($info->maxAlt);
            $calc->setcantPaneles($info->cantPaneles);
			
			try{
				$em->persist($calc);
				$em->flush();
				echo json_encode(array(
					'success' => true,
					'status' => 'creado'
				));
			}catch(\Doctrine\ORM\ORMException $e){
				$error = $this->get('logger')->error($e->getMessage());
				echo json_encode(array(
					'success' => false,
					'msg' => $error
				));
			}
		}else{
			$obj->setCod($art);
			$obj->setapoyoTapas($info->apoyoTapas);
			$obj->setprof($info->prof);
			$obj->setancho($info->ancho);
			$obj->setchapaPiso($info->chapaPiso);
			$obj->setcantAdic($info->cantAdicional);
			$obj->setperfilInt($info->perfilIntermedio);
			$obj->setaletaTipo($info->aletaTipo);
			$obj->setpisosManual($info->pisosManual);
			$obj->setpisosManual7($info->pisosManual7);
			$obj->settipo($info->tipo);
			$obj->setaletaFluA($info->aletaFluA);
			$obj->setaletaVenA($info->aletaVenA);            
            $obj->setmaxAlt($info->maxAlt);
            $obj->setcantPaneles($info->cantPaneles);
			
			try{
				$em->persist($obj);
				$em->flush();
				echo json_encode(array(
					'success' => true,
					'status' => 'actualizado'
				));
			}catch(\Doctrine\ORM\ORMException $e){
				$error = $this->get('logger')->error($e->getMessage());
				echo json_encode(array(
					'success' => false,
					'msg' => $error
				));
			}
		}
	}
}
