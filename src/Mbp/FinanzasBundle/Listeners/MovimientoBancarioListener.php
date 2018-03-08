<?php
namespace  Mbp\FinanzasBundle\Listeners;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Mbp\ProveedoresBundle\Entity\OrdenPago;
use Mbp\FinanzasBundle\Entity\MovimientosBancos;
use Mbp\FinanzasBundle\Entity\DetalleMovimientosBancos;
use Mbp\FinanzasBundle\Entity\Cobranzas;


class MovimientoBancarioListener
{
	
	//si la orden de pago consta de movimientos bancarios registramos los mismos
	public function postPersist(LifecycleEventArgs $args)
	{
		$entity = $args->getEntity();
		$entityManager = $args->getEntityManager();
		$repoProveedor = $entityManager->getRepository('MbpProveedoresBundle:Proveedor');
        if ($entity instanceof OrdenPago) {
           foreach ($entity->getPagoDetalleId() as $detalle) {
	        	if(($detalle->getIdFormaPagos()->getConceptoBancoId())){
	        		$movBancario = new MovimientosBancos;
					$detalleMovBrio = new DetalleMovimientosBancos;        		
	        							
					$movBancario->setCuentaBancaria($detalle->getCuentaId());
					$movBancario->setConceptoBancoId($detalle->getIdFormaPagos()->getConceptoBancoId());
					$movBancario->setFechaMovimiento(new \DateTime);
					
					$detalleMovBrio->setFechaDiferida($detalle->getDiferido());
					$detalleMovBrio->setImporte($detalle->getImporte());
					$detalleMovBrio->setNumComprobante($detalle->getNumero());
					
					$proveedor = $repoProveedor->find($entity->getProveedorId());
					$detalleMovBrio->setProveedorId($proveedor);
					
					$detalle->setMovBancoId($detalleMovBrio);
					
					$movBancario->addDetallesMovimiento($detalleMovBrio);
	        		$entityManager->persist($movBancario);
					$entityManager->persist($detalle);
					$entityManager->flush();
	        	}	
	        } 
        }

	    if($entity instanceof Cobranzas){
	    	$entity = $args->getEntity();
			$entityManager = $args->getEntityManager();
			$repoCliente = $entityManager->getRepository('MbpClientesBundle:Cliente');
	    	foreach ($entity->getCobranzaDetalleId() as $detalle) {
	        	if(($detalle->getFormaPagoId()->getConceptoBancoId())){
	        		$movBancario = new MovimientosBancos;
					$detalleMovBrio = new DetalleMovimientosBancos;
					
					$movBancario->setCuentaBancaria($detalle->getCuentaId());
					$movBancario->setConceptoBancoId($detalle->getFormaPagoId()->getConceptoBancoId());
					$movBancario->setFechaMovimiento(new \DateTime);
					
					$detalleMovBrio->setFechaDiferida($detalle->getVencimiento());
					$detalleMovBrio->setImporte($detalle->getImporte());
					$detalleMovBrio->setNumComprobante($detalle->getNumero());
					
					$cliente = $repoCliente->find($entity->getClienteId());
					$detalleMovBrio->setIdCliente($cliente);
					
					$detalle->setMovBancoId($detalleMovBrio);
					
					$movBancario->addDetallesMovimiento($detalleMovBrio);
	        		$entityManager->persist($movBancario);
					$entityManager->persist($detalle);
					$entityManager->flush();
	        	}	
	        } 
	    }
	}
}

















