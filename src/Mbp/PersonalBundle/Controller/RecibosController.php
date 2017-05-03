<?php

namespace Mbp\PersonalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Mbp\PersonalBundle\Entity\Recibos;
use Mbp\PersonalBundle\Entity\RecibosDetalle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class RecibosController extends Controller
{
	public $idP;
	public $empleadoSueldo;
	public $cantidad;
	public $codigoCalculo;
	public $compensatorio;
	public $anio;
	public $mesNum;
	public $periodo;
	public $banco;
	public $fechaPago;
	public $descripcion;
	public $antiguedadAnios;
	public static $mes = 30;
	public static $horas_mensualizados = 180;
	

	
	/**
     * @Route("/recibosLiquida", name="mbp_personal_recibosLiquida", options={"expose"=true})
     */    
	public function recibosLiquidaAction()
	{
		$recibo = new Recibos();
		$em = $this->getDoctrine()->getManager();
		$repoBancos = $em->getRepository('MbpFinanzasBundle:Bancos');
		$repoEmpleado = $em->getRepository('MbpPersonalBundle:Personal');		
		$req = $this->getRequest();
		$remunerativo = 0; //CONTADOR PARA CONCEPTOR REMUNERATIVOS SOBRE EL QUE SE CALCULAN LOS DESCUENTOS
		
		//OBTENGO DATOS DEL HEADER
		$compensatorio = $req->request->get('compensatorio');
		$this->idP = $req->request->get('idP');
		$compensatorio == "true" ? $this->compensatorio = 1 : $this->compensatorio = 0;  
		$this->anio = $req->request->get('anio');
		$this->mesNum = $req->request->get('mes');
		$this->periodo = $req->request->get('periodo');
		$this->banco = $req->request->get('banco');
		$this->descripcion = $req->request->get('descripcion');
		$this->fechaPago = \DateTime::createFromFormat('d/m/Y', $req->request->get('fechaPago'));
		
		
		$detalles = json_decode($req->request->get('data'));
		$banco = $repoBancos->find($this->banco);		
		$empleado = $repoEmpleado->find($this->idP);
		
		
		
		$antiguedad = $empleado->getFechaIngreso()->diff(new \DateTime("now"));
		$this->antiguedadAnios = $antiguedad->format("%Y");
		$localidad = $empleado->getLocalidad()->getNombre();
		$prov = $empleado->getLocalidad()->getDepartamentoId()->getProvinciaId()->getNombre();
		
		try{
			/* SE VALIDA SI EL EMPLEADO ESTA ACTIVO */
			if($empleado->getInactivo() == 1){
				throw new \Exception("El empleado se encuentra inactivo, contacte al administrador");				
			}
			//DATOS DEL RECIBO
			$recibo->setCompensatorio($this->compensatorio);
			$recibo->setBanco($banco);
			$recibo->setFechaPago($this->fechaPago);
			$recibo->setPeriodo($this->periodo);
			$recibo->setMes($this->mesNum);
			$recibo->setAnio($this->anio);
			$recibo->setTipoPago($this->descripcion);
			$recibo->setBasicoHist($empleado->getCategoria()->getSalario());
			$recibo->setCategoriaHist($empleado->getCategoria()->getCategoria());
			$recibo->setSindicatoHist($empleado->getCategoria()->getIdSindicato()->getSindicato());
			$recibo->setTarea($empleado->getTarea());
			$recibo->setAntiguedad($antiguedad->format("%Y"));
			$recibo->setDomicilio($empleado->getDireccion()." ".$localidad." ".$prov);
			$recibo->setEcivil($empleado->getEstado());
			$recibo->setObraSocial($empleado->getObraSocial());
			$recibo->addPersonal($empleado);
			
			
			
			//VALIDO LOS DATOS DEL RECIBO
			$validator = $this->get('validator');
    		$errors = $validator->validate($recibo);
			
			if(count($errors) > 0){
				$listErr="";
				
				foreach ($errors as $err) {
					$listErr = $listErr.$err->getMessage()."<br>";
				}
				
				return new Response(
				json_encode(array(
						'success' => false,	
						'msg' => $listErr		
					))
				);
			}
			
			//DATOS DE DETALLES VARIABLES
			if(!is_array($detalles)){
				$detalleVariable = $this->liquidarConceptosVariables($detalles, $this->idP);
				$recibo->addReciboDetalleId($detalleVariable);
				$remunerativo = $detalleVariable->getRemunerativo();
			}else{
				foreach ($detalles as $det) {
					$detalleVariable = $this->liquidarConceptosVariables($det, $this->idP);
					$recibo->addReciboDetalleId($detalleVariable);
					$remunerativo += $detalleVariable->getRemunerativo(); 
				}	
			}
			
			//DATOS FIJOS, NO INCLUYE ANTIGUEDAD
			$datosFijos = $this->insertaDatosFijos();
			foreach ($datosFijos as $detalleFijo) {
				$recibo->addReciboDetalleId($detalleFijo);
				$remunerativo += $detalleFijo->getRemunerativo();
			}
			
			//ANTIGUEDAD
			$detalleAntiguedad = $this->calculaAntiguedad($remunerativo);
			if($detalleAntiguedad != null){
				$recibo->addReciboDetalleId($detalleAntiguedad);
				$remunerativo += $detalleAntiguedad->getRemunerativo();
			}
			
			//DESCUENTOS SI EL RECIBO ES EN BLANCO
			if($this->compensatorio != TRUE){
				$descuentosDetalle = $this->liquidarDescuentos($remunerativo);
				foreach ($descuentosDetalle as $descuento) {
					$recibo->addReciboDetalleId($descuento);	
				}	
			}
						
			$em->persist($recibo);
			$em->flush();	
		}catch(\Exception $e){
			return new Response(
				json_encode(array(
					'success' => false,	
					'msg' => $e->getMessage()			
				))
			);
		}
		
		return new Response(
			json_encode(array(
				'success' => true
			))
		);
	}

	private function insertaDatosFijos()
	{
		$em = $this->getDoctrine()->getManager();
		$repoEmpleado = $em->getRepository('MbpPersonalBundle:Personal');
		
		$empleado = $repoEmpleado->find($this->idP);
		$datosFijos = $this->datosFijos(); //DATOS FIJOS QUE TIENE EL EMPLEADO SEGUN EL PERIODO DE LIQUIDACION
		$antiguedad = $empleado->getAntPorcentaje();
		$detalleFijos=array();
		
		foreach ($datosFijos as $dat) {			
			if(!$dat->getCodigoId()->getDescuento() && $dat->getCodigoId()->getDescripcion() != "ANTIGUEDAD" && $this->compensatorio != 1){
				$detalle = new RecibosDetalle();
				$detalle->setCantConceptoVar(1);
				$detalle->setValorConceptoHist($dat->getCodigoId()->getImporte());
				$dat->getCodigoId()->getRemunerativo() ? 
				$detalle->setRemunerativo($dat->getCodigoId()->getImporte()) :
				$detalle->setNoRemunerativo($dat->getCodigoId()->getImporte());
				$detalle->addCodigoSueldo($dat->getCodigoId());
				array_push($detalleFijos, $detalle);
			}
		}
		
		return $detalleFijos;
	}	
	
	private function calculaAntiguedad($remunerativo)
	{
		$em = $this->getDoctrine()->getManager();
		$repoEmpleado = $em->getRepository('MbpPersonalBundle:Personal');
		$repoConceptos = $em->getRepository('MbpPersonalBundle:CodigoSueldos');
		
		$empleado = $repoEmpleado->find($this->idP);
		
		//CUANDO LIQUIDO PREMIOS NO CALCULO ANTIGUEDAD
		if($this->periodo == 7 || $this->periodo == 8){
			return null;
		}
		
		$antiguedadConcepto = $repoConceptos->createQueryBuilder('c')
			->select('c')
			->where('c.descripcion = :desc')
			->setParameter('desc', "ANTIGUEDAD")
			->getQuery()
			->getResult();
		$calculoAnt = $remunerativo * $empleado->getAntPorcentaje() / 100 * $this->antiguedadAnios;
		
		if($empleado->getAntiguedad() == 0){return NULL;}
		
		$antiguedadDetalle = new RecibosDetalle();
		$antiguedadDetalle->setCantConceptoVar($this->antiguedadAnios);
		$antiguedadDetalle->setValorConceptoHist($calculoAnt);
		$antiguedadDetalle->setRemunerativo($calculoAnt);
		$antiguedadDetalle->addCodigoSueldo($antiguedadConcepto[0]);
		
		return $antiguedadDetalle;
	}

	private function liquidarDescuentos($totalRemunerativo)
	{
		$em = $this->getDoctrine()->getManager();
		$repoConceptos = $em->getRepository('MbpPersonalBundle:CodigoSueldos');
		$repoEmpleado = $em->getRepository('MbpPersonalBundle:Personal');
		
		$empleado = $repoEmpleado->find($this->idP);
		$datosFijos = $this->datosFijos(); //DATOS FIJOS QUE TIENE EL EMPLEADO SEGUN EL PERIODO DE LIQUIDACION
		
		$arrayDescuentos=array();
		
		foreach ($datosFijos as $datos) {
			if($datos->getCodigoId()->getDescuento()){
				$detalle = new RecibosDetalle();
				if($datos->getCodigoId()->getFijo()){
					$detalle->setDescuento($datos->getCodigoId()->getImporte());
					$detalle->setCantConceptoVar($datos->getCodigoId()->getImporte());
					$detalle->setValorConceptoHist($datos->getCodigoId()->getImporte());
					$detalle->addCodigoSueldo($datos->getCodigoId());
					array_push($arrayDescuentos, $detalle);					
				}else{
					$datos->getCodigoId()->getPorcentaje() ? 
					$detalle->setDescuento($totalRemunerativo * $datos->getCodigoId()->getImporte() / 100) :
					$detalle->setDescuento($datos->getCodigoId()->getImporte());
					$detalle->setCantConceptoVar($datos->getCodigoId()->getImporte());
					$detalle->setValorConceptoHist($datos->getCodigoId()->getImporte());
					$detalle->addCodigoSueldo($datos->getCodigoId());
					array_push($arrayDescuentos, $detalle);
				}
			}
		}		
		return $arrayDescuentos;
	}

	/*
	 * CONSULTA DATOS FIJOS DEL PERIODO A LIQUIDAR
	 */
	private function datosFijos()
	{
		$em = $this->getDoctrine()->getManager();
		$repoPerConceptos = $em->getRepository('MbpPersonalBundle:PersonalConceptosSueldo');
		
		$qb = $repoPerConceptos->createQueryBuilder('c')
			->select('c', 'concepto', 'periodo')
			->leftJoin('c.codigo_id', 'concepto')
			->leftJoin('concepto.periodo', 'periodo')
			->where('c.personal_id = :idP')
			->andWhere('periodo.numero = :per')
			->setParameter('idP', $this->idP)
			->setParameter('per', $this->periodo)
			->getQuery();
			
		$datosFijos = $qb->getResult();
		return $datosFijos;
	}
	
	
	private function liquidarConceptosVariables($data)
	{
		$em = $this->getDoctrine()->getManager();
		$repoConceptos = $em->getRepository('MbpPersonalBundle:CodigoSueldos');
		$repoEmpleado = $em->getRepository('MbpPersonalBundle:Personal');
		$empleado = $repoEmpleado->find($this->idP);
		$concepto = $repoConceptos->find($data->idConcepto);		
		
		//SETEO DATOS DEL DETALLE DE RECIBO
		$detalle = new RecibosDetalle();
		$detalle->setCantConceptoVar($data->cantidadConcepto);
		$detalle->setValorConceptoHist($empleado->getSueldoBlanco());
		$detalle->setValorCompensatorioHist($empleado->getCompensatorio());
		$detalle->addCodigoSueldo($concepto);
		if($concepto->getRemunerativo()){
			$detalle->setRemunerativo($data->subTotal);	
			return $detalle;
		}
		if($concepto->getDescuento()){
			$detalle->setRemunerativo($data->subTotal);
			return $detalle;	
		}
		if($concepto->getNoRemunerativo()){
			$detalle->setExento($data->subTotal);
			return $detalle;
		}
	}
	
	
	//CALCULA CADA CONCEPTO SEGUN DATOS DE ENTRADA COMO PERIODO, PERSONA, SINDICATO...ETC
	/**
     * @Route("/calculaConcepto", name="mbp_personal_calculaConcepto", options={"expose"=true})
     */  
	public function calculaConcepto()
	{
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		
		
		//OBTENGO DATOS DEL HEADER
		$this->idP = $req->request->get('idP');
		$this->empleadoSueldo = $req->request->get('importe');
		$this->cantidad = $req->request->get('cantidad');
		$this->codigoCalculo = $req->request->get('codigoCalculo');
		$this->compensatorio = $req->request->get('compensatorio');
		$this->anio = $req->request->get('anioLiquidacion');
		
		$calculoClass = $this->get('calculoConceptos');
		$empleadoSueldo = $calculoClass->initParams($this->idP, $this->codigoCalculo, $this->cantidad, $this->empleadoSueldo, $this->compensatorio);
		$subTotal = $calculoClass->init($this->codigoCalculo);
		
		
		return new Response(json_encode(array(
			'importe' => $empleadoSueldo,
			'subTotal' => $subTotal
		))
		);		
	}
	
	/*
	 * LECTURA DE CONCEPTOS GUARDADOS EN UN PERIODO DETERMINADO
	 */	
 	/**
     * @Route("/conceptosLectura", name="mbp_personal_conceptosLectura", options={"expose"=true})
     */  
	public function conceptosLecturaAction()
	{
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		
		$idPersonal = $req->request->get('idPersonal');
		$codPeriodo = $req->request->get('codPeriodo');
		$mes = $req->request->get('mes');
		$anio = $req->request->get('anio');
		//DETERMINO SI LOS REGISTROS SERAN COMPENSATORIOS O NO
		$compensatorio = $req->request->get('compensatorio');
		$compensatorio == 'true' ? $compensatorio = 1 : $compensatorio = 0;
		
		
		try{
			$repo = $this->getDoctrine()->getRepository('MbpPersonalBundle:Recibos');
			$query = $repo->createQueryBuilder('g')
			->select('g.id, d.valorConceptoHist, d.cantConceptoVar, c.id AS codigoId, c.descripcion, c.codigoCalculo, p.compensatorio')
			->join('g.personal', 'p')
			->join('g.reciboDetalleId', 'd')
			->join('d.codigoSueldos', 'c')			
			->where('p.idP = :idPersonal')
			->andWhere('g.periodo = :periodo')
			->andWhere('g.mes = :mes')
			->andWhere('g.anio = :anio')
			->andWhere('g.compensatorio = :comp')
			->setParameter('idPersonal', $idPersonal)
			->setParameter('periodo', $codPeriodo)
			->setParameter('mes', $mes)
			->setParameter('anio', $anio)
			->setParameter('comp', $compensatorio)
			->getQuery();
			$res = $query->getResult();	
			
			
			//CALCULO EL IMPORTE SEGUN FORMA DE CALCULO DEL CONCEPTO
			$calculoClass = $this->get('calculoConceptos');		
			
			$resul = array();
			$i=0;
			foreach ($res as $resu) {
				$resul[$i]['idRecibo'] = $resu['id'];
				$resul[$i]['idConcepto'] = $resu['codigoId'];
				$resul[$i]['descripcionConcepto'] = $resu['descripcion'];
				$resul[$i]['cantidadConcepto'] = $resu['cantConceptoVar'];
				$resul[$i]['importe'] = $resu['valorConceptoHist'];
				$resul[$i]['subTotal'] = $calculoClass->init($resu['codigoCalculo'], $resu['cantConceptoVar'], $resu['valorConceptoHist'], $req->request->get('compensatorio'), $resu['compensatorio']);;
				$compensatorio == 1 ? $resul[$i]['compensatorio'] = 1 : $resul[$i]['compensatorio'] = 0;
				$i++;
			}	
			
			if($resul){
				echo json_encode(array(
					'msg' => 'Ya existe una liquidacion para este legajo en este periodo. Desea borrarla y generar una nueva?',
					'success' => 'info',
					'data' => $resul
				));
			}else{
				echo json_encode(array(
					'success' => true,
					'data' => '',
				));
			}	
			
		}catch(\Doctrine\ORM\ORMException $e){
			echo json_encode(array(
				'success' => false,
				'msg' => 'Se produjo un error, vuelva a intentarlo'
			));
			$this->get('logger')->error($e->getMessage());
		}
		
		
		return new Response();
	}
	
	/**
     * @Route("/recibosEliminarPeriodo", name="mbp_personal_recibosEliminarPeriodo", options={"expose"=true})
     */ 
	public function recibosEliminarPeriodoAction()
	{
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$repoRecibos = $em->getRepository('MbpPersonalBundle:Recibos');
		$repoCuentaEmpleados = $em->getRepository('MbpPersonalBundle:CuentaEmpleados');
		
		$idRecibo = $req->request->get('idRecibo');
		
		$res = $repoRecibos->find($idRecibo);
		$periodo = $res->getPeriodo();
		$mes = $res->getMes();
		$anio = $res->getAnio();
		$empleado = $res->getPersonal();
		$idPersonal = $empleado[0]->getIdP();
		$compensatorio = $res->getCompensatorio();
		
		$em->remove($res);
		$em->flush();
		
		//ELIMINO REGISTRO DE LA CUENTA CARRIENTE DE EMPLEADOS
		$qb = $repoCuentaEmpleados->createQueryBuilder('c')
			->select('')
			->join('c.idPersonal', 'p', 'WITH', 'p.idP = :idPersonal')
			->where('c.periodo = :periodo')
			->andWhere('c.mes = :mes')
			->andWhere('c.anio = :anio')
			->andWhere('c.compensatorio = :compensatorio')
			->setParameter('periodo', $periodo)
			->setParameter('mes', $mes)
			->setParameter('anio', $anio)
			->setParameter('idPersonal', $idPersonal)
			->setParameter('compensatorio', $compensatorio)
			->getQuery();
		
		$res = $qb->getResult();
					
		
		foreach ($res as $resu) {
			$em->remove($resu);
		}
		
		$em->flush();
		
		echo json_encode(array(
			'success' => true				
		));
						
		return new Response();
	}

	/**
     * @Route("/recibosReliquidar", name="mbp_personal_recibosReliquidar", options={"expose"=true})
     */ 
	public function recibosReliquidarAction()
	{
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$repo = $em->getRepository('MbpPersonalBundle:Recibos');
		$repoCuenta = $em->getRepository('MbpPersonalBundle:CuentaEmpleados');
				
		$mes = $req->request->get('mes');
		$pagoTipo = $req->request->get('pagoTipo');
		$anio = $req->request->get('anio');
		$compensatorio = $req->request->get('compensatorio');
		$compensatorio == 'on' ? $compensatorio = 1 : $compensatorio = 0;
		
		
		$qb = $repo->createQueryBuilder('r')
			->select()
			->where('r.mes = :mes')
			->andWhere('r.periodo = :pagoTipo')
			->andWhere('r.anio = :anio')
			->andWhere('r.compensatorio = :compensatorio')
			->setParameter('mes', $mes)
			->setParameter('pagoTipo', $pagoTipo)
			->setParameter('anio', $anio)
			->setParameter('compensatorio', $compensatorio)
			->getQuery();
			
		$res = $qb->getResult();
		
		$qbCuenta = $repoCuenta->createQueryBuilder('c')
			->select()
			->where('c.periodo = :pagoTipo')
			->andWhere('c.mes = :mes')
			->andWhere('c.anio = :anio')
			->andWhere('c.compensatorio = :compensatorio')
			->setParameter('pagoTipo', $pagoTipo)
			->setParameter('mes', $mes)
			->setParameter('anio', $anio)
			->setParameter('compensatorio', $compensatorio)
			->getQuery();
			
		$resCuenta = $qbCuenta->getResult();
		
		if(empty($res) && empty($resCuenta)){
			echo json_encode(array(
				'msg' => 'No hay registros para el periodo ingresado',
				'success' => 'info'
			));
		}else{
			foreach ($res as $reg) {
				$em->remove($reg);
				$em->flush();
			}
			foreach ($resCuenta as $cuenta) {
				$em->remove($cuenta);
				$em->flush();
			}
			echo json_encode(array(
				'success' => true,
				'msg' => 'Proceso exitoso'
			));
		}		
		return new Response();
	}
	
	/**
     * @Route("/aguinaldoLiquida", name="mbp_personal_aguinaldoLiquida", options={"expose"=true})
     */ 
	public function aguinaldoLiquidaAction()
	{
		$calculoClass = $this->get('calculoConceptos');
		
		$res = $calculoClass->resumenAguinaldoBlanco(2016, 4, 3);
		
		return new Response();
	}
}