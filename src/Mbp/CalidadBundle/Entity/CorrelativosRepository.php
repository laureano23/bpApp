<?php

namespace Mbp\CalidadBundle\Entity;

use Doctrine\ORM\EntityRepository;

use Mbp\ArticulosBundle\Entity\Articulos;
use Mbp\ClientesBundle\Entity\Cliente;

class CorrelativosRepository extends EntityRepository
{
	public function listarCorrelativos()
	{
		$date = new \DateTime('2013-03-11');
		try{
			$em = $this->getEntityManager();			
			$repo=$em->getRepository('MbpCalidadBundle:Correlativos');
			
			$res = $repo->createQueryBuilder('c')
				->select('c.cant, c.fecha, c.idCorrelativos, c.numCorrelativo, c.obs, enf.ot as otEnf, ot1.ot as ot1panel, ot2.ot as ot2panel, ot3.ot as ot3panel, ot4.ot as ot4panel')
				->leftJoin('c.ot_Enf', 'enf')
				->leftJoin('c.ot1panel', 'ot1')
				->leftJoin('c.ot2panel', 'ot2')
				->leftJoin('c.ot3panel', 'ot3')
				->leftJoin('c.ot4panel', 'ot4')
				->getQuery()
				->getArrayResult();
			
			echo json_encode(array(
				'data' => $res
			));
		}catch(\Doctrine\ORM\ORMException $e){
			$this->get('logger')->error($e->getMessage());
		}
	}	
	
	public function newCorrelativo($info)
	{
		/*
		 * Decodificamos el json proveniente de Extjs y creamos un array donde devolveremos la respuesta al cliente
		 */
		$data = json_decode($info);
		$finaldata = array();
				
		$em = $this->getEntityManager();
		$repo=$em->getRepository('MbpProduccionBundle:Ot');
		
		/*
		 * Comprobamos si el correlativo ya existe en nuestra tabla
		 */
		$num = $data->numCorrelativo;
		$q = "SELECT c FROM MbpCalidadBundle:Correlativos c WHERE c.numCorrelativo = :num";
		$query = $em->createQuery($q)
					->setParameter('num', $num);
		
		$exist = $query->getResult();
		
		/*
		 * Si el correlativo ya existe en la tabla enviamos un msj de advertencia al cliente
		 */
		
		if($exist){
			echo json_encode(array(
				'success' => false,
				'message' => 'Este numero correlativo ya existe'
			));
		}else{			
			/*
			 *Creamos un objeto date y le pasamos la fecha recuparada desde el cliente para luego insertar en la base 
			 */			
			
			$date = \DateTime::createFromFormat('d-m-y', $data->{'fecha.date'});
			
			/*
			 *Seteamos nuestro nuevo objeto 
			 **/		
			$corre = new Correlativos();
			$corre->setnumCorrelativo($data->numCorrelativo);						
			$corre->setcant($data->cant);
			$corre->setfecha($date);			
			$corre->setOtEnf($repo->find($data->otEnf));			
			$corre->setOt1panel($repo->find($data->ot1panel));
			$corre->setOt2panel($repo->find($data->ot2panel));
			$corre->setOt3panel($repo->find($data->ot3panel));
			$corre->setOt4panel($repo->find($data->ot4panel));
			$corre->setObs($data->obs);
			
			/*
			 *Persistimos el objeto a la base 
			*/	
			$em->persist($corre);		
			$em->flush();
			
			/*
			 *Devolvemos al cliente la info insertada en la bd 
			 **/
			$finaldata['idCorrelativos'] = $corre->getIdCorrelativos();
			$finaldata['numCorrelativo'] = $data->numCorrelativo;
			$finaldata['cant'] = $data->cant;
			$finaldata['fecha'] = array('date' => $date);
			$finaldata['otEnf'] = $data->otEnf;			
			$finaldata['ot1panel'] = $data->ot1panel;
			$finaldata['ot2panel'] = $data->ot2panel;
			$finaldata['ot3panel'] = $data->ot3panel;
			$finaldata['ot4panel'] = $data->ot4panel;
			$finaldata['obs'] = $data->obs;
			$finaldata['clientId'] = 'ext-record-'.$corre->getIdCorrelativos();
			
			echo json_encode(array(
				'success' => true,
				'data' => $finaldata
			));	
		}
	}

	public function updateCorrelativo($info)
	{
		/*
		 * Decodificamos el json proveniente de Extjs y creamos un array donde devolveremos la respuesta al cliente
		 */
		$data = json_decode($info);
		$finaldata = array();
				
		$em = $this->getEntityManager();
		$repo=$em->getRepository('MbpProduccionBundle:Ot');
				
		/*
		 * Creamos el objeto fecha con el string del cliente
		 **/
		$date = \DateTime::createFromFormat('d-m-y', $data->{'fecha.date'});
				
		/*
		 *Buscamos el id del registro a editar 
		 */			
		$id = $data->idCorrelativos;
		$reg = $em->getRepository('MbpCalidadBundle:Correlativos')->find($id);		
		
		/*
		 * Seteamos los datos actualizados
		 */
		 
		 try{
		 $reg->setnumCorrelativo($data->numCorrelativo);
		 $reg->setcant($data->cant);
		 $reg->setfecha($date);
		 $reg->setOtEnf($repo->find($data->otEnf));		 
		 $reg->setOt1panel($repo->find($data->ot1panel));
		 $reg->setOt2panel($repo->find($data->ot2panel));
		 $reg->setOt3panel($repo->find($data->ot3panel));
		 $reg->setOt4panel($repo->find($data->ot4panel));
		 $reg->setObs($data->obs);
		 
		 /*
		  * Ingresamos todo a la bd
		  */			 			 
		 $em->persist($reg);
		 
		 $em->flush();	
		 }catch (\Exception $e){
		 	switch (get_class($e)){
				case 'Doctrine\DBAL\DBALException':
					echo json_encode(array(
						'success' => false,
						'message' => 'Registro correlativo repetido'
					));
					break;
				default: 
					/*
					 *Devolvemos al cliente la info insertada en la bd 
					 **/
					$finaldata['idCorrelativos'] = $reg->getIdCorrelativos();
					$finaldata['numCorrelativo'] = $data->numCorrelativo;
					$finaldata['cant'] = $data->cant;
					$finaldata['fecha'] = array('date' => $date);
					$finaldata['otEnf'] = $data->otEnf;					
					$finaldata['ot1panel'] = $data->ot1panel;
					$finaldata['ot2panel'] = $data->ot2panel;
					$finaldata['ot3panel'] = $data->ot3panel;
					$finaldata['ot4panel'] = $data->ot4panel;
					$finaldata['obs'] = $data->obs;
					$finaldata['clientId'] = 'ext-record-'.$reg->getIdCorrelativos();
					 
					 /*
					  * Damos respuesta al cliente
					  */
					  echo json_encode(array(
						'success' => true,
						'data' => $finaldata
						));
				break;
		 	}
		 }
	}
	
	public function destroyCorrelativo($info)
	{
		/*
		 * Decodificamos el array enviado desde el cliente
		 */
		$data = json_decode($info);						
		$em = $this->getEntityManager();
		
		/*
		 * Buscamos el id en la BD
		 */
		 $reg = $em->getRepository('MbpCalidadBundle:Correlativos')->find($data->idCorrelativos);
		 
		 /*
		  * Eliminamos el id seleccionado
		  */
		  $em->remove($reg);
		  $em->flush();
	}

	public function buscarCorrelativo($numCorrelativo){
		$repo = $this->getEntityManager()->getRepository('MbpCalidadBundle:Correlativos');
		$data = $repo->createQueryBuilder('c')
			->select('art.codigo, art.descripcion')
			->leftJoin('c.ot_Enf', 'radiador')
			->leftJoin('radiador.idCodigo', 'art')
			->where('c.numCorrelativo = :num')
			->setParameter('num', $numCorrelativo)
			->getQuery()
			->getArrayResult();

		return $data;
	}
}








