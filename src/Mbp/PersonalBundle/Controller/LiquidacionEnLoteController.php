<?php

namespace Mbp\PersonalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Mbp\PersonalBundle\Clases\LiquidacionEnLote\LiquidacionEnLote;
use Mbp\PersonalBundle\Controller\RecibosController as RecibosController;
use Mbp\PersonalBundle\Entity\Recibos;
use Mbp\PersonalBundle\Entity\RecibosDetalle;

/*
 * CLASE PARA LIQUIDAR SUELDOS EN LOTE, A PARTIR DE LOS DATOS QUE ARROJA EL SISTEMA HORARIO GESVR
 * DESDE UNA PLANILLA EXCEL
 * */
class LiquidacionEnLoteController extends RecibosController
{
	private $objLote;
	private $remunerativo=0;
	private $tipoLiquidacion;
	public static $hsNormalesDiarias = 9;
	public static $coeficienteCalorias = 0.04;
	
		
	/**
     * @Route("/LiquidarEnLote", name="mbp_personal_LiquidarEnLote", options={"expose"=true})
     */    
	public function LiquidarEnLote()
	{
		//DATOS DEL REQUEST
		$request = $this->getRequest();	
		$file = $request->files->get('archivoLote');
		//DATOS DEL REQUEST
		$request = $this->getRequest();
		$this->periodo = $request->request->get('pagoTipo');
		$this->mesNum = $request->request->get('mes');
		$this->anio = $request->request->get('anio');
		$this->compensatorio = 0;
		$this->fechaPago = \DateTime::createFromFormat('d/m/Y', $request->request->get('fechaPago'));
		$this->banco = $request->request->get('banco');
		$this->descripcion = $request->request->get('descripcion');
		$this->tipoLiquidacion = $request->request->get('tipoLiquidacion');
		$this->compensatorio = $request->request->get('compensatorio') == "on" ? 1 : 0;
		
		
		$response = new Response;
		$em = $this->getDoctrine()->getManager(); 
		
			
		$basePath = realpath($this->getParameter('directorio_liquidaciones'));
		$fileTarget = $basePath.'/fichadas.xlsx';
		$phpExcelService = $this->get('phpexcel');
		
		try{
			if(file_exists($fileTarget)){
				unlink($fileTarget);
			}
			
			$file->move(
				$basePath,
				'fichadas.xlsx'
			);					
			
						
			$liquidacion = new LiquidacionEnLote($fileTarget, $phpExcelService, $this->periodo, $this->mesNum, $this->anio, $em);			
			
			$empleados = $liquidacion->getEmpleadosCollection();
						
			//ERRORES EN LA COLECCION DE EMPLEADOS
			$errores = $liquidacion->getEmpleadosCollection()->getError();
			//ERRORES DEL LOTE DE LIQUIDACION
			$erroresLote = $liquidacion->getErrores();
						
			if(!empty($errores) || !empty($erroresLote)){
				$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
				$errColec = $liquidacion->getEmpleadosCollection()->getError();
				return $response->setContent(
					json_encode(
						array(
							'success' => false,
							'tipo' => 'validacion',
							'msg' => array(
								'errorColeccion' => $errColec,
								'errorLote' => $erroresLote							
							)
						)
					)
				);			
			}
			
			$this->setObjLote($liquidacion);
			
			if($this->periodo == 7 || $this->periodo == 8){
				$this->LiquidarPremiosLote();
				$empleado = $empleados->getEmpleado();
			}else{
				$this->CrearRecibosLote();
			}
			
			
			$resp = new Response;
			return $resp->setContent(
				json_encode(array(
					'success' => true
				)));
			
		}catch(\Exception $e){
			throw $e;
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			return $response->setContent(
				json_encode(
					array(
						'success' => false,
						'msg' => $e->getMessage()
					)
				)
			);
		}
	}
 
	private function CrearRecibosLote()
	{
		$em = $this->getDoctrine()->getManager();
		$repoEmpleados = $em->getRepository('MbpPersonalBundle:Personal');
		$repoBancos = $em->getRepository('MbpFinanzasBundle:Bancos');
		$repoRecibos = $em->getRepository('MbpPersonalBundle:Recibos');
		
		//VALIDAMOS SI EN EL PERIODO YA EXISTE ALGUNA LIQUIDACION
		$this->ValidarPeriodoLiquidado();
		
		
		//BUSCAMOS LOS EMPLEADOS QUE ADHERIDOS A LIQUIDACION POR LOTE
		$empleados = $repoEmpleados->findBy(
			array('liquidaPorLote' => true, 'periodo' => $this->tipoLiquidacion)
		);
		$banco = $repoBancos->find($this->banco);
		
		
		if(empty($empleados)){
			throw new \Exception("No hay empleados para liquidar por lote", 1);				
		}
				
		
		foreach ($empleados as $empleado) {
			$this->idP = $empleado->getIdP();
			$antiguedad = $empleado->getFechaIngreso()->diff(new \DateTime("now"));
			$this->antiguedadAnios = $antiguedad->format('%Y');
			$recibo = new Recibos;
			
			$recibo->setCompensatorio($this->compensatorio);
			$recibo->setFechaPago($this->fechaPago);
			$recibo->setBanco($banco);
			$recibo->setPeriodo($this->periodo);
			$recibo->setMes($this->mesNum);
			$recibo->setAnio($this->anio);
			$recibo->setTipoPago($this->descripcion);
			$recibo->setBasicoHist($empleado->getCategoria()->getSalario());
			$recibo->setCategoriaHist($empleado->getCategoria()->getCategoria());
			$recibo->setSindicatoHist($empleado->getCategoria()->getIdSindicato()->getSindicato());
			$recibo->setTarea($empleado->getTarea());			
			$recibo->setAntiguedad($antiguedad->format("%Y"));
			$localidad = $empleado->getLocalidad()->getNombre();
			$prov = $empleado->getLocalidad()->getDepartamentoId()->getProvinciaId()->getNombre();
			$recibo->setDomicilio($empleado->getDireccion()." ".$localidad." ".$prov);
			$recibo->setEcivil($empleado->getEstado());
			$recibo->setObraSocial($empleado->getObraSocial());
			$recibo->addPersonal($empleado);
									
			$detalleVariable = $this->liquidarConceptosVariables($empleado, $recibo);
			
			$datosFijos = $this->insertaDatosFijos();
			
			//DATOS FIJOS
			foreach ($datosFijos as $detalleFijo) {
				$recibo->addReciboDetalleId($detalleFijo);
				$this->remunerativo += $detalleFijo->getRemunerativo();
			}
		
			
			//LIQUIDAR NOVEDADES DEL LOTE
			$this->LiquidarNovedadesLote($empleado, $recibo);
			
			
						
			//HS EXTRAS SE LIQUIDAN EN NEGRO
			if($this->compensatorio == TRUE){
				$detalleExtras = $this->LiquidarHsExtras($empleado, $recibo);
			}
			
			//ANTIGUEDAD
			$detalleAntiguedad = $this->calculaAntiguedad($this->remunerativo);
			if($detalleAntiguedad != null){
				$recibo->addReciboDetalleId($detalleAntiguedad);
				$this->remunerativo += $detalleAntiguedad->getRemunerativo();
			}
						
			
			//DESCUENTOS SI EL RECIBO ES EN BLANCO
			if($this->compensatorio != TRUE){
				$descuentosDetalle = $this->liquidarDescuentos($this->remunerativo);
				if(is_array($descuentosDetalle)){
					foreach ($descuentosDetalle as $descuento) {
						$recibo->addReciboDetalleId($descuento);	
					}		
				}				
			}
			
			$em->persist($recibo);
			$em->flush();
			
			$this->remunerativo = 0; //REINICIAMOS LA VARIABLE DE ACUMULACION DE REMUNERATIVOS
		}				
	}

	public function LiquidarPremiosLote()
	{		
		$this->ValidarPeriodoLiquidado();
		$em = $this->getDoctrine()->getManager();
		$repoEmpleados = $em->getRepository('MbpPersonalBundle:Personal');
		$repoBancos = $em->getRepository('MbpFinanzasBundle:Bancos');
		
		//BUSCAMOS LOS EMPLEADOS QUE ADHERIDOS A LIQUIDACION POR LOTE
		$empleados = $repoEmpleados->findBy(
			array('liquidaPorLote' => true, 'periodo' => $this->tipoLiquidacion, 'liquidaPremio' => true)
		);
		$banco = $repoBancos->find($this->banco);
		
				
		if(empty($empleados)){
			throw new \Exception("No hay empleados para liquidar por lote", 1);				
		}
		
		foreach ($empleados as $empleado) {
			$this->idP = $empleado->getIdP();
			$antiguedad = $empleado->getFechaIngreso()->diff(new \DateTime("now"));
			$this->antiguedadAnios = $antiguedad->format('%Y');
			$recibo = new Recibos;
			
			$recibo->setCompensatorio($this->compensatorio);
			$recibo->setFechaPago($this->fechaPago);
			$recibo->setBanco($banco);
			$recibo->setPeriodo($this->periodo);
			$recibo->setMes($this->mesNum);
			$recibo->setAnio($this->anio);
			$recibo->setTipoPago($this->descripcion);
			$recibo->setBasicoHist($empleado->getCategoria()->getSalario());
			$recibo->setCategoriaHist($empleado->getCategoria()->getCategoria());
			$recibo->setSindicatoHist($empleado->getCategoria()->getIdSindicato()->getSindicato());
			$recibo->setTarea($empleado->getTarea());			
			$recibo->setAntiguedad($antiguedad->format("%Y"));
			$localidad = $empleado->getLocalidad()->getNombre();
			$prov = $empleado->getLocalidad()->getDepartamentoId()->getProvinciaId()->getNombre();
			$recibo->setDomicilio($empleado->getDireccion()." ".$localidad." ".$prov);
			$recibo->setEcivil($empleado->getEstado());
			$recibo->setObraSocial($empleado->getObraSocial());
			$recibo->addPersonal($empleado);
						
			$this->liquidarPuntualidad($empleado, $recibo);
			$this->liquidarAsistencia($empleado, $recibo);
			
			//if(empty($puntualidad) && empty($asistencia)) return;
			
			$datosFijos = $this->insertaDatosFijos();
			
			$em->persist($recibo);
			$em->flush();
			
			$this->remunerativo = 0; //REINICIAMOS LA VARIABLE DE ACUMULACION DE REMUNERATIVOS
		}				
	}

	private function liquidarPuntualidad($empleado, $recibo)
	{
		$detalleRecibo = new Recibosdetalle;
		$lote = $this->getObjLote();
		$planillaEmpleado;
		$em = $this->getDoctrine()->getManager();
		$repoConceptos = $em->getRepository('MbpPersonalBundle:CodigoSueldos');
		
		foreach ($lote->getEmpleadosCollection()->getEmpleado() as $emp) {
			if($emp->getLegajo() == $empleado->getLegajo()){
				$planillaEmpleado = $emp;
			}
		}
		
		if (empty($planillaEmpleado)) return false;
		
		$conceptoPunt = $repoConceptos->findOneBy(array('descripcion' => 'PREMIO PUNTUALIDAD HORAS'));
		$hsPuntualidad = $planillaEmpleado->getHsPuntualidad();
		
		
		if($hsPuntualidad == 0) return false;
		
		$importe = ($empleado->getCategoria()->getSalario() + $empleado->getCompensatorio()) * 1.5;
		
				
		$detalleRecibo->setCantConceptoVar($hsPuntualidad);
		$detalleRecibo->setValorConceptoHist($importe);
		$detalleRecibo->setRemunerativo($importe * $hsPuntualidad);
		$detalleRecibo->setValorCompensatorioHist($empleado->getCompensatorio());
		$detalleRecibo->addCodigoSueldo($conceptoPunt);
		
		
		$recibo->addReciboDetalleId($detalleRecibo);
	}

	private function liquidarAsistencia($empleado, $recibo)
	{
		$detalleRecibo = new Recibosdetalle;
		$lote = $this->getObjLote();
		$planillaEmpleado;
		$em = $this->getDoctrine()->getManager();
		$repoConceptos = $em->getRepository('MbpPersonalBundle:CodigoSueldos');
		
		foreach ($lote->getEmpleadosCollection()->getEmpleado() as $emp) {
			if($emp->getLegajo() == $empleado->getLegajo()){
				$planillaEmpleado = $emp;
			}
		}
				
		if (empty($planillaEmpleado)) return false;
		
		$conceptoAsis = $repoConceptos->findOneBy(array('descripcion' => 'PREMIO ASISTENCIA HORAS'));
		$hsAsistencia = $planillaEmpleado->getHsAsistencia();
		
		if($hsAsistencia == 0) return false;
		
		$importe = ($empleado->getCategoria()->getSalario() + $empleado->getCompensatorio()) * 1.5;
		
				
		$detalleRecibo->setCantConceptoVar($hsAsistencia);
		$detalleRecibo->setValorConceptoHist($importe);
		$detalleRecibo->setRemunerativo($importe * $hsAsistencia);
		$detalleRecibo->setValorCompensatorioHist($empleado->getCompensatorio());
		$detalleRecibo->addCodigoSueldo($conceptoAsis);
		
		
		$recibo->addReciboDetalleId($detalleRecibo);
	}

	private function ValidarPeriodoLiquidado()
	{
		$em = $this->getDoctrine()->getManager();
		$repoEmpleados = $em->getRepository('MbpPersonalBundle:Personal');
		$repoBancos = $em->getRepository('MbpFinanzasBundle:Bancos');
		$repoRecibos = $em->getRepository('MbpPersonalBundle:Recibos');
		
		//VALIDAMOS SI EN EL PERIODO YA EXISTE ALGUNA LIQUIDACION
		$liquidacion = $repoRecibos->findBy(
			array('periodo' => $this->periodo, 'mes' => $this->mesNum, 'anio' => $this->anio, 'compensatorio' => $this->compensatorio)
		);
		
		if(!empty($liquidacion)){
			throw new \Exception("Ya existe una liquidación en este periodo, debe borrarla para poder liquidar en lote", 1);			
		}
	}

	
	private function liquidarConceptosVariables($empleado, $recibo=null) 
	{
		$em = $this->getDoctrine()->getManager();
		$repoConceptos = $em->getRepository('MbpPersonalBundle:CodigoSueldos');
		
		
		foreach ($this->objLote->getEmpleadosCollection()->getEmpleado() as $objEmpleado) {				
			if($objEmpleado->getLegajo() == $empleado->getLegajo()){
				$detalleRecibo = new RecibosDetalle;
				if($this->periodo == 1 || $this->periodo == 2){
					$concepto = $repoConceptos->findByDescripcion("HORAS NORMALES");
					if(empty($concepto)){
						throw new \Exception("No existe el concepto de horas normales", 1);						
					}
					
					$hsNormalesTrabajadas = $objEmpleado->getHsNormalesTrabajadas();
					if($hsNormalesTrabajadas == 0) continue;	//PUEDE SUCEDER QUE EL EMPLEADO NO TENGA HORAS NORMALES TRABAJADAS					
					$sueldoBlanco = $empleado->getSueldoBlanco();					
					$detalleRecibo->setCantConceptoVar($hsNormalesTrabajadas);
					$detalleRecibo->setValorConceptoHist($sueldoBlanco);
					
					//SI ESTAMOS LIQUIDANDO EN NEGRO TOMAMOS EL VALOR HORA COMPENSATORIO
					$res = $this->DetalleReciboStrategy($empleado, $detalleRecibo, $objEmpleado);
					
					if($res != false){
						$detalleRecibo->setValorCompensatorioHist($empleado->getCompensatorio());
						$detalleRecibo->addCodigoSueldo($concepto[0]);						
						$recibo->addReciboDetalleId($detalleRecibo);
						
					}
					
					//SI EL EMPLEADO TIENE REGIMEN DE CALORIAS
					if($empleado->getLiquidaCalorias() == true){
						$this->LiquidarCalorias($empleado, $recibo, $objEmpleado);
					}
				}else{
					
				}
			}
		}
	}

	private function LiquidarCalorias($empleado, $recibo, $objEmpleado)
	{
		$em = $this->getDoctrine()->getManager();
		$repoConceptos = $em->getRepository('MbpPersonalBundle:CodigoSueldos');
		$hsNormalesTrabajadas = $objEmpleado->getHsNormalesTrabajadas();
		
		$calorias;
		if($this->compensatorio != true){
			$calorias = $repoConceptos->findOneByDescripcion('CALORIAS HORAS NORMALES');	
		}else{
			$calorias = $repoConceptos->findOneByDescripcion('CALORIAS HS. EXTRAS 50%');
		}
		
		
		if(empty($calorias)){
			throw new \Exception("No existe el concepto CALORIAS", 1);			
		}
		
		$detalleRecibo = new RecibosDetalle;		
		$detalleRecibo->setValorConceptoHist($empleado->getSueldoBlanco());
		$detalleRecibo->setValorCompensatorioHist($empleado->getCompensatorio());
		$detalleRecibo->addCodigoSueldo($calorias);
				
		$hsNormalesTrabajadas = $objEmpleado->getHsNormalesTrabajadas();
		
		if($this->compensatorio == true){
			$sueldoComp = $empleado->getCompensatorio();
			$sueldoBlanco = $empleado->getSueldoBlanco();
			$hsExtrasTrabajadas = $objEmpleado->getHsExtrasTrabajadas();
			$detalleRecibo->setCantConceptoVar($hsExtrasTrabajadas * self::$coeficienteCalorias);
			$importeCalorias = $hsExtrasTrabajadas * ($sueldoComp + $sueldoBlanco) * self::$coeficienteCalorias * 1.5;			
			$detalleRecibo->setRemunerativo($importeCalorias);
			$this->remunerativo += $importeCalorias;
		}else{
			$sueldoBlanco = $empleado->getSueldoBlanco();
			$detalleRecibo->setCantConceptoVar($hsNormalesTrabajadas * self::$coeficienteCalorias);
			$detalleRecibo->setRemunerativo($hsNormalesTrabajadas * $sueldoBlanco * self::$coeficienteCalorias);
			$this->remunerativo += $hsNormalesTrabajadas * $sueldoBlanco * self::$coeficienteCalorias;	
		}
		
		$recibo->addReciboDetalleId($detalleRecibo);
	}
	
	//DETERMINA SEGUN EL ENTORNO DE LIQUIDACION QUE VALOR HS APLICARÁ
	private function DetalleReciboStrategy($empleado, $detalleRecibo, $objEmpleado, $coef=1)
	{
		$hsNormalesTrabajadas = $objEmpleado->getHsNormalesTrabajadas();
		if($this->compensatorio == true){
			$sueldoComp = $empleado->getCompensatorio();
			if($sueldoComp == 0) return false;
			$detalleRecibo->setRemunerativo($hsNormalesTrabajadas * $sueldoComp * $coef);
			$this->remunerativo += $hsNormalesTrabajadas * $sueldoComp * $coef;
		}else{
			$sueldoBlanco = $empleado->getSueldoBlanco();
			$detalleRecibo->setRemunerativo($hsNormalesTrabajadas * $sueldoBlanco * $coef);
			$this->remunerativo += $hsNormalesTrabajadas * $sueldoBlanco * $coef;	
		}	
		return $detalleRecibo;	
	}

	public function LiquidarNovedadesLote($empleado, $recibo)
	{
		$em = $this->getDoctrine()->getManager();
		$repoConceptos = $em->getRepository('MbpPersonalBundle:CodigoSueldos');
		
		foreach ($this->objLote->getEmpleadosCollection()->getEmpleado() as $objEmpleado) {
			foreach ($objEmpleado->getNovedades() as $novedad) {
				if($objEmpleado->getLegajo() == $empleado->getLegajo()){
					$nuevaNovedad = $repoConceptos->find($novedad);
					$sueldoBlanco = $empleado->getSueldoBlanco();
					$detalles = $recibo->getReciboDetalleId();
					$sueldoComp = $empleado->getCompensatorio();
					
					$novedadFlag=0;
					foreach ($detalles as $detalle) {
						$codigosSueldos = $detalle->getCodigoSueldos();
						foreach ($codigosSueldos as $idCodigo) {
							if($idCodigo->getId() == $novedad){								
								$detalle->setCantConceptoVar($detalle->getCantConceptoVar() + self::$hsNormalesDiarias);
								if($this->compensatorio != true){
									$detalleRecibo->setRemunerativo($detalle->getCantConceptoVar() * $sueldoBlanco);
									$this->remunerativo += self::$hsNormalesDiarias * $sueldoBlanco;																		
								}else{
									if($sueldoComp == 0) return;
									$detalleRecibo->setRemunerativo($detalle->getCantConceptoVar() * $sueldoComp);
									$this->remunerativo += self::$hsNormalesDiarias * $sueldoComp;	
								}									
								$novedadFlag++;		
							}	
						} 
						
					}	
					
					if($novedadFlag == 0){
						$detalleRecibo = new RecibosDetalle;
						
						$detalleRecibo->setCantConceptoVar(self::$hsNormalesDiarias);
						$detalleRecibo->setValorConceptoHist($sueldoBlanco);
						//SI ESTAMOS LIQUIDANDO EN NEGRO TOMAMOS EL VALOR HORA COMPENSATORIO
						if($this->compensatorio != true){							
							$detalleRecibo->setRemunerativo(self::$hsNormalesDiarias * $sueldoBlanco);
							$this->remunerativo += self::$hsNormalesDiarias * $sueldoBlanco;
						}else{
							if($sueldoComp == 0) return;
							$detalleRecibo->setRemunerativo(self::$hsNormalesDiarias * $sueldoComp);
							$this->remunerativo += self::$hsNormalesDiarias * $sueldoComp;								
						}	
						//$detalleRecibo->setRemunerativo(self::$hsNormalesDiarias * $sueldoBlanco);						
						$detalleRecibo->setValorCompensatorioHist($empleado->getCompensatorio());
						$detalleRecibo->addCodigoSueldo($nuevaNovedad);
						$recibo->addReciboDetalleId($detalleRecibo);		
					}					
				}				
			}
		}	
	}

	public function LiquidarHsExtras($empleado, $recibo)
	{
		$em = $this->getDoctrine()->getManager();
		$repoConceptos = $em->getRepository('MbpPersonalBundle:CodigoSueldos');
		
		foreach ($this->objLote->getEmpleadosCollection()->getEmpleado() as $objEmpleado) { 
			if($objEmpleado->getHsExtrasTrabajadas() != "" && $empleado->getLegajo() == $objEmpleado->getLegajo()){
				$detalleExtras = new RecibosDetalle;
				$codigoHsExtra = $repoConceptos->findOneByDescripcion('HORAS EXTRAS 50%');
				if($codigoHsExtra == ''){
					throw new \Exception("No existe el concepto de HORAS EXTRAS", 1);					
				}
				
				$valorHsExtra = ($empleado->getSueldoBlanco() + $empleado->getCompensatorio()) * 1.5;
				
				$detalleExtras->setCantConceptoVar($objEmpleado->getHsExtrasTrabajadas());
				$detalleExtras->setValorConceptoHist($empleado->getSueldoBlanco());
				$detalleExtras->setRemunerativo($objEmpleado->getHsExtrasTrabajadas() * $valorHsExtra);
				$this->remunerativo += $objEmpleado->getHsExtrasTrabajadas() * $valorHsExtra;
				$detalleExtras->setValorCompensatorioHist($empleado->getCompensatorio());
				$detalleExtras->addCodigoSueldo($codigoHsExtra);
				$recibo->addReciboDetalleId($detalleExtras);
			}			
		}
	}
	
	
	public function getObjLote(){
		return $this->objLote;
	}
	
	public function setObjLote($objLote){
		$this->objLote = $objLote;
	}
}