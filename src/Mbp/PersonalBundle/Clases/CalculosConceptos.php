<?php
namespace Mbp\PersonalBundle\Clases;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;
use Mbp\PersonalBundle\Entity\Recibos;
use Mbp\PersonalBundle\Entity\RecibosDetalle;

class CalculosConceptos extends Controller 
{
	public static $mes = 30;
	public static $horas_mensualizados = 180;
	public $em;
	public $codigoCalculo;
	public $cant;
	public $empleadoSueldo;
	public $compensatorio;
	public $valorComp=0;
	public $idP;
		 
	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}
	
	public function initParams($idP, $codigoCalculo, $cant, $empleadoSueldo, $compensatorio)
	{
		$repo = $this->em->getRepository('MbpPersonalBundle:Personal');
		
				
		if($empleadoSueldo == 0){
			//SI EL IMPORTE DEL CONCEPTO ES VARIABLE DEBO BUSCARLE EN LA CATEGORIA DEL EMPLEADO
			$query = $repo->createQueryBuilder('p')
			->select('p.idP, p.compensatorio, c.salario')
			->join('p.categoria', 'c')
			->where('p.idP = :idPersonal')
			->setParameter('idPersonal', $idP)
			->getQuery();
			$res = $query->getResult();	
			$this->empleadoSueldo = $res[0]['salario'];
			$this->valorComp = $res[0]['compensatorio'];
		}else{
			$this->empleadoSueldo = $empleadoSueldo;
		}
		$this->idP = $idP;
		$this->codigoCalculo = $codigoCalculo;
		$this->cant = $cant;
		$this->compensatorio = $compensatorio;	
		
		return $this->empleadoSueldo;	
	}
	
	public function init()
	{		
		switch ($this->codigoCalculo) {
			case '1':
				return $this->formula1($this->cant, $this->empleadoSueldo, $this->compensatorio, $this->valorComp);
				break;
			
			case '2':
				return $this->formula2($this->cant, $this->empleadoSueldo, $this->compensatorio, $this->valorComp);
				break;
				
			case '3':
				return $this->formula3($this->empleadoSueldo);
				break;
				
			case '4':
				return $this->formula4($this->cant, $this->empleadoSueldo, $this->compensatorio, $this->valorComp);
				break;
				
			case '5':
				return $this->formula5($this->cant, $this->empleadoSueldo, $this->compensatorio, $this->valorComp);
				break;
			
			case '6':
				return $this->formula6($this->cant, $this->empleadoSueldo, $this->compensatorio, $this->valorComp);
				break;
			
			case '7':
				return $this->formula7($this->cant, $this->empleadoSueldo, $this->compensatorio, $this->valorComp);
				break;

			case '8':
				return $this->formula8($this->cant, $this->empleadoSueldo, $this->compensatorio, $this->valorComp);
				break;
				
			default:
				
				break;
		}	
	}
	
	/*
	 * CODIGO CALCULO: 1
	 * DESCRIPCION: Cantidad de horas por Valor de hora
	 * @cant		| float
	 * @$valorHs	| float
	 */
	private function formula1($cant, $valorHs, $compensatorio, $valorComp)
	{
		if($compensatorio == "true"){
			$valorHs = $valorComp;
		}
		$res = $cant * $valorHs;
		return $res;
	}
	
	/*
	 * CODIGO CALCULO: 2
	 * DESCRIPCION: Cantidad de horas por Valor de hora por 1.5
	 * @cant		| float
	 * @$valorHs	| float
	 */
	private function formula2($cant, $valorHs, $compensatorio, $valorComp)
	{
		if($compensatorio == "true"){
			$valorHs = $valorHs + $valorComp;
		}
		$res = $cant * $valorHs * 1.5;
		return $res;
	}
	
	/*
	 * CODIGO CALCULO: 3
	 * DESCRIPCION: valor fijo del codigo
	 * @$valorFijo	| float
	 */
	private function formula3($valorFijo)
	{
		return $valorFijo;
	}
	
	/*
	 * CODIGO CALCULO: 4
	 * DESCRIPCION: mensualizado, valor de categoria
	 * @$valorMes	| float
	 */
	private function formula4($cant, $valorMes, $compensatorio, $valorComp)
	{
		$res;
		if($compensatorio == "true"){
			$res = $cant * $valorComp / 30;
		}else{
			$res = $cant * $valorMes / 30;
		}		
		return $res;
	}
	
	/*
	 * CODIGO CALCULO: 5
	 * DESCRIPCION: horas extras para personal mensualizado
	 * @$valorMes	| float
	 */
	private function formula5($cant, $valorMes, $compensatorio, $valorComp)
	{
		$res;
		if($compensatorio == true){
			$res = $cant * ($valorComp + $valorMes) / CalculosConceptos::$horas_mensualizados * 1.5;
		}else{
			$res = $cant * $valorMes / CalculosConceptos::$horas_mensualizados * 1.5;	
		}		
		return $res;
	}
	
	/*
	 * CODIGO CALCULO: 6
	 * DESCRIPCION: devuelve el importe calculado para el aguinaldo
	 * @$valorMes	| float
	 */
	private function formula6($cant, $valorMes, $compensatorio, $valorComp)
	{
		$res;
		if($compensatorio == true){
			$res = $cant * ($valorComp + $valorMes) / CalculosConceptos::$horas_mensualizados * 1.5;
		}else{
			$res = $cant * $valorMes / CalculosConceptos::$horas_mensualizados * 1.5;	
		}		
		return $res;
	}
	
	/*
	 * CODIGO CALCULO: 7
	 * DESCRIPCION: Cantidad de horas por Valor de hora por 2
	 * @cant		| float
	 * @$valorHs	| float
	 */
	private function formula7($cant, $valorHs, $compensatorio, $valorComp)
	{
		if($compensatorio == "true"){
			$valorHs = $valorHs + $valorComp;
		}
		$res = $cant * $valorHs * 2;
		return $res;
	}

	/*
	 * CODIGO CALCULO: 8
	 * DESCRIPCION: Vacaciones mensualizados
	 * @cant		| float
	 * @$valorHs	| float
	 */
	private function formula8($cant, $valorMes, $compensatorio, $valorComp)
	{
		$res;
		if($compensatorio == "true"){
			$res = $cant * $valorComp / 25;
		}else{
			$res = $cant * $valorMes / 25;
		}		
		return $res;
	}
	
	/*
	 * DEFINE LA DESCRIPCION DEL CONCEPTO LIQUIDADO SEGUN UN PERIODO DADO
	 * @$periodo	| integer
	 */
	private function descripcionPeriodo($periodo)
	{
		switch ($periodo) {
			case 1:
				return '1° quincena';
				break;
			
			case 2:
				return '2° quincena';
				break;
			case 3:
				return 'Mes';
				break;
				
			case 4:
				return 'Vacaciones';
				break;
			case 5:
				return 'SAC';
				break;
			case 6:
				return 'Otros';
				break;
			case 7:
				return 'Premios 1° quinc.';
				break;
			case 8:
				return 'Premios 2° quinc.';
				break;
			default:
				
				break;
		}
	}
	
	
	
	/*
	 * CALCULA TOTAL DE REMUNERATIVOS
	 */
	public function totalRemunerativos($idP, $periodo, $mes, $anio, $comp)
	{
		$repo = $this->em->getRepository('MbpPersonalBundle:Recibos');
		$query = $repo->createQueryBuilder('g')
		->select('sum(d.remunerativo)')
		->join('g.reciboDetalleId', 'd')
		->join('g.personal', 'p')
		->join('d.codigoSueldos', 'c')
		->where('p.idP = :idPersonal')
		->andWhere('g.periodo = :periodo')
		->andWhere('g.mes = :mes')
		->andWhere('g.anio = :anio')
		->andWhere('g.compensatorio = :compensatorio')
		->setParameter('idPersonal', $idP)
		->setParameter('periodo', $periodo)
		->setParameter('mes', $mes)
		->setParameter('anio', $anio)
		->setParameter('compensatorio', $comp)
		->getQuery();
		$res = $query->getResult();		
		
		return $res[0][1];				
	}
	
	
	/*
	 * RECIBE EL AÑO Y SEMESTRE Y DEVUELVE EL IMPORTE A LIQUIDAR EN CONCEPTO DE AGUINALDO POR CADA EMPLEADO EN BLANCO
	 * */
	public function resumenAguinaldoBlanco($año, $mes, $idP)
	{
		$em = $this->em;
		$repo = $em->getRepository('MbpPersonalBundle:Recibos');
		
		$mesDesde = 0;
		$mesHasta = 0;
			
		if(1 <= $mes && $mes <= 6){
			$mesDesde = 1;
			$mesHasta = 6;	
		}else{
			$mesDesde = 7;
			$mesHasta = 12;
		}
		
		$qb = $repo->createQueryBuilder('r')
			->select('personal.nombre, (SUM(detalle.remunerativo)) / 2 as remunerativo, r.mes')
			->join('r.reciboDetalleId', 'detalle')
			->join('r.personal', 'personal')
			->andWhere('r.mes >= :mesDesde')
			->andWhere('r.mes <= :mesHasta')
			->andWhere('r.compensatorio = 0')
			->andWhere('personal.idP = :idP')
			->andWhere('r.anio = :anio')
			->andWhere('r.periodo != 7') //EL CALCULO NO INCLUYE LOS CONCEPTO DE PREMIOS
			->andWhere('r.periodo != 5') //EL CALCULO NO INCLUYE LOS CONCEPTO DE SAC
			->setParameter('mesDesde', $mesDesde)
			->setParameter('mesHasta', $mesHasta)
			->setParameter('anio', $año)
			->setParameter('idP', $idP)
			->groupBy('personal.idP, r.mes')
			->getQuery()
			->getResult();
		
		$rem=0;
		$res;		
		
		foreach ($qb as $reg) {
			if($reg['remunerativo'] > $rem){
				$res = $reg;
				$rem = $reg['remunerativo'];				
			}			
		}
		return $res['remunerativo'];
	}

	/*
	 * RECIBE EL AÑO Y SEMESTRE Y DEVUELVE EL IMPORTE A LIQUIDAR EN CONCEPTO DE AGUINALDO POR CADA EMPLEADO EN NEGRO
	 * */
	public function resumenAguinaldoAmarillo($año, $mes, $idP)
	{
		$em = $this->em;
		$repo = $em->getRepository('MbpPersonalBundle:Recibos');
		
		$mesDesde = 0;
		$mesHasta = 0;
			
		if(1 <= $mes && $mes <= 6){
			$mesDesde = 1;
			$mesHasta = 6;	
		}else{
			$mesDesde = 7;
			$mesHasta = 12;
		}
		
		$qb = $repo->createQueryBuilder('r')
			->select('personal.nombre, (SUM(detalle.remunerativo)) / 2 as remunerativo, r.mes')
			->join('r.reciboDetalleId', 'detalle')
			->join('r.personal', 'personal')
			->andWhere('r.mes >= :mesDesde')
			->andWhere('r.mes <= :mesHasta')
			->andWhere('r.compensatorio = 1')
			->andWhere('personal.idP = :idP')
			->andWhere('r.anio = :anio')
			->andWhere('r.periodo != 7') //EL CALCULO NO INCLUYE LOS CONCEPTO DE PREMIOS
			->setParameter('mesDesde', $mesDesde)
			->setParameter('mesHasta', $mesHasta)
			->setParameter('anio', $año)
			->setParameter('idP', $idP)
			->groupBy('personal.idP, r.mes')
			->getQuery()
			->getResult();
		
		$rem=0;
		$res=0;		
		
		foreach ($qb as $reg) {
			if($reg['remunerativo'] > $rem){
				$res = $reg;
				$rem = $reg['remunerativo'];				
			}			
		}
		return $res['remunerativo'];
	}
}



















 