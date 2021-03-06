<?php

namespace Mbp\ProduccionBundle\Entity;
use Mbp\ProduccionBundle\Entity\OperacionesFormula;
use Symfony\Component\HttpFoundation\Response;

/**
 * OperacionesFormulaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OperacionesFormulaRepository extends \Doctrine\ORM\EntityRepository
{
	public function formulaSegunArt($codigo, $tc)
	{
		$em = $this->getEntityManager();
		
		//REPOSITORIOS
		$repoFormula = $em->getRepository('MbpProduccionBundle:OperacionesFormula');
		$repoCodigo = $em->getRepository('MbpArticulosBundle:Articulos');
				
		try{
			
			
			//ARTICULO
			if($codigo){
				$articulo = $repoCodigo->findByCodigo($codigo);		
				$idArt = $articulo[0]->getId();
				
				//FORMULA MO
				$dql = 'SELECT formula, operacion, centro FROM MbpProduccionBundle:OperacionesFormula formula
						JOIN formula.idOperacion operacion
						JOIN operacion.centroCosto centro
						WHERE formula.idArticulo= :idArt';
				$query = $em->createQuery($dql)
						->setParameter('idArt', $idArt);
				$res = $query->getArrayResult();
				
				if(!$res){
					echo json_encode(array(
						'success' => true,
						'msg' => 'El articulo no tiene formula'						
					));
					return;
				}
									
				for($i=0; $i<count($res); $i++){
					$res[$i]['codigo'] = $codigo;
					$res[$i]['idOperacion']['centroCosto']['moneda'] == true ? $res[$i]['idOperacion']['centroCosto']['costo'] =  $res[$i]['idOperacion']['centroCosto']['costo'] * $tc : ""; 
				}
				
				$res[0]['codigo'] =  $codigo;
				
				
				//RESPONSE
				echo json_encode(array(
					'items' => $res
				));	
			}else{
				echo json_encode(array(
					'items' => ''
				));
			}	
		}catch(\Doctrine\ORM\ORMException $e){
			$this->get('logger')->error($e->getMessage());
			echo json_encode(array(
				'success' => false,
				'msg' => $e->getMessage()
			));
		}		
		
	}
	
	public function nuevoItem($codigo, $data)
	{
		$em = $this->getEntityManager();
		
		//DECODE
		$info = json_decode($data);
		
		//REPOSITORIOS
		$repoFormula = $em->getRepository('MbpProduccionBundle:OperacionesFormula');
		$repoOperaciones = $em->getRepository('MbpProduccionBundle:Operaciones');
		$repoCodigo = $em->getRepository('MbpArticulosBundle:Articulos');
		
		try{
			//ARTICULO
			$articulo = $repoCodigo->findByCodigo($codigo);
			
			//OPERACION
			$dql =  'SELECT ope, centro FROM MbpProduccionBundle:Operaciones ope
					 JOIN ope.centroCosto centro
					 WHERE ope.id= :idOpe';
			$query = $em->createQuery($dql)
					->setParameter('idOpe', $info->idOperacion);
			$ope = $query->getArrayResult();
			$opeObj = $repoOperaciones->find($info->idOperacion);
					
			//TIEMPO
			$time = new \DateTime;
			$time = $time->createFromFormat('H:i:s', $info->tiempo);
			
			//EDIT
			if($info->id > 0){
				$formula = $repoFormula->find($info->id);
				
				$formula->setIdOperacion($opeObj);
				$formula->setIdArticulo($articulo[0]);
				$formula->setTiempo($time);
				
				//PERSIST
				$em->persist($formula);
				$em->flush();
			}else{
				//NEW
				$formula = new OperacionesFormula();
				
				$formula->setIdOperacion($opeObj);
				$formula->setIdArticulo($articulo[0]);
				$formula->setTiempo($time);
				
				//PERSIST
				$em->persist($formula);
				$em->flush();	
			}	
			
			//RESPONSE
			echo json_encode(array(
				'success' => true,
				'items' => array(
					'id' => $formula->getId(),
					'idOperacion' => $ope[0],
					'tiempo' => $time 
				)
			));	
		}catch(\Doctrine\ORM\ORMException $e){
			$this->get('logger')->error($e->getMessage());
			echo json_encode(array(
				'success' => false,
				'msg' => $e->getMessage()
			));
		}				
	}

	
}












