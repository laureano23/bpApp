<?
namespace Mbp\FinanzasBundle\Clases;
use Mbp\FinanzasBundle\Clases\wsaa;
use Mbp\FinanzasBundle\Clases\wsfev1;

class Faele
{
	private $wsaa;
	protected $wsfev1;
	public $docTipo;
	public $ptoVta;
	
	public function __construct($ptoVta, $docTipo){
		$this->docTipo = $docTipo;
		$this->ptoVta = $ptoVta;	
		$this->wsaa = new wsaa(); 
		$this->wsfev1 = new wsfev1();
		

		// Carga el archivo TA.xml
		if($this->wsfev1->openTA() == false) return array(
			'success' => false,
			'msg' => 'Error al abrir el ticket xml TA'
		);
		//VERIFICO QUE EL WSAA 
		if($this->wsaa->get_expiration() < date("Y-m-d h:m:i")) {
			if ($this->wsaa->generar_TA()) {				
			} else {
				return array(
					'success' => false,
					'msg' => 'Error al obtener el TA'
				);
			}
		} else {
			return array(
				'success' => false,
				'msg' => 'TA expiracion '. $this->wsaa->get_expiration()
			);
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
				
		$cae = $this->wsfev1->FECAESolicitar($nro, // ultimo numero de comprobante autorizado mas uno 
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
		$nro = $this->wsfev1->FECompUltimoAutorizado($this->ptoVta, $tipoCbte);
		
		if($nro === FALSE){ return array(
			'success' => false,
			'msg'=> $this->wsfev1->Msg
			);
		}
		else{
			return array(
				'success' => true,
				'nro' => $nro
			);
		}
	}

	//OBTIENE LOS TIPOS DE COMPROBANTE
	public function getTiposCbte()
	{
		return $this->wsfev1->FEParamGetTiposCbte();
	}
}
