<?php
namespace Mbp\PersonalBundle\Clases\LiquidacionEnLote;

/*
 * ESTA CLASE PERMITE CREAR UN OBJETO EMPLEADO CON LOS DATOS DE LAS FICHADAS A PARTIR DEL REGISTRO EN EXCEL
 * QUE BRINDA SISTEMA GESVR DEL RELOJ
 * */

class Empleado
{
	/*Columnas de la planilla Excel*/
	private $legajo;
	private $nombre;	
	private $fecha = array(); //en la fecha se guardan los horarios de entrada y salida
	private $dia = array();	
	private $hsNormalesTrabajadas = 0;
	private $phpExcelService;
	private $novedades = array(); //SON LAS OBSERVACIONES QUE CORRESPONDEN A NOVEDADES DE RECIBO DE SUELDO
	private $hsPuntualidad = 5;
	private $hsAsistencia = 10;
	private $hsJustificadas=0;	//REPRESENTA LAS HORAS JUSTIFICADAS POR CONCEPTOS COMO POR EJ: LICENCIA POR ENFERMEDAD, ETC
	
		 
	public function __construct()
	{
		
	}
	
	public function setLegajo($legajo){
		$this->legajo = $legajo;
	}
}



















 