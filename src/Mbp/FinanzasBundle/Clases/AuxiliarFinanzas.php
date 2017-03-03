<?php

namespace Mbp\FinanzasBundle\Clases;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mbp\FinanzasBundle\Entity\ParametrosFinanzas;

class AuxiliarFinanzas extends Controller
{
    /*
	 * CALCULA SALDO DE CUENTA CORRIENTE
	 * @data | array de datos
	 * @debe | string define el nombre que tiene la columna debe en el array
	 * @haber| string define el nombre que tiene la columna haber en el array
	 */
	public function calculaSaldo(&$data, $debe, $haber)
	{
		$saldo = array();
		$pagado = 0;
		$neto = 0;
		for ($i=0; $i < count($data); $i++) { 
			for ($j=0; $j <= $i; $j++) { 
				$pagado = $data[$j][$debe] + $pagado;
				$neto = $data[$j][$haber] + $neto;
			}
			$data[$i]['saldo'] = number_format($neto - $pagado, 2);
			$pagado = 0;
			$neto = 0;
		}
	}
	
    public function SubTotalFacturaAction($resulFacturas)
    {
    	$subTotal = array();
		$i=0;
		$aux = 0; 		
		
		if(empty($resulFacturas)){
			return;
		}
		
		foreach ($resulFacturas as $factura) {
			foreach ($factura->getfacturaDetalleId() as $detalleFc) {
				$subTotal[$i] = $detalleFc->getPrecio() * $detalleFc->getCantidad() + $aux;
				$aux = $subTotal[$i];
			}	
			$i++;
			$aux = 0;		
		}
		
		return $subTotal;
	}
	
	public function SubTotalCobranzaAction($resulPagos)
    {
    	$subTotalCobranza = array();
		$i=0;
		$aux = 0; 		
		
		if(empty($resulPagos)){
			return;
		}
		
		foreach ($resulPagos as $cobranzas) {
			foreach ($cobranzas->getCobranzaDetalleId() as $detalleCobranza) {
				$subTotalCobranza[$i] = $detalleCobranza->getImporte() + $aux;
				$aux = $subTotalCobranza[$i];
			}	
			$i++;
			$aux = 0;		
		}		
		return $subTotalCobranza;
	}	
	
	public function TipoDeComprobante($tipo)
	{
		switch ($tipo) {
			case '1':
				return "FA";
				break;
			case '2':
				return "ND A";
				break;
			case '3':
				return "NC A";
				break;
			case '6':
				return "FC B";
				break;
			case '7':
				return "ND B";
				break;
			case '8':
				return "NC B";				
				break;
			default:
				
				break;
		}
	}
}




















