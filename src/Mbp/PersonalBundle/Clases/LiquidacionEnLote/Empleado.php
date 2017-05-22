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
	private $phpExcelService;
	
		 
	public function __construct()
	{
		
	}
	
	public function getLegajo(){
		return $this->legajo;
	}
	
	public function setLegajo($legajo){
		$this->legajo = $legajo;
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
		array_push($this->fichadaEntrada, $fichadaEntrada);
	}
	
	public function getFichadaSalida(){
		return $this->fichadaSalida;
	}
	
	public function addFichadaSalida($strHora){
		$fichadaSalida = \DateTime::CreateFromFormat('H:i', $strHora);
		array_push($this->fichadaSalida, $fichadaSalida);
	}
	
	public function addObservacion($observacion){
		array_push($this->observaciones, $observacion);
	}
	
	public function getObservaciones(){
		return $this->observaciones;
	}
}



















 