<?php
namespace  Mbp\ProduccionBundle\Listeners;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Mbp\ProduccionBundle\Entity\Ot;

class CierreOTListener
{	
	public function postUpdate(LifecycleEventArgs $args)
	{
		$entity = $args->getEntity();

        if (!$entity instanceof Ot) {
            return;
        }

        $entityManager = $args->getEntityManager();
        $uow = $entityManager->getUnitOfWork();
        
        $cambios=$uow->getEntityChangeSet($entity);        

        if(isset($cambios['aprobado'])){ //estamos haciendo un cambio en la OT de la cantidad aprobada de piezas
            $articulo=$entity->getIdCodigo();
            $oldValue=$cambios['aprobado'][0];
            $newValue=$cambios['aprobado'][1];

            $diff=$newValue-$oldValue;
            $articulo->setStock($articulo->getStock() + $diff);
            $articulo->setFechaStock(new \DateTime());
            $entityManager->persist($articulo);
            $entityManager->flush();
        }
        return;
	}
}

















