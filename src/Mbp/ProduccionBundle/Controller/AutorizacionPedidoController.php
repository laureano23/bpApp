<?php

namespace Mbp\ProduccionBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Mbp\ProduccionBundle\Entity\Ot;

class AutorizacionPedidoController extends Controller
{	
	/** 
     * @Route("/autorizarPedido", name="mbp_produccion_autorizarPedido", options={"expose"=true})
     */
    public function autorizarPedido()
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
		$response = new Response;
		
		try{
			$data = $request->request->get('pedidos');		
			$stdObj = json_decode($data);			
			
			$repoPedidos = $em->getRepository('MbpProduccionBundle:PedidoClientesDetalle');
			$repoSectores = $em->getRepository('MbpProduccionBundle:Sectores');

			$pedido=$repoPedidos->find($stdObj->idDetalle);
			$pedido->setCantAutorizada($stdObj->cantAutorizada);
			$pedido->setAutorizoEntrega($this->get('security.context')->getToken()->getUser());
			$pedido->setObservacionesAutorizacion($stdObj->observacionesAutorizacion);


			$em->persist($pedido);
			$em->flush();
			
			//NOTIFICACION
			$pusher = $this->container->get('lopi_pusher.pusher');
			$sector=$repoSectores->findOneByDescripcion('ADMINISTRACION');
		    $data=array(
				'message' => 'Se autorizó una entrega, verficiar panel de autorizaciones',
				'sectorReceptor' => $sector->getDescripcion(),
				'env'=>$this->container->get('kernel')->getEnvironment()
			);
			
		    $pusher->trigger('my-channel', 'my-event', json_encode($data));
				        
	        return $response->setContent(
				json_encode(array('success' => true))
			);
		}catch(\Exception $e){
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR); 
			return $response->setContent(
					json_encode(array('success' => false, 'msg' => $e->getMessage()))
				);
		}		
    }	
}
