<?php
namespace Mbp\PersonalBundle\Clases\LiquidacionEnLote;

use Mbp\PersonalBundle\Clases\LiquidacionEnLote\EmpleadosCollection;

/*
 * ESTA CLASE PREPARA LA COLECCION DE EMPLEADOS PARA LIQUIDAR LAS HORAS SEGUN EL PERIODO
 * */

class LiquidacionEnLote
{
	private $empleadosCollection;
	public static $hsNormales = 9;
	public $filePath;
	public $phpExcelService;
	private $columnas;
	private $errores = array(); //COLECCION DE ERRORES DE VALIDACION DE LA PLANILLA
	private $periodo;	
	private $horasNormales;
	private $diasMensuales; //CANTIDAD DE DIAS HABILES DEL MES PARA LIQUIDACION DE EMPLEADOS MENSUALES
	private $mes;
	private $anio;
	private $trimestre;	//DETERMINA EL TRIMESTRE QUE ESTOY LIQUIDANDO, SOLO PARA PREMIOS
	private $posicionMesTrimestre; //VARIABLE DE CALCULO PARA PREMIOS
	private $trimestresAll = array(
			1 => array(
				0 => 1,
				1 => 2,
				2 => 3
			),
			2 => array(
				0 => 4,
				1 => 5,
				2 => 6
			),
			3 => array(
				0 => 7,
				1 => 8,
				2 => 9
			),
			4 => array(
				0 => 10,
				1 => 11,
				2 => 12
			),
		);
	
	private $em;
	
	public function __construct($filePath, $phpExcelService, $periodo, $mes, $anio, $em)
	{
		$this->filePath = $filePath;
		$this->phpExcelService = $phpExcelService;
		$this->periodo = $periodo;
		$this->empleadosCollection = new EmpleadosCollection($filePath, $phpExcelService);
		$this->mes = $mes;
		$this->anio = $anio;
		$this->em = $em;
		
		$empleados = $this->empleadosCollection->getEmpleado();
		
		$err = $this->empleadosCollection->getError();
		if(!empty($err)){
			return;
		}
		
		
		
		$this->ValidarPeriodo();
		$this->CalcularHorasJustificadas();
		$this->CalcularHorasNormales();
		$this->CalcularHorasTrabajadas();
		
		$this->CalcularNovedades();		
		
		
		//SI CALCULAMOS PREMIOS CALCULAMOS PUNTUALIDAD Y ASISTENCIA DE LA COLECCION
		if($this->periodo == 7 || $this->periodo == 8){
			$this->calcularPuntualidad($this->periodo, $this->mes);
			$this->calcularAsistencia($this->mes);
		}
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
			
			case 7:
				$this->ValidarPremioQuincena1();
				break;
			default:
				
				break;
		}
	}
	
	//FUNCION AUXILIAR PARA VALIDAR LA PLANILLA DE PREMIOS
	private function setTrimestre()
	{
		$empleados = $this->getEmpleadosCollection()->getEmpleado();
		$fechas = $empleados[0]->getFecha(); 
		
		$posFechas = count($fechas);
		
		$primerFecha = $fechas[0];
		$ultimaFecha = $fechas[$posFechas-1];
		
		$mes = $ultimaFecha->format('n');
		
		if($mes >=1 && $mes <= 3) $this->trimestre = 1;
		if($mes >=4 && $mes <= 6) $this->trimestre = 2;
		if($mes >=7 && $mes <= 9) $this->trimestre = 3;
		if($mes >=10 && $mes <= 12) $this->trimestre = 4;
	}
	
	//FUNCION AUXILIAR PARA VALIDAR LA PLANILLA DE PREMIOS
	private function PosicionMesTrimestre()
	{
		$empleados = $this->getEmpleadosCollection()->getEmpleado();
		$fechas = $empleados[0]->getFecha(); 
		$posFechas = count($fechas);
		$ultimaFecha = $fechas[$posFechas-1];
		
		$trimestres = array(
			0 => 1,
			1 => 2,
			2 => 3,
			3 => 4,
		);
		
		$existe=false;
		$contadorMeses = 0;
		$posicionMes = null;
		$contadorTrimestres = 0;
		
		foreach ($this->trimestresAll as $trim) {
			if($trimestres[$contadorTrimestres] == $this->trimestre){				
				while ($contadorMeses < 3) {
					if($ultimaFecha->format('n') == $trim[$contadorMeses]){						
						$this->posicionMesTrimestre = $contadorMeses;	
						return;					
					}	
					$contadorMeses++;
				}
				$contadorMeses == 0;		
			}
			$contadorTrimestres++;
		}
	}
	
	private function ValidarPremioQuincena1()
	{
		$this->setTrimestre();
		$this->PosicionMesTrimestre();
		$contadorMeses = 0;
		
		if($this->posicionMesTrimestre == 0) $contadorMeses=0;
		if($this->posicionMesTrimestre == 1) $contadorMeses=1;
		if($this->posicionMesTrimestre == 3) $contadorMeses=2;
		
		
		
		$empleados = $this->getEmpleadosCollection()->getEmpleado();
		$fechas = $empleados[0]->getFecha();
		
		$i=0;
		$existe = false;
		
		foreach ($this->trimestresAll[$this->trimestre] as $meses) {
			while ($meses != $this->trimestresAll[$this->trimestre][$this->posicionMesTrimestre]) {
				foreach ($fechas as $fecha) {
					if($fecha->format('n') == $meses){
						$existe = true;
					}				
					
				}
				
				if($existe == FALSE){
					$this->addError("La planilla no tiene el trimeste correcto para liquidar el premio");
					return;	
				}
				
				if($this->posicionMesTrimestre == $i){
					return;
				}
				$i++;	
			}
			
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
			$this->horasNormales = $dias*self::$hsNormales;	
		}
		
		if($this->periodo == 2){
			
			foreach ($empleados[0]->getFecha() as $fechaEntrada) {
				if($fechaEntrada->format('D') == 'Sat' || $fechaEntrada->format('D') == 'Sun'){
					$dias++;	
				}				
			}
			
			$this->horasNormales = ($diasMes - 15 - $dias) * self::$hsNormales;	
		}
		
		if($this->periodo == 3){
			foreach ($empleados[0]->getFecha() as $fechaEntrada) {
				if($fechaEntrada->format('D') != 'Sat' && $fechaEntrada->format('D') != 'Sun'){
					$dias++;	
				}				
			}
			$this->diasMensuales = $dias;	
		}
		
		/* SI TENEMOS UN FERIADO SABADO O DOMINGO DEBEMOS AUMENTAR LAS HS NORMALES */
		$i=0;
		$fechaEntrada = $empleados[0]->getFecha();	
		foreach ($empleados[0]->getObservaciones() as $obs) {					
			if($obs == 6 && $fechaEntrada[$i]->format('D') == 'Sat' || $obs == 6 && $fechaEntrada[$i]->format('D') == 'Sun'){
				$this->horasNormales += 9;
			}
			$i++;
		}
		
	}
	
	/*
	 * FUNCION PARA CALCULAR LAS HORAS TRABAJADAS DE LA COLECCION DE EMPLEADOS
	 * */
	private function CalcularHorasTrabajadas()
	{
		foreach ($this->getEmpleadosCollection()->getEmpleado() as $empleado) {			
			$entradas = $empleado->getEntradas();
			$salidas = $empleado->getSalidas();
			$dias = $empleado->getDia();
			$horas = 0;
			$minutosSalida = 0;
			$minutosEntrada = 0;
			
						
			
			$dia=0;
			foreach ($entradas as $entrada) {				
				foreach ($entrada as $fichada) {					
					$contadorSalida=0;
					$horas = $horas + ($salidas[$dia][$contadorSalida]->format('H') - $fichada->format('H'));
					$minutosSalida = $minutosSalida + ($salidas[$dia][$contadorSalida]->format('i'));
					$minutosEntrada = $minutosEntrada + ($entradas[$dia][$contadorSalida]->format('i'));
					$contadorSalida++;			
				}
				$dia++;											
			}
			
						
			//AJUSTE POR MINUTOS EXCEDIDOS
			if($minutosSalida >= 30 || $minutosEntrada >= 30){
				$ajuste = $minutosSalida/60 - $minutosEntrada/60;
				$horas = $horas + $ajuste;
			}			
			
			//SE AGREGAN HORAS JUSTIFICADAS POR LICENCIAS
			$hsJustificadas = $empleado->getHsJustificadas();
			if($empleado->getHsJustificadas() > 0){
				$horas += $hsJustificadas;
			}
			
			//SON LAS HORAS QUE EL EMPLEADO DEBERIA HABER TRABAJO
			$hsATrabajar = $this->horasNormales - $hsJustificadas;		
			
			$hsNormales = 0;
			$hsExtras = 0;
			
			
			if($horas <= $hsATrabajar){
				$hsNormales = $horas;
				$hsExtras = 0;
				
			}else{
				$hsNormales = $hsATrabajar;
				$hsExtras = $horas - $this->horasNormales;
				$hsExtras <= 0 ? $hsExtras = 0 : $hsExtras;
			}	
			$empleado->setHsNormalesTrabajadas($hsNormales);
			$empleado->setHsExtrasTrabajadas($hsExtras);
		}
	}

	private function CalcularHorasJustificadas()
	{
		$repoConceptos = $this->em->getRepository('MbpPersonalBundle:CodigoSueldos');
		foreach ($this->getEmpleadosCollection()->getEmpleado() as $empleado) {
			foreach ($empleado->getObservaciones() as $observacion) {
				$novedad = $repoConceptos->findOneByCodigoObservacion($observacion);
				if(!empty($novedad)){
					$empleado->setHsJustificadas($empleado->getHsJustificadas()+self::$hsNormales);
				}
			}	
		}
	}

	private function CalcularNovedades()
	{
		//SUMA DE NOVEDADES
		$repoConceptos = $this->em->getRepository('MbpPersonalBundle:CodigoSueldos');
		foreach ($this->getEmpleadosCollection()->getEmpleado() as $empleado) {			
			foreach ($empleado->getObservaciones() as $observacion) {
				$novedad = $repoConceptos->findOneByCodigoObservacion($observacion);
				if(!empty($novedad)){
					$empleado->addNovedad($novedad->getId());
				}
			}
		}
	}
	
	/*
	 * FUNCION PARA DETERMINAR SI EL EMPLEADO TUVO LLEGADAS TARDES EN LA QUINCENA Y CALCULAR LAS HORAS DE PUNTUALIDAD
	 * PARA LIQUIDACIONES QUINCENALES
	 * */
	private function calcularPuntualidad($periodo, $mes)
	{
		$empleados = $this->getEmpleadosCollection()->getEmpleado();
		
		foreach ($empleados as $emp) {
			$entradas = $emp->getFichadaEntrada();
			foreach ($entradas as $ent) {
				$dias;
				$periodo == 7 ? $dias = 0 : $dias = 15; //7 ES PRIMER QUINCENA Y 8 2°
				if($ent->format('m') == $mes && $ent->format('d') > $dias){ //LA PLANILLA CARGA EL TRIMESTRE PERO SOLO ANALIZAMOS LA QUINCENA EN CURSO
					if($ent->format('H') > 6 || $ent->format('i') > 5){ //SI EL EMPLEADO LLEGO DESPUES DE 6:05 PIERDE LA PUNTUALIDAD
						$emp->setHsPuntualidad(0);
						break; //SI PERDIO LA PUNTUALIDAD PASAMO AL SIEGUIENTE EMPLEADO
					}elseif($ent->format('H') == 6 && $ent->format('i') > 0 && $ent->format('i') <= 5){ //SI EL EMPLEADO INGRESA ENTRE 06:00 Y 06:05 PIERDE 2 HS DE PUNTUALIDAD
						$emp->setHsPuntualidad($emp->getHsPuntualidad() - 2);
					}	
				}				
			}
		}
	}
	
	/*
	 * FUNCION PARA CALCULAR LAS HORAS DE PREMIO A COBRAR POR ASISTENCIA SEGUN POLITICAS  DE LA EMPRESA
	 * */
	private function calcularAsistencia($mes)
	{
		$empleados = $this->getEmpleadosCollection()->getEmpleado();
		
		foreach ($empleados as $emp) {
			$entradas = $emp->getFichadaEntrada();
			foreach ($entradas as $ent) {
							
			}
		}
	}
}



















 