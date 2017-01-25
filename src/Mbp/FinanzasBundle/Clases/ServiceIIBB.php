<?
namespace Mbp\FinanzasBundle\Clases;
use Symfony\Component\HttpFoundation\Request;
use Mbp\FinanzasBundle\Clases\curlFileUploader;

class ServiceIIBB
{
	private $user;
	private $pass;
	private $strXml;
	private $fechaDesde;
	private $fechaHasta;
	private $cuitConsulta;
	private $rootDir;
	private $urlArba;
	private $response;


	public function __construct($user, $pass, $rootDir, $urlArba)
	{
		$this->user = $user;
		$this->pass = $pass;
		$this->rootDir = realpath($rootDir.'/../src/Mbp/FinanzasBundle/Clases/xml');
		$this->urlArba = $urlArba;
		$this->fechaDesde=date('Ymd',mktime(0,0,0,date('m'),1,date('Y')));
		$this->fechaHasta=date('Ymd',mktime(0,0,0,((int)date('m'))+1,0,date('Y')));
	}

	public function setOpts($cuitConsulta)
	{
		$this->cuitConsulta = $cuitConsulta; 

		$this->request();
	}

	private function doXml()
	{
		$xmlStr = <<<XML
<?xml version='1.0' encoding = "ISO-8859-1"?>
<CONSULTA-ALICUOTA>
<fechaDesde>$this->fechaDesde</fechaDesde>
<fechaHasta>$this->fechaHasta</fechaHasta>
<cantidadContribuyentes>1</cantidadContribuyentes>
<contribuyentes class="list">
<contribuyente>
<cuitContribuyente>$this->cuitConsulta</cuitContribuyente> 
</contribuyente>
</contribuyentes>
</CONSULTA-ALICUOTA>
XML;
		
		$clave = md5($xmlStr);
		$this->file = $this->rootDir.'/DFEServicioConsulta_'.$clave.'.xml';

		if( file_exists($this->file) )
			unlink($this->file);
		file_put_contents($this->file,$xmlStr);

	}

	private function request()
	{

		$this->doXml();



		$objCurlFileUploader = new CurlFileUploader($this->file,
				$this->urlArba,
				"file", 
				Array('user'=>$this->user,'password'=>$this->pass));

		$respuesta=$objCurlFileUploader->UploadFile();



		$this->response = json_decode(json_encode((array)simplexml_load_string($respuesta)),1);
	}

	public function getAlicuotaPercepcion()
	{
		return $this->response['contribuyentes']['contribuyente']['alicuotaPercepcion'];
	}

	public function getAlicuotaRetencion()
	{
		return $this->response['contribuyentes']['contribuyente']['alicuotaRetencion'];	
	}
}