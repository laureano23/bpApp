<?php

namespace Mbp\ProduccionBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Mbp\ProduccionBundle\Entity\CalculoRad;
use Mbp\ProduccionBundle\Entity\Brazing;

use Mbp\ProduccionBundle\Clases\Calculo;

class CalculoController extends Controller
{
	//Verifica si el codigo del panel tiene ya los datos guardado en la BD
	public function verificaCalcAction()
	{
		$em = $this->getDoctrine()->getManager();
		
		//Traigo el codigo enviado
		$req = $this->getRequest();
		$idArt = $req->request->get('cod');
		
		$repo = $em->getRepository('MbpProduccionBundle:CalculoRad');
		$res = $repo->findOneByCod($idArt);
	
		
		if(!$res){
			$estado = false;
			$msg = 'Este panel no tiene calculo ';
			$record = '';
		}else{
			$estado = true;
			$msg = 'Este panel tiene calculo';
			$record = array(
				'id' => $res->getid(),
				'apoyoTapas' => $res->getapoyoTapas(),
				'prof' => $res->getprof(),
				'ancho' => $res->getancho(),
				'chapaPiso' => $res->getchapaPiso(),
				'cantAdic' => $res->getcantAdic(),
				'perfilInt' => $res->getperfilInt(),
				'aletaTipo' => $res->getaletaTipo(),
				'pisosManual' => $res->getpisosManual(),
				'pisosManual7' => $res->getpisosManual7(),
				'tipo' => $res->gettipo(),
				'aletaFluA' => $res->getaletaFluA(),
				'aletaVenA' => $res->getaletaVenA(),
			);		
		}
		
		echo json_encode(array(
			'success' => $estado,
			'msg' => $msg,
			'record' => $record
		));
		return new Response();
	}
	
	public function radiadoresAceiteAireAction()
	{
		$calc = $this->get('mbp.calculo');	
		
		$calc->setVariablesEntrada();			
		$getPisosAletaVen = $calc->getPisosAletaVen();
		$getCantidadPerfilVen = $calc->getCantidadPerfilVen();
		$getPisosAletaAdicional7 = $calc->getPisosAletaAdicional7();
		$getCantidadPerfilAdicional7 = $calc->getCantidadPerfilAdicional7();
		$getPisosAletaFlu = $calc->getPisosAletaFlu();
		$getCantidadAletaFlu = $calc->getCantidadAletaFlu();
		$getCantidadRecorteFlu = $calc->getCantidadRecorteFlu();
		$getCantidadPerfilFlu = $calc->getCantidadPerfilFlu();
		$getCantidadChapaIntermedia = $calc->getCantidadChapaIntermedia();
		$getCantidadChapaPiso = $calc->getCantidadChapaPiso();
		$getCantidadChapaAdicional = $calc->getCantidadChapaAdicional();
		$getAnchoPaneles = $calc->getAnchoPaneles();
		
        $data1 = array(			
			'descripcion' => $calc->getTipoAletaVen(),
			'largo' => $calc->getLargoAletaVen(),
			'ancho' => $calc->getAnchoAletaVen(),
			'espesor' => $calc->getEspesorAletaVen(),
			'pisosp1' => $getPisosAletaVen['pisosAletaVenP1'],
			'cantp1' => '',
			'pisosp2' => $getPisosAletaVen['pisosAletaVenP2'],
			'cantp2' => '',
			'pisosp3' => $getPisosAletaVen['pisosAletaVenP3'],
			'cantp3' => '',
			'pisosp4' => $getPisosAletaVen['pisosAletaVenP4'],
			'cantp4' => ''			
		);
		
		$data2 = array(			
			'descripcion' => $calc->getTipoPerfilVen(),
			'largo' => $calc->getLargoPerfilVen(),
			'ancho' => '',
			'espesor' => '',
			'pisosp1' => '',
			'cantp1' => $getCantidadPerfilVen['cantPerfilVenP1'],
			'pisosp2' => '',
			'cantp2' => $getCantidadPerfilVen['cantPerfilVenP2'],
			'pisosp3' => '',
			'cantp3' => $getCantidadPerfilVen['cantPerfilVenP3'],
			'pisosp4' => '',
			'cantp4' => $getCantidadPerfilVen['cantPerfilVenP4']			
		);
		
		$data3 = array(			
			'descripcion' => 'Aleta Aire Ref. 7',
			'largo' => $calc->getLargoPisoAdicional7(),
			'ancho' => $calc->getAnchoPisoAdicional7(),
			'espesor' => $calc->getEspesorPisoAdcional7(),
			'pisosp1' => $getPisosAletaAdicional7['pisosP1Aleta7'],
			'cantp1' => '',
			'pisosp2' => $getPisosAletaAdicional7['pisosP2Aleta7'],
			'cantp2' => '',
			'pisosp3' => $getPisosAletaAdicional7['pisosP3Aleta7'],
			'cantp3' => '',
			'pisosp4' => $getPisosAletaAdicional7['pisosP4Aleta7'],
			'cantp4' => ''			
		);
		
		$data4 = array(			
			'descripcion' => $calc->getTipoPerfilAdicional7(),
			'largo' => $calc->getLargoPerfilAdicional7(),
			'ancho' => '',
			'espesor' => '',
			'pisosp1' => '',
			'cantp1' => $getCantidadPerfilAdicional7['cantP1PerfilAd7'],
			'pisosp2' => '',
			'cantp2' => $getCantidadPerfilAdicional7['cantP2PerfilAd7'],
			'pisosp3' => '',
			'cantp3' => $getCantidadPerfilAdicional7['cantP3PerfilAd7'],
			'pisosp4' => '',
			'cantp4' => $getCantidadPerfilAdicional7['cantP4PerfilAd7']			
		);
		
		$data5 = array(			
			'descripcion' => $calc->getTipoAletaFlu(),
			'largo' => $calc->getLargoAletaFlu(),
			'ancho' => $calc->getAnchoAletaFlu(),
			'espesor' => $calc->getEspesorAletaFlu(),
			'pisosp1' => $getPisosAletaFlu['pisosP1AletaFlu'],
			'cantp1' => $getCantidadAletaFlu['cantidadP1AletaFlu'],
			'pisosp2' => $getPisosAletaFlu['pisosP2AletaFlu'],
			'cantp2' => $getCantidadAletaFlu['cantidadP2AletaFlu'],
			'pisosp3' => $getPisosAletaFlu['pisosP3AletaFlu'],
			'cantp3' => $getCantidadAletaFlu['cantidadP3AletaFlu'],
			'pisosp4' => $getPisosAletaFlu['pisosP4AletaFlu'],
			'cantp4' => $getCantidadAletaFlu['cantidadP4AletaFlu'],
		);
		
		$data6 = array(			
			'descripcion' => $calc->getTipoRecorteFlu(),
			'largo' => $calc->getLargoRecorteFlu(),
			'ancho' => $calc->getAnchoRecorteFlu(),
			'espesor' => $calc->getEspesorRecorteFlu(),
			'pisosp1' => '',
			'cantp1' => $getCantidadRecorteFlu['cantP1RecorteFlu'],
			'pisosp2' => '',
			'cantp2' => $getCantidadRecorteFlu['cantP2RecorteFlu'],
			'pisosp3' => '',
			'cantp3' => $getCantidadRecorteFlu['cantP3RecorteFlu'],
			'pisosp4' => '',
			'cantp4' => $getCantidadRecorteFlu['cantP4RecorteFlu'],
		);
		
		$data7 = array(			
			'descripcion' => $calc->getTipoPerfilFlu(),
			'largo' => $calc->getLargoPerfilFlu(),
			'ancho' => '',
			'espesor' => '',
			'pisosp1' => '',
			'cantp1' => $getCantidadPerfilFlu['perfilesP1Fluido'],
			'pisosp2' => '',
			'cantp2' => $getCantidadPerfilFlu['perfilesP2Fluido'],
			'pisosp3' => '',
			'cantp3' => $getCantidadPerfilFlu['perfilesP3Fluido'],
			'pisosp4' => '',
			'cantp4' => $getCantidadPerfilFlu['perfilesP4Fluido'],
		);
		
		$data8 = array(			
			'descripcion' => 'Chapa Intermedia',
			'largo' => $calc->getLargoChapaIntermedia(),
			'ancho' => $calc->getAnchoChapaIntermedia(),
			'espesor' => $calc->getEspesorChapaIntermedia(),
			'pisosp1' => '',
			'cantp1' => $getCantidadChapaIntermedia['cantidadP1ChapaIntermedia'],
			'pisosp2' => '',
			'cantp2' => $getCantidadChapaIntermedia['cantidadP2ChapaIntermedia'],
			'pisosp3' => '',
			'cantp3' => $getCantidadChapaIntermedia['cantidadP3ChapaIntermedia'],
			'pisosp4' => '',
			'cantp4' => $getCantidadChapaIntermedia['cantidadP4ChapaIntermedia']
		);
		
		$data9 = array(			
			'descripcion' => 'Chapa de Piso',
			'largo' => $calc->getLargoChapaPiso(),
			'ancho' => $calc->getAnchoChapaPiso(),
			'espesor' => $calc->getEspesorChapaPiso(),
			'pisosp1' => '',
			'cantp1' => $getCantidadChapaPiso['cantP1Piso'],
			'pisosp2' => '',
			'cantp2' => $getCantidadChapaPiso['cantP2Piso'],
			'pisosp3' => '',
			'cantp3' => $getCantidadChapaPiso['cantP3Piso'],
			'pisosp4' => '',
			'cantp4' => $getCantidadChapaPiso['cantP4Piso']
		);
		
		$data10 = array(			
			'descripcion' => 'Chapa piso adicional',
			'largo' => $calc->getLargoChapaAdicional(),
			'ancho' => '',
			'espesor' => '',
			'pisosp1' => '',
			'cantp1' => $getCantidadChapaAdicional['cantP1PisoAd'],
			'pisosp2' => '',
			'cantp2' => $getCantidadChapaAdicional['cantP2PisoAd'],
			'pisosp3' => '',
			'cantp3' => $getCantidadChapaAdicional['cantP3PisoAd'],
			'pisosp4' => '',
			'cantp4' => $getCantidadChapaAdicional['cantP4PisoAd'],
		);


		echo json_encode(array(
		'anchoCalc' => $getAnchoPaneles,
		'data' => array(
			$data1,	
			$data2,
			$data3,
			$data4,
			$data5,
			$data6,
			$data7,
			$data8,
			$data9,
			$data10
			)			
		));
				 
		 return new Response();
	}
	
	public function saveCalculoAction()
	{
		$req = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		$data = $req->request->get('data');        
		$info = json_decode($data);
		
		$rep = $em->getRepository('MbpProduccionBundle:CalculoRad');
		$rep->saveCalculo($info);
		
		return new Response();
	}
	
	/*
	 * Parametros Brazing
	 */
	public function readBrazingParamsAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpProduccionBundle:Brazing');		
		
		$data = $request->query->get('tipoCarga');
		
		$repo->readBrazingParamas($data);
		
		return new Response();
	}
	
	public function updateBrazingParamsAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpProduccionBundle:Brazing');
		
		$inf = $request->request->get('data');
		$data = json_decode($inf);
		
		$repo->updateBrazingParam($data);
		return new Response();
	}
}

























