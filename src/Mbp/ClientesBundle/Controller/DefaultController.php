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
		
		$data = json_decode($request->request->get('data'));
		
		
		
		
		$response = new Response;
		$repoDepto = $em->getRepository('MbpPersonalBundle:Departamentos');
		$repoProvincia = $em->getRepository('MbpPersonalBundle:Provincia');
		$repoLocalidad = $em->getRepository('MbpPersonalBundle:Localidades');
		$repoClientes = $em->getRepository('MbpClientesBundle:Cliente');
		$repoIva = $em->getRepository('MbpFinanzasBundle:PosicionIVA');
		$repoTransporte = $em->getRepository('MbpClientesBundle:Transportes');
		$repoVendedor = $em->getRepository('MbpFinanzasBundle:Vendedor');
		
		try{
			//throw new \Exception("Error Processing Request", 1);
			$cliente = 0;
			if($data->id > 0){
				$cliente = $repoClientes->find($data->id);
			}else{
				$cliente = new Cliente();	
			}
			$estadoCuenta = $data->cuentaCerrada == 'on' ? 1 : 0;
			$intereses = $data->intereses == 'on' ? 1 : 0;
					
			$cliente->setRsocial($data->rsocial);
			$cliente->setDenominacion($data->denominacion);
			$cliente->setDireccion($data->direccion);
			$cliente->setDepartamento($repoDepto->find($data->departamento));
			$cliente->setProvincia($repoProvincia->find($data->provincia));
			$cliente->setLocalidad($repoLocalidad->find($data->localidad));
			$cliente->setEmail($data->email);
			if($data->cuit == 0){
				$cliente->setCuit(null);
			}else{
				$cliente->setCuit($data->cuit);	
			}
			$cliente->setCPostal($data->cPostal);
			$iva = $repoIva->find($data->iva);
			
			if(empty($iva)){
				throw new \Exception("No existe la posicion de IVA", 1); 
			}
			
			$cliente->setIva($iva);
			$cliente->setTelefono1($data->telefono1);
			$cliente->setContacto1($data->contacto1);
			$cliente->setTelefono2($data->telefono2);
			$cliente->setContacto2($data->contacto2);
			$cliente->setTelefono3($data->telefono3);
			$cliente->setContacto3($data->contacto3);
			$cliente->setCondVenta($data->condVenta);
			$cliente->setVencimientoFc($data->vencimientoFc);
			$cliente->setCuentaCerrada($estadoCuenta);
			$cliente->setIntereses($intereses);
			$cliente->setTasaInt($data->tasa);
			$cliente->setDescuentoFijo($data->descuentoFijo);
			$cliente->setNotasCC($data->notasCC);
			$cliente->setComision($data->comision);
			$cliente->setNoAplicaPercepcion($data->noAplicaPercepcion);
			$cliente->setObservaciones($data->observaciones);
			
			$transporte = $repoTransporte->find($data->transporte);
			if(!empty($transporte)) $cliente->setTransporteId($transporte);
			
			$vendedor = $repoVendedor->find($data->vendedor);
			if(!empty($vendedor)) $cliente->setVendedor($vendedor);
			
			$em->persist($cliente);
			$em->flush();

			return $response->setContent(
				json_encode(array(
					'success' => true,
					'data' => array('id' => $cliente->getId())
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
	
	/**
     * @Route("/cc/notas", name="mbp_clientes_cc_notas", options={"expose"=true})
     */ 
	public function notas()
	{
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();
		$repo = $em->getRepository('MbpClientesBundle:Cliente');
		$response=new Response();
		
		try{
			$id = $request->request->get('idCliente');
			$notas = $request->request->get('notas');
			$cliente = $repo->find($id);
			
			$cliente->setNotasCC($notas);
			
			$em->persist($cliente);
			$em->flush();
			
			return $response->setContent( json_encode(array(
				'success' => true
			)));
		}catch(\Exception $e){
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			return $response->setContent( json_encode(array(
				'success' => false,
				'msg' => $e->getMessage()
			)));
		}
	}
}


















