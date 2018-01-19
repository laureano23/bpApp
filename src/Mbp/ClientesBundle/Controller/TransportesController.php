<?php

namespace Mbp\ClientesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Mbp\ClientesBundle\Entity\Transportes;

class TransportesController extends Controller
{   
	 /**
     * @Route("/listarTransportes", name="mbp_clientes_listarTransportes", options={"expose"=true})
     */
    public function listarTransportes()
    {
        $em = $this->getDoctrine()->getManager();		
		$request = $this->getRequest();
		$response = new Response;
		
		try{
			$rep = $em->getRepository('MbpClientesBundle:Transportes');
			
			$data = $rep->listar();
			
			$response->setContent(json_encode(array('success' => TRUE, 'data' => $data)));
			
			return $response;
		}catch(\Exception $e){
			return new Response(
				json_encode(array(
					'success' => false,	
					'msg' => $e->getMessage()			
				))
			);
		}
		
			
    }
	
	/**
     * @Route("/crearTransporte", name="mbp_clientes_crearTransporte", options={"expose"=true})
     */
    public function crearTransporte()
    {
        $em = $this->getDoctrine()->getManager();		
		$request = $this->getRequest();
		$response = new Response;
		
		try{
			$param = $request->request->get('data');
			$param = json_decode($param);
			
			$transporte;
			$repo = $em->getRepository('MbpClientesBundle:Transportes');
			
			if($param->id > 0){
				$transporte = $repo->find($param->id);
			}else{
				$transporte = new Transportes;
			}
						
			$repoDepartamentos = $em->getRepository('MbpPersonalBundle:Departamentos');
			$depto = $repoDepartamentos->find($param->departamento);
			
			$transporte->setContacto($param->contacto);
			$transporte->setDepartamento($depto);
			$transporte->setDireccion($param->direccion);
			$transporte->setHorarios($param->horarios);
			$transporte->setNombre($param->nombre);
			$transporte->setTelefono($param->telefono);
			
			$em->persist($transporte);
			$em->flush();
			
			$param->id = $transporte->getId();
			$param->departamento = $depto->getNombre();
			$param->provincia = $depto->getProvinciaId()->getNombre();
			
			$response->setContent(json_encode(array('success' => TRUE, 'data' => $param)));
			
			return $response;
		}catch(\Exception $e){
			return new Response(
				json_encode(array(
					'success' => false,	
					'msg' => $e->getMessage()			
				))
			);
		}
		
    }

	/**
     * @Route("/borrarTransporte", name="mbp_clientes_borrarTransporte", options={"expose"=true})
     */
    public function borrarTransporte()
    {
        $em = $this->getDoctrine()->getManager();		
		$request = $this->getRequest();
		$response = new Response;
		
		try{
			$param = $request->request->get('data');
			$param = json_decode($param);
			$repo = $em->getRepository('MbpClientesBundle:Transportes');
			
			$transporte = $repo->find($param->id);
			
			$transporte->setInactivo(TRUE);
			$em->persist($transporte);
			$em->flush();
						
			$response->setContent(json_encode(array('success' => TRUE)));
			
			return $response;
		}catch(\Exception $e){
			return new Response(
				json_encode(array(
					'success' => false,	
					'msg' => $e->getMessage()			
				))
			);
		}
		
    }
}


















