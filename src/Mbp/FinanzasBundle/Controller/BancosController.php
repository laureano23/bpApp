<?php

namespace Mbp\FinanzasBundle\Controller;	

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Mbp\FinanzasBundle\Entity\Bancos;
use Mbp\FinanzasBundle\Entity\ConceptosBanco;
use Mbp\FinanzasBundle\Entity\CuentasBancarias;
use Mbp\FinanzasBundle\Entity\MovimientosBancos;
use Mbp\FinanzasBundle\Entity\DetalleMovimientosBancos;

class BancosController extends Controller
{
	/**
     * @Route("/bancos/listarCuentas", name="mbp_finanzas_listaCuentas", options={"expose"=true})
     */
    public function listarCuentas()
    {
    	$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpFinanzasBundle:Bancos');
		$response = new Response;
		
		try{
			$qb = $repo->createQueryBuilder('b')
				->select("c.id, CONCAT(c.tipo, c.numero, '----',b.nombre) AS cuenta")
				->join('b.cuentasBancarias', 'c')
				->where('b.inactivo = 0')
				->andWhere('c.inactivo = 0')
				->getQuery()
				->getArrayResult();
				
			return $response->setContent(json_encode(array('success' => true, 'data' => $qb)));		
		}catch(\Exception $e){
			return $response->setContent(json_encode(array('success' => false)));
		}
    }
	
	/**
     * @Route("/bancos/listar", name="mbp_finanzas_listaBancos", options={"expose"=true})
     */
    public function listaBancosAction()
    {
    	$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpFinanzasBundle:Bancos');
		
		
		$qb = $repo->createQueryBuilder('b')
			->select('')
			->where('b.inactivo = 0')
			->getQuery()
			->getArrayResult();
			
		
		echo json_encode(array(
			'success' => true,
			'items' => $qb
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
     * @Route("/bancos/EliminarBanco", name="mbp_bancos_EliminarBanco", options={"expose"=true})
     */
    public function eliminarBanco()
	{
		
		$idBanco = $this->getRequest()->request->get('idBanco');
		$em = $this->getDoctrine()->getEntityManager();		
		$repo = $em->getRepository('MbpFinanzasBundle:Bancos');
		$response = new Response;
		
		try{
			$res = $repo->find($idBanco);
			
			$res->setInactivo(true);
			
			foreach ($res->getCuentasBancarias() as $cuenta) {
				$cuenta->setInactivo(TRUE);
				$em->persist($cuenta);
			}
			
			$em->persist($res);
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
					'error' => $e->getMessage()
				))
			);
			
			return $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}
	
	/**
     * @Route("/bancos/EliminarCuenta", name="mbp_bancos_EliminarCuenta", options={"expose"=true})
     */
    public function eliminarCuenta()
	{
		
		$idCuenta = $this->getRequest()->request->get('idCuenta');
		$em = $this->getDoctrine()->getEntityManager();		
		$repo = $em->getRepository('MbpFinanzasBundle:CuentasBancarias');
		$response = new Response;
		
		try{
			$res = $repo->find($idCuenta);
			
			$res->setInactivo(true);
			$em->persist($res);
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
					'error' => $e->getMessage()
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
			$cuentas = $this->getRequest()->request->get('cuentas');
			$data = json_decode($data);
			$cuentas = json_decode($cuentas);
			
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
			foreach ($cuentas as $c) {
				$cuenta = new CuentasBancarias;
				$cuenta->setTipo($c->cuentaTipo);
				$cuenta->setNumero($c->cuentaNro);
				$cuenta->setCbu($c->cbu); 
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
				->select("c.id, c.concepto, 
					CASE WHEN c.imputaDebe = true THEN 'Debe' ELSE 'Haber' END AS imputaDebe,
					c.inactivo")
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
			$contabiliza = $this->getRequest()->request->get('imputaDebe');
			$id = $this->getRequest()->request->get('id');
			$concepto;
			
			if(!empty($id)){
				$repo = $em->getRepository('MbpFinanzasBundle:ConceptosBanco'); 
				$concepto = $repo->find($id);
			}else{
				$concepto = new ConceptosBanco;
			}
			
			$concepto->setConcepto($data);
			$concepto->setImputaDebe($contabiliza == 'Debe' ? true:false);
			
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

	/**
     * @Route("/bancos/guardarMovimientoBanco", name="mbp_bancos_guardarMovimientoBanco", options={"expose"=true})
     */
    public function guardarMovimientoBanco()
	{
		$response = new Response;
		$em = $this->getDoctrine()->getEntityManager();	
		
		try{			
			$operacion = json_decode($this->getRequest()->request->get('operacion'));
			$comprobantes = json_decode($this->getRequest()->request->get('comprobantes'));
			
			//REPOSITORIOS
			$repoCuentasBrias = $em->getRepository('MbpFinanzasBundle:CuentasBancarias');
			$repoConceptoMov = $em->getRepository('MbpFinanzasBundle:ConceptosBanco');
			$repoChequeTerceros = $em->getRepository('MbpFinanzasBundle:CobranzasDetalle');
			
			$cuentaBancaria = $repoCuentasBrias->find($operacion->cuenta);
			$conceptoMov = $repoConceptoMov->find($operacion->conceptoMov);			
									
			$movimiento = new MovimientosBancos;	
			$movimiento->setFechaMovimiento(new \DateTime); //CAMBIAR A FECHA DEL CLIENTE
			
			//DETALLES DEL MOVIMIENTO
			foreach ($comprobantes as $comp) {
				$detalleMov = new DetalleMovimientosBancos;
				//SI EL COMPROBANTE QUE SE ENVIA CORRESPONDE A UN CHEQUE DE TERCEROS SE REGISTRA
				if($comp->idChequeTerceros){
					$chequeTerceros = $repoChequeTerceros->find($comp->idChequeTerceros);
					//SETEAMOS ESTADO DE CHEQUE DE TERCEROS COMO DEPOSITADO
					$chequeTerceros->setEstado(2);
					$chequeTerceros->setMovBancoId($detalleMov);
					$em->persist($chequeTerceros);
					$detalleMov->setChequeTerceros($chequeTerceros);
					$detalleMov->setNumComprobante($chequeTerceros->getNumero());
					$detalleMov->setFechaDiferida($chequeTerceros->getVencimiento());
					$detalleMov->setImporte($chequeTerceros->getImporte());					
				}else{
					$detalleMov->setNumComprobante($comp->numCbte);
					$detalleMov->setFechaDiferida(new \DateTime);
					$detalleMov->setImporte($comp->importe);
					$detalleMov->setObservaciones($comp->obsCbte);
				}
				$movimiento->addDetallesMovimiento($detalleMov);
			}
			
			$movimiento->setCuentaBancaria($cuentaBancaria);
			$movimiento->setConceptoBancoId($conceptoMov);
			
			$em->persist($movimiento);			
			$em->persist($chequeTerceros);			
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





















