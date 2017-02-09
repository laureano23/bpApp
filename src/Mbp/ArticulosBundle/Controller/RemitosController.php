<?php

namespace Mbp\ArticulosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Mbp\ArticulosBundle\Entity\RemitosClientes;

class RemitosController extends Controller
{
    /**
     * @Route("/generarRemitoCliente", name="mbp_articulos_generarRemitoCliente", options={"expose"=true})
     */
    public function generarRemitoClienteAction()
    {
    	$em = $this->getDoctrine()->getManager();
        $req = $this->getRequest();
        $repo = $em->getRepository('MbpArticulosBundle:RemitosClientes');
        $validador = $this->get('validator');	//VALIDADOR DE ENTIDADES SERVICIO
        $response = new Response;        

        try{
        	$items = json_decode($req->request->get('items'));
			$idCliente = json_decode($req->request->get('idCliente'));
			

			foreach ($items as $item) {
				$remito = new RemitosClientes;
				$remito->setDescripcion($item->descripcion);
				$remito->setCantidad(0.001);
				$remito->setUnidad($item->unidad);
				$remito->setOc($item->oc);
				$remito->setFecha(new \DateTime);
				$remito->setRemitoNum(1);
				$remito->setClienteId(null);
				$remito->setArticuloId(null);

				$errors = $validador->validate($remito);

				if(count($errors) > 0){
					$resp = array('success' => false);

					foreach ($errors as $error) {
						$resp[$error->getPropertyPath()] = $error->getMessage();
					}

					print_r($resp);

					$response->setContent(json_encode($resp));
					$response->setStatusCode($response::HTTP_BAD_REQUEST);

					return $response;
				}


				$em->persist($remito);
				$response->setContent(json_encode(array('success' => true)));
				return $response;
			}	
        }catch(\Exception $e){
        	$response->setContent(json_encode(array('success' => true, 'msg' => $e->getMessage())));
        	$response->setStatusCode($response::HTTP_INTERNAL_SERVER_ERROR);
        	return $response;
        }
    }
}
















