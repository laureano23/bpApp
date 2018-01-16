<?php

namespace Mbp\PersonalBundle\Entity;

/**
 * CodigoSueldosRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CodigoSueldosRepository extends \Doctrine\ORM\EntityRepository
{
	/*
	 * @param $esVariable	| boolean 
	 */
	  
	public function listarCodigos($esVariable, $idP)
	{
		$em = $this->getEntityManager();
		$repo = $em->getRepository('MbpPersonalBundle:CodigoSueldos');
		$repoPersonal = $em->getRepository('MbpPersonalBundle:Personal');
		
		try{
			/*
			 * SI ESTAMOS LIQUIDANDO UN RECIBO CONSULTA SEGUN EL EMPLEADO SU VALOR DE CATEGORIA
			 */
			$empleado = 0;
			$empleadoSueldo = 0;
			
			if($idP > 0){
				$empleado = $repoPersonal->find($idP);
				$empleadoSueldo = $empleado->getCategoria()->getSalario();
			}
			
			
			$query = 0;
			/*
			 * CONSULTA POR CODIGOS VARIABLES
			 */ 
			if($esVariable == 1){
				$dql = 'SELECT codigos FROM MbpPersonalBundle:CodigoSueldos codigos
					WHERE codigos.inactivo = 0
					AND codigos.variable = :esVariable';
				$rs = $query = $em->createQuery($dql);
				$rs->setParameter('esVariable', $esVariable);
			}else{
				/*
				 * CONSULTA POR TODOS LOS CODIGOS 
				 */
				$qb = $repo->createQueryBuilder('c')
					->select('c, p')
					->leftJoin('c.periodo', 'p')
					->where('c.inactivo = 0')
					->getQuery();
			}			
			
			$res = $qb->getArrayResult();
			
			$rep = array();
			$i = 0;
			foreach($res as $resu){
				$rep[$i]['id'] = $resu['id'];
				$rep[$i]['descripcion'] = $resu['descripcion'];
				$rep[$i]['unidad'] = $resu['unidad'];
				$rep[$i]['remunerativo'] = $resu['remunerativo'] == 1 ? 1 : 0;
				$rep[$i]['noRemunerativo'] = $resu['noRemunerativo'] == 1 ? 1 : 0;
				$rep[$i]['descuento'] = $resu['descuento'] == 1 ? 1 : 0;
				$rep[$i]['asignacion'] = $resu['asignacion'] == 1 ? 1 : 0;
				$rep[$i]['fijo'] = $resu['fijo'] == 1 ? 1 : 0;
				$rep[$i]['inactivo'] = $resu['inactivo'];	
				foreach ($resu['periodo'] as $periodo) {
					$periodo['numero'] == 1 ? $rep[$i]['quincena1'] = 1 : '';
					$periodo['numero'] == 2 ? $rep[$i]['quincena2'] = 1 : '';
					$periodo['numero'] == 3 ? $rep[$i]['mes'] = 1 : '';
					$periodo['numero'] == 4 ? $rep[$i]['vacaciones'] = 1 : '';
					$periodo['numero'] == 5 ? $rep[$i]['sac'] = 1 : '';
					$periodo['numero'] == 6 ? $rep[$i]['otros'] = 1 : '';
					$periodo['numero'] == 7 ? $rep[$i]['premios1'] = 1 : '';
					$periodo['numero'] == 8 ? $rep[$i]['premios2'] = 1 : '';
					$periodo['numero'] == 9 ? $rep[$i]['liquidacionFinal'] = 1 : '';
				}					
				$rep[$i]['importe'] = $resu['importe'];
				$rep[$i]['empleadoSueldo'] = $empleadoSueldo;
				$rep[$i]['porcentaje'] = $resu['porcentaje'];
				$rep[$i]['codigoCalculo'] = $resu['codigoCalculo'];
				$rep[$i]['codigoObservacion'] = $resu['codigoObservacion'];
				$i++;
			}
			
			echo json_encode(array(
				'items' => $rep,
				'success' => true
			));
		}catch(\Doctrine\ORM\ORMException $e){
			$this->get('logger')->error($e->getMessage());
		}
	}
	
	/*
	 * Crea o edita objeto CONCEPTO
	 */
	public function crearConcepto($jsonData)
	{
		$em = $this->getEntityManager();
		$repo = $em->getRepository('MbpPersonalBundle:CodigoSueldos');
		$repoPeriodos = $em->getRepository('MbpPersonalBundle:Periodos');
		$obj = 0;
		try{
			if($jsonData->id > 0){
				$obj = $repo->find($jsonData->id);
			}else{
				$obj = new CodigoSueldos();
			}
			
			$obj->setDescripcion($jsonData->descripcion);
			$obj->setImporte($jsonData->importe);
			$obj->setcodigoCalculo($jsonData->codigoCalculo);
			$obj->setUnidad($jsonData->unidad);
			$obj->setCodigoObservacion($jsonData->codigoObservacion);
						
			$jsonData->porcentaje > 0 ? $obj->setPorcentaje(true) : $obj->setPorcentaje(false);		
			$jsonData->remunerativo == 1 ? $obj->setRemunerativo(true) : $obj->setRemunerativo(false);
			$jsonData->descuento == 1 ? $obj->setDescuento(true) : $obj->setDescuento(false);
			$jsonData->asignacion == 1 ? $obj->setAsignacion(true) : $obj->setAsignacion(false);
			$jsonData->fijo == 1 ? $obj->setFijo(true) : $obj->setFijo(false);
			$jsonData->noRemunerativo == 1 ? $obj->setNoRemunerativo(true) : $obj->setNoRemunerativo(false);
			
			//PERIODOS					
			$quincena1 = $repoPeriodos->findByNumero(1);
			$quincena2 = $repoPeriodos->findByNumero(2);
			$mes = $repoPeriodos->findByNumero(3);
			$vacaciones = $repoPeriodos->findByNumero(4);
			$sac = $repoPeriodos->findByNumero(5);
			$otros = $repoPeriodos->findByNumero(6);
			$premios1 = $repoPeriodos->findByNumero(7);
			$premios2 = $repoPeriodos->findByNumero(8);
			$liquidacionFinal = $repoPeriodos->findByNumero(9);
			
			//\Doctrine\Common\Util\Debug::dump($liquidacionFinal);
			
			//SI ESTOY EDITANDO TRAIGO LOS PERIODOS DEL OBJETO ASOCIADOS Y LOS ELIMINO PARA CARGAR LOS NUEVOS ENVIADOS
			$periodos = $obj->getPeriodo();
			if($periodos != null){
				foreach ($periodos as $periodo) {
					$obj->removePeriodo($periodo);
				}					
			}
			
			//CARGO LOS NUEVOS PERIODOS
			$jsonData->quincena1 == 1 ? $obj->addPeriodo($quincena1[0]) : "";
			$jsonData->quincena2 == 2 ? $obj->addPeriodo($quincena2[0]) : "";
			$jsonData->mes == 3 ? $obj->addPeriodo($mes[0]) : "";
			$jsonData->vacaciones == 4 ? $obj->addPeriodo($vacaciones[0]) : "";
			$jsonData->sac == 5 ? $obj->addPeriodo($sac[0]) : "";
			$jsonData->otros == 6 ? $obj->addPeriodo($otros[0]) : "";
			$jsonData->premios1 == 7 ? $obj->addPeriodo($premios1[0]) : "";
			$jsonData->premios2 == 8 ? $obj->addPeriodo($premios2[0]) : "";
			$jsonData->liquidacionFinal == 9 ? $obj->addPeriodo($liquidacionFinal[0]) : "";
			
			$em->persist($obj);
			$em->flush();
											
			echo json_encode(array(
				'success' => true,
				'idConcepto' => $obj->getId()
			));
		}catch(\Exception $e){
			echo json_encode(array(
				'success' => false,
				'msg' => $e->getMessage()
			));
		}
	}

	public function eliminaConcepto($jsonData)
	{
		$em = $this->getEntityManager();
		$repo = $em->getRepository('MbpPersonalBundle:CodigoSueldos');
		
		try{
			$obj = $repo->find($jsonData->id);
			$obj->setInactivo(true);
			$em->persist($obj);
			$em->flush();
			
			
			echo json_encode(array(
				'success' => true
			));
		}catch(\Exception $e){
			echo json_encode(array(
				'success' => false,
				'msg' => $e->getMessage()
			));
		}
	}
}
