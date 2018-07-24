<?php
namespace Mbp\ProduccionBundle\Entity;

/**
 * PedidoClientesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PedidoClientesRepository extends \Doctrine\ORM\EntityRepository
{
	
	public function listarPedidos()
	{
		try{
			$em = $this->getEntityManager();
			$repo = $em->getRepository('MbpProduccionBundle:PedidoClientes');
			
			$qb = $repo->createQueryBuilder("p")
				->select("DATE_FORMAT(p.fechaPedido, '%d/%m/%Y') as fechaPedido, p.oc, p.autEntrega, p.id as idPedido,
					c.rsocial as clienteDesc,
					d.cantidad,	DATE_FORMAT(d.fechaProg, '%d/%m/%Y') as fechaProgramacion, d.entregado, d.descripcion, d.id as idDetalle,
					cod.codigo")
				->leftJoin('p.cliente', 'c')
				->leftJoin('p.detalleId', 'd')
				->leftJoin('d.codigo', 'cod')
				->where('d.inactivo = 0')				
				->andWhere('p.inactivo = 0')	
				->getQuery()
				->getArrayResult();
			
			return $qb;
			
		}catch(\Doctrine\ORM\ORMException $e){
			$this->get('logger')->error($e->getMessage());
			echo json_encode(array(
				'success' => false,
				'msg' => $e->getMessage()
			));
		}
	}

	public function pedidosPorArticuloCliente($codigo, $idCliente)
	{
		$em = $this->getEntityManager();
		$repo = $em->getRepository('MbpProduccionBundle:PedidoClientes');

		$qb = $repo->createQueryBuilder('p')
			->select('p.oc, p.id as pedidoNum, art.codigo, det.entregado, det.cantidad, (det.cantidad - det.entregado) AS pendiente')
			->join('p.detalleId', 'det')
			->join('det.codigo', 'art')
			->where('art.codigo = :cod')
			->andWhere('p.cliente = :cliente')
			->andWhere('det.inactivo = 0')
			->andWhere('p.inactivo = 0')	
			->setParameter('cliente' , $idCliente)
			->setParameter('cod' , $codigo)
			->getQuery()
			->getArrayResult();

		return $qb;

	}
	
	public function listarPedidosClienteCodigo($idCliente, $codigo){		
		$em = $this->getEntityManager();
		$repo = $em->getRepository('MbpProduccionBundle:PedidoClientes');
		
		$qb = $repo->createQueryBuilder("p")
			->select("DATE_FORMAT(p.fechaPedido, '%d/%m/%Y') as fechaPedido, p.oc, p.autEntrega, p.id as idPedido,
				c.rsocial as clienteDesc,
				d.cantidad,	DATE_FORMAT(d.fechaProg, '%d/%m/%Y') as fechaProgramacion, d.entregado, d.descripcion, d.id as idDetalle,
				cod.codigo")
			->leftJoin('p.cliente', 'c')
			->leftJoin('p.detalleId', 'd')
			->leftJoin('d.codigo', 'cod')
			->where('d.inactivo = 0')
			->andWhere('c.id =:cliente')
			->andWhere('cod.codigo =:codigo')
			->andWhere('p.inactivo = 0')	
			->setParameter('cliente', $idCliente)
			->setParameter('codigo', $codigo)				
			->getQuery()
			->getArrayResult();
		
		return $qb;
	}

	public function listarPedidosCodigo($codigo){		
		$em = $this->getEntityManager();
		$repo = $em->getRepository('MbpProduccionBundle:PedidoClientes');
		
		$qb = $repo->createQueryBuilder("p")
			->select("DATE_FORMAT(p.fechaPedido, '%d/%m/%Y') as fechaPedido, p.oc, p.autEntrega, p.id as idPedido,
				c.rsocial as clienteDesc,
				d.cantidad,	DATE_FORMAT(d.fechaProg, '%d/%m/%Y') as fechaProgramacion, d.entregado, d.descripcion, d.id as idDetalle,
				cod.codigo")
			->leftJoin('p.cliente', 'c')
			->leftJoin('p.detalleId', 'd')
			->leftJoin('d.codigo', 'cod')
			->where('d.inactivo = 0')
			->andWhere('p.inactivo = 0')	
			->andWhere('cod.codigo =:codigo')
			->setParameter('codigo', $codigo)				
			->getQuery()
			->getArrayResult();
		
		return $qb;
	}
}






















