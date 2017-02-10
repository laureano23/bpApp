<?php

namespace Mbp\ArticulosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Mbp\ArticulosBundle\Entity\RemitosClientes;
use Mbp\ArticulosBundle\Entity\RemitosClientesDetalles;

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
        $repoClientes = $em->getRepository('MbpClientesBundle:Cliente');
        $repoArticulos = $em->getRepository('MbpArticulosBundle:Articulos');
        $validador = $this->get('validator');	//VALIDADOR DE ENTIDADES SERVICIO
        $response = new Response;        

        try{
        	$items = json_decode($req->request->get('items'));
			$idCliente = json_decode($req->request->get('clienteId'));
			$errors = array();
			
			$cliente = $repoClientes->findById($idCliente);


			$remito = new RemitosClientes;
			$remito->setFecha(new \DateTime);
			$remito->setRemitoNum(1);
			$remito->setClienteId($cliente[0]);

			

			foreach ($items as $item) {
				$remitoDetalle = new RemitosClientesDetalles;
				$remitoDetalle->setDescripcion($item->descripcion);
				$remitoDetalle->setCantidad($item->cantidad);
				$remitoDetalle->setUnidad($item->unidad);
				$remitoDetalle->setOc($item->oc);

				$articulo = $repoArticulos->findByCodigo($item->codigo);
				$remitoDetalle->setArticuloId($articulo[0]);
				$remito->addDetalleRemito($remitoDetalle);


				$errors = $validador->validate($remito);
				
				if(count($errors) > 0){
					$resp = array('success' => false, 'tipo' => 'validacion');
					$errList = array();

					foreach ($errors as $error) {
						$errList[$error->getPropertyPath()] = $error->getMessage();
					}
					$resp['errors'] = $errList;


					$response->setContent(json_encode($resp));
					$response->setStatusCode($response::HTTP_BAD_REQUEST);

					return $response;
				}
			
				$em->persist($remito);
				$em->flush();				
				$response->setContent(json_encode(array('success' => true)));
				return $response;				
			}//EOF FOREACH ITEM
        }catch(\Exception $e){
        	$response->setContent(json_encode(array('success' => false, 'msg' => $e->getMessage())));
        	$response->setStatusCode($response::HTTP_INTERNAL_SERVER_ERROR);
        	return $response;        
        }
    }
}















