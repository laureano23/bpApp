<?php
namespace Mbp\ArticulosBundle\Clases; 

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class FileUploader
{
    private $targetDir;
	
	/**
     * @Assert\File(
     *     maxSize = "1024k",
     *     mimeTypes = {"application/pdf", "application/x-pdf", "image/jpeg", "image/png"},
     *     mimeTypesMessage = "Los formatos perimitidos son PDF, JPG, PNG"
     * )
     */
	private $_file;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }
	
	public function setFile(UploadedFile $file)
	{
		$this->_file = $file;
	}
	
    public function upload()
    {
    	if(!isset($this->_file)){
    		throw new \Exception("Primero se debe cargar el archivo", 1);
			
    	}
		
        $fileName = md5(uniqid()).'.'.$this->_file->guessExtension();

        $this->_file->move($this->targetDir, $fileName);

        return $fileName;
    }

    public function getTargetDir()
    {
        return $this->targetDir;
    }
	
	public function getFile()
	{
		return $this->_file;
	}
	
	public function deleteFile()
	{
		
	}
}