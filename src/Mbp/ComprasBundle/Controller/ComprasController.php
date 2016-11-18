<?php

namespace Mbp\ComprasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ComprasController extends Controller
{
    /**
     * @Route("/ordenCompra/nuevaOrdenCompra", name="mbp_compras_nuevaOrden", options={"expose"=true})
     */
    public function nuevaOrdenCompraAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
		$repo = $em->getRepository('MbpComprasBundle:OrdenCompra');
    	$req = $this->getRequest();
		$data = $req->request->get('detalle');
		$orden = $req->request->get('orden');
		$detalleData = json_decode($data);
		$ordenData = json_decode($orden);
		$usuario = $this->getUser()->getUserName();
		$repo->nuevaOC($detalleData, $ordenData, $usuario);
		
        return new Response(
			json_encode(array(
				'success' => true,
				'msg' => 'La orden fue generada exitosamente'
			))
		);
    }
}
