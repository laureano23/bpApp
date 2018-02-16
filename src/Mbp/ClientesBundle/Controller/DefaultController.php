<?php

namespace Mbp\ClientesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Mbp\ClientesBundle\Entity\Cliente;
use Mbp\ClientesBundle\Entity\ClienteRepository;

class DefaultController extends Controller
{   
	 /**
     * @Route("/clientesearch", name="mbp_clientes_search", options={"expose"=true})
     */
    public function clientesearchAction()
    {
        $em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();
		
		$param = $request->query->get('query');
		$rep = $em->getRepository('MbpClientesBundle:Cliente')->searchCliente($param);
		return new Response();
    }
    
	/**
     * @Route("/newcliente", name="mbp_clientes_new", options={"expose"=true})
     */ 
    public function newClienteAction()
    {
        $em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();
		$response = new Response;
		$repoDepto = $em->getRepository('MbpPersonalBundle:Departamentos');
		$repoProvincia = $em->getRepository('MbpPersonalBundle:Provincia');
		$repoLocalidad = $em->getRepository('MbpPersonalBundle:Localidades');
		$repoClientes = $em->getRepository('MbpClientesBundle:Cliente');
		$repoIva = $em->getRepository('MbpFinanzasBundle:PosicionIVA');
		$repoTransporte = $em->getRepository('MbpClientesBundle:Transportes');
		
		try{
			$cliente = 0;
			if($request->request->get('id') > 0){
				$cliente = $repoClientes->find($request->request->get('id'));
			}else{
				$cliente = new Cliente();	
			}
			$estadoCuenta = $request->request->get('cuentaCerrada') == 'on' ? 1 : 0;
			$intereses = $request->request->get('intereses') == 'on' ? 1 : 0;
					
			$cliente->setRsocial($request->request->get('rsocial'));
			$cliente->setDenominacion($request->request->get('denominacion'));
			$cliente->setDireccion($request->request->get('direccion'));
			$cliente->setDepartamento($repoDepto->find($request->request->get('departamento')));
			$cliente->setProvincia($repoProvincia->find($request->request->get('provincia')));
			$cliente->setLocalidad($repoLocalidad->find($request->request->get('localidad')));
			$cliente->setEmail($request->request->get('email'));
			$cliente->setCuit($request->request->get('cuit'));
			$cliente->setCPostal($request->request->get('cPostal'));
			$iva = $repoIva->find($request->request->get('iva'));
			
			if(empty($iva)){
				throw new \Exception("No existe la posicion de IVA", 1); 
			}
			
			$cliente->setIva($iva);
			$cliente->setTelefono1($request->request->get('telefono1'));
			$cliente->setContacto1($request->request->get('contacto1'));
			$cliente->setTelefono2($request->request->get('telefono2'));
			$cliente->setContacto2($request->request->get('contacto2'));
			$cliente->setTelefono3($request->request->get('telefono3'));
			$cliente->setContacto3($request->request->get('contacto3'));
			$cliente->setCondVenta($request->request->get('condVenta'));
			$cliente->setVencimientoFc($request->request->get('vencimientoFc'));
			$cliente->setCuentaCerrada($estadoCuenta);
			$cliente->setIntereses($intereses);
			$cliente->setTasaInt($request->request->get('tasa'));
			
			$transporte = $repoTransporte->find($request->request->get('transporte'));
			if(!empty($transporte)) $cliente->setTransporteId($transporte);
			
			$em->persist($cliente);
			$em->flush();

			return $response->setContent(
				json_encode(array(
					'success' => true,
					'id' => $cliente->getId()
				))
			);
		}catch(\Exception $e){
			$response->setStatusCode($response::HTTP_INTERNAL_SERVER_ERROR);
			return $response->setContent(
				json_encode(array(
					'success' => false,
					'msg' => $e->getMessage()
				))
			);
		}
    }

    /**
     * @Route("/eliminar", name="mbp_clientes_eliminar", options={"expose"=true})
     */ 
	public function eliminarAction()
	{
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();
		$repo = $em->getRepository('MbpClientesBundle:Cliente');
		
		$id = $request->request->get('id');
		$cliente = $repo->find($id);
		
		$em->remove($cliente);
		$em->flush();
		
		echo json_encode(array(
			'success' => true
		));
		
		return new Response();
	}
}


















