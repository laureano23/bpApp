<?php
namespace Mbp\SenchaBundle\Librerias;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

require_once __DIR__.'/JdbcConnection.php';
require_once __DIR__.'/php-jru.php';
require_once __DIR__.'/Java.inc';


class Reporteador extends Controller
{	
	public $rutaLogo;
	
	public function getRutaLogo($kernel)
	{		
		$this->rutaLogo = $kernel->locateResource('@MbpSenchaBundle/Resources/public/images/logo.jpg');
		return $this->rutaLogo;
	} 
	
	public function jru()
	{
		$jru = new \JRU;
		return $jru;
	}
	
	public function getJava()
	{
		$java = new \java('java.util.HashMap');
		return $java;
	}
		
	public function getJdbc($driver, $host, $user, $pass)
	{
		$jdbc = new \JdbcConnection($driver, $host, $user, $pass);
		return $jdbc;
	}
}
