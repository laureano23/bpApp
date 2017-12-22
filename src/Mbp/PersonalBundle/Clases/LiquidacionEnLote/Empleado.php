<?php
namespace Mbp\PersonalBundle\Clases\LiquidacionEnLote;

/*
 * ESTA CLASE PERMITE CREAR UN OBJETO EMPLEADO CON LOS DATOS DE LAS FICHADAS A PARTIR DEL REGISTRO EN EXCEL
 * QUE BRINDA SISTEMA GESVR DEL RELOJ
 * */

class Empleado
{
	/*Columnas de la planilla Excel*/
	private $legajo;
	private $nombre;	
	private $fecha = array(); //en la fecha se guardan los horarios de entrada y salida
	private $dia = array();	
	private $hsNormalesTrabajadas = 0;
	private $phpExcelService;
	private $novedades = array(); //SON LAS OBSERVACIONES QUE CORRESPONDEN A NOVEDADES DE RECIBO DE SUELDO
	private $hsPuntualidad = 5;
	private $hsAsistencia = 10;
	private $hsJustificadas=0;	//REPRESENTA LAS HORAS JUSTIFICADAS POR CONCEPTOS COMO POR EJ: LICENCIA POR ENFERMEDAD, ETC
	
		 
	public function __construct()
	{
		
	}
	
<<<<<<< HEAD
	public function setLegajo($legajo){
		$this->legajo = $legajo;
=======
	public function ordenarObs(){
		sort($this->observaciones);
	}
	
	public function getHsJustificadas()
	{
		return $this->hsJustificadas;
	}
	
	public function setHsJustificadas($hs)
	{
		$this->hsJustificadas = $hs;
>>>>>>> ramaSueldosNovedades
	}
	
	public function getLegajo(){
		return $this->legajo;		
	}
	
	public function setNombre($nombre){
		$this->nombre = $nombre;
	}
	
	public function getNombe(){
		return $this->nombre;
	}
	
	public function setFecha($fecha){
		$fecha = \DateTime::createFromFormat('d-m-Y', $fecha);
		array_push($this->fecha['fecha'], $fecha);
	}
	
	public function getFecha(){
		return $this->fecha;
	}
	
	public function setEntrada($entrada){
		$entrada = \DateTime::createFromFormat('H:i', $entrada);
		array_push($this->fecha['entrada'], $entrada);
	}
	
<<<<<<< HEAD
	public function getEntrada(){
		return $this->fecha;
=======
	// CONVIERTE LAS HORAS DE LA PLANILLA EN FORMAT H:i A OBJETOS DATETIME
	public function addFichadaEntrada($strHora){
		$fichadaEntrada = \DateTime::CreateFromFormat('H:i', $strHora);
		
		if($fichadaEntrada == false || $fichadaEntrada == ""){return;}
		
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
		
		
		array_push($this->fecha[0]['fichadaE'], $fichadaEntrada);
	}

	public function vaciarArrayEntradas(){
		$this->fichadaEntrada = array();
	}

	public function addEntradas($strHora){
		$fichadaEntrada = \DateTime::CreateFromFormat('H:i', $strHora);
		if($fichadaEntrada == false || $fichadaEntrada == ""){return;}
		
		array_push($this->fecha[0]['entradaSimple'], $fichadaEntrada);
	}
	
	public function getEntradas(){
		return $this->entradas;
	}
	
	public function getFichadaSalida(){
		return $this->fichadaSalida;
	}
	
	public function addFichadaSalida($strHora){		
		$fichadaSalida = \DateTime::CreateFromFormat('H:i', $strHora);
		
		if($fichadaSalida == false || $fichadaSalida == ""){return;}
		
		//VALIDACION DE SALIDAS SEGUN POLITICA DE LA EMPRESA
		if($fichadaSalida && $fichadaSalida->format('i') > 0 && $fichadaSalida->format('i') < 30){
			$fichadaSalida->setTime($fichadaSalida->format('H'), 0);
		}
		if($fichadaSalida && $fichadaSalida->format('i') >= 30 && $fichadaSalida->format('i') <= 59){
			$fichadaSalida->setTime($fichadaSalida->format('H'), 30);
		}
		
		array_push($this->fecha[0]['fichadaS'], $fichadaSalida);
	}
	
	public function addSalidas($strHora){
		$fichadaSalida = \DateTime::CreateFromFormat('H:i', $strHora);		
		if($fichadaSalida == false || $fichadaSalida == ""){return;}
		
		array_push($this->fecha[0]['salidaSimple'], $fichadaSalida);
	}
	
	public function getSalidas(){
		return $this->salidas;
	}
	
	public function vaciarArraySalidas(){
		$this->fichadaSalida = array();
	}
	
	/*
	 * GUARDAMOS SOLO EL CODIGO DE LA OBSERVACION PARA LUEGO COMPARAR CONTRA LA BD
	 * */
	public function addObservacion($observacion){
		$obs = str_split($observacion, 2);
		if($obs[0]==""){
			//$this->fecha[0]['novedades'] = -1;
			//array_push($this->observaciones, -1);
		}else{
			$this->fecha[0]['novedades'] = (int)$obs[0];
			array_push($this->observaciones, (int)$obs[0]);	
		}
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
		return $this->hsExtrasTrabajadas;
	}
	
	public function setHsExtrasTrabajadas($hsExtras){
		$this->hsExtrasTrabajadas = $hsExtras;
	}
	
	public function addNovedad($novedad){
		array_push($this->novedades, $novedad);
	}
	
	public function getNovedades(){
		return $this->novedades;
	}
	
	public function setHsPuntualidad($hs){
		$hs < 0 ? $this->hsPuntualidad = 0 : $this->hsPuntualidad = $hs; //SI QUIERO SETEAR UNA HORA NEGATIVA SETEA 0;
	}
	
	public function getHsPuntualidad(){
		return $this->hsPuntualidad;
	}
	
	public function setHsAsistencia($hs){
		$this->hsAsistencia = $hs;
	}
	
	public function getHsAsistencia(){
		return $this->hsAsistencia;
>>>>>>> ramaSueldosNovedades
	}
}



















 