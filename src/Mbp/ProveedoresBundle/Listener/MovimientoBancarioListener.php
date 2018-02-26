<?php
namespace  Mbp\ProveedoresBundle\Listener;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Mbp\ProveedoresBundle\Entity\OrdenPago;
use Mbp\FinanzasBundle\Entity\MovimientosBancos;
use Mbp\FinanzasBundle\Entity\DetalleMovimientosBancos;


class MovimientoBancarioListener
{
	
	//si la orden de pago consta de movimientos bancarios registramos los mismos
	public function postPersist(LifecycleEventArgs $args)
	{
		$entity = $args->getEntity();
        if (!$entity instanceof OrdenPago) {
            return;
        }

        $entityManager = $args->getEntityManager();
        
		
		$repoBanco = $entityManager->getRepository('MbpFinanzasBundle:Bancos');
		$repoProveedor = $entityManager->getRepository('MbpProveedoresBundle:Proveedor');
        foreach ($entity->getPagoDetalleId() as $detalle) {        	
        	//AJUSTAMOS STOCK DE ARTICULO
        	if($detalle->getIdFormaPagos()->getEsBancario() == true){
        		$movBancario = new MovimientosBancos;
				$detalleMovBrio = new DetalleMovimientosBancos;        		
        		
				\Doctrine\Common\Util\Debug::dump($detalle);
				$banco = $repoBanco->find(1);
				
				$movBancario->setBanco($banco);
				$movBancario->setConceptoBancoId(NULL);
				$movBancario->setFechaMovimiento(new \DateTime);
				
				$detalleMovBrio->setFechaDiferida($detalle->getDiferido());
				$detalleMovBrio->setImporte($detalle->getImporte());
				$detalleMovBrio->setNumComprobante($detalle->getNumero());
				
				$proveedor = $repoProveedor->find($entity->getProveedorId());
				$detalleMovBrio->setProveedorId($proveedor);
				
				$movBancario->addDetallesMovimiento($detalleMovBrio);
        		$entityManager->persist($movBancario);
				$entityManager->flush();
        	}	
        }
	}
}

















