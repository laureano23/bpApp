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
		
		//para modificar el estado de los cheques de terceros y devolverlos a cartera		
		$ordenPago = $args->getEntity();
		if (!$ordenPago instanceof OrdenPago) {
            return;
        }
		$entityManager = $args->getEntityManager();
				
		$repoChequeTerceros = $entityManager->getRepository('MbpFinanzasBundle:CobranzasDetalle');
		$detalleImputados = $ordenPago->getPagoDetalleId();
		
		foreach ($detalleImputados as $detalle) {
			if($detalle->getIdFormaPago()->getChequeTerceros()){
				$cheque = $repoChequeTerceros->findOneBy(
					array('banco' => $detalle->getBanco(), 'numero' => $detalle->getNumero())
				);
				$cheque->setEstado(0);
			}
		}
		
		$entityManager->flush();
	}
}

















