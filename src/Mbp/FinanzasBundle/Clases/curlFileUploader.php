<?php
namespace Mbp\FinanzasBundle\Clases;

class curlFileUploader 
{ 
	public $filePath;
	public $uploadURL;
	public $formFileVariableName;
	public $postParams = array();
	
	public function __construct($filePath, $uploadURL, $formFileVariableName, /* assosiative array */ $otherParams = false) {
		$this->filePath = $filePath;
		$this->uploadURL = $uploadURL;
		if(is_array($otherParams) && $otherParams != false) 
		{
			foreach ($otherParams as $fieldName => $fieldValue) {
				$this->postParams[$fieldName] = $fieldValue;
			}
		}
		$this->postParams[$formFileVariableName] = "@".$filePath;
		
	}
	
	public function UploadFile () 
	{
   		$ch = curl_init();
   		curl_setopt($ch, CURLOPT_URL, $this->uploadURL );
   		curl_setopt($ch, CURLOPT_POST, 1 );
   		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postParams);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 4); //timeout in seconds
   		$postResult = curl_exec($ch);


   		if (curl_errno($ch)) 
		{
			throw new \Exception("El sitio Web de ARBA no responde ".curl_error($ch), 1);				   
			exit();
   		}
		else
		{
   			curl_close($ch);
			return $postResult;
		}
	}
}
?>