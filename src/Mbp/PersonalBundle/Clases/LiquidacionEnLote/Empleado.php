<?php
namespace Mbp\PersonalBundle\Clases\LiquidacionEnLote;

/*
 * ESTA CLASE PERMITE CREAR UN OBJETO EMPLEADO CON LOS DATOS DE LAS FICHADAS A PARTIR DEL REGISTRO EN EXCEL
 * QUE BRINDA SISTEMA GESVR DEL RELOJ
 * */

class Empleado
{
	private $legajo;
	private $nombre;
	private $observaciones = array();
	private $fecha = array();
	private $dia = array();
	private $fichadaEntrada = array();
	private $fichadaSalida = array();
	private $hsNormalesTrabajadas = 0;
	private $hsExtrasTrabajadas = 0;
	private $phpExcelService;
	
		 
	public function __construct()
	{
		
	}
	
	public function getLegajo(){
		return $this->legajo;
	}
	
	public function setLegajo($legajo){
		$this->legajo = (int)$legajo; //SE CASTEA PARA QUITAR LOS CEROS DE LA IZQUIERDA
	}
	
	public function getNombre(){
		return $this->nombre;
	}
	
	public function setNombre($nombre){
		$this->nombre = $nombre;
	}
	
	public function getFecha(){
		return $this->fecha;
	}
	
	//CONVIERTE LAS FECHAS DE LA PLANILLA EN FORMAT d-M-y A OBJETOS DATETIME
	public function addFecha($strFecha){
		$fecha = \DateTime::CreateFromFormat('d-M-y', $strFecha);
		if($fecha == false){
			throw new \Exception("Fecha no Valida", 1);			
		}
		array_push($this->fecha, $fecha);
	}
	
	public function getDia(){
		return $this->dia;
	}
	
	public function addDia($dia){
		array_push($this->dia, $dia);
	}
	
	public function getFichadaEntrada(){
		return $this->fichadaEntrada;
	}
	
	// CONVIERTE LAS HORAS DE LA PLANILLA EN FORMAT H:i A OBJETOS DATETIME
	public function addFichadaEntrada($strHora){
		$fichadaEntrada = \DateTime::CreateFromFormat('H:i', $strHora);
		
		//VALIDACION DE ENTRADAS SEGUN POLITICA DE LA EMPRESA
		if($fichadaEntrada && $fichadaEntrada->format('i') > 0 && $fichadaEntrada->format('i') <= 5){
			$fichadaEntrada->setTime($fichadaEntrada->format('H'), 0);
		}
		if($fichadaEntrada && $fichadaEntrada->format('i') > 5 && $fichadaEntrada->format('i') <= 30){
			$fichadaEntrada->setTime($fichadaEntrada->format('H'), 30);
		}		
		if($fichadaEntrada && $fichadaEntrada->format('i') > 30 && $fichadaEntrada->format('i') <= 35){
			$fichadaEntrada->setTime($fichadaEntrada->format('H'), 30);
		}
		if($fichadaEntrada && $fichadaEntrada->format('i') > 35){
			$fichadaEntrada->setTime(($fichadaEntrada->format('H') + 1), 0);
		}		
		if($fichadaEntrada && $fichadaEntrada->format('H') < 6){
			$fichadaEntrada->setTime(6, 0);
		}
		array_push($this->fichadaEntrada, $fichadaEntrada);
	}
	
	public function getFichadaSalida(){
		return $this->fichadaSalida;
	}
	
	public function addFichadaSalida($strHora){		
		$fichadaSalida = \DateTime::CreateFromFormat('H:i', $strHora);
		
		//VALIDACION DE SALIDAS SEGUN POLITICA DE LA EMPRESA
		if($fichadaSalida && $fichadaSalida->format('i') > 0 && $fichadaSalida->format('i') < 30){
			$fichadaSalida->setTime($fichadaSalida->format('H'), 0);
		}
		if($fichadaSalida && $fichadaSalida->format('i') >= 30 && $fichadaSalida->format('i') <= 59){
			$fichadaSalida->setTime($fichadaSalida->format('H'), 30);
		}
		
		array_push($this->fichadaSalida, $fichadaSalida);
	}
	
	/*
	 * GUARDAMOS SOLO EL CODIGO DE LA OBSERVACION PARA LUEGO COMPARAR CONTRA LA BD
	 * */
	public function addObservacion($observacion){
		$obs = str_split($observacion, 2);
		array_push($this->observaciones, (int)$obs[0]);
	}
	
	public function getObservaciones(){
		return $this->observaciones;
	}
	
	public function getHsNormalesTrabajadas(){
		return $this->hsNormalesTrabajadas;
	}
	
	public function setHsNormalesTrabajadas($hsNormales){
		$this->hsNormalesTrabajadas = $hsNormales;
	}
	
	public function getHsExtrasTrabajadas(){
		return $this->hsNormalesTrabajadas;
	}
	
	public function setHsExtrasTrabajadas($hsExtras){
		$this->hsExtrasTrabajadas = $hsExtras;
	}
}



















 