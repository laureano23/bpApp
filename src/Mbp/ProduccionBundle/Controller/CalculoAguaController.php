<?php


namespace Mbp\ProduccionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


use Mbp\ProduccionBundle\Clases\Calculo;


class CalculoAguaController extends Controller
{
	/*
	 * RECIBE LOS PARAMETROS DE ENTRADA DE CALCULO, LOS PASA A LA PLANILLA EXCEL Y DEVUELVE LOS RESULTADOS
	 */
	public function calculoAguaAction()
	{
		//RECIBO PARAMETROS
		$request = $this->get('request');
		$tipoFluido = $request->get('tipoFluido');
		$tEntrada = $request->get('tEntrada');
		$tSalida = $request->get('tSalida');
		$tAmbiente = $request->get('tAmbiente');
		$caudal = $request->get('caudal');
		
				
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpProduccionBundle/Resources/doc/').'Intercambiador Placa Barra Aceite-Aire y Agua-Aire.xlsx';
		
		//CONFIGURA CACHE DE LA LIBRERIA
		$cache = $this->get('phpexcel')->phpExcelCache();
		$cacheMethod = $cache::cache_to_phpTemp;
		$cacheSettings = array( 
		    'memoryCacheSize' => '16MB'
		);
		
		$settings = $this->get('phpexcel')->phpExcelSettings();
		$settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
				
		$phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
		$reader = $this->get('phpexcel')->createReader('Excel2007');
		
		try{
			//MODIFICA EL TIEMPO MAXIMO PARA EJECUCION
			ini_set('max_execution_time', 300);
			
			$reader->setReadDataOnly(true);
			$obj = $reader->load($basePath);
			
			//CARGA DE DATOS DE ENTRADA
			$obj->setActiveSheetIndex(0);
			$obj->getActiveSheet()->setCellValue('F18', $tEntrada);
			$obj->getActiveSheet()->setCellValue('F20', $tSalida);
			$obj->getActiveSheet()->setCellValue('F22', $tAmbiente);
			$obj->getActiveSheet()->setCellValue('F24', $caudal);		
			$obj->getActiveSheet()->setCellValue('K7', $tipoFluido);
			
			//DATOS DE SALIDA
			$potenciaSolicitada = $obj->getActiveSheet()->getCell('N13')->getCalculatedValue();
			$equipo =  $obj->getActiveSheet()->getCell('K16')->getCalculatedValue();
			$potenciaDisipada =  $obj->getActiveSheet()->getCell('N18')->getCalculatedValue();
			$tSalidaRtdo =  $obj->getActiveSheet()->getCell('N20')->getCalculatedValue();
			$tReservaPotencia =  $obj->getActiveSheet()->getCell('N22')->getCalculatedValue();
			$perdidaCarga =  $obj->getActiveSheet()->getCell('N24')->getCalculatedValue();
			
			echo json_encode(array(
				'potenciaSolicitada' => $potenciaSolicitada,
				'equipo' => $equipo,
				'potenciaDisipada' => $potenciaDisipada,
				'tSalidaRtdo' => $tSalidaRtdo,
				'tReservaPotencia' => $tReservaPotencia,
				'perdidaCarga' => $perdidaCarga
			));
		}catch(Exception $e){
			$e->getMessage();
		}
        
        return new Response();        
	}

}

























