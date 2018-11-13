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
		};		
		
		//VERIFICO QUE EL WSAA 
		$time = strtotime($this->wsaa->get_expiration());
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

	public function getPuntoVenta(){
		return $this->ptoVta;
	}

	/*
	* Analiza los datos del certificado tales como identidad, emisor, caducidad.
	*/
	public function analizarCertificado(){
		print_r($this->wsaa->analizarCertificado());
	}
	
	/* PARAMS
	 * $cbte = desde y hasta	 
	 * $ptovta = punto de venta
	 * $regfe = array con todos los datos obligatorios para la generacion del cae
	 * $regfetrib = array con los datos tributarios de facturacion
	 * $regfeiva = array con los datos de IVA
	 * VER MANUAL PARA LA COMPOSICION DE LOS ARRAYS
	 * */
	public function generarFc(
		$cbteTipo, $concepto, $docNro, $cbteFch, $impNeto, $impTotConc,		
		$impIVA, $impTrib, $impOpEx, $impTotal, $fchServDesde, $fchServHasta, $fchVtoPago, $monId,
		$monCotiz, $idTributo, $descTributo, $baseImpTributo, $alictributo, $importeTributo,
		$idIVA, $baseImpIVA, $importeIVA, $cbtesAsoc)
	{
		

		$regfetrib=$this->getArrayImpFaele($idTributo, $descTributo, $baseImpTributo, $alictributo, $importeTributo);

		$regfeiva=$this->getArrayIVAFaele($idIVA, $baseImpIVA, $importeIVA);
			
		$regfeasoc=$this->getArrayCbtesAsoc($cbtesAsoc); //resta desarrollar 
		
		
		$nro = $this->ultimoNroComp($cbteTipo);
		if($nro['success'] == FALSE){ return $nro; }
		$nro = $nro['nro'];
		$nro++;		

		

		$cbteDesde=$nro; //si se quiere implementar por lote hay que modificar estas variables
		$cbteHasta=$nro;		

		$regfe=$this->getArrayDetalleFaele($cbteTipo, $concepto, $docNro, $cbteDesde, $cbteHasta, $cbteFch, $impNeto, $impTotConc,		
		$impIVA, $impTrib, $impOpEx, $impTotal, $fchServDesde, $fchServHasta, $fchVtoPago, $monId, $monCotiz);


		$cae = $this->FECAESolicitar($nro, // ultimo numero de comprobante autorizado mas uno 
                $this->ptoVta,  // el punto de venta
                $regfe, // los datos a facturar
				$regfeasoc, // comprobantes asociados
				$regfetrib, // otros tributos ej: iibb
				$regfeiva	// IVA
    	 );
		 
		if($cae['success'] == false || $cae['cae'] <= 0) {
			throw new \Exception(json_encode($cae['msg']['msg']), 1);			
		}
		
		$digito = $this->digitoVerificador($regfe['CbteTipo'], $cae['cae'], $cae['fecha_vencimiento']);
		
		return array(
			'cae' => $cae,
		 	'success' => true,
		 	'digitoVerificador' => $digito
		);
	}

	public function getArrayCbtesAsoc($arrayCbteAsoc){
		$regfeasoc=[];
		if (empty($arrayCbteAsoc)){
			return null;
		}else{
			foreach($arrayCbteAsoc as $cbte){
				\array_push($regfeasoc,
				array(
					'Tipo'=>$cbte['codigoAfip'],
					'PtoVta'=>$cbte['ptoVta'],
					'Nro'=>$cbte['fcNro']
				));
			}
			return $regfeasoc;
		}
		
		
		
	}

	public function getArrayIVAFaele($idIVA, $baseImpIVA, $importeIVA){

		$regfeiva['Id'] = $idIVA;
		$regfeiva['BaseImp'] = $baseImpIVA;
		$regfeiva['Importe'] = round($importeIVA,2);

		return $regfeiva;
	}

	public function getArrayImpFaele($idTributo, $descTributo, $baseImpTributo, $alictributo, $importeTributo){
		// Detalle de otros tributos
		$regfetrib['Id'] = $idTributo; 	//1: impuesto nacional, 2: imp. provincial, etc...		
		$regfetrib['Desc'] = $descTributo;//'impuesto IIBB';
		$regfetrib['BaseImp'] = $baseImpTributo;
		$regfetrib['Alic'] = $alictributo; 
		$regfetrib['Importe'] = $importeTributo;

		return $regfetrib;
	}

	public function getArrayDetalleFaele(
		$cbteTipo, $concepto, $docNro, $cbteDesde, $cbteHasta, $cbteFch, $impNeto, $impTotConc,		
		$impIVA, $impTrib, $impOpEx, $impTotal, $fchServDesde, $fchServHasta, $fchVtoPago, $monId,
		$monCotiz
	){
		$regfe['CbteTipo']=$cbteTipo;		
        $regfe['Concepto']=$concepto;//ESTE DATO DEBE VENIR DEL CLIENTE 1=PRODUCTOS, 2=SERVICIOS, 3=PRODUCTOS Y SERVICIOS
        $regfe['DocTipo']=$this->docTipo; //80=CUIT
        $regfe['DocNro']=$docNro;
        $regfe['CbteDesde']= $cbteDesde;	// nro de comprobante desde (para cuando es lote)
        $regfe['CbteHasta']=$cbteHasta;	// nro de comprobante hasta (para cuando es lote)
        $regfe['CbteFch']=$cbteFch; 	// fecha emision de factura
        $regfe['ImpNeto']=$impNeto;			// neto gravado
        $regfe['ImpTotConc']=$impTotConc;			// no gravado
        $regfe['ImpIVA']=round($impIVA, 2);			// IVA liquidado
        $regfe['ImpTrib']=$impTrib;			// otros tributos
        $regfe['ImpOpEx']=$impOpEx;			// operacion exentas
        $regfe['ImpTotal']=round($impTotal, 2);// total de la factura. ImpNeto + ImpTotConc + ImpIVA + ImpTrib + ImpOpEx
        $regfe['FchServDesde']=null;	// solo concepto 2 o 3
        $regfe['FchServHasta']=null;	// solo concepto 2 o 3
        $regfe['FchVtoPago']=null;		// solo concepto 2 o 3
        $regfe['MonCotiz'] = $monCotiz; //solo si la operacion es en otra moneda diferente al peso ARS
		$regfe['MonId']=$monId;
		
		return $regfe;
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

	public function consultarCaeEmitido($cbteTipo, $cbteNum, $ptoVta){
		return $this->FECompConsultar($cbteTipo, $cbteNum, $ptoVta);

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
