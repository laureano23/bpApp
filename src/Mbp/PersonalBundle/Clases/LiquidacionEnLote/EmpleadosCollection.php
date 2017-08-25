<?php
namespace Mbp\PersonalBundle\Clases\LiquidacionEnLote;

use Mbp\PersonalBundle\Clases\LiquidacionEnLote\Empleado;
use Symfony\Component\Validator\Constraints as Assert;

/*
 * ESTA CLASE AGRUPA UNA COLLECCION DE OBJETOS EMPLEADO Y VALIDA LOS DATOS OBTENIDOS DE
 * LA PLANILLA EXCEL PARA LAS LIQUIDACIONES EN LOTE
 * */

class EmpleadosCollection
{
	private $empleados = array();
	/**
     * @Assert\File(
     *     maxSize = "1024k",
     *     mimeTypes = {"application/vnd.ms-excel"},
     *     mimeTypesMessage = "Debe subir un archivo Excel"
     * )
     */
	public $filePath;
	public $phpExcelService;
	private $columnas;
	private $errores = array(); //COLECCION DE ERRORES DE VALIDACION DE LA PLANILLA
	static $columnLegajo = 0;
	static $columnNombre = 2;
	static $columnFecha = 3;
	static $columnDia = 4;
	static $columnaEntradas = 5;
	static $columnaSalidas = 6;
	static $columnaObservacion = 19;
			 
	public function __construct($filePath, $phpExcelService)
	{
		$this->filePath = $filePath;
		$this->phpExcelService = $phpExcelService;
		
				
		//SETEO CARACTERES DE LAS COLUMNAS (A,B,C,D...) QUE REPRESENTAN LAS COLUMNAS DE LA PLANILLA EXCEL
		$pos=0;
		for($i=65; $i<=90; $i++) {  
		    $this->columnas[$pos] = chr($i);
			$pos++;
		}
		
		$this->CargarArrayEmpleados();
		$this->ValidarColeccionEmpleados();	
		
	}
	
	
	
	private function CargarArrayEmpleados()
	{	
		//ARROJA UNA EXCEPCION SI NO ENCUENTRA EL ARCHIVO
		if(!file_exists($this->filePath)){
			throw new \Exception("No se encuentra el archivo", 1);
		}
	
		//CREA OBJETO EXCEL				
		$phpExcelObject = $this->phpExcelService->createPHPExcelObject($this->filePath);
		$activeSheet = $phpExcelObject->getActiveSheet();
		
		//CARGO LOS LEGAJOS
		
		$fila = 2;	//LA FILA 1 TIENE LOS ENCABEZADOS, SE COMIENZA POR LA FILA 2 LA CARGA DE DATOS
		$filaAnterior = 0;
				
		if($activeSheet->getCell($this->columnas[self::$columnLegajo].$fila)->getValue() == ""){
			throw new \Exception("No hay registros para cargar", 1);
			
		}
		
		$empleado = new Empleado;
		$empleado->setLegajo($activeSheet->getCell($this->columnas[self::$columnLegajo].$fila)->getValue());
		$empleado->setNombre($activeSheet->getCell($this->columnas[self::$columnNombre].$fila)->getValue());
				
		do {//CREO OBJETOS EMPLEADOS
			$empleado = new Empleado;
			$empleado->setLegajo($activeSheet->getCell($this->columnas[self::$columnLegajo].$fila)->getValue());
			$empleado->setNombre($activeSheet->getCell($this->columnas[self::$columnNombre].$fila)->getValue());		
					
			do{//CARGO LOS ARRAYS DE FECHA, DIA Y HORARIOS							
				$empleado->addFecha($activeSheet->getCell($this->columnas[self::$columnFecha].$fila)->getValue());
				$empleado->addDia($activeSheet->getCell($this->columnas[self::$columnDia].$fila)->getValue());
								
				$contFichadas = 0; //PARA AVANZAR POR LAS COLUMNAS DE FICHADAS (ENTRADAS Y SALIDAS)
				//BUCLE PARA CARGAR TODAS LAS ENTRADAS Y SALIDAS
				do{
					$empleado->addFichadaEntrada($activeSheet->getCell($this->columnas[self::$columnaEntradas + $contFichadas].$fila)->getValue());					
					$empleado->addFichadaSalida($activeSheet->getCell($this->columnas[self::$columnaSalidas + $contFichadas].$fila)->getValue());
					$contFichadas += 2;
						
				}while($contFichadas < 10);
				$empleado->addEntradas($empleado->getFichadaEntrada());
				$empleado->addSalidas($empleado->getFichadaSalida());
				$empleado->vaciarArrayEntradas();
				$empleado->vaciarArraySalidas();
				
				
				$empleado->addObservacion($activeSheet->getCell($this->columnas[self::$columnaObservacion].$fila)->getValue());
			
				$fila++; //AVANZO 1 FILA
				$filaAnterior = $fila - 1;
				
				//PARA EVITAR ENTRAR EN UN LOOP CUANDO CARGO EL ULTIMO REGISTRO
				if($activeSheet->getCell($this->columnas[self::$columnLegajo].$fila)->getValue() == ""){
					break;
				}				
				
			}while($activeSheet->getCell($this->columnas[self::$columnLegajo].$fila)->getValue() == $activeSheet->getCell($this->columnas[self::$columnLegajo].$filaAnterior)->getValue());
			
			//AGREGO EL EMPLEADO A LA COLECCION
			$this->addEmpleado($empleado);
			
			
		} while ($activeSheet->getCell($this->columnas[self::$columnLegajo].($fila))->getValue() != "");
		
	}	

	private function ValidarColeccionEmpleados()
	{
		//$this->ValidarFaltaDeIngreso();
		$this->ValidarIngresosEgresos();
	}

	
	
	/*
	 * FUNCION PARA COMPRAR SI LOS EGRESOS SON IGUALES A LOS INGRESOS
	 * */
	private function ValidarIngresosEgresos()
	{	
		$totalIngresos = 0;
		$totalEgresos = 0;
		
		foreach($this->getEmpleado() as $empleado){
			
									
			foreach($empleado->getEntradas() as $entrada){
				if(!empty($entrada)){
					$totalIngresos++;
				}
				
			}
			foreach($empleado->getSalidas() as $salida){
				if(!empty($salida)){
					$totalEgresos++;
				}
			}
			
			
			
			//VALIDAMOS LAS ENTRADAS Y SALIDAS DEBEN SER IGUALES
			if($totalEgresos != $totalIngresos){
				$str = $empleado->getNombre().": El total de entradas no es igual al de salidas";
				$this->addError($str);
			}
			
			$totalIngresos = 0;
			$totalEgresos = 0;
		}
	}
	
	/*
	 * FUNCION PARA DETERMINAR SI FALTA FICHAR ALGUN DIA Y NO HAY JUSTIFICACIONES
	 * */
	private function ValidarFaltaDeIngreso()
	{
		$i=0;
		foreach($this->getEmpleado() as $empleado){
			$dias = $empleado->getDia();
			$fecha = $empleado->getFecha();
			$observaciones = $empleado->getObservaciones();
			foreach($empleado->getEntradas() as $entrada){
				if(empty($entrada) && $dias[$i] != "Sábado" && $dias[$i] != "Domingo" && $observaciones[$i] == -1){
					$str = $empleado->getNombre()." ".$fecha[$i]->format('d/m/Y').": No hay fichada en la fecha";
					$this->addError($str);					
				}
				$i++;
			}
			$i=0;
		}
	}
	
	
	
	
	public function addEmpleado($empleado)
	{
		array_push($this->empleados, $empleado);
	}
	
	public function getEmpleado()
	{
		return $this->empleados;
	}
	
	public function addError($error)
	{
		$error = $error."</br>"; //CONCATENAMOS UN SALTO DE LINEA
		array_push($this->errores, $error);
	}
	
	public function getError()
	{
		return $this->errores;
	}	
}



















 