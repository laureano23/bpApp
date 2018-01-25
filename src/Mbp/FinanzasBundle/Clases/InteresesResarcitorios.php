<?php

namespace Mbp\FinanzasBundle\Clases;

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
		
		print_r($this->facturas);
		print_r($this->pagos);
		
		//TOMAMOS LA PRIMER FACTURA Y LA CANCELAMOS CON EL PAGO
		foreach($this->facturas as &$f){						
			
			foreach ($this->pagos as &$p) {
				if($p['monto'] >= $f['monto']){//el pago me alcanza para cubrir la factura
					$this->calculoInteres($f['monto'], $f['vencimiento'], $p['vencimiento']);
					$p['monto'] = $p['monto'] - $f['monto'];
					break;
				} 
				
				
				if($p['monto'] < $f['monto']){ //no alcanza el pago para la fc
					$this->calculoInteres($p['monto'], $f['vencimiento'], $p['vencimiento']);
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
	private function calculoInteres($importe, \DateTime $vencimientoFc, \DateTime $diferidoValor){
		if($diferidoValor <= $vencimientoFc) return; //el pago se esta realizando en termino no hay interes
		
		//obtenemos los dias de retraso
		$dias = $vencimientoFc->diff($diferidoValor);
		$dias = $dias->days;
		
		$interes = $importe * self::$tasaDiaria * $dias / 100;
		
		array_push($this->intereses, $interes); 
	}
	
	private function ordenarFechas(){
		$pagos = $this->pagos;
		usort($this->facturas, array($this, 'ordenar'));
		usort($this->pagos, array($this, 'ordenar'));
		//print_r($pagos);
	}
	
	private function ordenar($a, $b){
		return strtotime($a['vencimiento']->format('d-m-Y')) - strtotime($b['vencimiento']->format('d-m-Y'));
	}
}
