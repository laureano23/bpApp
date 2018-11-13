<?php

namespace Mbp\FinanzasBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Mbp\FinanzasBundle\Clases\Facturacion\Factura;

class DefaultControllerTest extends Controller
{
    /**
     * @Route("/pruebaObjetos", name="mbp_finanzas_pruebaObjetos", options={"expose"=true})
     */
    public function testIndex()
    {
        \print_r("hoasd");
        $em = $this->getDoctrine()->getManager();
        $repoFc = $em->getRepository('MbpFinanzasBundle:Facturas');
        $factura=new Factura(1, 1, new \DateTime, 100, 0, new \DateTime, $repoFc);
        //$factura->
        return new Response;
    }
}
