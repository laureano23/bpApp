<?php

namespace Mbp\ProduccionBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Mbp\ProduccionBundle\Entity\Ot;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * OtRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OtRepository extends EntityRepository
{
	public function readOtPaneles()
	{
		$em = $this->getEntityManager();
		$connection = $em->getConnection();
		
		$sql = 'SELECT descripcion, ot, codigo, rsocial, cantidad, apoyoTapas, prof, ancho, idCodigo
				FROM Ot
				JOIN articulos
					on Ot.idCodigo = articulos.idArticulos 
				JOIN cliente
					on Ot.idCliente = cliente.idCliente
				JOIN CalculoRad
					on Ot.idCodigo = CalculoRad.cod
				';
		$statement = $connection->prepare($sql);
		
		$statement->execute();
		$res = $statement->fetchAll();
		echo json_encode($res);
		
	}
	
    public function NewOtPanel($data)
    {
        $em = $this->getEntityManager();
		
		try{			
			/*
	         * Buscamos el objeto articulo que pasaremos a la ot
	         */
	        
	        $art = $em->getRepository('MbpArticulosBundle:Articulos')->find($data->{'idCodigo'});
			$cliente = $em->getRepository('MbpClientesBundle:Cliente')->find($data->{'idCliente'});
	        			 
	        $ot = new Ot();
	        
	        $ot->setidCodigo($art);
			$ot->setidCliente($cliente);
	        $ot->setcantidad($data->{'cantidad'});
	        
	        $em->persist($ot);
	        $em->flush();
			
			echo json_encode(array(
					'success' => true,					
				));
				        
		}catch(\Doctrine\ORM\ORMException $e){
			$this->get('logger')->error($e->getMessage());	
		}		
    }
	
	public function verificaOt($ot)
	{
		$em = $this->getEntityManager();
		
		try{
			/*
			 * Buscamos si el panel tiene un calculo generado y la ot si ya fue guardada
			 */
			$ote = $em->getRepository('MbpProduccionBundle:Ot')->findByOt($ot);
			
			if($ote){
			    echo json_encode(array(
                    'success' => false,
                    'msg' => 'Atencion esta OT ya fue ingresada'
                ));
			}else{
				echo json_encode(array(
                    'success' => true,
                ));
			}			        
		}catch(\Doctrine\ORM\ORMException $e){
			$this->get('logger')->error($e->getMessage());	
		}	
	}
	
	
	public function VerificaPanel($id)
	{
		$em = $this->getEntityManager();
		
		try{
			/*
			 * Buscamos si el panel tiene un calculo generado y la ot si ya fue guardada
			 */
			$calculo = $em->getRepository('MbpProduccionBundle:CalculoRad')->findByCod($id);
			
			if(!$calculo){
				echo json_encode(array(
					'success' => false,
					'msg' => 'Este panel no está calculado'
				));
			}else{
				echo json_encode(array(
					'success' => true,
				));
			}			        
		}catch(\Doctrine\ORM\ORMException $e){
			$this->get('logger')->error($e->getMessage());	
		}	
	}

	public function listadoOtParaCerrar()
	{
		$em = $this->getEntityManager();
		$repo = $em->getRepository('MbpProduccionBundle:Ot');
		
		$query = $repo->createQueryBuilder('o')
			->select("o.ot as otNum, DATE_FORMAT(o.fechaEmision, '%d/%m/%Y') as otEmision, c.rsocial as cliente, codigo.codigo, codigo.descripcion, o.cantidad as totalOt, DATE_FORMAT(o.fechaProg, '%d/%m/%Y') as programado, o.cantidad - o.aprobado - o.rechazado as pendiente, o.aprobado, o.rechazado")
			->join('o.idCodigo', 'codigo')
			->join('o.idCliente', 'c')
			->where('(o.cantidad - o.aprobado - o.rechazado) > 0')
			->orderBy('otNum', 'DESC')
			->getQuery()
			->getArrayResult();
			
		return $query;
	}
	
	public function listarOrdenes()
	{
		$em = $this->getEntityManager();
		$repo = $em->getRepository('MbpProduccionBundle:Ot');
		
		$query = $repo->createQueryBuilder('o')
			->select("o.otExterna as otNum, DATE_FORMAT(o.fechaEmision, '%d/%m/%Y') as otEmision,
					c.descripcion as cliente,
					codigo.codigo,
					codigo.descripcion,
					o.cantidad as totalOt,
					DATE_FORMAT(o.fechaProg, '%d/%m/%Y') as programado,
					o.otExterna,
					o.aprobado,
					o.rechazado,
					CASE WHEN o.estado = 0 THEN 'No comenzada'
					 WHEN o.estado = 1 THEN 'En proceso'
					 WHEN o.estado = 2 THEN 'Terminada'
					 WHEN o.estado = 3 THEN 'Generada'						
					 ELSE 'No comenzada' END as estado")
			->join('o.idCodigo', 'codigo')
			->join('o.sectorId', 'c')
			->where('o.otExterna IS NOT NULL')
			->orderBy('otNum', 'DESC')
			->getQuery()
			->getArrayResult();
			
		return $query;
	}
	
	public function listarOrdenesCompletas()
	{
		$em = $this->getEntityManager();
		$repo = $em->getRepository('MbpProduccionBundle:Ot');
		
		$query = $repo->createQueryBuilder('o')
			->select("o.ot as otNum, DATE_FORMAT(o.fechaEmision, '%d/%m/%Y') as otEmision,
					c.descripcion as cliente,
					codigo.codigo,
					codigo.descripcion,
					o.cantidad as totalOt,
					DATE_FORMAT(o.fechaProg, '%d/%m/%Y') as programado,
					o.otExterna,
					o.aprobado,
					o.rechazado,
					CASE WHEN o.estado = 0 THEN 'No comenzada'
					 WHEN o.estado = 1 THEN 'En proceso'
					 WHEN o.estado = 2 THEN 'Terminada'	
					 WHEN o.estado = 3 THEN 'Generada'					
					 ELSE 'No comenzada' END as estado")
			->join('o.idCodigo', 'codigo')
			->join('o.sectorId', 'c')
			->orderBy('otNum', 'DESC')
			->getQuery()
			->getArrayResult();
			
		return $query;
	}
	
	public function listarOrdenesParaProgramacion($sector)
	{
		$em = $this->getEntityManager();
		$repo = $em->getRepository('MbpProduccionBundle:Ot');
		
		$query = $repo->createQueryBuilder('o')
			->select("o.ot as otNum, DATE_FORMAT(o.fechaEmision, '%d/%m/%Y') as otEmision,
					codigo.codigo,
					codigo.descripcion,
					o.cantidad as totalOt,
					DATE_FORMAT(o.fechaProg, '%d/%m/%Y') as programado,
					emisor.descripcion as sectorEmisor,
					o.aprobado,
					o.rechazado,
					CASE WHEN o.estado = 0 THEN 'No comenzada'
					 WHEN o.estado = 1 THEN 'En proceso'
					 WHEN o.estado = 2 THEN 'Terminada'	
					 WHEN o.estado = 3 THEN 'Generada'					
					 ELSE 'No comenzada' END as estado")
			->join('o.idCodigo', 'codigo')
			->join('o.sectorEmisor', 'emisor')
			->where('o.sectorId = :sector')
			->andWhere('o.estado != 2')
			->setParameter('sector', $sector)
			->orderBy('o.fechaProg', 'ASC')
			->getQuery()
			->getArrayResult();
			
		return $query;
	}
	
	public function listarOrdenesExternas()
	{
		$em = $this->getEntityManager();
		$repo = $em->getRepository('MbpProduccionBundle:Ot');
		
		$query = $repo->createQueryBuilder('o')
			->select("o.ot as otNum, o.otExterna, DATE_FORMAT(o.fechaEmision, '%d/%m/%Y') as otEmision,
					codigo.codigo,
					codigo.descripcion,
					o.cantidad as totalOt,
					DATE_FORMAT(o.fechaProg, '%d/%m/%Y') as programado,
					emisor.descripcion as sectorEmisor,
					o.aprobado,
					o.rechazado,
					CASE WHEN o.estado = 0 THEN 'No comenzada'
					 WHEN o.estado = 1 THEN 'En proceso'
					 WHEN o.estado = 2 THEN 'Terminada'	
					 WHEN o.estado = 3 THEN 'Generada'					
					 ELSE 'No comenzada' END as estado")
			->join('o.idCodigo', 'codigo')
			->join('o.sectorEmisor', 'emisor')
			->where('o.otExterna > :ot')
			->andWhere('o.estado != 2')
			->setParameter('ot', 0)
			->orderBy('o.fechaProg', 'ASC')
			->getQuery()
			->getArrayResult();
			
		return $query;
	}
	
	public function listarOrdenesParaProgramacionPorEmisor($sector)
	{
		$em = $this->getEntityManager();
		$repo = $em->getRepository('MbpProduccionBundle:Ot');
		
		$query = $repo->createQueryBuilder('o')
			->select("o.ot as otNum, DATE_FORMAT(o.fechaEmision, '%d/%m/%Y') as otEmision,
					codigo.codigo,
					codigo.descripcion,
					o.cantidad as totalOt,
					DATE_FORMAT(o.fechaProg, '%d/%m/%Y') as programado,
					receptor.descripcion as sectorReceptor,
					o.aprobado,
					o.rechazado,
					CASE WHEN o.estado = 0 THEN 'No comenzada'
					 WHEN o.estado = 1 THEN 'En proceso'
					 WHEN o.estado = 2 THEN 'Terminada'	
					 WHEN o.estado = 3 THEN 'Generada'					
					 ELSE 'No comenzada' END as estado")
			->join('o.idCodigo', 'codigo')
			->join('o.sectorId', 'receptor')
			->where('o.sectorEmisor = :sector')
			->setParameter('sector', $sector)
			->orderBy('o.fechaProg', 'ASC')
			->getQuery()
			->getArrayResult();
			
		return $query;
	}

	public function listarOTEnProceso($codigo, $sector){
		$em = $this->getEntityManager();
		$repo = $em->getRepository('MbpProduccionBundle:Ot');
		
		$query = $repo->createQueryBuilder('o')
			->select("o.ot as otNum, DATE_FORMAT(o.fechaEmision, '%d/%m/%Y') as otEmision,
					codigo.codigo,
					codigo.descripcion,
					o.cantidad as totalOt,
					DATE_FORMAT(o.fechaProg, '%d/%m/%Y') as programado,
					receptor.descripcion as sectorReceptor,
					o.aprobado,
					o.rechazado,
					CASE WHEN o.estado = 0 THEN 'No comenzada'
					 WHEN o.estado = 1 THEN 'En proceso'
					 WHEN o.estado = 2 THEN 'Terminada'		
					 WHEN o.estado = 3 THEN 'Generada'				
					 ELSE 'No comenzada' END as estado")
			->join('o.idCodigo', 'codigo')
			->join('o.sectorId', 'receptor')
			->where('receptor = :sector')
			->andWhere('codigo.codigo = :codigo')
			->andWhere('o.estado < 2')
			->setParameter('sector', $sector)
			->setParameter('codigo', $codigo)
			->orderBy('o.fechaProg', 'ASC')
			->getQuery()
			->getArrayResult();
			
		return $query;
	}
}
