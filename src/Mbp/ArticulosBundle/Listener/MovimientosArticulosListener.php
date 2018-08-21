<?php
namespace  Mbp\ArticulosBundle\Listener;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Mbp\ArticulosBundle\Entity\MovimientosArticulos;

class MovimientosArticulosListener
{	
	public function postPersist(LifecycleEventArgs $args)
	{
		$entity = $args->getEntity();

        if (!$entity instanceof MovimientosArticulos) {
            return;
        }

        $entityManager = $args->getEntityManager();
        
        foreach ($entity->getMovDetalleId() as $detalle) {        	
        	//AJUSTAMOS STOCK DE ARTICULO
            $articulo = $detalle->getArticuloId();
            if($entity->getTipoMovimiento() == 0){ //0 es una entrada
                $articulo->setStock($articulo->getStock() + $detalle->getCantidad());
            }else{ //1 es una salida
                $articulo->setStock($articulo->getStock() - $detalle->getCantidad());
            }            
            $articulo->setFechaStock(new \DateTime());
        	$entityManager->persist($articulo);     
        }
        $entityManager->flush();
        return;
	}
}

















