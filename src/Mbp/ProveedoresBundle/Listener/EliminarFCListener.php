<?php
namespace  Mbp\ProveedoresBundle\Listener;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Mbp\ProveedoresBundle\Entity\Factura;
use Mbp\ProveedoresBundle\Entity\TransaccionOPFC;


class EliminarFCListener
{	
	public function preRemove(LifecycleEventArgs $args)
	{		
		$factura = $args->getEntity();		//PUEDE SER FACTURA U OTRO COMPROBANTE EJ: NOTA DE CREDITO
		if (!$factura instanceof Factura) {
            return;
        }
		
		$entityManager = $args->getEntityManager();
		$repo = $entityManager->getRepository('MbpProveedoresBundle:TransaccionOPFC');
		$entity = $repo->findByFacturaImputada($factura);
		
		for ($i=0; $i < count($entity); $i++) { 
			$entityManager->remove($entity[$i]);			
		}
		
		$entityManager->flush();
	}
}

















