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
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DefaultController extends Controller
{
    /**
     * @Route("/proveedores/listar", name="mbp_proveedores_listar", options={"expose"=true})
     */
    public function listarAction()
    {
    	$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpProveedoresBundle:Proveedor');
		
		try{
			$query = $repo->createQueryBuilder('p')
					->select('p.id, loc.id AS localidad, prov.id AS provincia, p.rsocial AS rSocial, p.denominacion, p.direccion, p.email, p.cuit, p.cPostal, p.telefono1, p.contacto1, p.telefono2, p.contacto2, p.telefono3, p.contacto3, p.condCompra, p.vencimientoFc, p.aplicaRetencion, p.porcentajeRetencion, p.cuentaCerrada, imputacion.id AS tipoGasto')
					->leftjoin('p.localidad', 'loc')
					->leftjoin('p.provincia', 'prov')
					->leftjoin('p.imputacionGastos', 'imputacion')
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
		$repoLocalidad = $em->getRepository('MbpPersonalBundle:Localidades');
		$repoProvincia = $em->getRepository('MbpPersonalBundle:Provincias');
		$repoImputaciones = $em->getRepository('MbpProveedoresBundle:ImputacionGastos');
		
		try{
			$data = $req->request->get('data');
			$decData = json_decode($data);
			
			$proveedor = 0;
			if($decData->id > 0){
				$proveedor = $repoProveedor->find($decData->id);
			}else{
				$proveedor = new Proveedor();	
			}
					
			$proveedor->setRsocial($decData->rSocial);
			$proveedor->setLocalidad($repoLocalidad->find($decData->localidad));
			$proveedor->setProvincia($repoProvincia->find($decData->provincia));
			$proveedor->setImputacionGastos($repoImputaciones->find($decData->tipoGasto));
			$proveedor->setDenominacion($decData->denominacion);
			$proveedor->setDireccion($decData->direccion);
			$proveedor->setEmail($decData->email);
			$proveedor->setCuit($decData->cuit);
			$proveedor->setCPostal($decData->cPostal);
			$proveedor->setTelefono1($decData->telefono1);
			$proveedor->setContacto1($decData->contacto1);
			$proveedor->setTelefono1($decData->telefono2);
			$proveedor->setContacto1($decData->contacto2);
			$proveedor->setTelefono1($decData->telefono3);
			$proveedor->setContacto1($decData->contacto3);
			$proveedor->setCondCompra($decData->condCompra);
			$proveedor->setVencimientoFc($decData->vencimientoFc);
			$proveedor->setPorcentajeRetencion($decData->vencimientoFc);
			$proveedor->setAplicaRetencion($decData->aplicaRetencion == 'on' ? true : false);
			$proveedor->setPorcentajeRetencion($decData->porcentajeRetencion);
			$proveedor->setCuentaCerrada($decData->estadoCuenta == 'on' ? true : false);
			
			$em->persist($proveedor);
			$em->flush();
			
			echo json_encode(array(
				'success' => true,
				'id' => $proveedor->getId()
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
     * @Route("/proveedores/crearFcProveedor", name="mbp_proveedores_crearFcProveedor", options={"expose"=true})
     */
    public function crearFcProveedorAction()
    {
    	$req = $this->getRequest();
		$data = $req->request->get('data');
		$objData = json_decode($data);
		$em = $this->getDoctrine()->getManager();
		$provRepo = $em->getRepository('MbpProveedoresBundle:Proveedor');
		$repoFc = $em->getRepository('MbpProveedoresBundle:Factura');
		$repoTipoGasto = $em->getRepository('MbpProveedoresBundle:ImputacionGastos');
		
		try{
			$fcProveedor;
			if($objData->idF > 0){
				$fcProveedor = $repoFc->find($objData->idF);
			}else{
				$fcProveedor = new Factura();	
			}
			
			$proveedor = $provRepo->find($objData->idProv);
			$tipoGasto = $repoTipoGasto->find($objData->tipoGasto);
			
			$fcProveedor->setproveedorId($proveedor);
			$fcProveedor->setfechaCarga(\DateTime::createFromFormat('d/m/Y', $objData->fechaCarga));
			$fcProveedor->setfechaEmision(\DateTime::createFromFormat('d/m/Y', $objData->fechaEmision));
			$fcProveedor->settipo($objData->tipo);
			$fcProveedor->setsucursal($objData->sucursal);
			$fcProveedor->setnumFc($objData->numFc);
			$fcProveedor->setneto($objData->neto);
			$fcProveedor->setnetoNoGrabado($objData->netoNoGrabado);
			$fcProveedor->setiva21($objData->iva21);
			
			$fcProveedor->setiva27($objData->iva27);
			$fcProveedor->setiva105($objData->iva10_5);
			$fcProveedor->setperIva5($objData->perIva5);
			$fcProveedor->setperIva3($objData->perIva3);
			$fcProveedor->setiibbCf($objData->iibbCf);
			$fcProveedor->setvencimiento(\DateTime::createFromFormat('d/m/Y', $objData->vencimiento));
			$fcProveedor->setconcepto($objData->concepto);
			$fcProveedor->setTotalFc();
			$fcProveedor->setImputacionGasto($tipoGasto);
			
			$em->persist($fcProveedor);
			$em->flush();
			
			echo json_encode(array(
				'success' => true,				
			));	
		}catch(\Exception $e){
			echo json_encode(array(
				'success' => false,
				'msg' => $e->getMessage()
			));
		}
    	return new Response();
	}

	/**
     * @Route("/proveedores/listarCC", name="mbp_proveedores_listarCC", options={"expose"=true})
     */
    public function listarCCAction()
    {
    	$req = $this->getRequest();
		$idProv = $req->query->get('idProv');
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpProveedoresBundle:Factura');
		$repoPagos = $em->getRepository('MbpProveedoresBundle:Pago');
		
		try{
			//CONSULTO EN CADA REPOSITORIO POR LOS PAGOS Y FACTURAS RESPECTIVAMENTE
			$fc = $repo->listarFacturasCC($idProv);
			$pagos = $repoPagos->listarPagos($idProv);
			
			/*RESPUESTA*/
			if(!empty($pagos)){
				foreach ($pagos as $rec) {
					array_push($fc, $rec);	
				}	
			}
			
			if(!empty($fc)){
				//ORDENO LA SALIDA DEL ARRAY PARA MOSTRARLA POR FECHA DE EMISION DEL COMPROBANTE
				usort($fc, array($this, 'ordenar'));
				
				//UTILIZO ESTA CLASE AUXILIAR PARA CALCULAR EL SALDO DE LA CUENTA
				$calculoClass = $this->get('ParametrosFinanzas');	
				$calculoClass->calculaSaldo($fc, $haber='haber', $debe='debe');	
			}	
			
			echo json_encode(array(
				'data' => $fc
			));
			
		}catch(\Exception $e){
			echo json_encode(array(
				'success' => false,
				'msg' => $e->getMessage()
			));
		}
		
		return new Response();
    }
	
	function ordenar($a, $b) {
	    return strtotime($a['fechaEmision']) - strtotime($b['fechaEmision']);
	}
	
	/**
     * @Route("/proveedores/nuevoPago", name="mbp_proveedores_nuevoPago", options={"expose"=true})
     */
    public function nuevoPagoAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$req = $this->getRequest();
		$data = $req->request->get('data');
		$idOP = $req->request->get('idOP');
		$idProv = $req->request->get('idProv');
		$fcImputar = $req->request->get('fcImputar'); 
		$decData = json_decode($data);
		$fcImputarDec = json_decode($fcImputar);
		$repoTipoPago = $em->getRepository('MbpFinanzasBundle:FormasPago');
		$repoProv = $em->getRepository('MbpProveedoresBundle:Proveedor');
		$repoFc = $em->getRepository('MbpProveedoresBundle:Factura');
		$repoOP = $em->getRepository('MbpProveedoresBundle:OrdenPago');
		
		try{
			$proveedor = $repoProv->find($idProv); //PROVEEDOR ASOCIADO
			$ordenPago = 0;
			if($idOP == 0){
				$ordenPago = new OrdenPago(); //CREO UNA NUEVA ORDEN DE PAGO
				$ordenPago->setEmision(new \DateTime());
				$ordenPago->setProveedorId($proveedor);	
				
				foreach ($decData as $rec) {
					$tipoPago = $repoTipoPago->findByDescripcion($rec->formaPago);
					$pago = new Pago(); //NUEVO DETALLE DE PAGO
					$pago->setEmision(new \DateTime());
					$pago->setNumero($rec->numero);
					$pago->setImporte($rec->importe);
					empty($rec->diferido) ? $pago->setDiferido(new \DateTime) : $pago->setDiferido(\DateTime::createFromFormat('d/m/Y', $rec->diferido));
					$pago->setBanco($rec->banco);
					empty($tipoPago) ? "" : $pago->setIdFormaPago($tipoPago[0]);
					
					$ordenPago->addPagoDetalleId($pago);	
				}	
			}else{
				$ordenPago = $repoOP->find($idOP);
			}
			
			//FACTURAS A IMPUTAR
			$factura;
			$transOpFc;	//OBEJTO QUE GUARDA EL MONTO APLICADO A CADA FC EN UN MOMENTO DADO
			foreach ($fcImputarDec as $fc) {
				$factura = $repoFc->find($fc->id);	
				$factura->setImputado($factura->getImputado() + $fc->aplicar);	
				$ordenPago->addFacturasImputada($factura);
			}
				
			$em->persist($ordenPago);
			$em->flush();
			
			foreach ($fcImputarDec as $fc) {
				$factura = $repoFc->find($fc->id);
				$transOpFc = new TransaccionOPFC();	//NUEVO OBJETO
				$transOpFc->setFacturaImputada($factura);
				$transOpFc->setAplicado($fc->aplicar);				
				$transOpFc->setOrdenPagoImputada($ordenPago);
				$em->persist($transOpFc);
				$em->flush();
			}		
			
			
			echo json_encode(array(
				'success' => true,
			));
		}catch(Exception $e){
			echo json_encode(array(
				'success' => false,
				'msg' => $e->getMessage()
			));
		}
		return new Response();
    }

	
	
	/**
     * @Route("/proveedores/eliminarComprobante", name="mbp_proveedores_eliminarComprobante", options={"expose"=true})
     */
    public function eliminarComprobanteAction()
    {
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$data = json_decode($req->request->get('data'));
		$repo;
		$id = 0;
		
		if($data->haber > 0){
			$repo = $em->getRepository('MbpProveedoresBundle:Factura');
			$id = $data->idF;			
		}else{
			$repo = $em->getRepository('MbpProveedoresBundle:OrdenPago');
			$id = $data->idOP;
		}
		
		try{
			$comprobante = $repo->find($id);
			$em->remove($comprobante);			
			$em->flush();
			
			echo json_encode(array(
				'success' => true,
			));
			return new Response();
		}catch(Exception $e){
			echo json_encode(array(
				'success' => false,
				'msg' => $e->getMessage()
			));
		}
    }
	
	/**
     * @Route("/proveedores/listaChequeTerceros", name="mbp_proveedores_listaChequeTerceros", options={"expose"=true})
     */
    public function listaChequeTercerosAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$repo = $em->getRepository('MbpFinanzasBundle:Cobranzas');
		
		$qb = $repo->createQueryBuilder('c')
			->select('c.emision, cl.rsocial AS librador, cd.id AS id, cd.importe, cd.numero, cd.banco, cd.vencimiento AS diferido, f.id AS fid')
			->join('c.cobranzaDetalleId', 'cd')
			->join('cd.formaPagoId', 'f')
			->join('c.clienteId', 'cl')
			->where('f.id = :idTipoPago')	//EL ID 3 CORRESPONDE A CHEQUE DE TERCEROS
			->andWhere('cd.estado = :estado')
			->setParameter('idTipoPago', 3)
			->setParameter('estado', 0)
			->getQuery();
		
		$res = $qb->getArrayResult();
		
		for ($i=0; $i < count($res); $i++) { 
			$res[$i]['emision'] = $res[$i]['emision']->format('d/m/Y'); 
			$res[$i]['diferido'] = $res[$i]['diferido']->format('d/m/Y');
			$res[$i]['marca'] = 0;
		}
		
		echo json_encode(array(
			'data' => $res
		));
    	
    	return new Response();
    }
    
    /**
     * @Route("/proveedores/listar_facturas", name="mbp_proveedores_listar_facturas", options={"expose"=true})
     */
    public function listar_facturasAction()
    {
    	$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpProveedoresBundle:Factura');
		$req = $this->getRequest();
		$idProv = $req->query->get('idProv');
		
		$fc = $repo->listaFacturasImputadas($idProv);
				
		echo json_encode(array(
			'data' => $fc
		));
		return new Response();
	}

	/**
     * @Route("/proveedores/listar_oredenPago_valores", name="mbp_proveedores_listar_oredenPago_valores", options={"expose"=true})
     */
    public function listar_oredenPago_valoresAction()
    {
    	$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpProveedoresBundle:OrdenPago');
		$req = $this->getRequest();
		$idOP = $req->request->get('idOP');
		
		$op = $repo->listarValoresOP($idOP);
				
		echo json_encode(array(
			'data' => $op,
			'idOP' => $op[0]['idOP']
		));
		return new Response();
	}
	
	/**
     * @Route("/proveedores/buscarFc", name="mbp_proveedores_buscarFc", options={"expose"=true})
     */
    public function buscarFcAction()
    {
    	$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpProveedoresBundle:Factura');
		$req = $this->getRequest();
		$idFc = $req->request->get('idFc');
				
		$fc = $repo->buscarFcPorId($idFc);
	//\Doctrine\Common\Util\Debug::dump($fc);
		echo json_encode(array(
			'data' => $fc,			
		));
		
		return new Response();
	}

	/**
     * @Route("/proveedores/limpiarcc", name="mbp_proveedores_limpiarcc", options={"expose"=true})
     */
    public function limpiarccAction()
    {
    	$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpProveedoresBundle:OrdenPago');
		
				
		$fc = $repo->findAll();
		
		foreach ($fc as $rec) {
			$em->remove($rec);
			$em->flush();
		}
				
		
		return new Response();
	}
}






















