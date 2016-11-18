<?php
namespace Mbp\ProduccionBundle\Clases\AceiteIntercooler;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManager;

class Calculo extends Controller
{
	protected $em;
	protected $requestStack;
	/*
	 *Variables de entrada de formulario 
	 */
	protected $tipo;					//Define Aire/Aceite | Intercooler
	protected $apoyoTapas;				//Define la distancia de apoyo de tapas
	protected $prof;					//Define la profundidad del panel
	protected $anchoPanel;				//Define el ancho del paquete
	protected $pisosManual;				//Define la cantidad de pisos de manera manual (sin calculo)
	protected $perfilIntermedio;		//Define si el panel lleva perfil intermedio
	protected $pisosManual7;			//Define la cantidad de pisos de 7 de manera manual
	protected $chapaPisoAdicional;		//Define si se utilizara chapa de piso adicional
	protected $cantChapaPisoAdicional;	//Define la cantidad adicional al utilizar chapa de piso
	protected $maxAlt;					//Define la altura maxima que podra tener un panel
	protected $cantPaneles;
	protected $abiertoCerrado;			//Define si la aleta será cerrada o abierta
	protected $aletaVenA;
	protected $aletaFluA;
	
	/*
	 * Variables estaticas materiales para refrigeracion lado ventilador
	 * */
	protected $espesorAleta10Ven;
	protected $espesorAleta7Ven;
	
	protected $altoPerfil10x12Ven;
	protected $anchoPerfil10x12Ven;
	
	protected $altoPerfil7x15Ven;
	protected $anchoPerfil7x15Ven;
	
	protected $adicionalCorte;
	
	/*
	 * Variables estaticas materiales para refrigeracion lado fluido
	 */
	protected $espesorAletaAceiteFlu;
	protected $espesorAire7Flu;
	protected $espesorAire10Flu;
	
	protected $espesorRecorteAletaAceiteFlu;
	protected $espesorRecorteAire7Flu;
	protected $espesorRecorteAire10Flu;
	
	protected $anchoPerfil2x8;
	protected $anchoPerfil7x6;
	protected $anchoPerfil10x9;
	
	/*
	 * Espesores de chapa
	 * */
	 protected $espesorChapaIntermedia;
	 protected $espesorChapaPiso;
	 protected $espesorChapaAdicional;
	
	/*
	 * Variables para el caluclo de materiales
	 */
	protected $aletaVentiladorTipo;
	protected $aletaVentiladorLargo;
	protected $aletaVentiladorAncho;
	protected $aletaVentiladorEspesor;
	protected $aletaVentiladorPisos;
	protected $aletaVentiladorCantidad;
	
	public function __construct(EntityManager $em, RequestStack $requestStack)
	{
		$this->em = $em;
		$this->requestStack = $requestStack;
		$this->espesorAleta10Ven = 9.9;
		$this->espesorAleta7Ven = 7;
		$this->altoPerfil10x12Ven = 10.1;
		$this->anchoPerfil10x12Ven = 12;
		$this->altoPerfil7x15Ven = 7;
		$this->anchoPerfil7x15Ven = 15;
		$this->espesorAletaAceiteFlu = 2.98;
		$this->espesorAire7Flu = 7;
		$this->espesorAire10Flu = 9.9;
		$this->espesorRecorteAletaAceiteFlu = 2.98;
		$this->espesorRecorteAire7Flu = 7;
		$this->espesorRecorteAire10Flu = 9.9;
		$this->anchoPerfil2x8 = 8.5;
		$this->anchoPerfil7x6 = 6;
		$this->anchoPerfil10x9 = 9;
		$this->espesorChapaIntermedia = 0.6;
		$this->espesorChapaPiso = 2;
		$this->espesorChapaAdicional = 5;
		$this->adicionalCorte = 1;
	}
	
	/*
	 * Inician las variables ingresadas por formulario
	 */
	public function setVariablesEntrada()
	{
		$request = $this->requestStack->getCurrentRequest();
		
		$this->tipo = $request->get('tipo');
		$this->apoyoTapas = $request->get('apoyoTapas');
		$this->prof = $request->get('prof');
		$this->anchoPanel = $request->get('ancho');
		$this->pisosManual = $request->get('pisosManual');
		$this->perfilIntermedio = $request->get('perfilIntermedio');
		$this->pisosManual7 = $request->get('pisosManual7');		
		$this->chapaPisoAdicional = $request->get('chapaPiso');
		$this->cantChapaPisoAdicional = $request->get('cantAdicional');
		$this->maxAlt = $request->get('maxAlt');
		$this->cantPaneles = $request->get('cantPaneles');
		$this->abiertoCerrado = $request->get('aletaTipo');
		$this->aletaVenA = $request->get('aletaVenA');
		$this->aletaFluA = $request->get('aletaFluA');
		
		return $this;
	}
	
	/*
	 * Calculos genericos del panel
	 */
	public function getTipoPanel()
	{
		if($this->tipo == 0){
			return 'Aire/Aceite';
		}else{
			return 'Intercooler';
		}
	}
    
    public function getTipoDeAleta()
    {
        return $this->abiertoCerrado;
    }
	 
	public function getPerfilIntStatus()
	{
		return $this->perfilIntermedio;
	}	
		
	public function cantidadTotalPisos()
	{
		$bloque1 = ($this->espesorChapaIntermedia + $this->espesorChapaPiso * 2 + $this->getEspesorAletaVen());
		$bloque2 = ($this->espesorChapaIntermedia * 2 + $this->getEspesorAletaVen() + $this->getEspesorAletaFlu());
		$pisosTeoricos =  (($this->anchoPanel - $bloque1) / $bloque2) + 1;		
		
		$pisosAdoptados = floor($pisosTeoricos); //(B)
		
		switch ($this->pisosManual) {
			case '0':
				return $pisosAdoptados;							
				break;
			
			case '':
				return $pisosAdoptados;							
				break;
			
			default:
				return $this->pisosManual;				
				break;
		}
	}	
	
	public function anchoCalculado()
	{
		$bloque1 = $this->espesorChapaIntermedia * 2 + $this->getEspesorAletaFlu() + $this->getEspesorAletaFlu();
		$bloque2 = $this->espesorChapaPiso * 2 + $this->espesorChapaIntermedia * 2 + $this->getEspesorAletaFlu();
		return ($this->cantidadTotalPisos() -1) * $bloque1 + $bloque2;		
	}
	
	//Calcula el ancho del aceite segun sea intercooler o aceite
	public function	aceiteAnchoTipo()
	{
		switch ($this->tipo) {
			case '0':
				return $this->aceiteMaxAncho = 216;
				break;
			
			default:
				return $this->aceiteMaxAncho = 180;
				break;
		}
	}
	
	public function calculoMultiplo()
	{
		if($this->apoyoTapas < $this->aceiteAnchoTipo()){
			return 1;			
		}else{
			return floor($this->apoyoTapas / $this->aceiteAnchoTipo());						
		}
	}
	
	//Calcula el ancho de cada panel
	public function getAnchoPaneles()
	{
		$getPisosAletaVen = $this->getPisosAletaVen();
		$getPisosAletaAdicional7 = $this->getPisosAletaAdicional7();
		$getPisosAletaFlu = $this->getPisosAletaFlu();
		$getCantidadChapaIntermedia = $this->getCantidadChapaIntermedia();
		$getCantidadChapaPiso = $this->getCantidadChapaPiso();
		$getCantidadChapaAdicional = $this->getCantidadChapaAdicional();
		
				$anchoP1 = $getPisosAletaVen['pisosAletaVenP1'] * $this->getEspesorAletaVen() + $getPisosAletaAdicional7['pisosP1Aleta7'] * $this->espesorAire7Flu + 
				$getPisosAletaFlu['pisosP1AletaFlu'] * $this->getEspesorAletaFlu() + $getCantidadChapaIntermedia['cantidadP1ChapaIntermedia'] * $this->getEspesorChapaIntermedia() +
				$getCantidadChapaPiso['cantP1Piso'] * $this->getEspesorChapaPiso() + $getCantidadChapaAdicional['cantP1PisoAd'] * $this->getEspesorChapaAdicional();
				
				$anchoP2 = $getPisosAletaVen['pisosAletaVenP2'] * $this->getEspesorAletaVen() + $getPisosAletaAdicional7['pisosP2Aleta7'] * $this->espesorAire7Flu + 
				$getPisosAletaFlu['pisosP2AletaFlu'] * $this->getEspesorAletaFlu() + $getCantidadChapaIntermedia['cantidadP2ChapaIntermedia'] * $this->getEspesorChapaIntermedia() +
				$getCantidadChapaPiso['cantP2Piso'] * $this->getEspesorChapaPiso() + $getCantidadChapaAdicional['cantP2PisoAd'] * $this->getEspesorChapaAdicional();
				
				$anchoP3 = $getPisosAletaVen['pisosAletaVenP3'] * $this->getEspesorAletaVen() + $getPisosAletaAdicional7['pisosP3Aleta7'] * $this->espesorAire7Flu + 
				$getPisosAletaFlu['pisosP3AletaFlu'] * $this->getEspesorAletaFlu() + $getCantidadChapaIntermedia['cantidadP3ChapaIntermedia'] * $this->getEspesorChapaIntermedia() +
				$getCantidadChapaPiso['cantP3Piso'] * $this->getEspesorChapaPiso() + $getCantidadChapaAdicional['cantP3PisoAd'] * $this->getEspesorChapaAdicional();
				
				$anchoP4 = $getPisosAletaVen['pisosAletaVenP4'] * $this->getEspesorAletaVen() + $getPisosAletaAdicional7['pisosP4Aleta7'] * $this->espesorAire7Flu + 
				$getPisosAletaFlu['pisosP4AletaFlu'] * $this->getEspesorAletaFlu() + $getCantidadChapaIntermedia['cantidadP4ChapaIntermedia'] * $this->getEspesorChapaIntermedia() +
				$getCantidadChapaPiso['cantP4Piso'] * $this->getEspesorChapaPiso() + $getCantidadChapaAdicional['cantP4PisoAd'] * $this->getEspesorChapaAdicional();
				
				$res = array(
					'anchoTotal' => $anchoP1 + $anchoP2 + $anchoP3 + $anchoP4,
					'anchoP1' => $anchoP1,
					'anchoP2' => $anchoP2,
					'anchoP3' => $anchoP3,
					'anchoP4' => $anchoP4,
				);
								
				return $res;		
	}
	
	/*
	 * Calculo de las caracteristicas de la aleta lado ventilador
	 */	
	public function getTipoAletaVen()
	{
		if($this->aletaVenA == 0){
			return 'Aleta Aire Ref. 7';
		}else{
			return 'Aleta Aire Ref. 10';
		}
	} 
	
	public function getLargoAletaVen()
	{
		return $this->apoyoTapas - (2 * ($this->anchoPerfil10x12Ven + $this->adicionalCorte));
	}
	
	public function getAnchoAletaVen()
	{
		return $this->prof;
	} 
	
	public function getEspesorAletaVen()
	{
		if($this->aletaVenA == 0){
			return $this->espesorAleta7Ven;
		}else{
			return $this->espesorAleta10Ven;
		}
	}
	
	public function getPisosAletaVen()
	{
		switch ($this->cantPaneles) {
			case '1':
				$res = array(
					'pisosAletaVenTotal' => $this->pisosManual > 1 ? $this->pisosManual : $this->cantidadTotalPisos(),
					'pisosAletaVenP1' => $this->pisosManual > 1 ? $this->pisosManual : $this->cantidadTotalPisos(),
					'pisosAletaVenP2' => 0,
					'pisosAletaVenP3' => 0,
					'pisosAletaVenP4' => 0
				);
				
				return $res;
				break;
			case '2':
				$res = array(
					'pisosAletaVenTotal' => $total = $this->pisosManual > 1 ? $this->pisosManual : $this->cantidadTotalPisos(),
					'pisosAletaVenP1' => $p1 = round($this->cantidadTotalPisos() / 2),
					'pisosAletaVenP2' => $total - $p1,
					'pisosAletaVenP3' => 0,
					'pisosAletaVenP4' => 0
				);
				
				return $res;
				break;
			
			case '3':
				$res = array(
					'pisosAletaVenTotal' => $total = $this->pisosManual > 1 ? $this->pisosManual : $this->cantidadTotalPisos(),
					'pisosAletaVenP1' => $p1 = round($total / 3),
					'pisosAletaVenP2' => $p2 = round(($total - $p1) / 2),
					'pisosAletaVenP3' => $p3 = $total - $p1 - $p2,
					'pisosAletaVenP4' => 0
				);
				
				return $res;
				break;
			
			case '4':
				$res = array(
					'pisosAletaVenTotal' => $total = $this->pisosManual > 1 ? $this->pisosManual : $this->cantidadTotalPisos(),
					'pisosAletaVenP1' => $p1 = round($total / 4),
					'pisosAletaVenP2' => $p2 = round(($total - $p1) / 3),
					'pisosAletaVenP3' => $p3 = round(($total - $p1 - $p2) / 2),
					'pisosAletaVenP4' => $p4 = $total - $p1 - $p2 - $p3
				);
				
				return $res;
				break;
			
			default:
				
				break;
		}
	}
	
	/*
	 * Calcula las caracteristicas de el perfil lado ventilador
	 */
	public function getTipoPerfilVen()
	 {
	 	if($this->aletaVenA == 0){
	 		return 'Perfil 7 x 15';
	 	}else{
	 		return 'Perfil 10.1 x 12';
	 	}
	 }
	 
	public function getLargoPerfilVen()
	 {
	 	return $this->prof;
	 }
	 
	public function getCantidadPerfilVen()
	 {
	 	$getPisosAletaVen = $this->getPisosAletaVen();
	 	
	 	switch ($this->cantPaneles) {
			 case '1':
				 $res = array(
				 	'cantidadTotalPerfilVen' => $getPisosAletaVen['pisosAletaVenTotal'] * 2,
				 	'cantPerfilVenP1' =>  $getPisosAletaVen['pisosAletaVenP1'] * 2,
				 	'cantPerfilVenP2' => 0,
				 	'cantPerfilVenP3' => 0,
				 	'cantPerfilVenP4' => 0
				 );
				 
				 return $res;
				 break;
			 
			 case '2':
				 $res = array(
				 	'cantidadTotalPerfilVen' => $getPisosAletaVen['pisosAletaVenTotal'] * 2,
				 	'cantPerfilVenP1' =>  $getPisosAletaVen['pisosAletaVenP1'] * 2,
				 	'cantPerfilVenP2' => $getPisosAletaVen['pisosAletaVenP2'] * 2,
				 	'cantPerfilVenP3' => 0,
				 	'cantPerfilVenP4' => 0
				 );
				 
				 return $res;
				 break;
				 
			case '3':
				$res = array(
				 	'cantidadTotalPerfilVen' => $getPisosAletaVen['pisosAletaVenTotal'] * 2,
				 	'cantPerfilVenP1' =>  $getPisosAletaVen['pisosAletaVenP1'] * 2,
				 	'cantPerfilVenP2' => $getPisosAletaVen['pisosAletaVenP2'] * 2,
				 	'cantPerfilVenP3' => $getPisosAletaVen['pisosAletaVenP3'] * 2,
				 	'cantPerfilVenP4' => 0
				 );
				 
				 return $res;
				break;
			 
			case '4':
				$res = array(
				 	'cantidadTotalPerfilVen' => $getPisosAletaVen['pisosAletaVenTotal'] * 2,
				 	'cantPerfilVenP1' =>  $getPisosAletaVen['pisosAletaVenP1'] * 2,
				 	'cantPerfilVenP2' => $getPisosAletaVen['pisosAletaVenP2'] * 2,
				 	'cantPerfilVenP3' => $getPisosAletaVen['pisosAletaVenP3'] * 2,
				 	'cantPerfilVenP4' => $getPisosAletaVen['pisosAletaVenP4'] * 2
				 );
				 
				 return $res;
				break;
			 
			 default:
				 
				 break;
		 }
	 }
	 
	 /*
	  * Calcula las caracteristicas del piso adicional de 7mm
	  */
	public function cantidadPisoAdicional7()
	 {
		$anchoCalculado = $this->anchoCalculado();
		$pisoDe7Largo = $this->getLargoPisoAdicional7();
		$dif = -1 * $this->anchoPanel + $this->anchoCalculado();	//-572 + 561.1 
		$calc = (-1 * $dif) / $pisoDe7Largo;
		$this->pisosDe7 = floor($calc);
		
		switch ($this->pisosManual) {
			case '':
				return 0;				
				break;
			case '0':
				return 0;
				break;
			default:
				return $this->pisosManual7;
				break;
		}
	 }
	
	public function getLargoPisoAdicional7()
	{		
		$res = $this->apoyoTapas - 2 * ($this->anchoPerfil7x15Ven + $this->adicionalCorte);
		return $res;
		
	}
	
	public function getAnchoPisoAdicional7()
	{
		if($this->getPisosAletaAdicional7() == 0){
			return 0;
		}else{
			return $this->prof;
		}
	}
	
	public function getEspesorPisoAdcional7()
	{
		return $this->espesorAleta7Ven;
	}
	  
	public function getPisosAletaAdicional7()
	{
	 	$case1 = ($this->cantidadPisoAdicional7() == 1 ? $this->cantidadPisoAdicional7() : $this->cantidadPisoAdicional7() == 2 ? round($this->cantidadPisoAdicional7() / 2) : $this->cantidadPisoAdicional7() == 3 ? $this->cantidadPisoAdicional7() / 3 : $this->cantidadPisoAdicional7() > 3 ? ceil($this->cantidadPisoAdicional7() / $this->cantPaneles) : 0);        
		$case2 = ($this->cantidadPisoAdicional7() == 2 ? round($this->cantidadPisoAdicional7() / 2) : $this->cantidadPisoAdicional7() == 3 ? round($this->cantidadPisoAdicional7() / 3) : $this->cantidadPisoAdicional7() > 3 ? ceil($this->cantidadPisoAdicional7() / $this->cantPaneles) : 0);
		$case3 = ($this->cantidadPisoAdicional7() == 3 ? round($this->cantidadPisoAdicional7() / 3) : $this->cantidadPisoAdicional7() > 3 ? round($this->cantidadPisoAdicional7() / $this->cantidadPisoAdicional7()) : 0);
		$case4 = ($this->cantidadPisoAdicional7() > 3 ? round($this->cantidadPisoAdicional7() / $this->cantidadPisoAdicional7()) : 0);
		
		switch ($this->cantPaneles) {
			case '1':
				$res = array(
					'pisosP1Aleta7' => $c1 = $this->cantidadPisoAdicional7(),
					'pisosP2Aleta7' => $c2 = 0,
					'pisosP3Aleta7' => $c3 = 0,
					'pisosP4Aleta7' => $c4 = 0,
					'totalAletaAdicional' => $c1 + $c2 + $c3 + $c4
				);
				
				return $res;
				break;
			
			case '2':
				$res = array(
					'pisosP1Aleta7' => $c1 = $case1,
					'pisosP2Aleta7' => $c2 = $case2,
					'pisosP3Aleta7' => $c3 = 0,
					'pisosP4Aleta7' => $c4 = 0,
					'totalAletaAdicional' => $c1 + $c2 + $c3 + $c4
				);
				
				return $res;
				break;
			
			case '3':
				$res = array(
					'pisosP1Aleta7' => $c1 = $case1,
					'pisosP2Aleta7' => $c2 = $case2,
					'pisosP3Aleta7' => $c3 = $case3,
					'pisosP4Aleta7' => $c4 = 0,
					'totalAletaAdicional' => $c1 + $c2 + $c3 + $c4
				);
				
				return $res;
				break;
			
			case '4':
				$res = array(
					'pisosP1Aleta7' => $c1 = $case1,
					'pisosP2Aleta7' => $c2 = $case2,
					'pisosP3Aleta7' => $c3 = $case3,
					'pisosP4Aleta7' => $c4 = $case4,
					'totalAletaAdicional' => $c1 + $c2 + $c3 + $c4
				);
				
				return $res;
				break;
			default:
				
				break;
		}
	}
		
	/*
	 * Calcula las caracteristicas del perfil para piso adicional de 7mm
	 */
	public function getTipoPerfilAdicional7()
	{
		return 'Perfil 7 x 15';
	}

	public function getLargoPerfilAdicional7()
	{
		return $this->prof;
	}
	
	public function getCantidadPerfilAdicional7()
	{
		$getPisosAletaAdicional7 = $this->getPisosAletaAdicional7();
		switch ($this->cantPaneles) {
			case '1':
				$res = array(
					'cantP1PerfilAd7' => $c1 = $getPisosAletaAdicional7['pisosP1Aleta7'] * 2,
					'cantP2PerfilAd7' => $c2 = 0,
					'cantP3PerfilAd7' => $c3 = 0,
					'cantP4PerfilAd7' => $c4 = 0,
					'totalPerfilAd' => $c1 + $c2 +$c3 + $c4
				);
				
				return $res;
				break;
			case '2':
				$res = array(
					'cantP1PerfilAd7' => $c1 = $getPisosAletaAdicional7['pisosP1Aleta7'] * 2,
					'cantP2PerfilAd7' => $c2 = $getPisosAletaAdicional7['pisosP2Aleta7'] * 2,
					'cantP3PerfilAd7' => $c3 = 0,
					'cantP4PerfilAd7' => $c4 = 0,
					'totalPerfilAd' => $c1 + $c2 +$c3 + $c4
				);
				
				return $res;
				break;
			case '3':
				$res = array(
					'cantP1PerfilAd7' => $c1 = $getPisosAletaAdicional7['pisosP1Aleta7'] * 2,
					'cantP2PerfilAd7' => $c2 = $getPisosAletaAdicional7['pisosP2Aleta7'] * 2,
					'cantP3PerfilAd7' => $c3 = $getPisosAletaAdicional7['pisosP3Aleta7'] * 2,
					'cantP4PerfilAd7' => $c4 = 0,
					'totalPerfilAd' => $c1 + $c2 +$c3 + $c4
				);
				
				return $res;
				break;
			case '4':
				$res = array(
					'cantP1PerfilAd7' => $c1 = $getPisosAletaAdicional7['pisosP1Aleta7'] * 2,
					'cantP2PerfilAd7' => $c2 = $getPisosAletaAdicional7['pisosP2Aleta7'] * 2,
					'cantP3PerfilAd7' => $c3 = $getPisosAletaAdicional7['pisosP3Aleta7'] * 2,
					'cantP4PerfilAd7' => $c4 = $getPisosAletaAdicional7['pisosP4Aleta7'] * 2,
					'totalPerfilAd' => $c1 + $c2 +$c3 + $c4
				);
				
				return $res;
				break;
			default:
				
				break;
		}
		
	}
	
	 
	
	/*
	 * Calculo de las caracteristicas de la aleta lado FLUIDO
	 */
	public function getTipoAletaFlu()
	{
		if($this->tipo == 1){
			if($this->aletaFluA == 1){
				return 'Aleta Aire 10';
			}else{
				return 'Aleta Aire 7';
			}
		}else{
			return 'Aleta de Aceite';
		}
	}
	
	public function getLargoAletaFlu()
	{
		if($this->tipo == 0){
			if($this->apoyoTapas < 216){
				return $this->apoyoTapas;
			}else{return 216;}			
		}else{
			if($this->apoyoTapas < 180){
				return $this->apoyoTapas;
			}else{return 180;}
		}
	}
	
	public function getAnchoAletaFlu()
	{
		if($this->perfilIntermedio == 1){
			return ($this->prof - 3 * ($this->getAnchoPerfilFlu() + $this->adicionalCorte)) / 2;
		}else{
			return $this->prof - 2 * ($this->getAnchoPerfilFlu() + $this->adicionalCorte);
		}
	}
	
	public function getEspesorAletaFlu()
	{
	 	if($this->tipo == 1){
			if($this->aletaFluA == 1){
				return $this->espesorAire10Flu;				
			}else{
				return $this->espesorAire7Flu;
			}				
		}else{
			return $this->espesorAletaAceiteFlu;
		}
	}
	
	public function getPisosAletaFlu()
	{
		$getPisosAletaAdicional7 = $this->getPisosAletaAdicional7();		
		$getPisosAletaVen = $this->getPisosAletaVen();
		
		switch ($this->cantPaneles) {
			case '1':
				$res = array(					
					'pisosP1AletaFlu' => $c1 = $getPisosAletaAdicional7['pisosP1Aleta7'] + $getPisosAletaVen['pisosAletaVenP1'] - 1,
					'pisosP2AletaFlu' => $c2 = 0,
					'pisosP3AletaFlu' => $c3 = 0,
					'pisosP4AletaFlu' => $c4 = 0,
					'totalPisosAletaFluido' => $c1 + $c2 + $c3 + $c4,
				);
				
				return $res;
				break;
			case '2':
				$res = array(					
					'pisosP1AletaFlu' => $c1 = $getPisosAletaAdicional7['pisosP1Aleta7'] + $getPisosAletaVen['pisosAletaVenP1'] - 1,
					'pisosP2AletaFlu' => $c2 = $getPisosAletaAdicional7['pisosP2Aleta7'] + $getPisosAletaVen['pisosAletaVenP2'] - 1,
					'pisosP3AletaFlu' => $c3 = 0,
					'pisosP4AletaFlu' => $c4 = 0,
					'totalPisosAletaFluido' => $c1 + $c2 + $c3 + $c4,
				);
				
				return $res;
				break;
			case '3':
				$res = array(					
					'pisosP1AletaFlu' => $c1 = $getPisosAletaAdicional7['pisosP1Aleta7'] + $getPisosAletaVen['pisosAletaVenP1'] - 1,
					'pisosP2AletaFlu' => $c1 = $getPisosAletaAdicional7['pisosP2Aleta7'] + $getPisosAletaVen['pisosAletaVenP2'] - 1,
					'pisosP3AletaFlu' => $c1 = $getPisosAletaAdicional7['pisosP3Aleta7'] + $getPisosAletaVen['pisosAletaVenP3'] - 1,
					'pisosP4AletaFlu' => $c1 = 0,
					'totalPisosAletaFluido' => $c1 + $c2 + $c3 + $c4,
				);
				
				return $res;
				break;
			case '4':
				$res = array(					
					'pisosP1AletaFlu' => $c1 = $getPisosAletaAdicional7['pisosP1Aleta7'] + $getPisosAletaVen['pisosAletaVenP1'] - 1,
					'pisosP2AletaFlu' => $c2 = $getPisosAletaAdicional7['pisosP2Aleta7'] + $getPisosAletaVen['pisosAletaVenP2'] - 1,
					'pisosP3AletaFlu' => $c3 = $getPisosAletaAdicional7['pisosP3Aleta7'] + $getPisosAletaVen['pisosAletaVenP3'] - 1,
					'pisosP4AletaFlu' => $c4 = $getPisosAletaAdicional7['pisosP4Aleta7'] + $getPisosAletaVen['pisosAletaVenP4'] - 1,
					'totalPisosAletaFluido' => $c1 + $c2 + $c3 + $c4,
				);
				
				return $res;
				break;
			default:
				
				break;
		}
	}
	
	public function	getCantidadAletaFlu()
	{
		$getPisosAletaAdicional7 = $this->getPisosAletaAdicional7();		
		$getPisosAletaVen = $this->getPisosAletaVen();
		
		switch ($this->cantPaneles) {
			case '1':
				$res = array(
					'cantidadTotalAletaFlu' => $this->perfilIntermedio == 1 ?  ($getPisosAletaVen['pisosAletaVenTotal'] + $this->cantidadPisoAdicional7() - 1) * 2 : ($getPisosAletaVen['pisosAletaVenTotal'] + $this->cantidadPisoAdicional7() - 1) * $this->calculoMultiplo(),
					'cantidadP1AletaFlu' => $this->perfilIntermedio == 1 ? ($getPisosAletaVen['pisosAletaVenP1'] + $getPisosAletaAdicional7['pisosP1Aleta7'] - 1) * 2 : ($getPisosAletaVen['pisosAletaVenP1'] + $getPisosAletaAdicional7['pisosP1Aleta7'] - 1) * $this->calculoMultiplo(),
					'cantidadP2AletaFlu' => 0,
					'cantidadP3AletaFlu' => 0,
					'cantidadP4AletaFlu' => 0,
				);
				
				return $res;
				break;
			case '2':
				$res = array(
					'cantidadTotalAletaFlu' => $this->perfilIntermedio == 1 ?  ($getPisosAletaVen['pisosAletaVenTotal'] + $this->cantidadPisoAdicional7() - 1) * 2 : ($getPisosAletaVen['pisosAletaVenTotal'] + $this->cantidadPisoAdicional7() - 1) * $this->calculoMultiplo(),
					'cantidadP1AletaFlu' => $this->perfilIntermedio == 1 ? ($getPisosAletaVen['pisosAletaVenP1'] + $getPisosAletaAdicional7['pisosP1Aleta7'] - 1) * 2 : ($getPisosAletaVen['pisosAletaVenP1'] + $getPisosAletaAdicional7['pisosP1Aleta7'] - 1) * $this->calculoMultiplo(),
					'cantidadP2AletaFlu' => $this->perfilIntermedio == 1 ? ($getPisosAletaVen['pisosAletaVenP2'] + $getPisosAletaAdicional7['pisosP2Aleta7'] - 1) * 2 : ($getPisosAletaVen['pisosAletaVenP2'] + $getPisosAletaAdicional7['pisosP1Aleta7'] - 1) * $this->calculoMultiplo(),
					'cantidadP3AletaFlu' => 0,
					'cantidadP4AletaFlu' => 0,
				);
				
				return $res;
				break;
			case '3':
				$res = array(
					'cantidadTotalAletaFlu' => $this->perfilIntermedio == 1 ?  ($getPisosAletaVen['pisosAletaVenTotal'] + $this->cantidadPisoAdicional7() - 1) * 2 : ($getPisosAletaVen['pisosAletaVenTotal'] + $this->cantidadPisoAdicional7() - 1) * $this->calculoMultiplo(),
					'cantidadP1AletaFlu' => $this->perfilIntermedio == 1 ? ($getPisosAletaVen['pisosAletaVenP1'] + $getPisosAletaAdicional7['pisosP1Aleta7'] - 1) * 2 : ($getPisosAletaVen['pisosAletaVenP1'] + $getPisosAletaAdicional7['pisosP1Aleta7'] - 1) * $this->calculoMultiplo(),
					'cantidadP2AletaFlu' => $this->perfilIntermedio == 1 ? ($getPisosAletaVen['pisosAletaVenP2'] + $getPisosAletaAdicional7['pisosP2Aleta7'] - 1) * 2 : ($getPisosAletaVen['pisosAletaVenP2'] + $getPisosAletaAdicional7['pisosP1Aleta7'] - 1) * $this->calculoMultiplo(),
					'cantidadP3AletaFlu' => $this->perfilIntermedio == 1 ? ($getPisosAletaVen['pisosAletaVenP3'] + $getPisosAletaAdicional7['pisosP3Aleta7'] - 1) * 2 : ($getPisosAletaVen['pisosAletaVenP3'] + $getPisosAletaAdicional7['pisosP1Aleta7'] - 1) * $this->calculoMultiplo(),
					'cantidadP4AletaFlu' => 0,
				);
				
				return $res;
				break;
			case '4':
				$res = array(
					'cantidadTotalAletaFlu' => $this->perfilIntermedio == 1 ?  ($getPisosAletaVen['pisosAletaVenTotal'] + $this->cantidadPisoAdicional7() - 1) * 2 : ($getPisosAletaVen['pisosAletaVenTotal'] + $this->cantidadPisoAdicional7() - 1) * $this->calculoMultiplo(),
					'cantidadP1AletaFlu' => $this->perfilIntermedio == 1 ? ($getPisosAletaVen['pisosAletaVenP1'] + $getPisosAletaAdicional7['pisosP1Aleta7'] - 1) * 2 : ($getPisosAletaVen['pisosAletaVenP1'] + $getPisosAletaAdicional7['pisosP1Aleta7'] - 1) * $this->calculoMultiplo(),
					'cantidadP2AletaFlu' => $this->perfilIntermedio == 1 ? ($getPisosAletaVen['pisosAletaVenP2'] + $getPisosAletaAdicional7['pisosP2Aleta7'] - 1) * 2 : ($getPisosAletaVen['pisosAletaVenP2'] + $getPisosAletaAdicional7['pisosP2Aleta7'] - 1) * $this->calculoMultiplo(),
					'cantidadP3AletaFlu' => $this->perfilIntermedio == 1 ? ($getPisosAletaVen['pisosAletaVenP3'] + $getPisosAletaAdicional7['pisosP3Aleta7'] - 1) * 2 : ($getPisosAletaVen['pisosAletaVenP3'] + $getPisosAletaAdicional7['pisosP3Aleta7'] - 1) * $this->calculoMultiplo(),
					'cantidadP4AletaFlu' => $this->perfilIntermedio == 1 ? ($getPisosAletaVen['pisosAletaVenP4'] + $getPisosAletaAdicional7['pisosP4Aleta7'] - 1) * 2 : ($getPisosAletaVen['pisosAletaVenP4'] + $getPisosAletaAdicional7['pisosP4Aleta7'] - 1) * $this->calculoMultiplo()
				);
				
				return $res;
				break;	
			default:
				
				break;
		}
	}
	
	/*
	 * Calculo de las caracteristicas del recorte lado FLUIDO
	 */
	public function getTipoRecorteFlu()
	{
		if($this->tipo == 1){
			if($this->aletaFluA == 1){
				return 'Recorte Aire 10';
			}else{return 'Recorte Aire 7';}
		}else{return 'Recorte de Aceite';}
	}
	
	public function getLargoRecorteFlu()
	{
		return $this->apoyoTapas - ($this->getLargoAletaFlu() * $this->calculoMultiplo());
	}
	
	public function getAnchoRecorteFlu()
	{
		return $this->getAnchoAletaFlu();
	}
	
	public function getEspesorRecorteFlu()
	{
		return $this->getEspesorAletaFlu();
	}
	
	public function getCantidadRecorteFlu()
	{
		$getPisosAletaFlu = $this->getPisosAletaFlu();
		
		$res = array(
			'totalRecorteFlu' => $getPisosAletaFlu['totalPisosAletaFluido'],
			'cantP1RecorteFlu' => $getPisosAletaFlu['pisosP1AletaFlu'],
			'cantP2RecorteFlu' => $getPisosAletaFlu['pisosP2AletaFlu'],
			'cantP3RecorteFlu' => $getPisosAletaFlu['pisosP3AletaFlu'],
			'cantP4RecorteFlu' => $getPisosAletaFlu['pisosP4AletaFlu'],
		);
		
		return $res;
	}
	
	/*
	 * Calculo de las caracteristicas del perfil lado FLUIDO
	 */
	public function getTipoPerfilFlu()
	{
		if($this->tipo == 1){
			if($this->aletaFluA == 1){
				return 'Perfil 10,1 x 9';
			}else{return 'Perfil 7x 6';}
		}else{return 'Perfil 2,6 x 8,5';}
	} 
	
	public function getLargoPerfilFlu()
	{
		if($this->tipo == 0){
			return $this->apoyoTapas + 2 * 4; //El 4 es el sobrante de aceite, una constante sacada de la planilla de calculo
		}else{
			return $this->apoyoTapas;
		}
	}
	
	public function getCantidadPerfilFlu()	
	{
		$getPisosAletaFlu = $this->getPisosAletaFlu();
		
		$res = array(
			'totalPerfilesFluido' => $this->perfilIntermedio == 1 ? $getPisosAletaFlu['totalPisosAletaFluido'] * 3 : $getPisosAletaFlu['totalPisosAletaFluido'] * 2,
			'perfilesP1Fluido' => $this->perfilIntermedio == 1 ? $getPisosAletaFlu['pisosP1AletaFlu'] * 3 : $getPisosAletaFlu['pisosP1AletaFlu'] * 2,
			'perfilesP2Fluido' => $this->perfilIntermedio == 1 ? $getPisosAletaFlu['pisosP2AletaFlu'] * 3 : $getPisosAletaFlu['pisosP2AletaFlu'] * 2,
			'perfilesP3Fluido' => $this->perfilIntermedio == 1 ? $getPisosAletaFlu['pisosP3AletaFlu'] * 3 : $getPisosAletaFlu['pisosP3AletaFlu'] * 2,
			'perfilesP4Fluido' => $this->perfilIntermedio == 1 ? $getPisosAletaFlu['pisosP4AletaFlu'] * 3 : $getPisosAletaFlu['pisosP4AletaFlu'] * 2,
		);
		return $res;
	}
	
	public function getAnchoPerfilFlu()
	{
		if($this->tipo == 1){
			if($this->aletaFluA == 1){
				return $this->anchoPerfil10x9;
			}else{return $this->anchoPerfil7x6;}
		}else{return $this->anchoPerfil2x8;}
	}
	
	/*
	 * Calcula las caracteristicas de la chapa intermedia
	 */
	public function getLargoChapaIntermedia()
	{
		return $this->apoyoTapas;
	}	
	
	public function getAnchoChapaIntermedia()
	{
		return $this->prof;
	}
	
	public function getEspesorChapaIntermedia()
	{
		return $this->espesorChapaIntermedia;
	}
	
	public function getCantidadChapaIntermedia()
	{
		$getPisosAletaFlu = $this->getPisosAletaFlu();
		switch ($this->cantPaneles) {
			case '1':
				$res = array(
					'totalChapaIntermedia' => $getPisosAletaFlu['totalPisosAletaFluido'] * 2 + 2,
					'cantidadP1ChapaIntermedia' => $getPisosAletaFlu['pisosP1AletaFlu'] * 2 + 2,
					'cantidadP2ChapaIntermedia' => 0,
					'cantidadP3ChapaIntermedia' => 0,
					'cantidadP4ChapaIntermedia' => 0,
				);
				return $res;
				break;
			case '2':
				$res = array(
					'totalChapaIntermedia' => $getPisosAletaFlu['totalPisosAletaFluido'] * 2 + 2,
					'cantidadP1ChapaIntermedia' => $getPisosAletaFlu['pisosP1AletaFlu'] * 2 + 2,
					'cantidadP2ChapaIntermedia' => $getPisosAletaFlu['pisosP2AletaFlu'] * 2 + 2,
					'cantidadP3ChapaIntermedia' => 0,
					'cantidadP4ChapaIntermedia' => 0,
				);
				return $res;
				break;
			case '3':
				$res = array(
					'totalChapaIntermedia' => $getPisosAletaFlu['totalPisosAletaFluido'] * 2 + 2,
					'cantidadP1ChapaIntermedia' => $getPisosAletaFlu['pisosP1AletaFlu'] * 2 + 2,
					'cantidadP2ChapaIntermedia' => $getPisosAletaFlu['pisosP2AletaFlu'] * 2 + 2,
					'cantidadP3ChapaIntermedia' => $getPisosAletaFlu['pisosP3AletaFlu'] * 2 + 2,
					'cantidadP4ChapaIntermedia' => 0,
				);
				return $res;
				break;
			case '4':
				$res = array(
					'totalChapaIntermedia' => $getPisosAletaFlu['totalPisosAletaFluido'] * 2 + 2,
					'cantidadP1ChapaIntermedia' => $getPisosAletaFlu['pisosP1AletaFlu'] * 2 + 2,
					'cantidadP2ChapaIntermedia' => $getPisosAletaFlu['pisosP2AletaFlu'] * 2 + 2,
					'cantidadP3ChapaIntermedia' => $getPisosAletaFlu['pisosP3AletaFlu'] * 2 + 2,
					'cantidadP4ChapaIntermedia' => $getPisosAletaFlu['pisosP4AletaFlu'] * 2 + 2,
				);
				return $res;
				break;
			default:
				
				break;
		}
		
	}

	/*
	 * Calcula las caracteristicas de la chapa de piso
	 */
	public function getLargoChapaPiso()
	{
		return $this->apoyoTapas;
	}
	
	public function getAnchoChapaPiso()
	{
		return $this->prof;
	}
	
	public function getEspesorChapaPiso()
	{
		return $this->espesorChapaPiso;
	}
	
	public function getCantidadChapaPiso()
	{
		switch ($this->cantPaneles) {
            case '1':                 
                if($this->chapaPisoAdicional == 1 & $this->cantChapaPisoAdicional == 1){
                    return $res = array(
						'cantP1Piso' => $c1 = 1,
						'cantP2Piso' => $c2 = 0,
						'cantP3Piso' => $c3 = 0,
						'cantP4Piso' => $c4 = 0,
						'totalChapasPiso' => $c1 + $c2 + $c3 + $c4
					);
                }
				
				if($this->chapaPisoAdicional == 1 & $this->cantChapaPisoAdicional == 2){
                    return $res = array(
						'cantP1Piso' => $c1 = 0,
						'cantP2Piso' => $c2 = 0,
						'cantP3Piso' => $c3 = 0,
						'cantP4Piso' => $c4 = 0,
						'totalChapasPiso' => $c1 + $c2 + $c3 + $c4
					);
                }
				
				if($this->chapaPisoAdicional == 0){
					return $res = array(
						'cantP1Piso' => $c1 = 2,
						'cantP2Piso' => $c2 = 0,
						'cantP3Piso' => $c3 = 0,
						'cantP4Piso' => $c4 = 0,
						'totalChapasPiso' => $c1 + $c2 + $c3 + $c4
					);
				}
                           
                break;
            case '2':
                if($this->chapaPisoAdicional == 1 & $this->cantChapaPisoAdicional == 1){
                    return $res = array(
						'cantP1Piso' => $c1 = 2,
						'cantP2Piso' => $c2 = 1,
						'cantP3Piso' => $c3 = 0,
						'cantP4Piso' => $c4 = 0,
						'totalChapasPiso' => $c1 + $c2 + $c3 + $c4
					);
                }
				if($this->chapaPisoAdicional == 1 & $this->cantChapaPisoAdicional == 2){
                    return $res = array(
						'cantP1Piso' => $c1 = 1,
						'cantP2Piso' => $c2 = 1,
						'cantP3Piso' => $c3 = 0,
						'cantP4Piso' => $c4 = 0,
						'totalChapasPiso' => $c1 + $c2 + $c3 + $c4
					);
                }
				
				if($this->chapaPisoAdicional == 0){
					return $res = array(
						'cantP1Piso' => $c1 = 2,
						'cantP2Piso' => $c2 = 2,
						'cantP3Piso' => $c3 = 0,
						'cantP4Piso' => $c4 = 0,
						'totalChapasPiso' => $c1 + $c2 + $c3 + $c4
					);
				}
                
                break;
            case '3':
                if($this->chapaPisoAdicional == 1 & $this->cantChapaPisoAdicional == 1){
                    return $res = array(
						'cantP1Piso' => $c1 = 2,
						'cantP2Piso' => $c2 = 2,
						'cantP3Piso' => $c3 = 1,
						'cantP4Piso' => $c4 = 0,
						'totalChapasPiso' => $c1 + $c2 + $c3 + $c4
					);
                }
				if($this->chapaPisoAdicional == 1 & $this->cantChapaPisoAdicional == 2){
                    return $res = array(
						'cantP1Piso' => $c1 = 1,
						'cantP2Piso' => $c2 = 2,
						'cantP3Piso' => $c3 = 1,
						'cantP4Piso' => $c4 = 0,
						'totalChapasPiso' => $c1 + $c2 + $c3 + $c4
					);
                }
				
				if($this->chapaPisoAdicional == 0){
					return $res = array(
						'cantP1Piso' => $c1 = 2,
						'cantP2Piso' => $c2 = 2,
						'cantP3Piso' => $c3 = 2,
						'cantP4Piso' => $c4 = 0,
						'totalChapasPiso' => $c1 + $c2 + $c3 + $c4
					);
				}              
                break;
            case '4':
                if($this->chapaPisoAdicional == 1 & $this->cantChapaPisoAdicional == 1){
                    return $res = array(
						'cantP1Piso' => $c1 = 2,
						'cantP2Piso' => $c2 = 2,
						'cantP3Piso' => $c3 = 2,
						'cantP4Piso' => $c4 = 1,
						'totalChapasPiso' => $c1 + $c2 + $c3 + $c4
					);
                }
				if($this->chapaPisoAdicional == 1 & $this->cantChapaPisoAdicional == 2){
                    return $res = array(
						'cantP1Piso' => $c1 = 1,
						'cantP2Piso' => $c2 = 2,
						'cantP3Piso' => $c3 = 2,
						'cantP4Piso' => $c4 = 1,
						'totalChapasPiso' => $c1 + $c2 + $c3 + $c4
					);
                }
				
				if($this->chapaPisoAdicional == 0){
					return $res = array(
						'cantP1Piso' => $c1 = 2,
						'cantP2Piso' => $c2 = 2,
						'cantP3Piso' => $c3 = 2,
						'cantP4Piso' => $c4 = 2,
						'totalChapasPiso' => $c1 + $c2 + $c3 + $c4
					);
				}
                break;
            default:
                
                break;
        }
	}

	
	/*
	 * Calcula las caracteristicas de la chapa de piso adicional
	 */
	public function getLargoChapaAdicional()
	{
		if($this->chapaPisoAdicional == 1){
			return $this->apoyoTapas;
		}else{return 0;}
	}
	
	public function getAnchoChapaAdicional()
	{
		if($this->chapaPisoAdicional == 1){
			return $this->prof;
		}else{return 0;}
	}
	
	public function getEspesorChapaAdicional()
	{
		if($this->chapaPisoAdicional == 1){
			return $this->espesorChapaAdicional;
		}else{return 0;}
	}
	
	public function getCantidadChapaAdicional()
	{	
		switch ($this->cantPaneles) {
            case '1':
				$res = array(					
					'cantP1PisoAd' => $c1 = $this->cantChapaPisoAdicional,
					'cantP2PisoAd' => $c2 = 0,
					'cantP3PisoAd' => $c3 = 0,
					'cantP4PisoAd' => $c4 = 0,
					'totalChapaPisoAd' => $c1 + $c2 + $c3 + $c4,
				);
                return $res;
				
                break;
            case '2':
				$res = array(					
					'cantP1PisoAd' => $c1 = $this->cantChapaPisoAdicional > 1 ? $this->cantChapaPisoAdicional - 1 : $this->cantChapaPisoAdicional,
					'cantP2PisoAd' => $c2 = $this->cantChapaPisoAdicional > 1 ? 1 : 0, 
					'cantP3PisoAd' => $c3 = 0,
					'cantP4PisoAd' => $c4 = 0,
					'totalChapaPisoAd' => $c1 + $c2 + $c3 + $c4					
				);
                return $res;
                
                break;
            case '3':
				$res = array(					
					'cantP1PisoAd' => $c1 = $this->cantChapaPisoAdicional > 1 ? $this->cantChapaPisoAdicional - 1 : $this->cantChapaPisoAdicional,
					'cantP2PisoAd' => $c2 = $this->cantChapaPisoAdicional > 1 ? 1 : 0, 
					'cantP3PisoAd' => $c3 = 0,
					'cantP4PisoAd' => $c4 = 0,
					'totalChapaPisoAd' => $c1 + $c2 + $c3 + $c4	
				);
                return $res;                
                
                break;
            case '4':
				$res = array(
					'cantP1PisoAd' => $c1 = $this->cantChapaPisoAdicional > 1 ? $this->cantChapaPisoAdicional - 1 : $this->cantChapaPisoAdicional,
					'cantP2PisoAd' => $c2 = $this->cantChapaPisoAdicional > 1 ? 1 : 0, 
					'cantP3PisoAd' => $c3 = 0,
					'cantP4PisoAd' => $c4 = 0,
					'totalChapaPisoAd' => $c1 + $c2 + $c3 + $c4						
				);
                return $res; 
                break;
            default:
                
                break;
        }
	}

	/*
	 * Getters y Setters de las variables de entrada
	 */
	
	/*
	 * Tipo
	 */
	public function getTipo(){
		return $this->tipo;
	}
	public function setTipo($tipo){
		return $this->tipo = $tipo;
	}
	/*
	 * apoyoTapas
	 */
	public function getapoyoTapas(){
		return $this->apoyoTapas;
	}
	public function setapoyoTapas($apoyoTapas){
		return $this->apoyoTapas = $apoyoTapas;
	}
	/*
	 * prof
	 */
	public function getprof(){
		return $this->prof;
	}
	public function setprof($prof){
		return $this->prof = $prof;
	}
	/*
	 * anchoPanel
	 */
	public function getanchoPanel(){
		return $this->anchoPanel;
	}
	public function setanchoPanel($anchoPanel){
		return $this->anchoPanel = $anchoPanel;
	}
	/*
	 * pisosManual
	 */
	public function getpisosManual(){
		return $this->pisosManual;
	}
	public function setpisosManual($pisosManual){
		return $this->pisosManual = $pisosManual;
	}
	/*
	 * perfilIntermedio
	 */
	public function getperfilIntermedio(){
		return $this->perfilIntermedio;
	}
	public function setperfilIntermedio($perfilIntermedio){
		return $this->perfilIntermedio = $perfilIntermedio;
	}
	/*
	 * pisosManual7
	 */
	public function getpisosManual7(){
		return $this->pisosManual7;
	}
	public function setpisosManual7($pisosManual7){
		return $this->pisosManual7 = $pisosManual7;
	}
	/*
	 * chapaPisoAdicional
	 */
	public function getchapaPisoAdicional(){
		return $this->chapaPisoAdicional;
	}
	public function setchapaPisoAdicional($chapaPisoAdicional){
		return $this->chapaPisoAdicional = $chapaPisoAdicional;
	}
	/*
	 * cantChapaPisoAdicional
	 */
	public function getcantChapaPisoAdicional(){
		return $this->cantChapaPisoAdicional;
	}
	public function setcantChapaPisoAdicional($cantChapaPisoAdicional){
		return $this->cantChapaPisoAdicional = $cantChapaPisoAdicional;
	}
	/*
	 * maxAlt
	 */
	public function getmaxAlt(){
		return $this->maxAlt;
	}
	public function setmaxAlt($maxAlt){
		return $this->maxAlt = $maxAlt;
	}
	/*
	 * cantPaneles
	 */
	public function getcantPaneles(){
		return $this->cantPaneles;
	}
	public function setcantPaneles($cantPaneles){
		return $this->cantPaneles = $cantPaneles;
	}
	/*
	 * abiertoCerrado
	 */
	public function getabiertoCerrado(){
		return $this->abiertoCerrado;
	}
	public function setabiertoCerrado($abiertoCerrado){
		return $this->abiertoCerrado = $abiertoCerrado;
	}
	/*
	 * aletaVenA
	 */
	public function getaletaVenA(){
		return $this->aletaVenA;
	}
	public function setaletaVenA($aletaVenA){
		return $this->aletaVenA = $aletaVenA;
	}
	/*
	 * aletaFluA
	 */
	public function getaletaFluA(){
		return $this->aletaFluA;
	}
	public function setaletaFluA($aletaFluA){
		return $this->aletaFluA = $aletaFluA;
	}
}


















