<?php

namespace Mbp\FinanzasBundle\Clases;

/*Clase que calcula los intereses por pago fuera de término
 * 
 * devuelve un array con la siguiente estructura
 * 
 * [0] => Array
 * 	(
 * 		[interes] => 0.32
 * 		[comprobante] => 2
 * 		[monto] => 25
 * 	)
 * 
 * */
class InteresesResarcitorios
{
	
	public $facturas, $pagos;
	public static $tasaDiaria = 0.08;
	
	//cargamos en un array para probar los intereses calculados
	public $intereses=array();
			 
	public function __construct(){
		
	}
	
	public function calcularIntereses(array $facturas, array $pagos){
		//PRIMERO ORDENAMOS LAS FCS Y PAGOS POR FECHA DE VENCIMIENTO
		$this->facturas = $facturas;
		$this->pagos = $pagos;
		$this->ordenarFechas();
		
		
		//TOMAMOS LA PRIMER FACTURA Y LA CANCELAMOS CON EL PAGO
		foreach($this->facturas as &$f){						
			
			foreach ($this->pagos as &$p) {
				if($p['monto'] >= $f['monto']){//el pago me alcanza para cubrir la factura
					$this->calculoInteres($f['monto'], $f, $p);
					$p['monto'] = $p['monto'] - $f['monto'];
					break;
				} 
				
				
				if($p['monto'] < $f['monto']){ //no alcanza el pago para la fc
					$this->calculoInteres($p['monto'], $f, $p);
					$f['monto'] -= $p['monto'];
					$p['monto']=0;
				}
			}
			
		}

	}

	public function getIntereses(){
		echo "*********INTERESES**********";
		print_r($this->intereses);
	}
	
	//FORMULAR PARA CALCULAR EL INTERES DIARIO
	private function calculoInteres($importe, $fc, $valor){
		if($valor['vencimiento'] <= $fc['vencimiento']) return; //el pago se esta realizando en termino no hay interes
		
		//obtenemos los dias de retraso
		$dias = $fc['vencimiento']->diff($valor['vencimiento']);
		$dias = $dias->days;
				
		$reg['interes'] = $importe * self::$tasaDiaria * $dias / 100;
		$reg['comprobante'] = $fc['cbteNum'];
		$reg['monto'] = $importe;
		
		array_push($this->intereses, $reg); 
	}
	
	private function ordenarFechas(){
		$pagos = $this->pagos;
		usort($this->facturas, array($this, 'ordenar'));
		usort($this->pagos, array($this, 'ordenar'));
	}
	
	private function ordenar($a, $b){
		return strtotime($a['vencimiento']->format('d-m-Y')) - strtotime($b['vencimiento']->format('d-m-Y'));
	}
}
