<?php
namespace Mbp\PersonalBundle\Clases\LiquidacionEnLote;

use Mbp\PersonalBundle\Clases\LiquidacionEnLote\EmpleadosCollection;

/*
 * ESTA CLASE PREPARA LA COLECCION DE EMPLEADOS PARA LIQUIDAR LAS HORAS SEGUN EL PERIODO
 * */

class LiquidacionEnLote
{
	private $empleadosCollection;
	public $filePath;
	public $phpExcelService;
	private $columnas;
	private $errores = array(); //COLECCION DE ERRORES DE VALIDACION DE LA PLANILLA
	private $periodo;
	private $horasNormales;
	private $diasMensuales; //CANTIDAD DE DIAS HABILES DEL MES PARA LIQUIDACION DE EMPLEADOS MENSUALES
	private $mes;
	private $anio;
	
	public function __construct($filePath, $phpExcelService, $periodo, $mes, $anio)
	{
		$this->filePath = $filePath;
		$this->phpExcelService = $phpExcelService;
		$this->periodo = $periodo;
		$this->empleadosCollection = new EmpleadosCollection($filePath, $phpExcelService);
		$this->mes = $mes;
		$this->anio = $anio;
		
		$this->ValidarPeriodo();
		$this->CalcularHorasNormales();
		$this->CalcularHorasTrabajadas();
	}
	
	/*
	 * VALIDAR PERIODO QUE SE LIQUIDA, DEBE TENER LOS DIAS HABILES CORRESPONDIENTES
	 * PERIODO: 1, 1° QUINCENA
	 * PERIODO: 2, 2° QUINCENA
	 * PERIODO: 3, MENSUALES
	 * */
	public function ValidarPeriodo()
	{
		switch ($this->periodo) {
			case 1:
				$this->ValidarPrimerQuincena();
				break;
			
			case 2:
				$this->ValidarSegundaQuincena();
				break;
				
			case 3:
				$this->ValidarMes();
				break;
			
			default:
				
				break;
		}
	}
	
	private function ValidarPrimerQuincena()
	{
		//VALIDAR 1° QUINCENA
		$i=1;
		foreach ($this->empleadosCollection->getEmpleado() as $empleado) {
			foreach ($empleado->getFecha() as $fecha) {	
					
				if($fecha->format('j') != $i){
					$strError = $empleado->getNombre()." ".$fecha->format('d/m/Y').": Esta fecha no coincide con la 1° quincena";
					$this->addError($strError);
				}
				$i++;
			}
			$i=1;
		}
	}
	
	private function ValidarSegundaQuincena()
	{
		//VALIDAR 2° QUINCENA
		$i=16; //LA 2° QUINCENA COMIENZA EL DIA 16 DE CADA MES
		$empleados = $this->empleadosCollection->getEmpleado();
		$fechas = $empleados[0]->getFecha();
		$mes = $fechas[0]->format('n');
		$anio = $fechas[0]->format('Y');
		$diasDelMes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
		
		foreach ($empleados as $empleado) {
			foreach ($empleado->getFecha() as $fecha) {		
					
				if($fecha->format('j') != $i){
					$strError = $empleado->getNombre()." ".$fecha->format('d/m/Y').": Esta fecha no coincide con la 2° quincena";
					$this->addError($strError);
				}
				if($i == $diasDelMes){
					return;	//CUANDO SE RECORRIÓ HASTA EL ULTIMO DIA DEL MES, SALIMOS DE LA FUNCION
				}
				$i++;				
			}
			$i=1;
		}
	}
	
	private function ValidarMes()
	{
		//VALIDAR MES
		$i=1; 
		$empleados = $this->empleadosCollection->getEmpleado();
		$fechas = $empleados[0]->getFecha();
		$mes = $fechas[0]->format('n');
		$anio = $fechas[0]->format('Y');
		$diasDelMes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
		
		foreach ($empleados as $empleado) {
			foreach ($empleado->getFecha() as $fecha) {		
					
				if($fecha->format('j') != $i){
					$strError = $empleado->getNombre()." ".$fecha->format('d/m/Y').": Esta fecha no coincide con el mes a liquidar";
					$this->addError($strError);
				}
				if($i == $diasDelMes){
					return;	//CUANDO SE RECORRIÓ HASTA EL ULTIMO DIA DEL MES, SALIMOS DE LA FUNCION
				}
				$i++;				
			}
			$i=1;
		}
	}
	
	public function getEmpleadosCollection()
	{
		return $this->empleadosCollection;
	}
	
	public function getErrores()
	{
		return $this->errores;
	}
	
	private function addError($strError)
	{
		array_push($this->errores, $strError);
	}
	
	/*
	 * FUNCION QUE DE ACUERDO AL PERIODO A LIQUIDAR Y AL MES CALCULA LAS HORAS NORMALES
	 * */
	private function CalcularHorasNormales()
	{
		$empleados = $this->getEmpleadosCollection()->getEmpleado();
		
		$dias=0;
		$diasMes = cal_days_in_month(CAL_GREGORIAN, $this->mes, $this->anio);
		
		if($this->periodo == 1){
			foreach ($empleados[0]->getFecha() as $fechaEntrada) {
				if($fechaEntrada->format('D') != 'Sat' && $fechaEntrada->format('D') != 'Sun'){
					$dias++;
				}				
			}
			$this->horasNormales = $dias*9;	
		}
		
		if($this->periodo == 2){
			foreach ($empleados[0]->getFecha() as $fechaEntrada) {
				if($fechaEntrada->format('D') != 'Sat' && $fechaEntrada->format('D') != 'Sun'){
					$dias++;	
				}				
			}
			$this->horasNormales = ($diasMes - 15) * 9;	
		}
		
		if($this->periodo == 3){
			foreach ($empleados[0]->getFecha() as $fechaEntrada) {
				if($fechaEntrada->format('D') != 'Sat' && $fechaEntrada->format('D') != 'Sun'){
					$dias++;	
				}				
			}
			$this->diasMensuales = $dias;	
		}
	}
	
	/*
	 * FUNCION PARA CALCULAR LAS HORAS TRABAJADAS DE LA COLECCION DE EMPLEADOS
	 * */
	private function CalcularHorasTrabajadas()
	{
		foreach ($this->getEmpleadosCollection()->getEmpleado() as $empleado) {
			$contadorSalida=0;
			$entradas = $empleado->getFichadaEntrada();
			$salidas = $empleado->getFichadaSalida();
			$horas = 0;
			$minutos = 0;
			foreach ($entradas as $entrada) {
				if(!empty($entrada)){
					$horas = $horas + ($salidas[$contadorSalida]->format('H') - $entrada->format('H'));
					$minutos = $minutos + ($salidas[$contadorSalida]->format('i'));
					
				}	
				$contadorSalida++;									
			}
			
			//AJUSTE POR MINUTOS EXCEDIDOS
			if($minutos >= 30){
				$ajuste = $minutos/60;
				$horas = $horas + $ajuste;
			}
			
			$hsNormales = 0;
			$hsExtras = 0;
			if($horas <= $this->horasNormales){
				$hsNormales = $horas;
				$hsExtras = 0;
			}else{
				$hsNormales = $this->horasNormales;
				$hsExtras = $horas - $this->horasNormales;
			}
						
			
			$empleado->setHsNormalesTrabajadas($hsNormales);
			$empleado->setHsExtrasTrabajadas($hsExtras);
		}
	}
}



















 