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
		if($this->openTA() == false) return array(
			'success' => false,
			'msg' => 'Error al abrir el ticket xml TA'
		);
		
		
		//VERIFICO QUE EL WSAA 
		$time = strtotime($this->wsaa->get_expiration());
		
		$newTime = \DateTime::createFromFormat('U', $time);
		$this->wsaa->generar_TA();
		/*if($newTime < new \DateTime) {
			if ($this->wsaa->generar_TA()) {				
			} else {
				throw new \Exception("Error al obtener el TA", 1);
			}
		} else {
			throw new \Exception("TA expiracion ".$this->wsaa->get_expiration(), 1);
		}*/
		
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
		 
		 return array(
		 	'cae' => $cae,
		 	'success' => true
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
}
