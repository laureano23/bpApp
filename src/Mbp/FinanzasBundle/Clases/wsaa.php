<?php
namespace Mbp\FinanzasBundle\Clases;
/*
Ejemplo en codigo php para conectarse y obtener cae mediante uso de web service Afip en modo homologacion (testing).
Este programa se entrega ABSOLUTAMENTE SIN GARANTIA.
El siguiente codigo fuente es una adaptacion de ejemplos encontrados en la web.
2015 Pablo <pablin.php@gmail.com>
*/
class wsaa {
	public static $CERT;        	# The X.509 certificate in PEM format. Importante setear variable $path
	public static $PRIVATEKEY;  	# The private key correspoding to CERT (PEM). Importante setear variable $path
	public static $PASSPHRASE = "";         				# The passphrase (if any) to sign
	public static $PROXY_ENABLE = false;
	//https://wsaahomo.afip.gov.ar/ws/services/LoginCms?WSDL // para obtener WSDL
	public static $URL; // homologacion (testing)
	// CONST URL = "https://wsaa.afip.gov.ar/ws/services/LoginCms"; // produccion 
	
	const TA 	= "/xml/TA.xml";        			# Archivo con el Token y Sign
	const WSDL 	= "/wsaa.wsdl";      			# The WSDL corresponding to WSAA	
  
	/*
	* path del directorio donde esta wsaa.php
	*/	
	private $path = __DIR__; 
	

	/*
	* manejo de errores
	*/
	public $error = '';

	/**
	* Cliente SOAP
	*/
	private $client;

	/*
	* servicio del cual queremos obtener la autorizacion
	*/
	private $service; 
  
  
	/*
	* Constructor
	*/
	public function __construct($cert, $key, $url) 
	{
		self::$CERT = $cert;
		self::$PRIVATEKEY = $key;
		self::$URL = $url;
		
	    $this->service = 'wsfe';    
	    
	    // seteos en php
	    ini_set("soap.wsdl_cache_enabled", "0");    
	    
	    // validar archivos necesarios
	    if (!file_exists($this->path."/keys/".self::$CERT)) $this->error .= " Failed to open ".self::$CERT;
	    if (!file_exists($this->path."/keys/".self::$PRIVATEKEY)) $this->error .= " Failed to open ".self::$PRIVATEKEY;
	    if (!file_exists($this->path.self::WSDL)) $this->error .= " Failed to open ".self::WSDL;
	    
	    if(!empty($this->error)) {
			throw new \Exception('WSAA class. Faltan archivos necesarios para el funcionamiento '.$this->error);
	    }
	    
	    $this->client = new \SoapClient($this->path.self::WSDL, array(
			'soap_version'   => SOAP_1_2,
			'location'       => self::$URL,
			'trace'          => 1,
			'exceptions'     => 0
			)
	    );
	}
  
	/*
	* Crea el archivo xml de TRA
	*/
	private function create_TRA()
	{
	$TRA = new \SimpleXMLElement(
			'<?xml version="1.0" encoding="UTF-8"?>' .
			'<loginTicketRequest version="1.0">'.
			'</loginTicketRequest>');
	$TRA->addChild('header');
	$TRA->header->addChild('uniqueId', date('U'));
	$TRA->header->addChild('generationTime', date('c',date('U')-60));
	$TRA->header->addChild('expirationTime', date('c',date('U')+60));
	$TRA->addChild('service', $this->service);
	$TRA->asXML($this->path.'/xml/TRA.xml');
	}
  
	/*
	* This functions makes the PKCS#7 signature using TRA as input file, CERT and
	* PRIVATEKEY to sign. Generates an intermediate file and finally trims the 
	* MIME heading leaving the final CMS required by WSAA.
	* 
	* devuelve el CMS
	*/
	private function sign_TRA()
	{		
	
    $STATUS = openssl_pkcs7_sign($this->path . "/xml/TRA.xml", $this->path . "/xml/TRA.tmp", "file://" . $this->path."/keys/".self::$CERT,
		array("file://" . $this->path."/keys/".self::$PRIVATEKEY, self::$PASSPHRASE),
		array(),
		!PKCS7_DETACHED
    );
	
	
    
    if (!$STATUS)
		throw new \Exception("ERROR generating PKCS#7 signature");
      
    $inf = fopen($this->path."/xml/TRA.tmp", "r");
    $i = 0;
    $CMS = "";
    while (!feof($inf)) { 
		$buffer = fgets($inf);
		if ( $i++ >= 4 ) $CMS .= $buffer;
    }
    
    fclose($inf);
    unlink($this->path."/xml/TRA.tmp");
    
    return $CMS;
	}
  
	/*
	* Conecta con el web service y obtiene el token y sign
	*/
	private function call_WSAA($cms)
	{     
	$results = $this->client->loginCms(array('in0' => $cms));

	// para logueo
	file_put_contents($this->path."request-loginCms.xml", $this->client->__getLastRequest());
	file_put_contents($this->path."response-loginCms.xml", $this->client->__getLastResponse());

	if (is_soap_fault($results)) 
		throw new \Exception("SOAP Fault: ".$results->faultcode.': '.$results->faultstring);

	return $results->loginCmsReturn;
	}
  
	/*
	* Convertir un XML a Array
	*/
	private function xml2array($xml) 
	{    
		$json = json_encode( simplexml_load_string($xml));
		return json_decode($json, TRUE);
	}    
  
	/*
	* funcion principal que llama a las demas para generar el archivo TA.xml
	* que contiene el token y sign
	*/
	public function generar_TA()
	{
	$this->create_TRA();
	$TA = $this->call_WSAA( $this->sign_TRA() );
					
	if (!file_put_contents($this->path.self::TA, $TA))
		throw new \Exception("Error al generar al archivo TA.xml");

	$this->TA = $this->xml2Array($TA);
	  
	return true;
	}
  
	/*
	* Obtener la fecha de expiracion del TA
	* si no existe el archivo, devuelve false
	*/
	public function get_expiration() 
	{    
	// si no esta en memoria abrirlo
	if(empty($this->TA)) {
		$TA_file = file($this->path.self::TA, FILE_IGNORE_NEW_LINES);
		if($TA_file) {
			$TA_xml = '';
			for($i=0; $i < sizeof($TA_file); $i++)
				$TA_xml.= $TA_file[$i];        
			$this->TA = $this->xml2Array($TA_xml);
			$r = $this->TA['header']['expirationTime'];
		} else {
			$r = false;
		}
	} else {
		$r = $this->TA['header']['expirationTime'];
	}
	return $r;
	}
	
}
?>
