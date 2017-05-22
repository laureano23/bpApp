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
	
	public function __construct($filePath, $phpExcelService, $periodo)
	{
		$this->filePath = $filePath;
		$this->phpExcelService = $phpExcelService;
		$this->periodo = $periodo;
		$this->empleadosCollection = new EmpleadosCollection($filePath, $phpExcelService);
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
}



















 