<?php

namespace Mbp\FinanzasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Mbp\FinanzasBundle\Entity\Cobranzas;
use Mbp\FinanzasBundle\Entity\CobranzasDetalle;
use Mbp\FinanzasBundle\Entity\TransaccionCobranzaFactura;

class CobranzasController extends Controller
{
    /**
     * @Route("/cobros/listarTipos", name="mbp_finanzas_listaTipos", options={"expose"=true})
     */
    public function listarTiposAction()
	{
		$em = $this->getDoctrine()->getEntityManager();		
		$repo = $em->getRepository('MbpFinanzasBundle:FormasPago');
		$resu = $repo->createQueryBuilder('t')
			->select()
			->where('t.inactivo = 0')
			->getQuery()
			->getResult();
		
		$resp = array();
		$i=0;
		foreach ($resu as $res) {
			$resp[$i]['id'] = $res->getId();
			$resp[$i]['descripcion'] = $res->getDescripcion();
			$i++;
		}
		echo json_encode(array(
			'items' => $resp
		));
		return new Response();
	}
	
	/**
     * @Route("/Cobranza/NuevaCobranza", name="mbp_Cobranza_NuevaCobranza", options={"expose"=true})
     */	
    public function NuevaCobranzaAction()
	{
		$req = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		$response = new Response;
		
		try{
			$data = $req->request->get('data');
			$fcImputadas = json_decode($req->request->get('aplicados'));
			$idCliente = json_decode($req->request->get('cliente'));
			$datosRecibo = json_decode($req->request->get('datosRecibo'));
			$repoCliente = $em->getRepository('MbpClientesBundle:Cliente');
			$repoTipoPago = $em->getRepository('MbpFinanzasBundle:FormasPago');
			$repoFacturas = $em->getRepository('MbpFinanzasBundle:Facturas');
			$repoTransaccion = $em->getRepository('MbpFinanzasBundle:TransaccionCobranzaFactura');
			
			$decodeData = json_decode($data);			
					
			$cobranza = new Cobranzas();		
			$cobranza->setEmision(new \DateTime);
			$cliente = $repoCliente->find($idCliente);
			$cobranza->setClienteId($cliente);
			$cobranza->setPtoVenta((int)$datosRecibo->ptoVta);
			$cobranza->setNumRecibo((int)$datosRecibo->reciboNum);
			
			foreach($decodeData as $data){
				$detalleCob = new CobranzasDetalle();
				$formaPago = $repoTipoPago->findByDescripcion($data->formaPago);
				
				$detalleCob->setImporte($data->importe);
				$detalleCob->setNumero($data->numero);
				$detalleCob->setBanco($data->banco);
				$detalleCob->setImporte($data->importe);
				$detalleCob->setVencimiento(\DateTime::createFromFormat('d/m/Y', $data->diferido));
				$detalleCob->setFormaPagoId($formaPago[0]);
				
				$cobranza->addCobranzaDetalleId($detalleCob);
			}
			$em->persist($cobranza);
			
			
			//SI SE APLICARON FACTURAS A LA COBRANZA
			if($fcImputadas != ""){
				foreach ($fcImputadas as $fc) {
					$factura = $repoFacturas->find($fc->id);
					//VALIDO QUE EL IMPORTE A APLICAR NO SUPERE EL TOTAL DE LA FC
					$aplicado = $repoTransaccion->getTotalAplicadoFactura($fc->id);
					$restante = $factura->getTotal() - $aplicado;
					if($fc->aplicar > $restante){
						throw new \Exception("El máximo imputable a esta factura es ".$restante);
						
					}
					
					$transaccion = new TransaccionCobranzaFactura;
					$transaccion->setAplicado($fc->aplicar);
					
					$transaccion->setFacturaImputada($factura);
					$transaccion->setCobranzaImputada($cobranza);
					
					$em->persist($transaccion);
				}
			}
			
			$em->flush();
		}catch(\Exception $e){
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			return $response->setContent(
				json_encode(array(
					'success'=>false,
					'msg' => $e->getMessage()
				))
			);
		}
			
		return $response->setContent(
			json_encode(array(
				'success'=>true
			))
		);
	}

	/**
     * @Route("/Cobranza/VerCobranza", name="mbp_Cobranza_VerCobranza", options={"expose"=true})
     */	
    public function VerCobranzaAction()
	{
		
	}
}





















