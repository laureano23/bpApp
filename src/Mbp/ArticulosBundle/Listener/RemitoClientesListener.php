<?php
namespace  Mbp\ArticulosBundle\Listener;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Mbp\ArticulosBundle\Entity\RemitosClientes;
use Mbp\FinanzasBundle\Entity\ParametrosFinanzas;

class RemitoClientesListener
{	
	public function postPersist(LifecycleEventArgs $args)
	{
		$entity = $args->getEntity();

        if (!$entity instanceof RemitosClientes) {
            return;
        }

        $entityManager = $args->getEntityManager();
        
        foreach ($entity->getDetalleRemito() as $detalle) {        	
        	//AJUSTAMOS STOCK DE ARTICULO
        	$articulo = $detalle->getArticuloId();
            $articulo->setStock($articulo->getStock() - $detalle->getCantidad());
            $articulo->setFechaStock(new \DateTime());
        	$entityManager->persist($articulo);       
        }

        //AUMENTO EN 1 EL NUMERO DE REMITO
        $repoParametros = $entityManager->getRepository('MbpFinanzasBundle:ParametrosFinanzas');
        $param = $repoParametros->find(1);

        $param->setRemitoNum($param->getRemitoNum() + 1);

        $entityManager->persist($param); 

        $entityManager->flush();
        return;
	}
}

















