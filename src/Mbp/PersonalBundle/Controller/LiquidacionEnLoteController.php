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
	
		
	/**
     * @Route("/LiquidarEnLote", name="mbp_personal_LiquidarEnLote", options={"expose"=true})
     */    
	public function LiquidarEnLote()
	{
		//DATOS DEL REQUEST
		$request = $this->getRequest();
		$periodo = $request->request->get('pagoTipo');
		$file = $request->files->get('archivoLote');
		$mes=5;
		$anio=2017;
		$compensatorio=0;		
		$banco=1;
		//DATOS DEL REQUEST
		$request = $this->getRequest();
		$this->periodo = $request->request->get('pagoTipo');
		$this->mesNum = $request->request->get('mes');
		$this->anio = $request->request->get('anio');
		$this->compensatorio = 0;
		$this->fechaPago = \DateTime::createFromFormat('d/m/Y', $request->request->get('fechaPago'));
		$this->banco = $request->request->get('banco');
		$this->descripcion = $request->request->get('descripcion');
		
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
			
			$liquidacion = new LiquidacionEnLote($fileTarget, $phpExcelService, $periodo, $mes, $anio, $banco);			
						
			//ERRORES EN LA COLECCION DE EMPLEADOS
			$errores = json_encode($liquidacion->getEmpleadosCollection()->getError());
			//ERRORES DEL LOTE DE LIQUIDACION
			$erroresLote = json_encode($liquidacion->getErrores());
			if($errores!="[]" || $erroresLote!="[]"){
				$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
				return $response->setContent(
					json_encode(
						array(
							'success' => false,
							'tipo' => 'validacion',
							'msg' => array(
								'errorColeccion' => $errores,
								'errorLote' => $erroresLote							
							)
						)
					)
				);			
			}
			
			$this->setObjLote($liquidacion);
			$this->CrearRecibosLote();
			
			$resp = new Response;
			return $resp->setContent(
				json_encode(array(
					'success' => true
				)));
			
		}catch(\Exception $e){
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
		$liquidacion = $repoRecibos->findBy(
			array('periodo' => $this->periodo, 'mes' => $this->mesNum, 'anio' => $this->anio)
		);
		
		if(!empty($liquidacion)){
			throw new \Exception("Ya existe una liquidación en este periodo, debe borrarla para poder liquidar en lote", 1);			
		}
		
		//BUSCAMOS LOS EMPLEADOS QUE ADHERIDOS A LIQUIDACION POR LOTE
		$empleados = $repoEmpleados->findByLiquidaPorLote(true);
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
						
			$detalleVariable = $this->LiquidarConceptosVariables($empleado, $recibo);
			$datosFijos = $this->insertaDatosFijos();
			//DATOS FIJOS
			foreach ($datosFijos as $detalleFijo) {
				\Doctrine\Common\Util\Debug::dump($detalleFijo);
				$recibo->addReciboDetalleId($detalleFijo);
				$this->remunerativo += $detalleFijo->getRemunerativo();
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
				foreach ($descuentosDetalle as $descuento) {
					$recibo->addReciboDetalleId($descuento);	
				}	
			}
			
			$em->persist($recibo);
			$em->flush();
		}				
	}


	private function LiquidarConceptosVariables($empleado, $recibo)
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
					//print_r($hsNormalesTrabajadas);
					//exit;
					$sueldoBlanco = $empleado->getSueldoBlanco();
					$detalleRecibo->setCantConceptoVar($hsNormalesTrabajadas);
					$detalleRecibo->setValorConceptoHist($sueldoBlanco);
					$detalleRecibo->setRemunerativo($hsNormalesTrabajadas * $sueldoBlanco);
					$this->remunerativo += $hsNormalesTrabajadas * $sueldoBlanco;
					$detalleRecibo->setValorCompensatorioHist($empleado->getCompensatorio());
					$detalleRecibo->addCodigoSueldo($concepto[0]);
					$recibo->addReciboDetalleId($detalleRecibo);
				}
			}
		}
	}
	
	
	public function getObjLote(){
		return $this->objLote();
	}
	
	public function setObjLote($objLote){
		$this->objLote = $objLote;
	}
}