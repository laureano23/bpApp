<?php

namespace Mbp\FinanzasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Mbp\FinanzasBundle\Entity\Bancos;
use Mbp\FinanzasBundle\Entity\ConceptosBanco;
use Mbp\FinanzasBundle\Entity\CuentasBancarias;

class BancosController extends Controller
{
	/**
     * @Route("/bancos/listar", name="mbp_finanzas_listaBancos", options={"expose"=true})
     */
    public function listaBancosAction()
    {
    	$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpFinanzasBundle:Bancos');
		$res = $repo->findAll();
		
		$resu = array();
		
		$i=0;
		foreach ($res as $reg) {
			$resu[$i]['id'] = $reg->getId();
			$resu[$i]['nombre'] = $reg->getNombre();
			$i++;
		}
		echo json_encode(array(
			'success' => true,
			'items' => $resu
		));
		
        return new Response();
    }
	
    /**
     * @Route("/bancos/ListarBanco", name="mbp_bancos_ListarBanco", options={"expose"=true})
     */
    public function ListarBanco()
	{
		$response = new Response;
		$idBanco = $this->getRequest()->request->get('idBanco');
		$em = $this->getDoctrine()->getEntityManager();		
		$repo = $em->getRepository('MbpFinanzasBundle:Bancos');
		
		try{
			$res = $repo->listarDatosBanco($idBanco);
			
			$response->setContent(
				json_encode(array(
					'success' => true,
					'data' => $res
				))
			);
			
			return $response;
		}catch(\Exception $e){
			$response->setContent(
				json_encode(array(
					'success' => false
				))
			);
			
			return $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	} 
	
	/**
     * @Route("/bancos/NuevoBanco", name="mbp_bancos_NuevoBanco", options={"expose"=true})
     */
    public function NuevoBanco()
	{
		$response = new Response;
		$idBanco = $this->getRequest()->request->get('idBanco');
		$em = $this->getDoctrine()->getEntityManager();	
		$repo = $em->getRepository('MbpFinanzasBundle:Bancos');	
		
		try{
			$data = $this->getRequest()->request->get('data');
			$data = json_decode($data);
			
			$banco = $repo->find($data->idBanco);
			
			if(empty($banco)){
				$banco = new Bancos;
				$banco->setNombre($data->idBanco);
			}
									
			$banco->setDireccion($data->direccion);
			$banco->setCp($data->cp);
			$banco->setLocalidad($data->localidad);
			$banco->setTelefono($data->telefono);
			$banco->setEmail($data->email);
			$banco->setCuit($data->cuit);
			$banco->setContacto($data->contacto);
			$banco->setCargo($data->cargo);
			$banco->setTelContacto($data->telContacto);
			$banco->setEmailContacto($data->emailContacto);
			
			$cuenta = 0;
			if(!empty($data->cuentaNumeroNuevo) && !empty($data->cuentaTipoNuevo) && !empty($data->cbuNuevo)){
				$cuenta = new CuentasBancarias;
				$cuenta->setTipo($data->cuentaTipoNuevo);
				$cuenta->setNumero($data->cuentaNumeroNuevo);
				$cuenta->setCbu($data->cbuNuevo); 
				$cuenta->setBanco($banco);
				
				$banco->addCuentasBancaria($cuenta);
			}
			
			//VALIDACIONES
			$validador = $this->get('validator');
			
			$errors = $validador->validate($banco);
						
			
			if(count($errors) > 0){
				$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
				$errList = array();
				foreach ($errors as $error) {
					$errList[$error->getPropertyPath()] = $error->getMessage();
				}
				
				$response->setContent(
					json_encode(array(
						'success' => false,
						'errors' => $errList,
						'tipo' => 'validacion'
						))
					);
				
				return $response;
			}
			
			$em->persist($banco);
			$em->flush();
			
			$response->setContent(
				json_encode(array(
					'success' => true,
					'idBanco' => $banco->getId()
				))
			);
			
			return $response;
		}catch(\Exception $e){
			$response->setContent(
				json_encode(array(
					'success' => false,
					'msg' => $e->getMessage()
				))
			);
			
			return $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	} 

	/**
     * @Route("/bancos/listarConceptosBanco", name="mbp_bancos_listarConceptosBanco", options={"expose"=true})
     */
    public function listarConceptosBanco()
	{
		$response = new Response;
		$em = $this->getDoctrine()->getEntityManager();	
		$repo = $em->getRepository('MbpFinanzasBundle:ConceptosBanco');	
		
		try{
			$qb = $repo->createQueryBuilder('c')
				->select('')
				->where('c.inactivo = 0')
				->getQuery()
				->getArrayResult();
			
			
			
			$response->setContent(
				json_encode(array(
					'success' => true,
					'data' => $qb
				))
			);
			
			return $response;
		}catch(\Exception $e){
			$response->setContent(
				json_encode(array(
					'success' => false,
					'msg' => $e->getMessage()
				))
			);
			
			return $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	} 
	
	/**
     * @Route("/bancos/nuevoConceptoBanco", name="mbp_bancos_nuevoConceptoBanco", options={"expose"=true})
     */
    public function nuevoConceptoBanco()
	{
		$response = new Response;
		$em = $this->getDoctrine()->getEntityManager();	
		
		try{			
			$data = $this->getRequest()->request->get('concepto');
			$id = $this->getRequest()->request->get('id');
			$concepto;
			
			if(!empty($id)){
				$repo = $em->getRepository('MbpFinanzasBundle:ConceptosBanco'); 
				$concepto = $repo->find($id);
			}else{
				$concepto = new ConceptosBanco;
			}
			
			$concepto->setConcepto($data);
			
			$em->persist($concepto);
			$em->flush();
			
			
			$response->setContent(
				json_encode(array(
					'success' => true,
				))
			);
			
			return $response;
		}catch(\Exception $e){
			$response->setContent(
				json_encode(array(
					'success' => false,
					'msg' => $e->getMessage()
				))
			);
			
			return $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	} 
	
	/**
     * @Route("/bancos/eliminarConceptoBanco", name="mbp_bancos_eliminarConceptoBanco", options={"expose"=true})
     */
    public function eliminarConceptoBanco()
	{
		$response = new Response;
		$em = $this->getDoctrine()->getEntityManager();	
		
		try{			
			$id = $this->getRequest()->request->get('id');
			
			$repo = $em->getRepository('MbpFinanzasBundle:ConceptosBanco'); 
			$concepto = $repo->find($id);
			
			$concepto->setInactivo(true);
			
			$em->persist($concepto);
			$em->flush();
			
			
			$response->setContent(
				json_encode(array(
					'success' => true,
				))
			);
			
			return $response;
		}catch(\Exception $e){
			$response->setContent(
				json_encode(array(
					'success' => false,
					'msg' => $e->getMessage()
				))
			);
			
			return $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	} 
}





















