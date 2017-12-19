<?php

namespace Mbp\PersonalBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Mbp\ProduccionBundle\Entity;

/**
 * PersonalRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PersonalRepository extends EntityRepository
{
	public function listarPersonal()
	{
		$em = $this->getEntityManager();
		$dql = 'SELECT p.idP, p.nombre FROM MbpPersonalBundle:Personal p
			WHERE p.inactivo = 0
			ORDER BY p.nombre ASC';
		
		try{
			$reg = $em->createQuery($dql);
			$rs = $reg->getArrayResult();
			
			return $rs;
			
		}catch(\Doctrine\ORM\ORMException $e){
			$this->get('logger')->error($e->getMessage());
		}
	}
	
	public function fullListaPersonal($idPersonal)
	{
		$em = $this->getEntityManager();
		$repo = $em->getRepository('MbpPersonalBundle:Personal');
		
		try{
			$qb = $repo->createQueryBuilder('p')
					->select("	p.idP,
								p.nombre,
								p.direccion,
								p.telefonos,
								p.cPostal,
								p.documentoTipo,
								p.documentoNum,
								p.estado,
								p.nacionalidad,
								p.obraSocial,
								p.observaciones,
								DATE_FORMAT(p.fechaIngreso, '%d/%m/%Y') as fechaIngreso,
								DATE_FORMAT(p.fechaEgreso, '%d/%m/%Y') as fechaEgreso,
								DATE_FORMAT(p.fechaNacimiento, '%d/%m/%Y') as fechaNacimiento,
								p.tarea,
								p.periodo,
								p.compensatorio,
								p.cuil,
								p.tipoContratacion,
								p.antiguedad,
								p.antPorcentaje,
								p.talleCamisa,
								p.tallePantalon,
								p.talleCalzado,
								loc.id as localidad,
								depto.id as departamento,
								prov.id as provincia,
								cat.id as categoria,
								cat.salario as salario,
								sind.id as sindicato,
								sec.id as sector,
								p.liquidaPorLote,
								p.legajo,
								p.liquidaPremio,
								p.liquidaCalorias
								")
					->leftJoin('p.localidad', 'loc')
					->leftJoin('loc.departamentoId', 'depto')
					->leftJoin('depto.provinciaId', 'prov')
					->leftJoin('p.categoria', 'cat')
					->leftJoin('cat.idSindicato', 'sind')
					->leftJoin('p.sector', 'sec')
					->where('p.idP = :idP')
					->setParameter('idP', $idPersonal)
					->getQuery()
					->getResult();
			
			$datosFijosQuery = $repo->createQueryBuilder('p')
					->select("cod.id as codigo, cod.descripcion, cod.importe")
					->leftJoin('p.datosFijos', 'dat')
					->leftJoin('dat.codigo_id', 'cod')
					->where('p.idP = :idP')
					->setParameter('idP', $idPersonal)				
					->getQuery()
					->getArrayResult();		
			
			$i=0;
			foreach ($datosFijosQuery as $fijos) {
				$qb[0]['datosFijos'][$i] = $fijos;
				$i++;
			}
			echo json_encode($qb[0]);
			return;			
				
		}catch(\Doctrine\ORM\ORMException $e){
			$this->get('logger')->error($e->getMessage());
		}
	}
	
	/*
	 * NUEVO EMPLEADO
	 * @param $data	| jsonData
	 * return		| json msg 
	 */	
	public function crearEmpleado($data, $validator)
	{
		$em = $this->getEntityManager();
		$repoPersonal = $em->getRepository('MbpPersonalBundle:Personal');
		$empleado = 0;
		
		/*hack
		 * */
		 if(!isset($data->observaciones)){
		 	$data->{'observaciones'} = "";
		 }
		 /**/
		
		try{
			if($data->idP > 0){
				$empleado = $repoPersonal->find($data->idP); 
				
				/*SE VALIDA SI EL EMPLEADO ESTA INACTIVO*/
				if($empleado->getInactivo() == true){
					throw new \Exception('El empleado se encuentra inactivo, contacte al administrador');					
				}
			}else{
				$empleado = new Personal();	
			}
				
		
			//SECTOR
			$repoSector = $em->getRepository('MbpProduccionBundle:Sectores');
			$sector = $repoSector->find($data->sector);
			//LOCALIDAD
			$repoLocalidad = $em->getRepository('MbpPersonalBundle:Localidades');
			$localidad = $repoLocalidad->find($data->localidad);
			//CATEGORIA
			$repoCategoria = $em->getRepository('MbpPersonalBundle:Categorias');
			$categoria = $repoCategoria->find($data->categoria);	
			
			/*
			 * VALIDACION DE DNI 
			 */		
			$dni = $repoPersonal->findByDocumentoNum($data->documentoNum);
			if($dni && $data->idP == 0){
				echo json_encode(array(
					'success' => false,
					'msg' => 'El N° de documento ingresado ya existe'
				));
				return;
			}
			
			$compensatorio=0;
			if(property_exists($data, 'compensatorio')){
				$compensatorio = $data->compensatorio;
			}else{
				$compensatorio = $empleado->getCompensatorio();
			}
									
			$empleado->setNombre($data->nombre);
			$empleado->setSector($sector);
			$empleado->setLocalidad($localidad);
			$empleado->setCategoria($categoria);
			$empleado->setDireccion($data->direccion);
			$empleado->setTelefonos($data->telefonos);
			$empleado->setcPostal($data->cPostal);
			$empleado->setdocumentoTipo($data->documentoTipo);
			$empleado->setdocumentoNum($data->documentoNum);
			$empleado->setEstado($data->estado);
			$empleado->setfechaIngreso(\DateTime::createFromFormat('d/m/Y', $data->fechaIngreso));
			$data->fechaEgreso ? $empleado->setfechaEgreso(\DateTime::createFromFormat('d/m/Y', $data->fechaEgreso)) : '';			
			$empleado->setTarea($data->tarea);
			$empleado->setPeriodo($data->periodo);
			$empleado->setCompensatorio((float)$compensatorio);
			$empleado->setCuil((int)$data->cuil);
			$empleado->setTipoContratacion($data->tipoContratacion);
			if($data->antiguedad === 0){
				$empleado->setAntiguedad(false);
			}else{
				$empleado->setAntiguedad(true);
			} 
			$empleado->setantPorcentaje($data->antPorcentaje);
			$data->liquidaPorLote === 0 ? $empleado->setLiquidaPorLote(false) : $empleado->setLiquidaPorLote(true);
			$empleado->setLegajo($data->legajo);
			$empleado->setFechaNacimiento(\DateTime::createFromFormat('d/m/Y', $data->fechaNacimiento));
			$empleado->setObraSocial($data->obraSocial);
			$empleado->setNacionalidad($data->nacionalidad);
			$empleado->setObservaciones($data->observaciones);
			$data->tallePantalon == 0 ? $empleado->setTallePantalon(null) : $empleado->setTallePantalon($data->tallePantalon);
			$data->talleCamisa == 0 ? $empleado->setTalleCamisa(null) : $empleado->setTalleCamisa($data->talleCamisa);
			$data->talleCalzado == 0 ? $empleado->setTalleCalzado(null) : $empleado->setTalleCalzado($data->talleCalzado);
			$data->liquidaCalorias === 0 ? $empleado->setLiquidaCalorias(false) : $empleado->setLiquidaCalorias(true);
			$data->liquidaPremio === 0 ? $empleado->setLiquidaPremio(false) : $empleado->setLiquidaPremio(true);
			
			//VALIDO LOS DATOS DEL RECIBO
			
			$errors = $validator->validate($empleado);
			
			if(count($errors) > 0){
				$listErr="";
				
				$i = 0; 
				foreach ($errors as $err) {
					$path= $err->getPropertyPath();
					$listErr[$i]['id'] = $path;
					$listErr[$i]['msg'] = $err->getMessage();
					$i++;
				}
				
				return array(
						'success' => false,	
						'tipo' => 'validacion',
						'errors' => $listErr		
				);				
			}
			
						
			$em->persist($empleado);
			$em->flush();
			
			return array(
				'success' => true,
				'items' => array(
					'idP' => $empleado->getIdp(),
					'nombre' => $data->nombre,
					'cPostal' => $data->cPostal,
					'categoria' => $data->categoria,
					'sindicato' => $data->sindicato,
					'salario' => $categoria->getSalario(),
					'compensatorio' => $compensatorio,
					'departamento' => $data->departamento,
					'direccion' => $data->direccion,
					'documentoNum' => $data->documentoNum,
					'documentoTipo' => $data->documentoTipo,
					'estado' => $data->estado,
					'fechaEgreso' => $data->fechaEgreso,
					'fechaIngreso' => $data->fechaIngreso,
					'localidad' => $data->localidad,
					'periodo' => $data->periodo,
					'provincia' => $data->provincia,
					'tarea' => $data->tarea,
					'sector' => $data->sector,
					'telefonos' => $data->telefonos,
					'cuil' => $data->cuil,
					'tipoContratacion' => $data->tipoContratacion,
					'observaciones' => $data->observaciones
				)
			);			
		}catch(\Exception $e){
			return array(
				'success' => false,	
				'msg' => $e->getMessage()	
			);	
		}
	}
	
	/*
	 * BORRA EMPLEADO
	 * @param $idP	| int
	 * 
	 */
	public function eliminarEmpleado($idP)
	{
		$em = $this->getEntityManager();
		$repoPersonal = $em->getRepository('MbpPersonalBundle:Personal');
		
		try{
			$empleado = $repoPersonal->find($idP);
			$empleado->setInactivo(true);
			$em->flush();
			
			echo json_encode(array(
				'success' => true
			));			
		}catch(\Doctrine\ORM\ORMException $e){
			$this->get('logger')->error($e->getMessage());
		}
	}
	
	/*
	 * LISTA DATOS FIJOS DE SUELDOS DEL EMPLEADO
	 * @param $idP	| int
	 */
	public function listDatoFijoPersona($idP)
	{
		$em = $this->getEntityManager();
		
		$dql = 'SELECT p, categoria, concepto, datos FROM MbpPersonalBundle:Personal p					
		 			JOIN p.categoria categoria
		 			JOIN p.datosFijos concepto
					JOIN concepto.codigo_id datos
					WHERE p.idP = :idPersonal
					AND p.inactivo != 1';
		
		$res = $em->createQuery($dql);
		$res->setParameter('idPersonal', $idP);
		$fijos = $res->getArrayResult();
			
		if ($fijos){
			$datosFijos = $fijos[0]['datosFijos'];
			
			$arrayFijos = array();
			$i = 0;
			
			foreach ($datosFijos as $aux) {
				$arrayFijos[$i]['datosFijos']['idP'] = $fijos[0]['idP'];
				$arrayFijos[$i]['datosFijos']['id'] = $aux['codigo_id']['id'];
				$arrayFijos[$i]['datosFijos']['codigo'] = $aux['codigo_id']['id'];
				$arrayFijos[$i]['datosFijos']['descripcion'] = $aux['codigo_id']['descripcion'];
				$arrayFijos[$i]['datosFijos']['cantidad'] = $aux['codigo_id']['importe'];
				$arrayFijos[$i]['datosFijos']['importe'] = $aux['codigo_id']['importe'];			
				$i++;
			}
		}else{
			$fijos = NULL;
		}
		
		if($fijos[0] != NULL){
			echo json_encode(array(
				'success' => true,
				'items' => $arrayFijos	
			));
		}else{
			echo json_encode(array(
				'success' => true,
				'items' => ''
				)				
			);
		}
	}

	public function listarPorSector($sector)
	{
		$em = $this->getEntityManager();
		$repoPersonal = $em->getRepository('MbpPersonalBundle:Personal');
		$repoSec = $em->getRepository('MbpProduccionBundle:Sectores');
		
		$sector = $repoSec->findOneByDescripcion($sector);
		
		$query = $repoPersonal->createQueryBuilder('p')
			->select('p.nombre, p.idP')
			->where('p.sector = :sector')
			->setParameter('sector', $sector)
			->getQuery()
			->getArrayResult();
			
		return $query;
	}
}




























