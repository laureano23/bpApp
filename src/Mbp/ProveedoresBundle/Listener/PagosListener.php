<?php
namespace  Mbp\ProveedoresBundle\Listener;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Mbp\ProveedoresBundle\Entity\OrdenPago;
use Mbp\FinanzasBundle\Entity\MovimientosBancos;
use Mbp\FinanzasBundle\Entity\DetalleMovimientosBancos;


class PagosListener
{
	//si la orden de pago consta de movimientos bancarios registramos los mismos
	public function postPersist(LifecycleEventArgs $args)
	{
		$entity = $args->getEntity();
		$entityManager = $args->getEntityManager();
		
		//cuando genero la OP, debo buscar en las cobranzas los cheques de terceros que estoy entregando y marcarlos como estado=1
		if ($entity instanceof OrdenPago) {
			$repoCobranzas = $entityManager->getRepository('MbpFinanzasBundle:CobranzasDetalle');
			$detalles = $entity->getPagoDetalleId();
			
			foreach ($detalles as $d) {
				if($d->getIdFormaPago()->getChequeTerceros() == true){
					$detalleCobranza=$repoCobranzas->findOneBy(array(
						'importe' => $d->getImporte(),
						'numero' => $d->getNumero(),
						'banco' => $d->getBanco()
						));
					$detalleCobranza->setEstado(1);
					$entityManager->persist($detalleCobranza);
				}
			}
			$entityManager->flush();
		}
	}
}

















