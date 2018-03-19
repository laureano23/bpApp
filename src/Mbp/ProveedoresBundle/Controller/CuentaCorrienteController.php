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
use Mbp\ProveedoresBundle\Entity\CCProv;
use Mbp\FinanzasBundle\Entity\MovimientosBancos;
use Mbp\FinanzasBundle\Entity\DetalleMovimientosBancos;
use Mbp\FinanzasBundle\Entity\FormasPago;
use Mbp\FinanzasBundle\Entity\ConceptosBanco;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class CuentaCorrienteController extends Controller
{
    /**
     * @Route("/proveedores/cc/crearFcProveedor", name="mbp_proveedores_crearFcProveedor", options={"expose"=true})
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
		$repoTipoCbpte = $em->getRepository('MbpFinanzasBundle:TipoComprobante');
		
		
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
			
			$tipo = $repoTipoCbpte->find($objData->tipo);
			$fcProveedor->setTipoId($tipo);
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
			
			//nuevo mov de cc
			$cc = new CCProv;
			if($fcProveedor->getTipoId()->getEsNotaCredito()){
				$cc->setDebe($fcProveedor->getTotalFc());
			}else{
				$cc->setHaber($fcProveedor->getTotalFc());
			}
			
			$cc->setFechaEmision($fcProveedor->getFechaEmision());
			$cc->setFechaVencimiento($fcProveedor->getVencimiento());
			$cc->setFacturaId($fcProveedor);
			
			$fcProveedor->setCcId($cc);
			
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
     * @Route("/proveedores/cc/listarCC", name="mbp_proveedores_listarCC", options={"expose"=true})
     */
    public function listarCCAction()
    {
    	$req = $this->getRequest();
		$idProv = $req->query->get('idProv');
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpProveedoresBundle:CCProv');		
				
		try{
			//CONSULTO EN CADA REPOSITORIO POR LOS PAGOS Y FACTURAS RESPECTIVAMENTE
			$res = $repo->listarCCProv($idProv);
			
			
			echo json_encode(array(
				'data' => $res
			));
			
		}catch(\Exception $e){
			throw $e;
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
     * @Route("/proveedores/cc/eliminarComprobante", name="mbp_proveedores_eliminarComprobante", options={"expose"=true})
     */
    public function eliminarComprobanteAction()
    {
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$data = json_decode($req->request->get('data'));
		
		$id = 0;
		$response = new Response;
		
		//HACEMOS LA OPERACION COMO UNA TRANSACCION
		$em->getConnection()->beginTransaction();
		try{

			if($data->idF > 0){
				$id = $data->idF;	
				$repo = $em->getRepository("MbpProveedoresBundle:Factura");	
				$repoTr = $em->getRepository("MbpProveedoresBundle:TransaccionOPFC");	
				$comprobante = $repo->find($id);

				$transacciones = $repoTr->createQueryBuilder("tr")
					->select()
					->where("tr.facturaImputada = :fc")
					->setParameter("fc", $id)
					->getQuery()
					->getResult();

				foreach ($transacciones as $tr) {
					$em->remove($tr);								
				}

				$em->remove($comprobante);	
			}else{
				$id = $data->idOP;
				$repo = $em->getRepository("MbpProveedoresBundle:TransaccionOPFC");
				$comprobante = $repo->createQueryBuilder("tr")
					->select()
					->where("tr.ordenPagoImputada = :op")
					->setParameter("op", $id)
					->getQuery()
					->getResult();
				
				foreach ($comprobante as $comp) {
					$em->remove($comp);								
				}	

				//ELIMINO LA ORDEN DE PAGO CON TODOS LOS DETALLES ASOCIADOS
				$repoOP = $em->getRepository("MbpProveedoresBundle:OrdenPago");
				$ordenPago = $repoOP->find($id);
				$em->remove($ordenPago);
			}
			
			$em->flush();
			
			$em->getConnection()->commit();
			

			return $response->setContent(
				json_encode(array(
					'success' => true
				))
			);

		}catch(\Exception $e){
			$em->getConnection()->rollBack();
			
			return $response->setContent(
				json_encode(array(
					'success' => false,
					'msg' => $e->getMessage()
				))
			);
		}
    }
	
	/**
     * @Route("/proveedores/cc/listaChequeTerceros", name="mbp_proveedores_listaChequeTerceros", options={"expose"=true})
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
			->where('f.chequeTerceros = :chequeTerceros')	//EL ID 3 CORRESPONDE A CHEQUE DE TERCEROS
			->andWhere('cd.estado = :estado')
			->setParameter('chequeTerceros', true)
			->setParameter('estado', 0)	//ESTADO QUE INDICA CHEQUE EN CARTERA
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
     * @Route("/proveedores/cc/listar_facturas", name="mbp_proveedores_listar_facturas", options={"expose"=true})
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
     * @Route("/proveedores/cc/listar_oredenPago_valores", name="mbp_proveedores_listar_oredenPago_valores", options={"expose"=true})
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
     * @Route("/proveedores/cc/buscarFc", name="mbp_proveedores_buscarFc", options={"expose"=true})
     */
    public function buscarFcAction()
    {
    	$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpProveedoresBundle:Factura');
		$req = $this->getRequest();
		$idFc = $req->request->get('idFc');
				
		$fc = $repo->buscarFcPorId($idFc);
		echo json_encode(array(
			'data' => $fc,			
		));
		
		return new Response();
	}

	/**
     * @Route("/proveedores/cc/limpiarcc", name="mbp_proveedores_limpiarcc", options={"expose"=true})
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
	
	/**
     * @Route("/proveedores/cc/balance", name="mbp_proveedores_balance", options={"expose"=true})
     */
    public function balance()
    {
    	$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$repo = $em->getRepository('MbpProveedoresBundle:Proveedor');
		$repoTipo = $em->getRepository('MbpFinanzasBundle:TipoComprobante');
		$response = new Response;
		
		try{
			$neto = $req->request->get('neto');
			$proveedorId = $req->request->get('proveedorId');
			$obs = $req->request->get('observaciones');
			
			$balance = new Factura;
			$proveedor = $repo->find($proveedorId);			
			$balance->setFechaCarga(new \DateTime);
			$balance->setFechaEmision(new \DateTime);
			$balance->setEsBalance(true);
			$balance->setNeto($neto);
			$balance->setObservaciones($obs);
			$balance->setSucursal(0);
			$balance->setNumFc(0);
			$balance->setProveedorId($proveedor);
			$balance->setVencimiento(new \DateTime);
			$balance->setTotalFc($neto);
			
			$tipo = $repoTipo->findOneByEsBalance(true);
			$balance->setTipoId($tipo);
			
			//nuevo mov de cc
			$cc = new CCProv;
			if($neto >= 0){
				$cc->setHaber($neto);
			}else{
				$cc->setDebe($neto*-1);
			}
			
			$cc->setFechaEmision($balance->getFechaEmision());
			$cc->setFechaVencimiento($balance->getVencimiento());
			$cc->setFacturaId($balance);
			$balance->setCcId($cc);
			
			
			$em->persist($balance);
			$em->flush();
									
			return $response->setContent(json_encode(array('success' => TRUE)));
		}catch(\Exception $e){
			return $response->setContent(json_encode(array('success' => FALSE, 'msg' => $e->getMessage())));
		}	
	}
}






















