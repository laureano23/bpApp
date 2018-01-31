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
	private $legajosParaLiquidar;
	
	public function __construct($filePath, $phpExcelService, $periodo, $mes, $anio, $em)
	{
		$this->filePath = $filePath;
		$this->phpExcelService = $phpExcelService;
		$this->periodo = $periodo;
		$this->mes = $mes;
		$this->anio = $anio;
		$this->em = $em;
		
		$this->legajosParaLiquidar();
		$this->empleadosCollection = new EmpleadosCollection($filePath, $phpExcelService, $this->legajosParaLiquidar);
		
		
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
			$this->calcularAsistencia($periodo, $mes);	
					
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
	
	//TRAER LOS NUMEROS DE LEGAJO QUE ESTAN DESIGNADOS PARA LIQUIDAR POR LOTE
	public function legajosParaLiquidar()
	{
		$repo = $this->em->getRepository('MbpPersonalBundle:Personal');
		$this->legajosParaLiquidar = $repo->createQueryBuilder('p')
			->select('p.legajo')
			->where('p.liquidaPorLote = true')
			->andWhere('p.legajo > 0')
			->getQuery()
			->getArrayResult();
	}
	
	//FUNCION AUXILIAR PARA VALIDAR LA PLANILLA DE PREMIOS
	private function setTrimestre()
	{
		$empleados = $this->getEmpleadosCollection()->getEmpleado();
		$fechas = $empleados[0]->getFecha(); 
		
		$posFechas = count($fechas);
		
		$ultimaFecha = $fechas[0]['fecha'];
		
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
		$ultimaFecha = $fechas[0]['fecha'];
		
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
		
		
		foreach ($this->trimestresAll[$this->trimestre] as $mes) {
			while ($mes == $this->trimestresAll[$this->trimestre][$this->posicionMesTrimestre]) {
				foreach ($fechas as $fecha) {
					if($fecha['fecha']->format('n') == $mes){						
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
		foreach ($this->empleadosCollection->getEmpleado() as $empleado) {
			$datos = $empleado->getFecha();
			foreach ($datos as $fecha) {
				if(array_key_exists('fecha', $fecha) == false) continue;
				if($fecha['fecha']->format('j') >= 1 && $fecha['fecha']->format('j') <= 15){
					continue;					
				}else{
					$strError = $empleado->getNombre()." ".$fecha['fecha']->format('d/m/Y').": Esta fecha no coincide con la 1° quincena";
					$this->addError($strError);
				}
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
		$cantFechas = count($fechas);
		$mes = $fechas[0]['fecha']->format('n');
		$anio = $fechas[0]['fecha']->format('Y');
		$diasDelMes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
		
		foreach ($empleados as $empleado) {
			foreach ($empleado->getFecha() as $fecha) {
				if($fecha['fecha']->format('j') != $diasDelMes){
					$strError = $empleado->getNombre()." ".$fecha['fecha']->format('d/m/Y').": Esta fecha no coincide con la 2° quincena";
					$this->addError($strError);
				}
				if($i == $diasDelMes){
					return;	//CUANDO SE RECORRIÓ HASTA EL ULTIMO DIA DEL MES, SALIMOS DE LA FUNCION
				}
				$diasDelMes--;				
			}
			$diasDelMes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);;
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
			$fechas = $empleados[0]->getFecha();
			
			foreach ($fechas as $fechaEntrada) {
				if(array_key_exists('fecha', $fechaEntrada) == false) continue;
				if($fechaEntrada['fecha']->format('D') != 'Sat' && $fechaEntrada['fecha']->format('D') != 'Sun'){
					$dias++;
				}				
			}
			$this->horasNormales = $dias*self::$hsNormales;	
		}
		
		if($this->periodo == 2){
			
			foreach ($empleados[0]->getFecha() as $fechaEntrada) {
				if($fechaEntrada['fecha']->format('D') == 'Sat' || $fechaEntrada['fecha']->format('D') == 'Sun'){
					$dias++;	
				}				
			}
			
			$this->horasNormales = ($diasMes - 15 - $dias) * self::$hsNormales;	
		}
		
		
		
		if($this->periodo == 3){
			foreach ($empleados[0]->getFecha() as $fechaEntrada) {
				if($fechaEntrada['fecha']->format('D') != 'Sat' && $fechaEntrada['fecha']->format('D') != 'Sun'){
					$dias++;	
				}				
			}
			$this->diasMensuales = $dias;	
		}
		
		/* SI TENEMOS UN FERIADO SABADO O DOMINGO DEBEMOS AUMENTAR LAS HS NORMALES */
		$fechaEntrada = $empleados[0]->getFecha();
		$ultimaFecha=count($fechaEntrada) - 1;
		foreach ($empleados[0]->getObservaciones() as $obs) {			
			if($obs == 6 && $fechaEntrada[$ultimaFecha]['fecha']->format('D') == 'Sat' || $obs == 6 && $fechaEntrada[$ultimaFecha]['fecha']->format('D') == 'Sun'){
				$this->horasNormales += 9;
				
			}
			$ultimaFecha--;
		}
	}
	
	/*
	 * FUNCION PARA CALCULAR LAS HORAS TRABAJADAS DE LA COLECCION DE EMPLEADOS
	 * */
	private function CalcularHorasTrabajadas()
	{
		
		foreach ($this->getEmpleadosCollection()->getEmpleado() as $empleado) {			
			$fechas = $empleado->getFecha();
			$dias = $empleado->getDia();
			$horas = 0;
			$minutosSalida = 0;
			$minutosEntrada = 0;
			
						
			
			$dia=0;
			foreach ($fechas as $fecha) {
				if(array_key_exists('fichadaE', $fecha) == FALSE) continue;
				$contadorSalida=0;				
				foreach ($fecha['fichadaE'] as $fichada) {
					$horas = $horas + ($fecha['fichadaS'][$contadorSalida]->format('H') - $fichada->format('H'));
					$minutosSalida = $minutosSalida + ($fecha['fichadaS'][$contadorSalida]->format('i'));
					$minutosEntrada = $minutosEntrada + ($fecha['fichadaE'][$contadorSalida]->format('i'));
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
		$repoConceptos = $this->em->getRepository('MbpPersonalBundle:CodigoSueldos');
		foreach ($empleados as $emp) {
			$entradas = $emp->getFecha();
			
			foreach ($entradas as $ent) {
				$existeEntrada = false;
				//NO HACEMOS ANALISIS SI NO HAY FICHADA O SI EL DIA A ANALIZAR ES SABADO O DOMINGO
				if(
					empty($ent['entradaSimple']) && $ent['novedades'] == -1 ||
					$ent['fecha']->format('D') == "Sat"
					|| $ent['fecha']->format('D') == "Sun")
					{
						continue;
					} 
								
				$dias;
				$periodo == 7 ? $dias = 0 : $dias = 15; //7 ES PRIMER QUINCENA Y 8 2°
				
				if(!empty($ent['entradaSimple'])){
					$existeEntrada = true;
				}
				
				if($ent['fecha']->format('m') == $mes && $ent['fecha']->format('j') > $dias){ //LA PLANILLA CARGA EL TRIMESTRE PERO SOLO ANALIZAMOS LA QUINCENA EN CURSO		
					
					/*REVISAR SI EN EL DIA HAY ALGUNA NOVEDAD QUE ANULE EL PREMIO*/
					foreach ($emp->getNovedades() as $novedad) {						
						$novedad = $repoConceptos->find($novedad);
						if($novedad->getCuentaAsistencia() == true){							
							$emp->setHsPuntualidad(0);
							break; //SI PERDIO LA PUNTUALIDAD PASAMOS AL SIGUIENTE EMPLEADO
						}
					}
					
					//print_r($ent);
					if($existeEntrada == true){
						if($ent['entradaSimple'][0]->format('H') > 6){ //SI EL EMPLEADO LLEGO DESPUES DE 6:05 PIERDE LA PUNTUALIDAD	
							
							$emp->setHsPuntualidad(0);
							break; //SI PERDIO LA PUNTUALIDAD PASAMOS AL SIGUIENTE EMPLEADO
						}else{
							if($ent['fecha']->format('m') == $mes && 
								$ent['entradaSimple'][0]->format('H') == 6 &&
								$ent['entradaSimple'][0]->format('i') > 0 &&
								$ent['entradaSimple'][0]->format('i') <= 5){ //SI EL EMPLEADO INGRESA ENTRE 06:00 Y 06:05 PIERDE 2 HS DE PUNTUALIDAD
								$emp->setHsPuntualidad($emp->getHsPuntualidad() - 2);
							}else{ //SI INGRESA MAS ALLA DE LAS 6.05 PIERDE TODA LA PUNTUALIDAD							
								if($ent['entradaSimple'][0]->format('H') == 6 && $ent['entradaSimple'][0]->format('i') > 5){
									$emp->setHsPuntualidad(0);	
								}
							}	
						}	
					}	
				}	
				$existeEntrada = false;			
			}
		}
	}

	private function calcularAsistencia($periodo, $mes)
	{
		$empleados = $this->getEmpleadosCollection()->getEmpleado();
		$contAsistencia=0; //SE PERMITE HASTA 6 HORAS TRIMESTRALES
		
				
		
		foreach ($empleados as $emp) {
			$fechas = $emp->getFecha();
			$ausenteTotal=0;
			
			$ausenteFueraPeriodo = $this->contarSalidasFueraDeJornada($periodo, $mes, $emp);
			$ausenteEnPeriodo = $this->contarSalidasEnJornada($periodo, $mes, $emp);
			
			
			
			if($ausenteFueraPeriodo > 6){
				$ausenteFueraPeriodo = 0;
			}
			
			$ausenteTotal = $ausenteEnPeriodo + $ausenteFueraPeriodo;
			
			if($emp->getLegajo() == 50){
			//print_r($ausenteTotal);	
			}
			
			if($ausenteTotal > 6){
				$emp->setHsAsistencia(0);
			}
			
			
		}	
	}

	//CONTAR LAS HORAS NO TRABAJADAS EN LA JORNADA LABORAL FUERA DEL PERIODO QUE ESTAMOS LIQUIDANDO
	private function contarSalidasFueraDeJornada($periodo, $mes, $emp)
	{
		$strFecha;
		$contAsistencia=0; //SE PERMITE HASTA 6 HORAS TRIMESTRALES
		
		if($periodo == 7){
			$strFecha = "01/".$mes."/".$this->anio;
		}else{
			$strFecha = "31/".$mes."/".$this->anio;
		}
		
		$fechaLimite = \DateTime::createFromFormat('d/m/Y', $strFecha);
		
		$fechas = $emp->getFecha();
		
		foreach ($fechas as $fecha) {
			if($fecha['fecha'] < $fechaLimite){
				
				//NO HACEMOS ANALISIS SI NO HAY FICHADA O SI EL DIA A ANALIZAR ES SABADO O DOMINGO
				if(empty($fecha['fichadaE'][0]) || $fecha['fecha']->format('D') == "Sat" || $fecha['fecha']->format('D') == "Sun") continue;
				
				$primerFichadaDelDia=true;
				$ultimaSalida;
				foreach ($fecha['fichadaE'] as $ent) {
					
					//AJUSTAMOS LAS HORAS DE LA PRIMER ENTRADA
					if($ent->format('G') > 6 && $primerFichadaDelDia){
						//$primerFichadaDelDia = false; 
						$contAsistencia += $ent->format('G') - 6;								
					}
					
					//AJUSTAMOS LOS MINUTOS DE LA PRIMER ENTRADA
					if($ent->format('i') > 0 && $primerFichadaDelDia){
						//$primerFichadaDelDia = false; 
						$contAsistencia += 0.5;						
					}
					
					//SI HAY MAS DE UNA ENTRADA EL MISMO DIA ANALIZAR CUANTAS HORAS ESTUVO FUERA DE LA EMPRESA
					$contSalida=0;
					if($primerFichadaDelDia == false && $ent->format('G') > 6){
						$minutos=0;
						$horasTomadas = $ent->format('G') - $fecha['fichadaS'][$contSalida]->format('G');
						if($ent->format('i') > 0){
							$minutos += 0.5;
						}
						$contAsistencia += $horasTomadas;
						$contSalida++;						
					}
					
					$primerFichadaDelDia = false;
					$ultimaSalida = $fecha['fichadaS'][$contSalida];
				}
				//ANTES DE PASAR A LA SIGUIENTE FECHA ANALIZAMOS SI LA ULTIMA SALIDA ES < A 15HS
				if($ultimaSalida->format('G') < 15){
					$contAsistencia += 15 - $ultimaSalida->format('G');
				}
				
				//AJUSTAMOS LOS MINUTOS DE LA ULTIMA SALIDA
				if($ultimaSalida->format('G') < 15 && $ultimaSalida->format('i') > 0){
					$contAsistencia -= 0.5;
				}	
			}
		}
		return $contAsistencia;
	}
	
	//CONTAR LAS HORAS NO TRABAJADAS EN LA JORNADA LABORAL FUERA DEL PERIODO QUE ESTAMOS LIQUIDANDO
	private function contarSalidasEnJornada($periodo, $mes, $emp)
	{
		$strFecha;
		$contAsistencia=0; //SE PERMITE HASTA 6 HORAS TRIMESTRALES
		$repoConceptos = $this->em->getRepository('MbpPersonalBundle:CodigoSueldos');
		
		if($periodo == 7){
			$strFecha = "01/".$mes."/".$this->anio;
		}else{
			$strFecha = "15/".$mes."/".$this->anio;
		}
		
		$fechaInicio = \DateTime::createFromFormat('d/m/Y', $strFecha);
		
		$fechas = $emp->getFecha();
		
		foreach ($fechas as $fecha) {
			if($fecha['fecha'] >= $fechaInicio){
				
				//NO HACEMOS ANALISIS SI NO HAY FICHADA O SI EL DIA A ANALIZAR ES SABADO O DOMINGO Y NO HAY OBSERVACIONES
				if(empty($fecha['fichadaE'][0]) && $fecha['novedades'] == -1 ||
					$fecha['fecha']->format('D') == "Sat" ||
					$fecha['fecha']->format('D') == "Sun")
					{
						continue;	
					} 
				
				$primerFichadaDelDia=true;
				$ultimaSalida;
				$existeSalida = false;
				
				foreach ($emp->getNovedades() as $novedad) {						
					$novedad = $repoConceptos->find($novedad);
					if($novedad->getCuentaAsistencia() == true){
						
						return 10; //RETORNAMOS UN VALOR MAYOR A 6 PARA MARCAR QUE PERDIO LA ASISTENCIA
					}
				}
				
				foreach ($fecha['fichadaE'] as $ent) {
					
					//AJUSTAMOS LAS HORAS DE LA PRIMER ENTRADA
					if($ent->format('G') > 6 && $primerFichadaDelDia){
						//$primerFichadaDelDia = false; 
						$contAsistencia += $ent->format('G') - 6;								
					}
					
					//AJUSTAMOS LOS MINUTOS DE LA PRIMER ENTRADA
					if($ent->format('i') > 0 && $primerFichadaDelDia){
						//$primerFichadaDelDia = false; 
						$contAsistencia += 0.5;						
					}
					
					//SI HAY MAS DE UNA ENTRADA EL MISMO DIA ANALIZAR CUANTAS HORAS ESTUVO FUERA DE LA EMPRESA
					$contSalida=0;
					if($primerFichadaDelDia == false && $ent->format('G') > 6){
						$minutos=0;
						$horasTomadas = $ent->format('G') - $fecha['fichadaS'][$contSalida]->format('G');
						if($ent->format('i') > 0){
							$minutos += 0.5;
						}
						$contAsistencia += $horasTomadas;
						//$contSalida++;						
					}
					
					
					$primerFichadaDelDia = false;
					$ultimaSalida = $fecha['fichadaS'][$contSalida];
					$existeSalida = true;
				}
				
				
				//ANTES DE PASAR A LA SIGUIENTE FECHA ANALIZAMOS SI LA ULTIMA SALIDA ES < A 15HS				
				if($existeSalida == true && $ultimaSalida->format('G') < 15){
					
					$contAsistencia += 15 - $ultimaSalida->format('G');
				}
				
				//AJUSTAMOS LOS MINUTOS DE LA ULTIMA SALIDA
				if($existeSalida == true && $ultimaSalida->format('G') < 15 && $ultimaSalida->format('i') > 0){
					$contAsistencia -= 0.5;
				}
			}
		}
		return $contAsistencia;
	}
}



















 