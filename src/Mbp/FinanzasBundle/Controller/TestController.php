<?php

namespace Mbp\FinanzasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Mbp\FinanzasBundle\Entity\Facturas;
use Mbp\FinanzasBundle\Entity\FacturaDetalle;

class TestController extends Controller
{
	/**
     * @Route("/test/alicuotas", name="mbp_finanzas_alicuotas", options={"expose"=true})
     */
    public function alicuotas()
    {
    	//CONSULTA PERCEPCION DE IIBB
		$iibbService = $this->get('ServiceIIBB');	//SERVICIO PARA ALICUOTAS DE IIBB
		$iibbService->setOpts(27129680933);
		$alicuotaPercepcion = $iibbService->getAlicuotaRetencion();	
		
		print_r($alicuotaPercepcion);
    }
}