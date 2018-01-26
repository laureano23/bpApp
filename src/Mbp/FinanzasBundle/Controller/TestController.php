<?php

namespace Mbp\FinanzasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class TestController extends Controller
{
	/**
     * @Route("/test/intereses", name="mbp_finanzas_intereses", options={"expose"=true})
     */
    public function alicuotas(){
    	
		$service = $this->get('InteresesResarcitorios');	
		
		//fc1
		$fc1['monto'] = 1000;
		$fc1['cbteNum'] = 1;
		$fc1['vencimiento'] = new \DateTime('+ 1 month');
		
			
		$fc2['monto'] = 50;		
		$fc2['cbteNum'] = 2;
		$fc2['vencimiento'] = new \DateTime('+ 2 weeks');
		
		$facturas[0] = $fc1;
		$facturas[1] = $fc2;
	
		
		//pagos
		$pago1['monto'] = 25;
		$pago1['vencimiento'] = new \DateTime();
		
		$pago2['monto'] = 50;
		$pago2['vencimiento'] = new \DateTime('+ 30 days');
		
		$pago3['monto'] = 1000;
		$pago3['vencimiento'] = new \DateTime('+ 60 days');
		
		$pagos[0] = $pago1;		
		$pagos[1] = $pago2;
		$pagos[2] = $pago3;
		
		
		$service->calcularIntereses($facturas, $pagos);
		$service->getIntereses();
		//new \Mbp\FinanzasBundle\Clases\InteresesResarcitorios();
		
		return new Response;
    }
	
	
}