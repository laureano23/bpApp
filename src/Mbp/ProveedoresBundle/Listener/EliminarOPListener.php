<?php
namespace  Mbp\ProveedoresBundle\Listener;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Mbp\ProveedoresBundle\Entity\OrdenPago;
use Mbp\ProveedoresBundle\Entity\TransaccionOPFC;


class EliminarOPListener
{	
	public function preRemove(LifecycleEventArgs $args)
	{		
		$ordenPago = $args->getEntity();
		if (!$ordenPago instanceof OrdenPago) {
            return;
        }
		$entityManager = $args->getEntityManager();
				
		$repoChequeTerceros = $entityManager->getRepository('MbpFinanzasBundle:CobranzasDetalle');
		$detalleImputados = $ordenPago->getPagoDetalleId();
		
		//\Doctrine\Common\Util\Debug::dump($detalleImputados);
		foreach ($detalleImputados as $detalle) {
			if($detalle->getIdFormaPago()->getDescripcion() == "CHEQUE DE TERCEROS"){
				$cheque = $repoChequeTerceros->findOneBy(
					array('banco' => $detalle->getBanco(), 'numero' => $detalle->getNumero())
				);
				$cheque->setEstado(0);
			}
		}
		
		/*$repo = $entityManager->getRepository('MbpProveedoresBundle:TransaccionOPFC');
		$entity = $repo->findByOrdenPagoImputada($ordenPago);
		
		for ($i=0; $i < count($entity); $i++) { 
			$entityManager->remove($entity[$i]);			
		}*/
		
		$entityManager->flush();
	}
}

















