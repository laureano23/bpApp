<?php

namespace Mbp\ProveedoresBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Mbp\ProveedoresBundle\Entity\Proveedor;
use Mbp\ProveedoresBundle\Entity\Factura;
use Mbp\ProveedoresBundle\Entity\Pago;
use Mbp\ProveedoresBundle\Entity\OrdenPago;
use Mbp\ProveedoresBundle\Entity\TransaccionOPFC;
use Mbp\FinanzasBundle\Entity\MovimientosBancos;
use Mbp\FinanzasBundle\Entity\DetalleMovimientosBancos;
use Mbp\FinanzasBundle\Entity\FormasPagos;
use Mbp\FinanzasBundle\Entity\ConceptosBanco;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DefaultController extends Controller
{
	/**
     * @Route("/proveedores/eliminarProveedor", name="mbp_proveedores_eliminarProveedor", options={"expose"=true})
     */
    public function eliminarProveedor()
    {
    	$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpProveedoresBundle:Proveedor');
		$req = $this->getRequest();
		$response = new Response;
		
		try{
			$idProv = $req->request->get('id');
			$proveedor = $repo->find($idProv);
			$proveedor->setInactivo(TRUE);
			
			$em->persist($proveedor);
			$em->flush();
			
			
	        return $response->setContent(
				json_encode(array('success' => true))
			);	
		}catch(\Exception $e){
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			return $response->setContent(json_encode(array(
				'success' => false,
				'msg' => $e->getMessage()
			)));
		}		
    }
	
    /**
     * @Route("/proveedores/listar", name="mbp_proveedores_listar", options={"expose"=true})
     */
    public function listarAction()
    {
    	$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpProveedoresBundle:Proveedor');
		
		try{
			$query = $repo->createQueryBuilder('p')
					->select('p.id, p.rsocial AS rsocial, 
							p.denominacion, p.direccion, p.email, p.cuit, p.cPostal, p.telefono1, p.contacto1,
							p.telefono2, p.contacto2, p.telefono3, p.contacto3, p.condCompra, p.vencimientoFc, 
							p.noAplicaRetencion, p.cuentaCerrada, imputacion.id AS tipoGasto,
							p.notasCC,
							prov.id AS provincia,
							dep.id AS departamento,
							loc.id AS localidad
							')
					->leftjoin('p.departamento', 'dep')
					->leftjoin('p.provincia', 'prov')
					->leftjoin('p.localidad', 'loc')
					->leftjoin('p.imputacionGastos', 'imputacion')
					->where('p.inactivo = 0')
					->getQuery();
					
			$res = $query->getArrayResult();
			
			echo json_encode(array(
				'data' => $res
			));
			
	        return new Response();	
		}catch(\Exception $e){
			echo json_encode(array(
				'success' => false,
				'msg' => $e->getMessage()
			));
		}		
    }
	
	/**
     * @Route("/proveedores/listarGastos", name="mbp_proveedores_listarGastos", options={"expose"=true})
     */
    public function listarGastosAction()
    {
    	$em = $this->getDoctrine()->getManager();
		$repoImputaciones = $em->getRepository('MbpProveedoresBundle:ImputacionGastos');
		
		try{
			$query = $repoImputaciones->createQueryBuilder('i')
					->select('i')
					->getQuery();
			$res = $query->getArrayResult();
			
			echo json_encode(array(
				'data' => $res
			));
			return new Response();	
		}catch(\Exception $e){
			echo json_encode(array(
				'success' => false,
				'msg' => $e->getMessage()
			));
		}
    }
	
	/**
     * @Route("/proveedores/nuevo", name="mbp_proveedores_nuevo", options={"expose"=true})
     */
    public function nuevoAction()
    {
    	$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$repoProveedor = $em->getRepository('MbpProveedoresBundle:Proveedor');
		$repoDepto = $em->getRepository('MbpPersonalBundle:Departamentos');
		$repoProvincia = $em->getRepository('MbpPersonalBundle:Provincia');
		$repoLocalidad = $em->getRepository('MbpPersonalBundle:Localidades');
		$repoImputaciones = $em->getRepository('MbpProveedoresBundle:ImputacionGastos');
		$response=new Response;
		
		try{
			$data = $req->request->get('data');
			$decData = json_decode($data);
			
			$proveedor = 0;
			if($decData->id > 0){
				$proveedor = $repoProveedor->find($decData->id);
			}else{
				$proveedor = new Proveedor();	
			}
					
			$proveedor->setRsocial($decData->rsocial);
			$proveedor->setProvincia($repoProvincia->find($decData->provincia));
			$proveedor->setDepartamento($repoDepto->find($decData->departamento));
			$proveedor->setLocalidad($repoLocalidad->find($decData->localidad));
			$proveedor->setImputacionGastos($repoImputaciones->find($decData->tipoGasto));
			$proveedor->setDenominacion($decData->denominacion);
			$proveedor->setDireccion($decData->direccion);
			$proveedor->setEmail($decData->email);
			$proveedor->setCuit($decData->cuit == "" ? NULL : $decData->cuit);
			$proveedor->setCPostal($decData->cPostal);
			$proveedor->setTelefono1($decData->telefono1);
			$proveedor->setContacto1($decData->contacto1);
			$proveedor->setTelefono2($decData->telefono2);
			$proveedor->setContacto2($decData->contacto2);
			$proveedor->setTelefono3($decData->telefono3);
			$proveedor->setContacto3($decData->contacto3);
			$proveedor->setCondCompra($decData->condCompra);
			$proveedor->setVencimientoFc($decData->vencimientoFc);
			$proveedor->setNoAplicaRetencion($decData->noAplicaRetencion == 'on' ? true : false);
			$proveedor->setCuentaCerrada($decData->cuentaCerrada == 'on' ? true : false);
			$proveedor->setNotasCC($decData->notasCC);
			
			$em->persist($proveedor);
			$em->flush();			
			
			return $response->setContent(json_encode(array('success'=>true, 'data'=> array('id'=>$proveedor->getId()))));	
		}catch(\Exception $e){
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			return $response->setContent(json_encode(array('success'=>false, 'msg'=>$e->getMessage())));
		}		
    }
	
	/**
     * @Route("/proveedores/NuevaFormaPago", name="mbp_proveedores_nuevaFormaPago", options={"expose"=true})
     */
    public function NuevaFormaPago()
    {
    	$em = $this->getDoctrine()->getManager();
		$repoFormaPago = $em->getRepository('MbpFinanzasBundle:FormasPagos');
		$response = new Response;
		$req = $this->getRequest();
		$data = json_decode($req->request->get('data'));
		
		try{
			$formaPago = 0;
			
			if($data->id != ""){
				$formaPago = $repoFormaPago->find($data->id);
			}else{
				$formaPago = new FormasPagos;
			}
			
			$formaPago->setDescripcion($data->descripcion);
			$data->retencionIIBB === "on" ? $formaPago->setRetencionIIBB(TRUE) : $formaPago->setRetencionIIBB(FALSE);
			$data->retencionIVA21 === "on" ? $formaPago->setRetencionIVA21(TRUE) : $formaPago->setRetencionIVA21(FALSE);
			$data->chequeTerceros === "on" ? $formaPago->setChequeTerceros(TRUE) : $formaPago->setChequeTerceros(FALSE);
			$data->esChequePropio === "on" ? $formaPago->setEsChequePropio(TRUE) : $formaPago->setEsChequePropio(FALSE);
			$data->depositaEnCuenta === "on" ? $formaPago->setDepositaEnCuenta(TRUE) : $formaPago->setDepositaEnCuenta(FALSE);
			
			if(!empty($data->conceptoMov)){
				$repoConceptosBanco = $em->getRepository('MbpFinanzasBundle:ConceptosBanco');
				
				$conceptoBanco = $repoConceptosBanco->find($data->conceptoMov);
				$formaPago->setConceptoBancoId($conceptoBanco);
			}
			
			$em->persist($formaPago);
			$em->flush();
			
			$response->setContent(
			json_encode(
				array(
					'success' => true
				)
			));	
			
			return $response;
		}catch(\Exception $e){
			$response->setContent(
				json_encode(
					array(
						'success' => false,
						'msg' => $e->getMessage()
					)
				));	
			
			return $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
		}		
	}

	/**
     * @Route("/proveedores/EliminarFormaPago", name="mbp_proveedores_EliminarFormaPago", options={"expose"=true})
     */
    public function EliminarFormaPago()
    {
    	$em = $this->getDoctrine()->getManager();
		$repoFormaPago = $em->getRepository('MbpFinanzasBundle:FormasPagos');
		$response = new Response;
		$req = $this->getRequest();
		$data = json_decode($req->request->get('data'));
		
		try{
			$formaPago = $repoFormaPago->find($data->id);
			
			$formaPago->setInactivo(true);
						
			$em->persist($formaPago);
			$em->flush();
			
			$response->setContent(
			json_encode(
				array(
					'success' => true
				)
			));	
			
			return $response;
		}catch(\Exception $e){
			$response->setContent(
				json_encode(
					array(
						'success' => false,
						'msg' => $e->getMessage()
					)
				));	
			
			return $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
		}
		
	}
}






















