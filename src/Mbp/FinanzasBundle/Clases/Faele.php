<?php
namespace Mbp\FinanzasBundle\Clases;
use Mbp\FinanzasBundle\Clases\wsaa;
use Mbp\FinanzasBundle\Clases\wsfev1;

class Faele extends wsfev1
{
	private $wsaa;
	public $docTipo;
	public $ptoVta;
	public $cert;
	public $key;
	public $url;
	
	public function __construct($ptoVta, $docTipo, $cert, $key, $url, $wsfeUrl, $cuit){
		parent::__construct($wsfeUrl, $cuit);
		$this->docTipo = $docTipo;
		$this->ptoVta = $ptoVta;	
		$this->wsaa = new wsaa($cert, $key, $url); 		
				

		// Carga el archivo TA.xml
		
		if($this->openTA() == false) {
			$this->wsaa->generar_TA();
			//throw new \Exception("Error al abrir el ticket TA", 1);
			
		};
		
		
		//VERIFICO QUE EL WSAA 
		$time = strtotime($this->wsaa->get_expiration());
		
		//$newTime = strtotime(\DateTime::createFromFormat('U', $time));
		//$this->wsaa->generar_TA();
		$newDate = new \DateTime($this->wsaa->get_expiration());
		
		$format_date  = date('Y-m-d H:i:s', strtotime($this->wsaa->get_expiration()));

		$expiracion = new \DateTime($format_date);
		$now = new \DateTime;
		
		if($expiracion < $now) {
		
			if ($this->wsaa->generar_TA()) {				
			
			}else {
				throw new \Exception("Error al obtener el TA", 1);
			}
		} else {
			//throw new \Exception("TA expiracion ".$this->wsaa->get_expiration(), 1);
		}
	}
	
	/* PARAMS
	 * $cbte = desde y hasta	 
	 * $ptovta = punto de venta
	 * $regfe = array con todos los datos obligatorios para la generacion del cae
	 * $regfetrib = array con los datos tributarios de facturacion
	 * $regfeiva = array con los datos de IVA
	 * VER MANUAL PARA LA COMPOSICION DE LOS ARRAYS
	 * */
	public function generarFc($regfe, $regfeasoc, $regfetrib, $regfeiva)
	{
		$nro = $this->ultimoNroComp($regfe['CbteTipo']);
		if($nro['success'] == FALSE){ return $nro; }
		$nro = $nro['nro'];
		$nro++;
				
		$cae = $this->FECAESolicitar($nro, // ultimo numero de comprobante autorizado mas uno 
                $this->ptoVta,  // el punto de venta
                $regfe, // los datos a facturar
				$regfeasoc,
				$regfetrib,
				$regfeiva	
    	 );
		 
		if($cae['success'] == false || $cae['cae'] <= 0) {
			return $cae;
		}
		
		$digito = $this->digitoVerificador($regfe['CbteTipo'], $cae['cae'], $cae['fecha_vencimiento']);
		
		 return array(
		 	'cae' => $cae,
		 	'success' => true,
		 	'digitoVerificador' => $digito
		 );
	}
	
	//OBTIENE EL ULTIMO NUMERO DE COMPROBANTE AUTOIRIZADO, POR ERROR DEVUELVE FALSE
	public function ultimoNroComp($tipoCbte)
	{
		$nro = $this->FECompUltimoAutorizado($this->ptoVta, $tipoCbte);
				
		if($nro == false){ 
			$msg = $this->Msg;
			if(empty($msg)){
				return 1;
			}else{
				throw new \Exception($msg[0], 1);	
			}
						
		}
		else{
			return array(
				'success' => true,
				'nro' => $nro
			);
		}
	}
	
	public function digitoVerificador($tipoCbte, $cae, $vtoCae){
		
		$cuit = str_split(parent::$CUIT);
		$ptoVta = str_pad($this->ptoVta, 4, "0", STR_PAD_LEFT);
		$tipoCbte = str_pad($tipoCbte, 2, "0", STR_PAD_LEFT);		
		
		$palabra = parent::$CUIT.$tipoCbte.$ptoVta.$cae.$vtoCae;
		
		
		//Etapa 1: Comenzar desde la izquierda, sumar todos los caracteres ubicados en las posiciones impares y pares
		$par=0;
		$sumaPar=0;
		$sumaImpar=0;
		$palabra = str_split($palabra);
		foreach ($palabra as $c) {
			$resto = $par%2;
			if($resto!=0){
				$sumaPar += $c;
			}else{
				$sumaImpar += $c;
			}			
			$par++;
		}
		
		//Etapa 2: Multiplicar la suma obtenida en la etapa 1 por el número 3.
		$sumaImpar = $sumaImpar*3;
		//Etapa 4: Sumar los resultados obtenidos en las etapas 2 y 3.
		$sumaTotal = $sumaImpar + $sumaPar;
		
		//Buscar el menor número que sumado al resultado obtenido en la etapa 4 dé un número múltiplo de 10. 
		//Este será el valor del dígito verificador del módulo 10.
		$numero=0;
		for ($i=0; $i <= 9; $i++) {
			 if((($sumaTotal + $i) % 10) == 0){
			 	$numero += $i;
			 }
		}
				
		return $numero;
	}	
}
