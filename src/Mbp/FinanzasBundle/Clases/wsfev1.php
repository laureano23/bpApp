<?php
namespace Mbp\FinanzasBundle\Clases;
/*
Ejemplo en codigo php para conectarse y obtener cae mediante uso de web service Afip en modo homologacion (testing).
Este programa se entrega ABSOLUTAMENTE SIN GARANTIA.
El siguiente codigo fuente es una adaptacion de ejemplos encontrados en la web.
2015 Pablo <pablin.php@gmail.com>
*/
class wsfev1 {
	const CUIT 	= 20086082293;                 		# CUIT del emisor de las facturas. Solo numeros sin comillas.

	const TA 	= "/xml/TA.xml";        				# Archivo con el Token y Sign
	//https://wswhomo.afip.gov.ar/wsfev1/service.asmx // Funciones
	//https://wswhomo.afip.gov.ar/wsfev1/service.asmx?WSDL // para obtener WSDL
	const WSDL = "/wsfev1.wsdl";                   	# The WSDL corresponding to WSFEV1	
	const LOG_XMLS = true;                     		# For debugging purposes
	const WSFEURL = "https://wswhomo.afip.gov.ar/wsfev1/service.asmx"; // homologacion wsfev1 (testing)
	//const WSFEURL = "?????????/wsfev1/service.asmx"; // produccion  

	/*
	* path del directorio donde esta wsaa.php
	*/	
	private $path = __DIR__; 
	
	/*
	* manejo de errores
	*/
	public $error = '';
	//public $ObsCode = array();
	//public $ObsMsg = '';
	public $Code = array();
	public $Msg = array();
	/**
	* Cliente SOAP
	*/
	private $client;
  
	/*
	* objeto que va a contener el xml de TA
	*/
	private $TA;
  
	/*
	* Constructor
	*/
	public function __construct()
	{
    
    // seteos en php
    ini_set("soap.wsdl_cache_enabled", "0");    
    
    // validar archivos necesarios
    if (!file_exists($this->path.self::WSDL)) $this->error .= " Failed to open ".self::WSDL;
    
    if(!empty($this->error)) {
    	echo $this->error."</br>";
		throw new \Exception('WSFE class. Faltan archivos necesarios para el funcionamiento');
    }        
	
    $this->client = new \SoapClient($this->path.self::WSDL, array( 
				'soap_version' => SOAP_1_2,
				'location'     => self::WSFEURL,
				'exceptions'   => 0,
				'trace'        => 1)
    ); 
	}
  
	/*
	* Chequea los errores en la operacion, si encuentra algun error falta lanza una exepcion
	* si encuentra un error no fatal, loguea lo que paso en $this->error
	*/
	private function _checkErrors($results, $method)
	{
    if (self::LOG_XMLS) {
		file_put_contents($this->path."/xml/request-".$method.".xml",$this->client->__getLastRequest());
		file_put_contents($this->path."/xml/response-".$method.".xml",$this->client->__getLastResponse());
    }
    if (is_soap_fault($results)) {
		throw new \Exception('WSFE class. FaultString: ' . $results->faultcode.' '.$results->faultstring);
    }
    
    if ($method == 'FEDummy') {return;}
	
    
    $XXX=$method.'Result';
	if(property_exists($results->$XXX, 'Errors')){
		if ($results->$XXX->Errors->Err->Code != 0) {
			$this->error = "Method=$method errcode=".$results->$XXX->Errors->Err->Code." errmsg=".$results->$XXX->Errors->Err->Msg;
		}	
		array_push($this->Code, $results->$XXX->Errors->Err->Code);	
	    array_push($this->Msg, utf8_decode($results->$XXX->Errors->Err->Msg));	
	    
	}	
	//asigna error a variable
	if ($method == 'FECAESolicitar' && property_exists($results->$XXX->FeDetResp->FECAEDetResponse, 'Observaciones')) {
		
		foreach ($results->$XXX->FeDetResp->FECAEDetResponse->Observaciones as $obs) {
			array_push($this->Code, $obs->Code);
			array_push($this->Msg, $obs->Msg);
		}
	/*	if ($results->$XXX->FeDetResp->FECAEDetResponse->Observaciones->Obs->Code){	
			
		}*/
		
		//if ($results->$XXX->FeDetResp->FECAEDetResponse->Observaciones->Obs[0]->Code){	
		//	$this->ObsCode = $results->$XXX->FeDetResp->FECAEDetResponse->Observaciones->Obs[0]->Code;
		//	$this->ObsMsg = $results->$XXX->FeDetResp->FECAEDetResponse->Observaciones->Obs[0]->Msg;
		//}		
	}
	
	
	
	//fin asigna error a variable
	if(!empty($this->Code)){
		return true;
	}
	return false;
	}

	/**
	* Abre el archivo de TA xml,
	* si hay algun problema devuelve false
	*/
	public function openTA()
	{
	$this->TA = simplexml_load_file($this->path.self::TA);

	return $this->TA == false ? false : true;
	}
  
	/*
	* Retorna el ultimo número autorizado.
	*/ 
	public function FECompUltimoAutorizado($ptovta, $tipo_cbte)
	{	
	$results = $this->client->FECompUltimoAutorizado(
		array('Auth'=>array('Token' => $this->TA->credentials->token,
							'Sign' => $this->TA->credentials->sign,
							'Cuit' => self::CUIT),
			'PtoVta' => $ptovta,
			'CbteTipo' => $tipo_cbte));
	//print_r($results);	
    $e = $this->_checkErrors($results, 'FECompUltimoAutorizado');
	//var_dump($results->FECompUltimoAutorizadoResult->CbteNro);
    return $e == false ? $results->FECompUltimoAutorizadoResult->CbteNro : false;
	} //end function FECompUltimoAutorizado
  
	/*
	* Retorna el ultimo comprobante autorizado para el tipo de comprobante /cuit / punto de venta ingresado.
	*/ 
	public function recuperaLastCMP ($ptovta, $tipo_cbte)
	{
	$results = $this->client->FERecuperaLastCMPRequest(
		array('argAuth' =>  array('Token' => $this->TA->credentials->token,
								'Sign' => $this->TA->credentials->sign,
								'cuit' => self::CUIT),
			'argTCMP' => array('PtoVta' => $ptovta,
								'TipoCbte' => $tipo_cbte)));
	$e = $this->_checkErrors($results, 'FERecuperaLastCMPRequest');
	
	return $e == false ? $results->FERecuperaLastCMPRequestResult->cbte_nro : false;
	} //end function recuperaLastCMP

	
	/*
	* Solicitud CAE y fecha de vencimiento 
	*/	
	public function FECAESolicitar($cbte, $ptovta, $regfe, $regfeasoc, $regfetrib, $regfeiva)
	{
	$params = array( 
		'Auth' => 
		array( 'Token' => $this->TA->credentials->token,
				'Sign' => $this->TA->credentials->sign,
				'Cuit' => self::CUIT ), 
		'FeCAEReq' => 
		array( 'FeCabReq' => 
			array( 'CantReg' => 1,
					'PtoVta' => $ptovta,
					'CbteTipo' => $regfe['CbteTipo'] ),
		'FeDetReq' => 
		array( 'FECAEDetRequest' => 
			array( 'Concepto' => $regfe['Concepto'],
					'DocTipo' => $regfe['DocTipo'],
					'DocNro' => $regfe['DocNro'],
					'CbteDesde' => $cbte,
					'CbteHasta' => $cbte,
					'CbteFch' => $regfe['CbteFch'],
					'ImpNeto' => $regfe['ImpNeto'],
					'ImpTotConc' => $regfe['ImpTotConc'], 
					'ImpIVA' => $regfe['ImpIVA'],
					'ImpTrib' => $regfe['ImpTrib'],
					'ImpOpEx' => $regfe['ImpOpEx'],
					'ImpTotal' => $regfe['ImpTotal'], 
					'FchServDesde' => $regfe['FchServDesde'], //null
					'FchServHasta' => $regfe['FchServHasta'], //null
					'FchVtoPago' => $regfe['FchVtoPago'], //null
					'MonId' => $regfe['MonId'], //PES 
					'MonCotiz' => $regfe['MonCotiz'], //1 
					'Tributos' => 
						array( 'Tributo' => 
							array ( 'Id' =>  $regfetrib['Id'], 
									'Desc' => $regfetrib['Desc'],
									'BaseImp' => $regfetrib['BaseImp'], 
									'Alic' => $regfetrib['Alic'], 
									'Importe' => $regfetrib['Importe'] ),
							), 
					'Iva' => 
						array ( 'AlicIva' => 
							array ( 'Id' => $regfeiva['Id'], 
									'BaseImp' => $regfeiva['BaseImp'], 
									'Importe' => $regfeiva['Importe'] ),
							), 
					), 
			), 
		), 
	);
	
	$results = $this->client->FECAESolicitar($params, 'FECAESolicitar');

    $e = $this->_checkErrors($results, 'FECAESolicitar');
   
	$resp_cae = $results->FECAESolicitarResult->FeDetResp->FECAEDetResponse->CAE;
	$resp_caefvto = $results->FECAESolicitarResult->FeDetResp->FECAEDetResponse->CAEFchVto;
	
	return $e == false ? Array( 'success' => true, 'cae' => $resp_cae, 'fecha_vencimiento' => $resp_caefvto ) 
		:array(
			'success' => false,
			'msg' => array(
				'code' => $this->Code,
				'msg' => $this->Msg,
				//'obs' => $this->wsfev1->ObsCode,
				//'msg' => $this->wsfev1->ObsMsg,
			)
		);
	} //end function FECAESolicitar
	
} // class

?>
