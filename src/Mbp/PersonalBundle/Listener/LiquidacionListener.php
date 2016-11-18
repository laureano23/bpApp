<?php
namespace Mbp\PersonalBundle\Listener;

use Mbp\PersonalBundle\Entity\Recibos;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mbp\PersonalBundle\Entity\CuentaEmpleados;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\Container;

class LiquidacionListener extends Controller
{
	protected $requestStack;
	protected $cont;
	
	public function __construct(RequestStack $requestStack, Container $cont)
	{
		$this->requestStack = $requestStack;
		$this->cont = $cont;
	}
	
	public function prePersist(LifecycleEventArgs $args)	
	{
		$entity = $args->getEntity();
		

		$calculoClass = $this->cont->get('calculoConceptos');
		if($entity instanceof Recibos){
			
			$personalRepo = $em->getRepository('MbpPersonalBundle:Personal');
									
			$mes = $this->requestStack->getCurrentRequest()->request->get('mes');
			$anio = $this->requestStack->getCurrentRequest()->request->get('anio');
			$periodo = $this->requestStack->getCurrentRequest()->request->get('periodo');
			$idP = $this->requestStack->getCurrentRequest()->request->get('idP');
			$compensatorio = $this->requestStack->getCurrentRequest()->request->get('compensatorio');
			$empleado = $personalRepo->find($idP); 
			
			$totalRemunerativos = $calculoClass->totalRemunerativos($idP, $periodo, $mes, $anio, $compensatorio);
			$descuentos = $calculoClass->liquidaDescuentos($empleado, $periodo, $mes, $anio, $compensatorio);
			$neto = $totalRemunerativos - $descuentos;			
			
			$cuentaEmpleados = new CuentaEmpleados();
			$cuentaEmpleados->setPeriodo($entity->getPeriodo());
			$cuentaEmpleados->setMes($entity->getMes());
			$cuentaEmpleados->setAnio($entity->getAnio());
			$cuentaEmpleados->setNeto($neto);
			$cuentaEmpleados->setIdPersonal($empleado);
			$cuentaEmpleados->setConcepto($calculoClass->descripcionPeriodo($periodo));
			
			$em->persist($cuentaEmpleados);
			$this->needsFlush = true;
		}
	}
	
	public function postFlush(PostFlushEventArgs $eventArgs)
	{
	    if($this->needsFlush) {
	        $this->needsFlush = false;
	        $eventArgs->getEntityManager()->flush();
	    }
	}
}

