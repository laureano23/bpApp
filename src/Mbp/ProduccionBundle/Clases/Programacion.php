<?php
namespace Mbp\ProduccionBundle\Clases;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Mbp\SenchaBundle\Librerias\DateTimeEnhanced;

class Programacion extends Controller {
	/*
	 * PARAMETROS DE LA CLASE
	 * @em
	 * @hsInicio	
	 * @hsFin		   
	*/
	protected $em;
	public $hsInicio;
	public $minutoInicio;
	public $segundoInicio;
	
	public $fechaInicio = array();
	public $fechaFin = array();
	
	public $hsFin;
	public $minutoFin;
	public $segundoFin;
	
	public $horaTotalOpe = array();
	public $minTotalOpe = array();
	public $segTotalOpe = array();
	
	public $cantOpe;
	public $cant;
	public $jornadaLab;
	public $intervaloJornada;
	public $fechaProg;
	public $minDesayuno;
	public $minAlmuerzo;
	public $cantJornadas;
	
	/*
	 * CONSTRCUTOR RETORNA LOS PARAMETROS DE LA DB QUE UTILIZARÁ LA CLASE 
	 */ 	
	public function __construct(EntityManager $entityManager)
	{
		$em = $this->em = $entityManager;
		
		$dql = 'SELECT param FROM MbpProduccionBundle:ProgParams param';
		$query = $em->createQuery($dql);
		$res = $query->getArrayResult();
				
		$this->hsInicio = $res[0]['hsInicio']->format('H');
		$this->minutoInicio = $res[0]['hsInicio']->format('i');
		$this->segundoInicio = $res[0]['hsInicio']->format('s');
		
		$this->hsFin = $res[0]['hsFin']->format('H');
		$this->minutoFin = $res[0]['hsFin']->format('i');
		$this->segundoFin = $res[0]['hsFin']->format('s');
		
		$diff = $res[0]['hsInicio']->diff($res[0]['hsFin']);
		$this->jornadaLab = $diff->format('%H');
		
		$this->intervaloJornada = 24 - $this->jornadaLab; 
		
		$this->minDesayuno = 15;
		$this->minAlmuerzo = 30;
	}
	
	 
	 /*
	 * RETORNA FECHA A PROGRAMAR Y CANT. DE OPEACIONES Y CANTIDAD DE PIEZAS A FABRICAR
	 * @info
	 */
	 public function paramsProg($info, $cant){
	 		 	
	 	$fechaProgramar = $info[1];	//FECHA A PROGRAMAR DEL CLIENTE
	 	$horaProgramar = $info[2]; //HORA A PROGRAMAR (SI EXISTE)
	 	
		$horaFormat;
	 	if($horaProgramar){
	 		$horaProg = explode('T', $horaProgramar);
			$horaFormat = \DateTime::CreateFromFormat('H:i:s', $horaProg[1]);
	 	}else{
	 		$horaFormat = new \DateTime();
			$horaFormat->setTime(0,0,0);
	 	}
	 	
		$fechaProg = explode('T', $fechaProgramar); //RETORNA FECHA TIPO 1970-01-01 00:45:00.000000
		
		$fechaFormat = new DateTimeEnhanced;				
		$fechaFormat = $fechaFormat->CreateFromFormat('Y-m-d', $fechaProg[0]); //CREA OBJETO FECHA
		
		//SI EL CLIENTE ENVIO UNA HORA ESPECIFICA A PROGRAMAR SE CARGA COMO PARAMETRO
		if($horaProgramar){
			$this->hsInicio = 0;
			$this->minutoInicio = 0;
			$this->segundoInicio = 0;
		}else{
			$this->hsInicio = 6;
			$this->minutoInicio = 0;
			$this->segundoInicio = 0;	
		}
		
		$fechaFormat->setTime($this->hsInicio + $horaFormat->format('H'), $this->minutoInicio + $horaFormat->format('i'), $this->segundoInicio + $horaFormat->format('s')); //SETEA EL TIEMPO SEGUN EL PARAMETRO DE INICIO DE JORNADA LABORAL
		
		$this->fechaProg = $fechaFormat;
		$this->cantOpe = count($info[0]);
		$this->cant = $cant;
		$this->trimHora($info);
	 }

	
	 /*
	 * FUNCION PARA CALCULAR LOS AJUSTES DE INTERVALOS ENTRE JORNADAS NECESARIOS
	 * @tiempoOpe 	| int en segundos
	 * @fechaInicio	| DateTime fecha en que comienza la OP. 
	 * return int 	| REPRESENTA HORAS DE INTERVALO
	 */
	public function intervalosAjuste($tiempoOpe, $fechaInicio)
	{
		$finJornada = \DateTime::CreateFromFormat('Y-m-d H:i:s', $fechaInicio->format('Y-m-d H:i:s'));
		$finJornada->setTime($this->hsFin, $this->minutoFin, $this->segundoFin);
		$primerJornada = $fechaInicio->diff($finJornada);
		$segundosPrimerJor = $primerJornada->format('%H') * 60 * 60 + $primerJornada->format('%i') * 60 + $primerJornada->format('%s');
		
		if($tiempoOpe > $segundosPrimerJor){
			//CUANTOS INTERVALOS DE JORNADA DEBO AJUSTAR
			$cantIntervaloJornada = 1 + ($tiempoOpe - $segundosPrimerJor) / ($this->jornadaLab * 60 * 60);
			$cantIntervaloJornada = floor($cantIntervaloJornada);
			$this->cantJornadas = $cantIntervaloJornada + 1;
			
			$intervaloTotal = $cantIntervaloJornada * $this->intervaloJornada;
			return $intervaloTotal;
		}else{
			return $intervaloTotal = 0;
		}		
	}
	
	
	/*
	 * FUNCION PARA CALCULAR LOS INTERVALOS DE REFRIGERIO NECESARIOS EN CADA OPERACION
	 * @tiempoOpe 	| int en segundos
	 * @fechaInicio	| DateTime fecha en que comienza la OP.
	 * @fechaFin 	| DateTime fecha en que termina con intervalo de ajuste la OP.  
	 * return int 	| REPRESENTA MINUTOS DE REFRIGERIO
	 */	
	public function intervalosRefrigerio($tiempoOpe, $fechaInicio, $fechaFin)
	{
		//ALMUERZO Y DESAYUNO AL INICIO
		$horaDesayunoI = \DateTime::CreateFromFormat('Y-m-d H:i:s', $fechaInicio->format('Y-m-d H:i:s'));
		$horaDesayunoI->setTime(7,30,0);		
		$horaAlmuerzoI = \DateTime::CreateFromFormat('Y-m-d H:i:s', $fechaInicio->format('Y-m-d H:i:s'));
		$horaAlmuerzoI->setTime(12,0,0);
		
		//ALMUERZO Y DESAYUNO AL FIN
		$horaDesayunoF = \DateTime::CreateFromFormat('Y-m-d H:i:s', $fechaFin->format('Y-m-d H:i:s'));
		$horaDesayunoF->setTime(7,30,0);		
		$horaAlmuerzoF = \DateTime::CreateFromFormat('Y-m-d H:i:s', $fechaFin->format('Y-m-d H:i:s'));
		$horaAlmuerzoF->setTime(12,0,0);
		
		$minRefrigerio;
		if($fechaFin->format('H') < $fechaInicio->format('H')){
		
			$minRefrigerio = $this->cantJornadas * $this->minDesayuno + $this->cantJornadas * $this->minAlmuerzo;
		}else{
			$minRefrigerio = 45;
		}
		
		if($fechaInicio > $horaDesayunoI && $minRefrigerio > 0){
			$minRefrigerio = $minRefrigerio - $this->minDesayuno;
		}
		if($fechaInicio > $horaAlmuerzoI && $minRefrigerio > 0){
			$minRefrigerio = $minRefrigerio - $this->minAlmuerzo;
		}
		
		if($fechaFin <= $horaDesayunoF && $minRefrigerio > 0){
			$minRefrigerio = $minRefrigerio - $this->minDesayuno;
		}
		if($fechaFin <= $horaAlmuerzoF && $minRefrigerio > 0){
			$minRefrigerio = $minRefrigerio - $this->minAlmuerzo;
		}
		$fechaFinAj = \DateTime::CreateFromFormat('Y-m-d H:i:s', $fechaFin->format('Y-m-d H:i:s'));
		$fechaFinAj->setTime($fechaFin->format('H'), $fechaFin->format('i') + $minRefrigerio, $fechaFin->format('s'));
		
		/*if($fechaFin < $horaAlmuerzoF && $fechaFinAj > $horaAlmuerzoF){			
			$minRefrigerio = $minRefrigerio + $this->minAlmuerzo;
		}*/
		return $minRefrigerio;	
		
	}

	/*
	 * AJUSTE POS REFRIGERIO
	 */ 
	public function ajustePosRefrigerio($fechaFinSinRef, $pos)
	{
		//ALMUERZO Y DESAYUNO AL INICIO
		$horaDesayunoI = \DateTime::CreateFromFormat('Y-m-d H:i:s', $fechaFinSinRef->format('Y-m-d H:i:s'));
		$horaDesayunoI->setTime(7,30,0);		
		$horaAlmuerzoI = \DateTime::CreateFromFormat('Y-m-d H:i:s', $fechaFinSinRef->format('Y-m-d H:i:s'));
		$horaAlmuerzoI->setTime(12,0,0);
		
		//print_r($this->fechaFin);	
		$minutos = 0;	
		if($fechaFinSinRef < $horaAlmuerzoI && $this->fechaFin[$pos] > $horaAlmuerzoI){		
			$minutos = $minutos + $this->minAlmuerzo;	
		}
		
		if($fechaFinSinRef < $horaDesayunoI && $this->fechaFin[$pos] > $horaDesayunoI){		
			$minutos = $minutos + $this->minDesayuno;	
		}
		
		$this->fechaFin[$pos]->setTime($this->fechaFin[$pos]->format('H'), $this->fechaFin[$pos]->format('i') + $minutos, $this->fechaFin[$pos]->format('s'));
	}
	
	public function trimHora($info)
	{
		$fechaFormat = $this->fechaProg;
		/*
		 * BUCLE PARA ALMACENAR TIEMPO DE CADA OPERACION ENVIADA POR EL CLIENTE
		 */
		$tiempoOpe = array();
		for($i=0; $i<$this->cantOpe; $i++){
			$tiempoOpe[$i] =  explode(' ', $info[0][$i]->tiempo->date); //RETORNA HORA TIPO 00:45:00.000000			
		}
		
		/*
		 * DESCOMPONEMOS EL TIEMPO PARA OPERAR CON ÉL
		 */
		$timeExploded;
		
		for($i=0; $i<$this->cantOpe; $i++){
			$timeExploded = explode(':', $tiempoOpe[$i][1]);
			//HORAS
			$this->horaTotalOpe[$i] = $timeExploded[0] * $this->cant;
			//MINUTOS
			$this->minTotalOpe[$i] = $timeExploded[1] * $this->cant;
			//SEGUNDOS
			$segFormat[$i] =  explode('.', $timeExploded[2]); //TRANSFORMA 15.0000 A 15, SACA EL PUNTO
			$this->segTotalOpe[$i] = $segFormat[$i][0] * $this->cant;
		}
		
		return array(
			'horas' => $this->horaTotalOpe,
			'minutos' => $this->minTotalOpe,
			'segundos' => $this->segTotalOpe
		);
	}
	
	/*
	 * AJUSTA LA FECHA DE FIN SI LA OPERACION DA COMO RESULTADO UN HORARIO FUERA DEL PARAMETRO DE FIN
	 */
	public function ajusteFechaFin($pos)
	{
		$finParametro = \DateTime::CreateFromFormat('Y-m-d H:i:s', $this->fechaFin[$pos]->format('Y-m-d H:i:s'));	
		$finParametro->setTime($this->hsFin, $this->minutoFin, $this->segundoFin);
		
		if($this->fechaFin[$pos] > $finParametro){
			return $this->fechaFin[$pos]->setTime($this->fechaFin[$pos]->format('H') + $finParametro->format('H'), $this->fechaFin[$pos]->format('i') + $finParametro->format('i'), $this->fechaFin[$pos]->format('s') + $finParametro->format('s'));
		}
	}
	 
	 /*
	  * CALCULA TIEMPO DE C/OPERACION * CANTIDAD A FABRICAR Y RETORNA FECHA INICIO Y FIN DE C/OPERACION EN UN ARRAY 
	  * @info
	  * @cantidad
	  */
	 public function tiempoOpe(){
	 	$fechaFormat = $this->fechaProg;
		
		/*
		 * SUMAMOS EL TIEMPO DE OPERACION + FECHA A PROGRAMAR = FECHA Y HORA FIN DE PROGRAMACION
		 * PARA COMENZAR UNA NUEVA OPERACION DEBE HABER TERMINADO LA ANTERIOR
		 */		 
		$fechaFinSinRef = array();	
		
		//VARIABLE AUXILIAR PARA NO MODIFICAR EL OBJETO ORIGINAL
		$inicioAux = \DateTime::CreateFromFormat('Y-m-d H:i:s', $fechaFormat->format('Y-m-d H:i:s'));	
					
		for($i=0; $i<$this->cantOpe; $i++){
			if($i==0){
				//LA PRIMER OPERACION COMIENZA POR DEFECTO EN LA FECHA A PROGRAMAR A LAS 6 AM.
				$this->fechaInicio[$i] = \DateTime::CreateFromFormat('Y-m-d H:i:s', $fechaFormat->format('Y-m-d H:i:s'));				
				//MOMENTO EN QUE TERMINA LA OP. SIN CONTEMPLAR LA JORNADA LABORAL
				$this->fechaFin[$i] = $inicioAux->setTime($this->horaTotalOpe[$i] + $fechaFormat->format('H'), $this->minTotalOpe[$i] + $fechaFormat->format('i'), $this->segTotalOpe[$i] + $fechaFormat->format('s'));
				$segundosOpe = $this->horaTotalOpe[$i] * 60 * 60 + $this->minTotalOpe[$i] * 60 + $this->segTotalOpe[$i];	
				
				//INTERVALOS DE JORNADAS LABORALES			
				$int = $this->intervalosAjuste($segundosOpe, $this->fechaInicio[$i]);							
				
				//FIN DE LA OPERACION SIN CONSIDERAR LOS REFRIGERIOS				
				$fechaFinSinRef = \DateTime::CreateFromFormat('Y-m-d H:i:s', $this->fechaFin[$i]->format('Y-m-d H:i:s'));
				$fechaFinSinRef->setTime($this->fechaFin[$i]->format('H') + $int, $this->fechaFin[$i]->format('i'), $this->fechaFin[$i]->format('s'));
				
				//AJUSTE SEGUN LOS MINUTOS DE REFRIGERIO
				$refrigerio = $this->intervalosRefrigerio($segundosOpe, $this->fechaInicio[$i], $fechaFinSinRef);				
				$this->fechaFin[$i]->setTime($fechaFinSinRef->format('H') + $int, $fechaFinSinRef->format('i') + $refrigerio, $fechaFinSinRef->format('s'));	
								
				//AJUSTE POS REFRIGERIO
				$this->ajustePosRefrigerio($fechaFinSinRef, $i);	
				
				//AJUSTE FECHA FIN
				$this->ajusteFechaFin($i);		
			}else{
				$auxFin = array();				
				$this->fechaInicio[$i] = \DateTime::CreateFromFormat('Y-m-d H:i:s', $this->fechaFin[$i-1]->format('Y-m-d H:i:s'));
				$segundosOpe = $this->horaTotalOpe[$i] * 60 * 60 + $this->minTotalOpe[$i] * 60 + $this->segTotalOpe[$i];		
				
				//INTERVALOS DE JORNADAS LABORALES						
				$int = $this->intervalosAjuste($segundosOpe, $this->fechaInicio[$i]);				
				$auxFin[$i] = \DateTime::CreateFromFormat('Y-m-d H:i:s', $this->fechaInicio[$i]->format('Y-m-d H:i:s'));
				
				
				//FIN DE LA OPERACION SIN CONSIDERAR LOS REFRIGERIOS
				$fechaFinSinRef = $auxFin[$i]->setTime($auxFin[$i]->format('H') + $this->horaTotalOpe[$i] + $int, $auxFin[$i]->format('i') + $this->minTotalOpe[$i], $auxFin[$i]->format('s') + $this->segTotalOpe[$i]);
				
				//AJUSTE SEGUN LOS MINUTOS DE REFRIGERIO				
				$refrigerio = $this->intervalosRefrigerio($segundosOpe, $this->fechaInicio[$i], $fechaFinSinRef);
				
				$this->fechaFin[$i] = \DateTime::CreateFromFormat('Y-m-d H:i:s', $fechaFinSinRef->format('Y-m-d H:i:s'));
				$this->fechaFin[$i]->setTime($fechaFinSinRef->format('H'), $fechaFinSinRef->format('i') + $refrigerio, $fechaFinSinRef->format('s'));
					
				//AJUSTE POS REFRIGERIO
				$this->ajustePosRefrigerio($fechaFinSinRef, $i);
				
				//AJUSTE FECHA FIN
				$this->ajusteFechaFin($i);
			}			
		}

		
		
		//GUARDA EL RETORNO
		$resp = array(
			'fechaInicio' => $this->fechaInicio,
			'fechaFin' => $this->fechaFin
		);
		
		return $resp;
	 }

	

	/*
	 * FUNCION QUE CONSULTA LOS PARAMETROS DE ENTRADA Y LAS PROGRAMACIONES PENDIENTES PARA
	 * CORREGIR LA FECHA DE INICIO Y FIN DE CADA OPERACION
	 */
	 public function parametrizarProg()
	 {
	 	
	 }
} 