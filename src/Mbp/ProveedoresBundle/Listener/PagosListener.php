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
	public $em;
	
	public function __construct(EntityManager $em)
	{
		$this->em = $em;	
	}
	
	/*
	 * LISTENER PARA QUE AL GENERAR UN PAGO CON CHEQUE DE TERCEROS QUEDE SETEADO CON CODIGO 1 COMO ENTREGADO A PROVEEDOR
	 */	
	public function onKernelRequest(GetResponseEvent $event)
	{
		$kernel    = $event->getKernel();
        $request   = $event->getRequest();
		
		if(!$request->request->get('listener') && $request->request->get('listener') != 'nuevoPago'){
			return;
		}
				
		if(!json_decode($request->request->get('data'))){
			return;
		}
		
		$data = json_decode($request->request->get('data'));
		
		if(!is_array($data)){
			return;
		}
		
		$repoCobranzaDetalle = $this->em->getRepository('MbpFinanzasBundle:CobranzasDetalle');
		$repoFormaPago = $this->em->getRepository('MbpFinanzasBundle:FormasPagos');
		foreach ($data as $rec) {
			/* SI EL CHEQUE TIENE ID = 0 ES PORQUE ES UN CHEQUE PROPIO */
			if($rec->idCheque == 0){ return; }
			
			$formaPago = $repoFormaPago->findOneByDescripcion($rec->formaPago);
			if(!empty($formaPago) && $formaPago->getChequeTerceros() == true){
				$cheque = $repoCobranzaDetalle->find($rec->idCheque);
				$cheque->setEstado(1);
				$this->em->persist($cheque);
			}
		}
	}
	
}

















