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
	protected $chapaPiso;				//Define si se utilizara chapa de piso adicional
	protected $cantAdicional;			//Define la cantidad adicional al utilizar chapa de piso
	protected $maxAlt;					//Define la altura maxima que podra tener un panel
	protected $abiertoCerrado;			//Define si la aleta será cerrada o abierta
	
	/*
	 * Variables internas de calculo
	 */
	protected $aireRefrigeracionNormal;
	protected $cantPaneles;				//Define la cantidad de paneles segun la altura maxima	
	protected $chapaAdC;				//Define la cantidad de pisos adicionales
	protected $chapaAdL;				//Define el largo de pisos adicionales
	protected $chapaAdA;				//Define el ancho de pisos adicionales
	protected $chapaAdE;				//Define el espesor de pisos adicionales
	protected $multiplo;				//Define una variable para el calcula de componentes del panel			
	protected $aceiteMaxAncho;			//Define el ancho del aceite para aceite o intercooler	
	protected $anchoCalculado;			//Define el ancho calculado del panel segun las dimesiones de todos sus componentes
	protected $anchoRecalc;				//Define el ancho recalculado del panel con los pisos manuales
	protected $pisosDe7;				//Define la cantidad de pisos de aire de 7mm
	protected $pisoDe7Largo;			//Define la longitud del piso de 7 de acuero a los componentes del conjunto	
	protected $chapaPisoC;				//Define la cantidad de chapas de piso
	protected $chapaPisoL;				//Define el largo de chapas de piso
	protected $chapaPisoA;				//Define el ancho de chapas de piso
	protected $chapaPisoE;				//Define el espesor de chapas de piso
	protected $chIntC;					//Define la cantidad de chapas intermedias
	protected $chIntL;					//Define el largo de la chapa intermedia
	protected $chIntA;					//Define el ancho de la chapa intermedia
	protected $chapaIntermediaEsp;		//Define el espesor de la chapa intermedia	
	protected $aire10C;					//Define la cantidad de aletas de aire
	protected $aire10E;					//Define el espesor o altura de la aleta de aire de Referencia 10mm
	protected $aire10L;					//Define el largo de la aleta de aire de Referencia 10mm
	protected $aire10A;					//Define el ancho de la aleta de aire de Referencia 10mm
	protected $aire7C;					//Define la cantidad aleta de aire de Referencia 7mm
	protected $aire7E;					//Define el espesor o altura de la aleta de aire de Referencia 7mm
	protected $aire7L;					//Define el largo de la aleta de aire de Referencia 7mm
	protected $aire7A;					//Define el ancho de la aleta de aire de Referencia 7mm	
	protected $aleta7IntercoolerC;		//Define la cantidad de aletas de Intercooler de 7
	protected $aleta7IntercoolerL;		//Define el largo de aletas de Intercooler de 7
	protected $aleta7IntercoolerA;		//Define el ancho de aletas de Intercooler de 7
	protected $aleta7IntercoolerE;		//Define el espesor de aletas de Intercooler de 7
	protected $recorte7IntercoolerC;
	protected $recorte7IntercoolerL;
	protected $recorte7IntercoolerA;
	protected $recorte7IntercoolerE;
	protected $per7x6IntercoolerC;
	protected $per7x6IntercoolerL;
	protected $per7x6IntercoolerA;
	protected $per7x6IntercoolerE;
	protected $aceite;					//Define la cantidad de aletas de aceite
	protected $aceiteP;					//Define la cantidad de pisos de aceite
	protected $aceiteE;					//Define la altura o espesor de la aleta de aceite de Referencia 3mm
	protected $aceiteL;					//Define el largo de las aletas de aceite
	protected $aceiteA;					//Define el ancho de la aleta de aceite
	protected $recorteA;				//Define la cantidad de recortes a utilizar
	protected $recorteAe;				//Define el espesor o altura del recorte de aceite
	protected $recorteAl;				//Define el espesor o altura del recorte de aceite
	protected $recorteAa;				//Define el ancho del recorte de aceite
	protected $per1;					//Define la cantidad de perfiles de Referencia 2.6x8.5
	protected $per1E;					//Define el espesor del perfil de Referencia 2.6x8.5
	protected $per1L;					//Define el largo del perfil de Referencia 2.6x8.5
	protected $per1A;					//Define el ancho del perfil de Referencia 2.6x8.5
	protected $per2;					//Define la cantidad de perfiles de Referencia 7x6
	protected $per2E;					//Define el espesor del perfil de Referencia 7x6
	protected $per2L;					//Define el largo del perfil de Referencia 7x6
	protected $per2A;					//Define el ancho del perfil de Referencia 7x6
	protected $per3;					//Define la cantidad de perfiles de Referencia 10.1x9
	protected $per3E;					//Define el espesor del perfil de Referencia 10.1x9
	protected $per3L;					//Define el largo del perfil de Referencia 10.1x9
	protected $per3A;					//Define el ancho del perfil de Referencia 10.1x9
	protected $per4;					//Define la cantidad del perfil de Referencia 7x15
	protected $per4E;					//Define el espesor del perfil de Referencia 7x15
	protected $per4L;					//Define el largo del perfil de Referencia 7x15
	protected $per4A;					//Define el ancho del perfil de Referencia 7x15
	protected $corteAd;					//Define el ancho de corte adicional
	
	/*
	* Variables para panel 1
	*/	
	public $pisosP1Aire10;				//Define la cantidad de pisos aleta aire Ref.10 para el panel 1
	public $cantP1Perfil10;				//Define la cantidad de perfiles de 10.1x12 para el panel 1
	public $pisosP1Aleta7;				//Define la cantidad de pisos de aleta aire Ref.7 para el panel 1
	public $cantP1Perfil715;			//Define la cantidad de perfiles 7x15
	public $pisosP1Aceite;				//Define la cantidad de pisos de aceite para el panel 1
	public $cantP1Aceite;				//Define la cantidad de aletas de aceite para el panel 1
	public $cantP1Recorte;				//Define la cantidad de recortes de aceite para el panel 1
	public $cantP1Perfil2x8;			//Define la cantidad de perfiles de 2.6x8.5 para el panel 1
	public $cantP1Intermedia;			//Define la cantidad de chapas intermedias para el panel 1
	public $cantP1Piso;					//Define la cantidad de chapas de piso para el panel 1
	public $cantP1PisoAd;				//Define la cantidad de chapas de piso adicionales para el panles 1
	public $anchoP1;					//Define el ancho del panel 1
	public $cantP1Aleta7Inter;
	public $pisosP1Aleta7Inter;
	public $cantP1Recorte7Inter;
	public $cantP1Perfil7x6Inter;
	
	
	/*
	* Variables para panel 2
	*/	
	public $pisosP2Aire10;				//Define la cantidad de pisos aleta aire Ref.10 para el panel 2
	public $cantP2Perfil10;				//Define la cantidad de perfiles de 10.1x12 para el panel 2
	public $pisosP2Aleta7;				//Define la cantidad de pisos de aleta aire Ref.7 para el panel 2
	public $cantP2Perfil715;			//Define la cantidad de perfiles 7x15
	public $pisosP2Aceite;				//Define la cantidad de pisos de aceite para el panel 2
	public $cantP2Aceite;				//Define la cantidad de aletas de aceite para el panel 2
	public $cantP2Recorte;				//Define la cantidad de recortes de aceite para el panel 2
	public $cantP2Perfil2x8;			//Define la cantidad de perfiles de 2.6x8.5 para el panel 2
	public $cantP2Intermedia;			//Define la cantidad de chapas intermedias para el panel 2
	public $cantP2Piso;					//Define la cantidad de chapas de piso para el panel 2
	public $cantP2PisoAd;				//Define la cantidad de chapas de piso adicionales para el panles 2
	public $anchoP2;					//Define el ancho del panel 2
	public $cantP2Aleta7Inter;
	public $pisosP2Aleta7Inter;
	public $cantP2Recorte7Inter;
	public $cantP2Perfil7x6Inter;
	
	/*
	* Variables para panel 3
	*/	
	public $pisosP3Aire10;				//Define la cantidad de pisos aleta aire Ref.10 para el panel 3
	public $cantP3Perfil10;				//Define la cantidad de perfiles de 10.1x12 para el panel 3
	public $pisosP3Aleta7;				//Define la cantidad de pisos de aleta aire Ref.7 para el panel 3
	public $cantP3Perfil715;			//Define la cantidad de perfiles 7x15
	public $pisosP3Aceite;				//Define la cantidad de pisos de aceite para el panel 3
	public $cantP3Aceite;				//Define la cantidad de aletas de aceite para el panel 3
	public $cantP3Recorte;				//Define la cantidad de recortes de aceite para el panel 3
	public $cantP3Perfil2x8;			//Define la cantidad de perfiles de 2.6x8.5 para el panel 3
	public $cantP3Intermedia;			//Define la cantidad de chapas intermedias para el panel 3
	public $cantP3Piso;					//Define la cantidad de chapas de piso para el panel 3
	public $cantP3PisoAd;				//Define la cantidad de chapas de piso adicionales para el panles 3
	public $anchoP3;					//Define el ancho del panel 3
	public $cantP3Aleta7Inter;
	public $pisosP3Aleta7Inter;
	public $cantP3Recorte7Inter;
	public $cantP3Perfil7x6Inter;
	
	/*
	* Variables para panel 4
	*/	
	public $pisosP4Aire10;				//Define la cantidad de pisos aleta aire Ref.10 para el panel 4
	public $cantP4Perfil10;				//Define la cantidad de perfiles de 10.1x12 para el panel 4
	public $pisosP4Aleta7;				//Define la cantidad de pisos de aleta aire Ref.7 para el panel 4
	public $cantP4Perfil715;			//Define la cantidad de perfiles 7x15
	public $pisosP4Aceite;				//Define la cantidad de pisos de aceite para el panel 4
	public $cantP4Aceite;				//Define la cantidad de aletas de aceite para el panel 4
	public $cantP4Recorte;				//Define la cantidad de recortes de aceite para el panel 4
	public $cantP4Perfil2x8;			//Define la cantidad de perfiles de 2.6x8.5 para el panel 4
	public $cantP4Intermedia;			//Define la cantidad de chapas intermedias para el panel 4
	public $cantP4Piso;					//Define la cantidad de chapas de piso para el panel 4
	public $cantP4PisoAd;				//Define la cantidad de chapas de piso adicionales para el panles 4
	public $anchoP4;					//Define el ancho del panel 4
	public $cantP4Aleta7Inter;
	public $pisosP4Aleta7Inter;
	public $cantP4Recorte7Inter;
	public $cantP4Perfil7x6Inter;
	
	
	/*
	 * Variables de prueba
	 * 
	 * */
	 
	 //Lado de fluido
	 protected $aletaFluA;				//0 aceite y 1 intercooler
	 protected $aletaFluidoN;			//Nombre de la aleta que refrigerará el fluido
	 protected $aletaFluidoC;
	 protected $aletaFluidoL;
	 protected $aletaFluidoA;
	 protected $aletaFluidoE;
	 
	 protected $recorteFluidoN;			//Nombre del recorte que refrigerará el fluido
	 protected $recorteFluidoC;
	 protected $recorteFluidoL;
	 protected $recorteFluidoA;
	 protected $recorteFluidoE;
	 
	 protected $perfilFluidoN;			//Nombre del perfil que refrigerará el fluido
	 protected $perfilFluidoC;
	 protected $perfilFluidoL;
	 protected $perfilFluidoA;
	 protected $perfilFluidoE;
	 
	 protected $perfilVentiladorN;		//Nombre del perfil del lado ventilador
	 protected $perfilVentiladorC;
	 protected $perfilVentiladorL;
	 protected $perfilVentiladorA;
	 protected $perfilVentiladorE;
	 
	 //Lado de ventilador
	 protected $aletaVenA;				//0 aceite y 1 intercooler
	 protected $aletaVentiladorN;		//Nombre de la aleta que se colocará del lado ventilador
	 protected $aletaVentiladorC;
	 protected $aletaVentiladorL;
	 protected $aletaVentiladorA;
	 protected $aletaVentiladorE;
	
	public function __construct(			
			EntityManager $em,
			RequestStack $requestStack,			//Define el objeto RequestStack
			$chapaIntermediaEsp = 0.6,			
			$chapaPisoE = 2,		
			$aire10E = 9.9,					
			$aire7E = 7,
			$aire7A = 15,			
			$aceiteE = 2.9,		
			$per1E = 2.6,									
			$per1A = 8.5,								
			$per2E = 6,									
			$per2A = 7,									
			$per3E = 9,									
			$per3A = 12,
			$per4E = 7,
			$per4A = 15,
			$corteAd = 1						
		)
	{	
		//Variables que inicio por defecto
		$this->requestStack = $requestStack;
		$this->em = $em;			
		$this->chapaIntermediaEsp = $chapaIntermediaEsp;
		$this->chapaPisoE = $chapaPisoE;		
		$this->aire10E = $aire10E;
		$this->aire7E = $aire7E;	
		$this->aire7A = $aire7A;
		$this->aceiteE = $aceiteE;				
		$this->per1E = $per1E;
		$this->per1A = $per1A;			
		$this->per2E = $per2E;		
		$this->per2A = $per2A;
		$this->per3E = $per3E;		
		$this->per3A = $per3A;
		$this->per4E = $per4E;
		$this->per4A = $per4A;
		$this->corteAd = $corteAd;
		$this->aireRefrigeracionNormal = 12;
		
		$this->setFormVars();
	}
	
	//Recibe y setea las variables de formulario
	public function setFormVars()
	{		
		$request = $this->requestStack->getCurrentRequest();
		
		
		$this->tipo = $request->get('tipo');
		$this->apoyoTapas = $request->get('apoyoTapas');
		$this->prof = $request->get('prof');
		$this->anchoPanel = $request->get('ancho');
		$this->pisosManual = $request->get('pisosManual');
		$this->perfilIntermedio = $request->get('perfilIntermedio');
		$this->pisosManual7 = $request->get('pisosManual7');
		$this->cantAdicional = $request->get('cantAdicional');
		$this->chapaPiso = $request->get('chapaPiso');
		$this->maxAlt = $request->get('maxAlt');
		$this->cantPaneles = $request->get('cantPaneles');
		$this->abiertoCerrado = $request->get('aletaTipo');
		$this->aletaVenA = $request->get('aletaVenA');
		$this->aletaFluA = $request->get('aletaFluA');
		
		return $this;
	}
	
	//Determina el tipo de aleta que se utilizará segun el radiador que se calcula
	public function getTipoAletaVen()
	{ 
		if($this->aletaVenA == 0){
			return $this->aletaVentiladorN = 'Aleta Aire Ref.7';
		}else{
			return $this->aletaVentiladorN = 'Aleta Aire Ref.10';
		}
	}
	
	public function getTipoAletaFluido()
	{
		if($this->tipo == 1 & $this->aletaFluA == 1){			
			return $this->aletaFluidoN = 'Aleta Aire 10';
		}
		
		if($this->tipo == 1 & $this->aletaFluA == 0){
			return $this->aletaFluidoN = 'Aleta Aire 7';
		}
		
		if($this->tipo == 0){
			return $this->aletaFluidoN = 'Aleta de Aceite';
		}	
	}
	
	public function getTipoRecorteFluido()
	{
		if($this->tipo == 1){
			if($this->aletaFluA == 1){
				return $this->recorteFluidoN = 'Recorte Aire 10';
			}else{
				return $this->recorteFluidoN = 'Recorte Aire 7';
			}
		}else{
			return $this->recorteFluidoN = 'Recorte Aceite';
		}
	}
	
	public function getTipoPerfilFluido()
	{
		if($this->tipo == 1){
			if($this->aletaFluA == 1){
				return $this->perfilFluidoN = 'Perfil 10.1 x 9';
			}else{
				return $this->perfilFluidoN = 'Perfil 7x6';
			}
		}else{
			return $this->perfilFluidoN = 'Perfil 2.6x8.5';
		}	
	}
	
	public function getTipoPerfilVentilador()
	{
		if($this->aletaVenA == 0){
			return $this->aletaVentiladorN = 'Perfil 7x15';
		}else{
			return $this->aletaVentiladorN = 'Perfil 10.1x12';
		}
	}
	
	//Determina las dimensiones de los perfiles
	public function getLargoPerfilFluido()
	{
		if($this->tipo == 0){
			return $this->perfilFluidoL = $this->apoyoTapas + 2 * 4;
		}else{
			return $this->perfilFluidoL = $this->apoyoTapas;
		}
	}
	
	public function getAnchoPerfilFluido()
	{
		if($this->tipo == 1){
			if($this->aletaFluA == 1){
				return $this->perfilFluidoA = $this->per3E;
			}else{
				return $this->perfilFluidoA = $this->per2E;
			}
		}else{
			return $this->perfilFluidoA = $this->per1A;
		}
	}
	
	
	//Determina la dimension de las aletas
	public function getEspesorAletaFluido()
	{
		if($this->tipo == 1){
			if($this->aletaFluA == 1){
				return $this->aletaFluidoE = $this->aire10E;
			}else{
				return $this->aletaFluidoE = 7;
			}				
		}else{
			return $this->aletaFluidoE = $this->aceiteE;
		}
	}
	
	public function getLargoAletaFluido()
	{
		switch ($this->tipo) {
			case '0':
				if($this->apoyoTapas < 216)
				{			
					$this->aletaFluidoL = $this->apoyoTapas;
					return $this->aletaFluidoL;
				}
				
				if($this->apoyoTapas >= 216)
				{			
					$this->aletaFluidoL = 216;
					return $this->aletaFluidoL;
				}			
				
				break;
			case '1':
				if($this->apoyoTapas < 180)
				{			
					$this->aletaFluidoL = $this->apoyoTapas;
					return $this->aletaFluidoL;
				}
				
				if($this->apoyoTapas >= 180)
				{			
					$this->aletaFluidoL = 180;
					return $this->aletaFluidoL;
				}
				break;
			default:
				return $this->aletaFluidoL = '';
				break;
		}
	}
	
	public function getAnchoAletaFluido()
	{
		switch ($this->perfilIntermedio) {
			case '1':
				$this->aletaFluidoA = ($this->prof - 3 * ($this->getAnchoPerfilFluido() + $this->corteAd)) / 2;
				return $this->aletaFluidoA;				
				break;
			
			case '0':
				$this->aletaFluidoA = $this->prof - 2 * ($this->getAnchoPerfilFluido() + $this->corteAd);
				return $this->aletaFluidoA;
				break;			
		}			
	}
	
	public function getLargoAletaVen()
	{
		return $this->aletaVentiladorL = $this->apoyoTapas - (2 * (1 + $this->aireRefrigeracionNormal));
	}
	
	public function getAnchoAletaVen()
	{
		return $this->aletaVentiladorA = $this->prof;
	}
	
	public function getEspesorAletaVen()
	{
		if($this->aletaVenA == 0){
			return $this->aletaVentiladorE = $this->per2A;
		}else{
			return $this->aletaVentiladorE = $this->aire10E;
		}
	}
	
	public function getLargoPerfilVen()
	{
		return $this->perfilVentiladorL = $this->prof;
	}
	
	public function getLargoRecorteFluido()
	{
		if($this->calculoMultiplo() > 0){
			return $this->recorteFluidoL = $this->apoyoTapas - ($this->getLargoAletaFluido() * $this->calculoMultiplo());
		}else{
			return $this->recorteFluidoL = 0;
		}
	}
	
	public function getAnchoRecorteFluido()
	{
		if($this->calculoMultiplo() > 0){
			return $this->recorteFluidoA = $this->getAnchoAletaFluido();
		}else{
			return $this->recorteFluidoA = 0;
		}
	} 
	
	public function getEspesorRecorteFluido()
	{
		return $this->recorteFluidoE = $this->getEspesorAletaFluido();
	}
	
	public function getCantidadRecorteFluido()
	{
		switch ($this->calculaPaneles()) {
			case '1':
				$res = array(
					'recorteFluidoP1' => '' 
				);
				break;
			
			default:
				
				break;
		}
	}
    
    public function  getCantidadAletaFluido()
    {
        return $this->cantPisos() + $this->cantPisos7() - 1;
    }
	
	public function getPisosAletaFluido()
	{
		switch ($this->calculaPaneles()) {
			case 'value':
				
				break;
			
			default:
				
				break;
		}
		$cantidadAire = $this->aire10Ref();
		$cantPisos7xPanel = $this->cantPisos7xPanel();
		$res = $cantidadAire['pisosP1Aire10'] + $cantPisos7xPanel['pisosP1Aleta7'] - 1;
		return $res;
		echo $res;
	}
	 /*
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * */
	
	//Get tipo (Acite/Intercooler)
	public function getTipo()
	{
		return $this->tipo;
	}
	
	public function getTipoNombre()
	{
			if($this->tipo == 0){
				return 'Aceite';
		}else{
			return 'Intercooler';
		}
	}
	
	
	//Get statusAleta
	public function getStatusAleta()
	{
		return $this->abiertoCerrado;
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
	
	//Calcula la cantidad de pisos del panel
	public function cantPisos()
	{
		$bloque1 = ($this->chapaIntermediaEsp + $this->chapaPisoE * 2 + $this->getEspesorAletaVen());
		$bloque2 = ($this->chapaIntermediaEsp * 2 + $this->getEspesorAletaVen() + $this->getEspesorAletaFluido());
		$pisosTeoricos =  (($this->anchoPanel - $bloque1) / $bloque2) + 1;
		//echo $pisosTeoricos;
		
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

	
	//Calcula la variable multiplo
	public function calculoMultiplo()
	{
		if($this->apoyoTapas < $this->aceiteAnchoTipo()){
			return $this->multiplo = 1;			
		}else{
			return $this->multiplo = floor($this->apoyoTapas / $this->aceiteAnchoTipo());						
		}
	}
	
	//Calcula la cantidad de pisos de 7mm
	public function cantPisos7()
	{
		$anchoCalculado = $this->anchoCalculado();
		$pisoDe7Largo = $this->largoPiso7();
		$dif = -1 * $this->anchoPanel + $anchoCalculado;	//-572 + 561.1 
		$calc = (-1 * $dif) / $pisoDe7Largo;
		$this->pisosDe7 = floor($calc);
		
		
		
		switch ($this->pisosManual) {
			case '':
				return $this->pisosDe7 = 0;				
				break;
			case '0':
				return $this->pisosDe7 = 0;
				break;
			default:
				return $this->pisosDe7 = $this->pisosManual7;
				break;
		}	
		
			
	}
	
	//Calcula la cantidad de pisos de 7mm por panel
	public function cantPisos7xPanel()
	{		
		
		$case1 = ($this->pisosDe7 == 1 ? $this->pisosDe7 : $this->pisosDe7 == 2 ? round($this->pisosDe7 / 2) : $this->pisosDe7 == 3 ? $this->pisosDe7 / 3 : $this->pisosDe7 > 3 ? ceil($this->pisosDe7 / $this->calculaPaneles()) : 0);        
		$case2 = ($this->pisosDe7 == 2 ? round($this->pisosDe7 / 2) : $this->pisosDe7 == 3 ? round($this->pisosDe7 / 3) : $this->pisosDe7 > 3 ? ceil($this->pisosDe7 / $this->calculaPaneles()) : 0);
		$case3 = ($this->pisosDe7 == 3 ? round($this->pisosDe7 / 3) : $this->pisosDe7 > 3 ? round($this->pisosDe7 / $this->pisosDe7) : 0);
		$case4 = ($this->pisosDe7 > 3 ? round($this->pisosDe7 / $this->pisosDe7) : 0);
		
				
		switch ($this->calculaPaneles()) {
			case '1':	
				$this->pisosP1Aleta7 = $this->cantPisos7();
				
				$res = array(
					'pisosP1Aleta7' => $this->pisosP1Aleta7,
					'pisosP2Aleta7' => 0,
					'pisosP3Aleta7' => 0,
					'pisosP4Aleta7' => 0
				);
				return $res;
				break;
				
			case '2':
				$res = array(
					'pisosP1Aleta7' => $this->pisosP1Aleta7 = $case1,
					'pisosP2Aleta7' => $this->pisosP2Aleta7 = $case2,
					'pisosP3Aleta7' => 0,
					'pisosP4Aleta7' => 0
				);
				return $res;
				
				break;
			
			case '3':
				$res = array(
					'pisosP1Aleta7' => $this->pisosP1Aleta7 = $case1,
					'pisosP2Aleta7' => $this->pisosP2Aleta7 = $case2,
					'pisosP3Aleta7' => $this->pisosP3Aleta7 = $case3,
					'pisosP4Aleta7' => 0
				);
				return $res;
				
				break;
			
			case '4':	
				$res = array(
					'pisosP1Aleta7' => $this->pisosP1Aleta7 = $case1,
					'pisosP2Aleta7' => $this->pisosP2Aleta7 = $case2,
					'pisosP3Aleta7' => $this->pisosP3Aleta7 = $case3,
					'pisosP4Aleta7' => $this->pisosP4Aleta7 = $case4
				);
				return $res;
				
				break;
			default:
				
				break;
		}
	}

	//Calcula la cantidad de aletas de aire de 7mm
	public function cantAire7()
	{
		return $this->aire7C = $this->cantPisos7();
	}
	
	//Calcula el largo de la aleta de aire Ref 7mm
	public function largoAire7()
	{
		switch ($this->cantPisos7()){
			case '0':
				return $this->aire7L = '';
				break;
			case '':
				return $this->aire7L = '';
				break;
			default:
				$this->aire7L = $this->apoyoTapas - 2 * ($this->aire7A + $this->corteAd);
				return $this->aire7L;
				break;
		}
	}
	
	//Calcula el ancho de la aleta de aire Ref 7mm
	public function anchoAire7()
	{
		switch ($this->cantPisos7()){
			case '0':
				return $this->aire7A = '';
				break;
			case '':
				return $this->aire7A = '';
				break;
			default:
				$this->aire7A = $this->prof;
				return $this->aire7A;
				break;
		}		
	}
	
	//Calcula el espesor de la aleta de aire Ref 7mm
	public function espesorAire7()
	{
		switch ($this->cantPisos7()){
			case '0':
				return $this->aire7E = '';
				break;
			case '':
				return $this->aire7E = '';
				break;
			default:
				return $this->aire7E;
				break;
		}		
	}
	
	//Calcula el espesor de perfil 2.6x8.5
	public function perfil2x8E()
	{
		return $this->per1E;
	}
	
	//Calcula la cantidad de perfil de 7x15
	public function perfil715c()
	{
		switch ($this->pisosDe7) {
			case '':
				return $this->per4;
				break;
			case '0':
				return $this->per4;
			default:
				$this->per4 = $this->cantPisos7() * 2;
				return $this->per4;		
				break;
		}
		
		
		
	}
	
	//Calcula la cantidad de perfiles 7x15 por panel
	public function perfil715Xpanel()
	{
		switch ($this->calculaPaneles()) {
			case '1':
				$this->cantP1Perfil715 = $this->pisosP1Aleta7 * 2;
				break;
			case '2':
				$this->cantP1Perfil715 = $this->pisosP1Aleta7 * 2;
				$this->cantP2Perfil715 = $this->pisosP2Aleta7 * 2;
				break;
			case '3':
				$this->cantP1Perfil715 = $this->pisosP1Aleta7 * 2;
				$this->cantP2Perfil715 = $this->pisosP2Aleta7 * 2;
				$this->cantP3Perfil715 = $this->pisosP3Aleta7 * 2;
				break;
			case '4':
				$this->cantP1Perfil715 = $this->pisosP1Aleta7 * 2;
				$this->cantP2Perfil715 = $this->pisosP2Aleta7 * 2;
				$this->cantP3Perfil715 = $this->pisosP3Aleta7 * 2;
				$this->cantP4Perfil715 = $this->pisosP4Aleta7 * 2;
				break;
			default:
				break;
		}
	}
	
	//Calcula el largo de el perfil de 7x15
	public function perfil715l()
	{
		switch ($this->perfil715c()) {
			case '':
				return $this->per4L = '';
				break;
			
			default:
				return $this->per4L = $this->prof;
				break;
		}
	}
	
	//Calcula el espesor del perfil 7x15
	public function perfil715e()
	{
		return $this->per4E;
	}
	
	//Calcula el largo del piso de 7mm
	public function largoPiso7()
	{
		$this->pisoDe7Largo = $this->getEspesorAletaFluido() + $this->chapaIntermediaEsp * 2 + $this->getEspesorAletaFluido();
		return $this->pisoDe7Largo;
	}
	
	//Calcula el ancho segun los componentes para luego comparar con el ancho pedido
	public function anchoCalculado()
	{
		$bloque1 = $this->chapaIntermediaEsp * 2 + $this->getEspesorAletaFluido() + $this->getEspesorAletaFluido();
		$bloque2 = $this->chapaPisoE * 2 + $this->chapaIntermediaEsp * 2 + $this->getEspesorAletaFluido();
		$this->anchoCalculado = ($this->cantPisos() -1) * $bloque1 + $bloque2;
		return $this->anchoCalculado;
	}
	
	//Calcula la cantidad de aletas de aire
	public function aire10Ref()
	{
		
		switch ($this->calculaPaneles()) {
			case '1':
				$res = array(
					'pisosP1Aire10' => $this->pisosP1Aire10 = $this->cantPisos(),
					'pisosP2Aire10' => 0,
					'pisosP3Aire10' => 0,
					'pisosP4Aire10' => 0
				);
				
				return $res;
				break;
			
			case '2':
				$res = array(
					'pisosP1Aire10' => $this->pisosP1Aire10 = round($this->cantPisos() / 2),
					'pisosP2Aire10' => $this->pisosP2Aire10 = $this->cantPisos() - $this->pisosP1Aire10,
					'pisosP3Aire10' => 0,
					'pisosP4Aire10' => 0
				);
				
				
				return $res;
				break;
				
			case '3':				
				$res = array(
					'pisosP1Aire10' => $this->pisosP1Aire10 = round($this->cantPisos() / 3),
					'pisosP2Aire10' => $this->pisosP2Aire10 = round(($this->cantPisos() - $this->pisosP1Aire10) / 2),
					'pisosP3Aire10' => $this->pisosP3Aire10 = $this->cantPisos() - $this->pisosP1Aire10 - $this->pisosP2Aire10,
					'pisosP4Aire10' => 0 
				);
				
				return $res;
				break;
				
			case '4':
				$res = array(
					'pisosP1Aire10' => $this->pisosP1Aire10 = round($this->cantPisos() / 4),
					'pisosP2Aire10' => $this->pisosP2Aire10 = round(($this->cantPisos() - $this->pisosP1Aire10) / 3),
					'pisosP3Aire10' => $this->pisosP3Aire10 = round(($this->cantPisos() - $this->pisosP1Aire10 - $this->pisosP2Aire10) / 2),
					'pisosP4Aire10' => $this->pisosP4Aire10 = round($this->cantPisos() - $this->pisosP1Aire10 - $this->pisosP2Aire10 - $this->pisosP3Aire10)
				);
				return $res;
				
				break;
			default:
				
				break;
		}
		$this->aire10C = $this->cantPisos();
		return $this->aire10C;
	}
	
	//Calcula el largo a cortar del aire
	public function aireLargo()
	{
		$this->aire10C = $this->apoyoTapas - (2 * ($this->per3A + $this->corteAd));
		return $this->aire10C;
	}
	
	//Calcula el ancho de la aleta de aire
	public function aireAncho()
	{
		$this->aire10A = $this->prof;
		return $this->aire10A;
	}
	
	//Calcula el espesor de la aleta de aire
	public function aireEspesor()
	{
		return $this->aire10E;
	}
	
	//Calcula la cantidad de perfiles de 10.1 x 12
	public function perfil10Ref()
	{
		switch ($this->calculaPaneles()) {
			case '1':
				$this->cantP1Perfil10 = $this->pisosP1Aire10 * 2;
				break;
			
			case '2':
				$this->cantP1Perfil10 = $this->pisosP1Aire10 * 2;
				$this->cantP2Perfil10 = $this->pisosP2Aire10 * 2;
				break;
				
			case '3':
				$this->cantP1Perfil10 = $this->pisosP1Aire10 * 2;
				$this->cantP2Perfil10 = $this->pisosP2Aire10 * 2;
				$this->cantP3Perfil10 = $this->pisosP3Aire10 * 2;				
				break;
				
			case '4':
				$this->cantP1Perfil10 = $this->pisosP1Aire10 * 2;
				$this->cantP2Perfil10 = $this->pisosP2Aire10 * 2;
				$this->cantP3Perfil10 = $this->pisosP3Aire10 * 2;
				$this->cantP4Perfil10 = $this->pisosP4Aire10 * 2;
				break;
			default:
				
				break;
		}
		$this->per3 = $this->cantPisos() * 2;
		return $this->per3;
	}
	
	//Calcula el largo del perfil de 10.1 x 12
	public function perfil10Largo()
	{
		$this->per3L = $this->prof;
		return $this->per3L;
	}
	
	//Calculo el ancho del perfil de 10.1 x 12
	public function perfil10Ancho()
	{
		return $this->per3A;
	}
	
	//Calcula el espesor del perfil de 10.1 x 12
	public function perfil10Espesor()
	{
		return $this->per3E;
	}	
	
	//Calcula los pisos de aleta de aceite
	public function pisosDeAceite()
	{
		switch ($this->tipo) {
			case '0':
				$this->aceiteP = $this->cantPisos() + $this->cantPisos7() - 1;
				return $this->aceiteP;		
				break;
			
			default:
				return $this->aceiteP = '';
				break;
		}
		 
	}
	
	//Calcula la cantidad de aletas de aceite
	public function cantAletaAceite()
	{
		$multiplo = $this->calculoMultiplo();
		if($this->tipo == 0){
			switch ($this->perfilIntermedio) {
				case '1':
					$this->aceiteC = ($this->cantPisos() + $this->cantPisos7() - 1) * 2;
					return $this->aceiteC;
					break;
				
				default:
					$this->aceiteC = ($this->cantPisos() + $this->cantPisos7() - 1) * $multiplo;
					return $this->aceiteC;				
					break;
			}	
		}else{
			return $this->aceiteC = '';
		}		
	}
	
	//Calcula la cantidad y pisos de aletas de aceite por panel
	public function cantAletaAceiteXpanel()
	{
		$multiplo = $this->calculoMultiplo();
		switch ($this->calculaPaneles()) {
			case '1':				
				$this->cantP1Aceite =  $this->perfilIntermedio == 1 ? ($this->pisosP1Aire10 + $this->pisosP1Aleta7 - 1) * 2 : ($this->pisosP1Aire10 + $this->pisosP1Aleta7 - 1) * $multiplo;
				return $this->cantP1Aceite;
				break;
			case '2':
				$this->cantP1Aceite =  $this->perfilIntermedio == 1 ? ($this->pisosP1Aire10 + $this->pisosP1Aleta7 - 1) * 2 : ($this->pisosP1Aire10 + $this->pisosP1Aleta7 - 1) * $multiplo;
				$this->cantP2Aceite =  $this->perfilIntermedio == 1 ? ($this->pisosP2Aire10 + $this->pisosP2Aleta7 - 1) * 2 : ($this->pisosP2Aire10 + $this->pisosP2Aleta7 - 1) * $multiplo;
				break;
			case '3':
				$this->cantP1Aceite =  $this->perfilIntermedio == 1 ? ($this->pisosP1Aire10 + $this->pisosP1Aleta7 - 1) * 2 : ($this->pisosP1Aire10 + $this->pisosP1Aleta7 - 1) * $multiplo;
				$this->cantP2Aceite =  $this->perfilIntermedio == 1 ? ($this->pisosP2Aire10 + $this->pisosP2Aleta7 - 1) * 2 : ($this->pisosP2Aire10 + $this->pisosP2Aleta7 - 1) * $multiplo;
				$this->cantP3Aceite =  $this->perfilIntermedio == 1 ? ($this->pisosP3Aire10 + $this->pisosP3Aleta7 - 1) * 2 : ($this->pisosP3Aire10 + $this->pisosP3Aleta7 - 1) * $multiplo;
				break;
			case '4':
				$this->cantP1Aceite =  $this->perfilIntermedio == 1 ? ($this->pisosP1Aire10 + $this->pisosP1Aleta7 - 1) * 2 : ($this->pisosP1Aire10 + $this->pisosP1Aleta7 - 1) * $multiplo;
				$this->cantP2Aceite =  $this->perfilIntermedio == 1 ? ($this->pisosP2Aire10 + $this->pisosP2Aleta7 - 1) * 2 : ($this->pisosP2Aire10 + $this->pisosP2Aleta7 - 1) * $multiplo;
				$this->cantP3Aceite =  $this->perfilIntermedio == 1 ? ($this->pisosP3Aire10 + $this->pisosP3Aleta7 - 1) * 2 : ($this->pisosP3Aire10 + $this->pisosP3Aleta7 - 1) * $multiplo;
				$this->cantP4Aceite =  $this->perfilIntermedio == 1 ? ($this->pisosP4Aire10 + $this->pisosP4Aleta7 - 1) * 2 : ($this->pisosP4Aire10 + $this->pisosP4Aleta7 - 1) * $multiplo;
				break;
			default:
				
				break;
		}
	}

	//Calcula los pisos de aceite por panel
	public function pisosAletaAceiteXpanel()
	{
		$aire10Ref = $this->aire10Ref();
		switch ($this->calculaPaneles()) {
			case '1':
				$this->pisosP1Aceite = $this->pisosDeAceite();
				return $res = array(
					'pisosP1Aceite' => $this->pisosP1Aceite
				);
				break;
			case '2':
				$res = array(
					'pisosP1Aceite' => $this->pisosP1Aceite = $aire10Ref['pisosP1Aire10'] + $this->pisosP1Aleta7 - 1,
					'pisosP2Aceite' => $this->pisosP2Aceite = $this->pisosP2Aire10 + $this->pisosP2Aleta7 - 1
				);
				return $res;				
				break;
			case '3':
				$res = array(
					'pisosP1Aceite' => $this->pisosP1Aceite = $this->pisosP1Aire10 + $this->pisosP1Aleta7 - 1,
					'pisosP2Aceite' => $this->pisosP2Aceite = $this->pisosP2Aire10 + $this->pisosP2Aleta7 - 1,
					'pisosP3Aceite' => $this->pisosP3Aceite = $this->pisosP3Aire10 + $this->pisosP3Aleta7 - 1
				);
				return $res;
				break;
			case '4':
				$res = array(
					'pisosP1Aceite' => $this->pisosP1Aceite = $this->pisosP1Aire10 + $this->pisosP1Aleta7 - 1,
					'pisosP2Aceite' => $this->pisosP2Aceite = $this->pisosP2Aire10 + $this->pisosP2Aleta7 - 1,
					'pisosP3Aceite' => $this->pisosP3Aceite = $this->pisosP3Aire10 + $this->pisosP3Aleta7 - 1,
					'pisosP4Aceite' => $this->pisosP4Aceite = $this->pisosP4Aire10 + $this->pisosP4Aleta7 - 1
				);
				return $res;
								
				break;
				
			default:
				
				break;
		}
	}
	
	//Calcula el largo de la aleta de aceite
	public function largoAceite()
	{
		switch ($this->tipo) {
			case '0':
				if($this->tipo == '0' & $this->apoyoTapas < 216)
				{			
					$this->aceiteL = $this->apoyoTapas;
					return $this->aceiteL;
				}
				
				if($this->tipo == '0' & $this->apoyoTapas >= 216)
				{			
					$this->aceiteL = 216;
					return $this->aceiteL;
				}
				
				if($this->tipo == '1' & $this->apoyoTapas < 180)
				{			
					$this->aceiteL = $this->apoyoTapas;
					return $this->aceiteL;
				}
				
				if($this->tipo == '1' & $this->apoyoTapas >= 180)
				{			
					$this->aceiteL = 180;
					return $this->aceiteL;
				}
				break;
			
			default:
				return $this->aceiteL = '';
				break;
		}	
		
	}
	
	//Calculo el ancho de la aleta de aceite
	public function anchoAceite()
	{
		if($this->tipo == 0){
			switch ($this->perfilIntermedio) {
				case '1':
					$this->aceiteA = ($this->prof - 3 * ($this->getAnchoPerfilFluido() + $this->corteAd)) / 2;
					return $this->aceiteA;				
					break;
				
				case '0':
					$this->aceiteA = $this->prof - 2 * ($this->getAnchoPerfilFluido() + $this->corteAd);
					return $this->aceiteA;
					break;
				
				default:
					return $this->aceiteA;
			}	
		}else{
			return $this->aceiteA = '';
		}		
	}
	
	//Calcula el espesor de la aleta de aceite
	public function espesorAceite()
	{
		switch ($this->tipo) {
			case '0':
				return $this->aceiteE;
				break;
			
			default:
				return $this->aceiteE = '';
				break;
		}		
	}
	
	//Calcula la cantidad de recortes de aceite
	public function recorteAceiteCant()
	{
		switch ($this->tipo) {
			case '0':
				if($this->apoyoTapas > 216)
				{
					$this->recorteA = $this->pisosDeAceite();	
				}				
				return $this->recorteA;
				break;
			
			default:
				return $this->recorteA = '';
				break;
		}
	}
	
	//Calcula la cantidad de recortes de aceite para cada panel
	public function recorteAceiteXpanel()
	{
		$pisosAletaAceiteXpanel = $this->pisosAletaAceiteXpanel();
		switch ($this->calculaPaneles()) {
			case '1':
				return $this->cantP1Recorte = $this->recorteAceiteCant() > 0 ? $pisosAletaAceiteXpanel['pisosP1Aceite'] : 0;
				break;
			case '2':
				return $this->cantP1Recorte = $this->recorteAceiteCant() > 0 ? $pisosAletaAceiteXpanel['pisosP1Aceite'] : 0;
				return $this->cantP2Recorte = $this->recorteAceiteCant() > 0 ? $pisosAletaAceiteXpanel['pisosP2Aceite'] : 0;
				break;
			case '3':
				return $this->cantP1Recorte = $this->recorteAceiteCant() > 0 ? $pisosAletaAceiteXpanel['pisosP1Aceite'] : 0;
				return $this->cantP2Recorte = $this->recorteAceiteCant() > 0 ? $pisosAletaAceiteXpanel['pisosP2Aceite'] : 0;
				return $this->cantP3Recorte = $this->recorteAceiteCant() > 0 ? $pisosAletaAceiteXpanel['pisosP3Aceite'] : 0;
				break;
			case '4':
				return $this->cantP1Recorte = $this->recorteAceiteCant() > 0 ? $pisosAletaAceiteXpanel['pisosP1Aceite'] : 0;
				return $this->cantP2Recorte = $this->recorteAceiteCant() > 0 ? $pisosAletaAceiteXpanel['pisosP2Aceite'] : 0;
				return $this->cantP3Recorte = $this->recorteAceiteCant() > 0 ? $pisosAletaAceiteXpanel['pisosP3Aceite'] : 0;
				return $this->cantP4Recorte = $this->recorteAceiteCant() > 0 ? $pisosAletaAceiteXpanel['pisosP4Aceite'] : 0;
				break;
			default:
				
				break;
		}
	}
	
	//Calcula el largo de los recortes de aceite
	public function recorteAceiteLargo()
	{
		switch ($this->tipo) {
			case '0':
				$this->recorteAl = $this->apoyoTapas - ($this->getLargoAletaFluido() * $this->calculoMultiplo());
				return $this->recorteAl;
				break;
			
			default:
				return $this->recorteAl = '';
				break;
		}
	}
	
	//Calcula el ancho del recorte de aceite
	public function recorteAceiteAncho()
	{
		if($this->calculoMultiplo() > 0){
			$this->recorteAa = $this->anchoAceite();
			return $this->recorteAa;
		}else{
			return $this->recorteAa = '';
		}
	}
	
	//Calcula el espesor del recorte de aceite
	public function recorteAceiteEsp()
	{
		$sobranteAceite = 4;
		switch($this->tipo){
			case '0':
				$this->recorteAe = $this->aceiteE;
				return $this->recorteAe;
				break;
			
			default:
				return $this->recorteAe = '';
				break;
		}
	}
	
	//Calcula la cantidad de perfiles de 2.6x8.5
	public function per1Cantidad()
	{
		switch($this->tipo){
			case '0':
				if($this->perfilIntermedio == 1){
					return $this->per1 = $this->pisosDeAceite() * 3;
				}else{
					return $this->per1 = $this->pisosDeAceite() * 2;
				}
				break;
				
			default:
				return $this->per1 = '';
				break;
		}
	}
	
	//Calcula la cantidad de perfiles de 2.6x8.5 por cada panel
	public function per1CantidadXpanel()
	{
		switch ($this->calculaPaneles()) {
			case '1':
				$this->cantP1Perfil2x8 = $this->perfilIntermedio == 1 ? $this->pisosP1Aceite * 3 : $this->pisosP1Aceite * 2;
				break;
			case '2':
				$this->cantP1Perfil2x8 = $this->perfilIntermedio == 1 ? $this->pisosP1Aceite * 3 : $this->pisosP1Aceite * 2;
				$this->cantP2Perfil2x8 = $this->perfilIntermedio == 1 ? $this->pisosP2Aceite * 3 : $this->pisosP2Aceite * 2;
				break;
			case '3':
				$this->cantP1Perfil2x8 = $this->perfilIntermedio == 1 ? $this->pisosP1Aceite * 3 : $this->pisosP1Aceite * 2;
				$this->cantP2Perfil2x8 = $this->perfilIntermedio == 1 ? $this->pisosP2Aceite * 3 : $this->pisosP2Aceite * 2;
				$this->cantP3Perfil2x8 = $this->perfilIntermedio == 1 ? $this->pisosP3Aceite * 3 : $this->pisosP3Aceite * 2;
				break;
			case '4':
				$this->cantP1Perfil2x8 = $this->perfilIntermedio == 1 ? $this->pisosP1Aceite * 3 : $this->pisosP1Aceite * 2;
				$this->cantP2Perfil2x8 = $this->perfilIntermedio == 1 ? $this->pisosP2Aceite * 3 : $this->pisosP2Aceite * 2;
				$this->cantP3Perfil2x8 = $this->perfilIntermedio == 1 ? $this->pisosP3Aceite * 3 : $this->pisosP3Aceite * 2;
				$this->cantP4Perfil2x8 = $this->perfilIntermedio == 1 ? $this->pisosP4Aceite * 3 : $this->pisosP4Aceite * 2;
				break;
			default:
				
				break;
		}
	}
	
	//Calcula la cantidad de perfiles de 2.6x8.5
	public function per1Largo()
	{
		$sobranteAceite = 4;
		switch($this->tipo){
			case '0':
				$this->per1L = $this->apoyoTapas + 2 * $sobranteAceite;
				return $this->per1L;
				break;
				
			default:
				return $this->per1L = '';
				break;
		}
	}
	
	//Calcula la cantidad de chapas intermedias
	public function chapaIntCantidad()
	{
		return $this->chIntC = $this->getCantidadAletaFluido() * 2 + 2;		
	}
    
    //Calcula la cantidad de chapas intermedias por panel
    public function chapaIntXpanel()
    {
    	$pisosAceite = $this->pisosAletaAceiteXpanel();
        switch ($this->calculaPaneles()) {
            case '1':
                $this->cantP1Intermedia = $pisosAceite['pisosP1Aceite'] * 2 + 2;
				return $this->cantP1Intermedia;
                break;
            case '2':
				$res = array(
					'cantP1Intermedia' => $this->cantP1Intermedia = $pisosAceite['pisosP1Aceite'] * 2 + 2,
					'cantP2Intermedia' => $this->cantP2Intermedia = $pisosAceite['pisosP2Aceite'] * 2 + 2
				);
                
				return $res;
               
                break;
            case '3':
				$res = array(
					'cantP1Intermedia' => $this->cantP1Intermedia = $this->pisosP1Aceite * 2 + 2,
					'cantP2Intermedia' => $this->cantP2Intermedia = $this->pisosP2Aceite * 2 + 2,
					'cantP3Intermedia' => $this->cantP3Intermedia = $this->pisosP3Aceite * 2 + 2
				);
                
                return $res;
                
                break;
            case '4':
				$res = array(
					'cantP1Intermedia' => $this->cantP1Intermedia = $this->pisosP1Aceite * 2 + 2,
					'cantP2Intermedia' => $this->cantP2Intermedia = $this->pisosP2Aceite * 2 + 2,
					'cantP3Intermedia' => $this->cantP3Intermedia = $this->pisosP3Aceite * 2 + 2,
					'cantP4Intermedia' => $this->cantP4Intermedia = $this->pisosP4Aceite * 2 + 2
				);
                return $res;
				
                break;
            default:
                
                break;
        }
    }
	
	//Calcula el largo de la chapa intermedia
	public function chapaIntLargo()
	{
		return $this->chIntl = $this->apoyoTapas;		
	}
	
	//Calcula el ancho de la chapa intermedia
	public function chapaIntAncho()
	{
		return $this->chIntA = $this->prof;		
	}
	
	//Calcula el espesor de la chapa intermedia
	public function chapaIntEspesor()
	{
		return $this->chapaIntermediaEsp;		
	}
	
	//Calcula la cantidad de chapas de piso
	public function chapaPisoCant()
	{
		switch ($this->chapaPiso){
			case '1':
				if($this->cantAdicional == 1){
					return $this->chapaPisoC = 1;
				}
				
				if($this->cantAdicional == 2){
					return $this->chapaPisoC = 0;
				}
				break;
			
			default:
				return $this->chapaPisoC = 2;
				break;
		}
	}
    
    //Calcula la cantidad de chapas de piso x panel
    public function chapaPisoXpanel()
    {
        switch ($this->calculaPaneles()) {
            case '1':                 
                if($this->chapaPiso == 1 & $this->cantAdicional == 1){
                    return $res = array(
						'cantP1Piso' => $this->cantP1Piso = 1,
						'cantP2Piso' => $this->cantP2Piso = 0,
						'cantP3Piso' => $this->cantP3Piso = 0,
						'cantP4Piso' => $this->cantP4Piso = 0
					);
                }
				
				if($this->chapaPiso == 1 & $this->cantAdicional == 2){
                    return $res = array(
						'cantP1Piso' => $this->cantP1Piso = 0,
						'cantP2Piso' => $this->cantP2Piso = 0,
						'cantP3Piso' => $this->cantP3Piso = 0,
						'cantP4Piso' => $this->cantP4Piso = 0
					);
                }
				
				if($this->chapaPiso == 0){
					return $res = array(
						'cantP1Piso' => $this->cantP1Piso = 2,
						'cantP2Piso' => $this->cantP2Piso = 0,
						'cantP3Piso' => $this->cantP3Piso = 0,
						'cantP4Piso' => $this->cantP4Piso = 0,
					);
				}
                           
                break;
            case '2':
                if($this->chapaPiso == 1 & $this->cantAdicional == 1){
                    return $res = array(
						'cantP1Piso' => $this->cantP1Piso = 2,
						'cantP2Piso' => $this->cantP2Piso = 1,
						'cantP3Piso' => $this->cantP3Piso = 0,
						'cantP4Piso' => $this->cantP4Piso = 0
					);
                }
				if($this->chapaPiso == 1 & $this->cantAdicional == 2){
                    return $res = array(
						'cantP1Piso' => $this->cantP1Piso = 1,
						'cantP2Piso' => $this->cantP2Piso = 1,
						'cantP3Piso' => $this->cantP3Piso = 0,
						'cantP4Piso' => $this->cantP4Piso = 0
					);
                }
				
				if($this->chapaPiso == 0){
					return $res = array(
						'cantP1Piso' => $this->cantP1Piso = 2,
						'cantP2Piso' => $this->cantP2Piso = 2,
						'cantP3Piso' => $this->cantP3Piso = 0,
						'cantP4Piso' => $this->cantP4Piso = 0,
					);
				}
                
                break;
            case '3':
                if($this->chapaPiso == 1 & $this->cantAdicional == 1){
                    return $res = array(
						'cantP1Piso' => $this->cantP1Piso = 2,
						'cantP2Piso' => $this->cantP2Piso = 2,
						'cantP3Piso' => $this->cantP3Piso = 1,
						'cantP4Piso' => $this->cantP4Piso = 0
					);
                }
				if($this->chapaPiso == 1 & $this->cantAdicional == 2){
                    return $res = array(
						'cantP1Piso' => $this->cantP1Piso = 1,
						'cantP2Piso' => $this->cantP2Piso = 2,
						'cantP3Piso' => $this->cantP3Piso = 1,
						'cantP4Piso' => $this->cantP4Piso = 0
					);
                }
				
				if($this->chapaPiso == 0){
					return $res = array(
						'cantP1Piso' => $this->cantP1Piso = 2,
						'cantP2Piso' => $this->cantP2Piso = 2,
						'cantP3Piso' => $this->cantP3Piso = 2,
						'cantP4Piso' => $this->cantP4Piso = 0,
					);
				}              
                break;
            case '4':
                if($this->chapaPiso == 1 & $this->cantAdicional == 1){
                    return $res = array(
						'cantP1Piso' => $this->cantP1Piso = 2,
						'cantP2Piso' => $this->cantP2Piso = 2,
						'cantP3Piso' => $this->cantP3Piso = 2,
						'cantP4Piso' => $this->cantP4Piso = 1
					);
                }
				if($this->chapaPiso == 1 & $this->cantAdicional == 2){
                    return $res = array(
						'cantP1Piso' => $this->cantP1Piso = 1,
						'cantP2Piso' => $this->cantP2Piso = 2,
						'cantP3Piso' => $this->cantP3Piso = 2,
						'cantP4Piso' => $this->cantP4Piso = 1
					);
                }
				
				if($this->chapaPiso == 0){
					return $res = array(
						'cantP1Piso' => $this->cantP1Piso = 2,
						'cantP2Piso' => $this->cantP2Piso = 2,
						'cantP3Piso' => $this->cantP3Piso = 2,
						'cantP4Piso' => $this->cantP1Piso = 2,
					);
				}
                break;
            default:
                
                break;
        }
    }
	
	//Calcula el largo de la chapa de piso
	public function chapaPisoLargo()
	{
		return $this->chapaPisoL = $this->apoyoTapas;
	}
	
	//Calcula el ancho de la chapa de piso
	public function chapaPisoAncho()
	{
		return $this->chapaPisoA = $this->prof;
	}
	
	//Calcula el espesor de la chapa de piso
	public function chapaPisoEspesor()
	{
		return $this->chapaPisoE;
	}
	
	//Calcula la cantidad de chapa de piso adicional
	public function chapaAdCantidad()
	{
		switch ($this->chapaPiso){
		case '1':
			if($this->cantAdicional == 1){
				return $this->chapaAdC = 1;
			}
			
			if($this->cantAdicional == 2){
				return $this->chapaAdC = 2;
			}
			break;
		
		default:
			return $this->chapaAdC = '';
			break;
		}
	}
	
    //Calcula la cantidad de chapa de piso Adicional por panel
    public function chapaAdXpanel()
    {
        switch ($this->calculaPaneles()) {
            case '1':
				$res = array(
					'cantP1PisoAd' => $this->cantP1PisoAd = $this->chapaPiso == 1 & $this->cantAdicional == 2 ? 0 : 0
				);
                return $res;
				
                break;
            case '2':
				$res = array(
					'cantP1PisoAd' => $this->cantP1PisoAd = $this->chapaPiso == 1 & $this->cantAdicional == 2 ? 1 : 0,
					'cantP2PisoAd' => $this->cantP2PisoAd = $this->chapaPiso == 1 ? 1 : 0
				);
                return $res;
                
                break;
            case '3':
				$res = array(
					'cantP1PisoAd' => $this->cantP1PisoAd = $this->chapaPiso == 1 & $this->cantAdicional == 2 ? 1 : 0,
					'cantP2PisoAd' => $this->cantP2PisoAd = $this->chapaPiso == 1 ? 1 : 0,
					'cantP3PisoAd' => $this->cantP3PisoAd = $this->chapaPiso == 1 ? 1 : 0
				);
                return $res;                
                
                break;
            case '4':
				$res = array(
					'cantP1PisoAd' => $this->cantP1PisoAd = $this->chapaPiso == 1 & $this->cantAdicional == 2 ? 1 : 0,
					'cantP2PisoAd' => $this->cantP2PisoAd = $this->chapaPiso == 1 ? 0 : 0,
					'cantP3PisoAd' => $this->cantP3PisoAd = $this->chapaPiso == 1 ? 0 : 0,
					'cantP4PisoAd' => $this->cantP4PisoAd = $this->chapaPiso == 1 ? 1 : 0
				);
                
                break;
            default:
                
                break;
        }
    }
    
	//Calcula el largo de la chapa de piso adicional
	public function chapaAdLargo()
	{
		switch ($this->chapaPiso){
		case '1':
			return $this->chapaAdL = $this->apoyoTapas;			
			break;
		
		default:
			return $this->chapaAdL = '';
			break;
		}
	}
	
	//Calcula el ancho de la chapa de piso adicional
	public function chapaAdAncho()
	{
		switch ($this->chapaPiso){
		case '1':
			return $this->chapaAdA = $this->prof;			
			break;
		
		default:
			return $this->chapaAdA = '';
			break;
		}
	}
	
	//Calcula el espesor de la chapa de piso adicional
	public function chapaAdEspesor()
	{
		switch ($this->chapaPiso){
		case '1':
			return $this->chapaAdE = 5;			
			break;
		
		default:
			return $this->chapaAdE = '';
			break;
		}
	}
	
	
	//Devuleve la cantidad de paneles calculada en JavaScript
	public function calculaPaneles()
	{
		return $this->cantPaneles;		
	}	
	
	//Calcula el ancho del panel 1
	public function anchoPaneles()
	{
		$aire10Ref = $this->aire10Ref();
		$pisosAletaAceiteXpanel = $this->pisosAletaAceiteXpanel();
		$chapaIntXpanel = $this->chapaIntXpanel();
		$cantPisos7xPanel = $this->cantPisos7xPanel();
		$chapaPiso = $this->chapaPisoXpanel();
		$chapaAdXpanel = $this->chapaAdXpanel();
		
		switch ($this->calculaPaneles()) {
			case '1':
				$this->anchoP1 = $aire10Ref['pisosP1Aire10'] * $this->getEspesorAletaVen() + $this->cantPisos7xPanel() * $this->getEspesorAletaFluido() + 
				$pisosAletaAceiteXpanel['pisosP1Aceite'] * $this->getEspesorAletaFluido() + $this->chapaIntXpanel() * $this->chapaIntermediaEsp  + $chapaPiso['cantP1Piso'] * $this->chapaPisoE + 
				$chapaAdXpanel['cantP1PisoAd'] * $this->chapaPisoE;
				
				$res = array(
					'anchoP1' => $this->anchoP1,
					'anchoP2' => 0,
					'anchoP3' => 0,
					'anchoP4' => 0
				);				
				return $res;
				break;
				
			case '2':				
				$this->anchoP1 = $aire10Ref['pisosP1Aire10'] * $this->getEspesorAletaVen() + $cantPisos7xPanel['pisosP1Aleta7'] * $this->getEspesorAletaFluido() + 
				$pisosAletaAceiteXpanel['pisosP1Aceite'] * $this->getEspesorAletaFluido() + $chapaIntXpanel['cantP1Intermedia'] * $this->chapaIntermediaEsp + $chapaPiso['cantP1Piso'] * $this->chapaPisoE + 
				$chapaAdXpanel['cantP1PisoAd'] * $this->chapaPisoE;
				
				$this->anchoP2 = $aire10Ref['pisosP2Aire10'] * $this->getEspesorAletaVen() + $cantPisos7xPanel['pisosP2Aleta7'] * $this->getEspesorAletaFluido() + 
				$pisosAletaAceiteXpanel['pisosP2Aceite'] * $this->getEspesorAletaFluido() + $chapaIntXpanel['cantP2Intermedia'] * $this->chapaIntermediaEsp + $chapaPiso['cantP2Piso'] * $this->chapaPisoE + 
				$chapaAdXpanel['cantP2PisoAd'] * $this->chapaPisoE;
				
				$res = array(
					'anchoP1' => $this->anchoP1,
					'anchoP2' => $this->anchoP2,
					'anchoP3' => 0,
					'anchoP4' => 0
				);				
				return $res;
			
				break;
				
			case '3':
				$this->anchoP1 = $aire10Ref['pisosP1Aire10'] * $this->getEspesorAletaVen() + $cantPisos7xPanel['pisosP1Aleta7'] * $this->getEspesorAletaFluido() + 
				$pisosAletaAceiteXpanel['pisosP1Aceite'] * $this->getEspesorAletaFluido() + $chapaIntXpanel['cantP1Intermedia'] * $this->chapaIntermediaEsp + $chapaPiso['cantP1Piso'] * $this->chapaPisoE + 
				$chapaAdXpanel['cantP1PisoAd'] * $this->chapaPisoE;
				
				$this->anchoP2 = $aire10Ref['pisosP2Aire10'] * $this->getEspesorAletaVen() + $cantPisos7xPanel['pisosP2Aleta7'] * $this->getEspesorAletaFluido() + 
				$pisosAletaAceiteXpanel['pisosP2Aceite'] * $this->getEspesorAletaFluido() + $chapaIntXpanel['cantP2Intermedia'] * $this->chapaIntermediaEsp + $chapaPiso['cantP2Piso'] * $this->chapaPisoE + 
				$chapaAdXpanel['cantP2PisoAd'] * $this->chapaPisoE;
				
				$this->anchoP3 = $aire10Ref['pisosP3Aire10'] * $this->getEspesorAletaVen() + $cantPisos7xPanel['pisosP3Aleta7'] * $this->getEspesorAletaFluido() + 
				$pisosAletaAceiteXpanel['pisosP3Aceite'] * $this->getEspesorAletaFluido() + $chapaIntXpanel['cantP3Intermedia'] * $this->chapaIntermediaEsp + $chapaPiso['cantP3Piso'] * $this->chapaPisoE + 
				$chapaAdXpanel['cantP3PisoAd'] * $this->chapaPisoE;
				
				$res = array(
					'anchoP1' => $this->anchoP1,
					'anchoP2' => $this->anchoP2,
					'anchoP3' => $this->anchoP3,
					'anchoP4' => 0
				);				
				return $res;
				
				break;
			
			case '4':
				$this->anchoP1 = $aire10Ref['pisosP1Aire10'] * $this->getEspesorAletaVen() + $cantPisos7xPanel['pisosP1Aleta7'] * $this->getEspesorAletaFluido() + 
				$pisosAletaAceiteXpanel['pisosP1Aceite'] * $this->getEspesorAletaFluido() + $chapaIntXpanel['cantP1Intermedia'] * $this->chapaIntermediaEsp + $chapaPiso['cantP1Piso'] * $this->chapaPisoE + 
				$chapaAdXpanel['cantP1PisoAd'] * $this->chapaPisoE;
				
				$this->anchoP2 = $aire10Ref['pisosP2Aire10'] * $this->getEspesorAletaVen() + $cantPisos7xPanel['pisosP2Aleta7'] * $this->getEspesorAletaFluido() +
				$pisosAletaAceiteXpanel['pisosP2Aceite'] * $this->getEspesorAletaFluido() + $chapaIntXpanel['cantP2Intermedia'] * $this->chapaIntermediaEsp + $chapaPiso['cantP2Piso'] * $this->chapaPisoE + 
				$chapaAdXpanel['cantP2PisoAd'] * $this->chapaPisoE;
				
				$this->anchoP3 = $aire10Ref['pisosP3Aire10'] * $this->getEspesorAletaVen() + $cantPisos7xPanel['pisosP3Aleta7'] * $this->getEspesorAletaFluido() + 
				$pisosAletaAceiteXpanel['pisosP3Aceite'] * $this->getEspesorAletaFluido() + $chapaIntXpanel['cantP3Intermedia'] * $this->chapaIntermediaEsp + $chapaPiso['cantP3Piso'] * $this->chapaPisoE + 
				$chapaAdXpanel['cantP3PisoAd'] * $this->chapaPisoE;
				
				$this->anchoP4 = $aire10Ref['pisosP4Aire10'] * $this->getEspesorAletaVen() + $cantPisos7xPanel['pisosP4Aleta7'] * $this->getEspesorAletaFluido() + 
				$pisosAletaAceiteXpanel['pisosP4Aceite'] * $this->getEspesorAletaFluido() + $chapaIntXpanel['cantP4Intermedia'] * $this->chapaIntermediaEsp + $chapaPiso['cantP4Piso'] * $this->chapaPisoE + 
				$chapaAdXpanel['cantP4PisoAd'] * $this->chapaPisoE;
				
				$res = array(
					'anchoP1' => $this->anchoP1,
					'anchoP2' => $this->anchoP2,
					'anchoP3' => $this->anchoP3,
					'anchoP4' => $this->anchoP4,
				);				
				return $res;				
				
				break;
			
			default:
				
				break;
		}
		
		/*
		 * Materiales para intercooler
		 */
		
	}	
}
























