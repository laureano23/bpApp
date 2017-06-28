<?php


namespace Mbp\ProduccionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


use Mbp\ProduccionBundle\Clases\Calculo;


class CalculoAguaController extends Controller
{
	/**
     * @Route("/calculoagua", name="mbp_produccion_calculoagua", options={"expose"=true})
     */
	public function calculoAguaAction()
	{
		//MODIFICA EL TIEMPO MAXIMO PARA EJECUCION
		ini_set('max_execution_time', 3000);
		//RECIBO PARAMETROS
		$request = $this->get('request');
		$tipoFluido = $request->get('tipoFluido');
		$tEntrada = $request->get('tEntrada');
		$tSalida = $request->get('tSalida');
		$tAmbiente = $request->get('tAmbiente');
		$caudal = $request->get('caudal');
		$response = new Response;
		
		try{		
			$kernel = $this->get('kernel');	
			$basePath = $kernel->locateResource('@MbpProduccionBundle/Resources/doc/').'RG-111 Intercambiador Placa Barra Aceite-Aire y Agua - Aire  Rev  05.xlsm';
								
			$reader = $this->get('phpexcel')->createReader('Excel2007');
			$reader->setLoadSheetsOnly(array(
				'Selección',
				'Selección por dimensión',
				'Selección depósito',
				'Matriz de equipos',
				'Cálculo x dimensión',
				'Cálculo depósito',				
				'Parametros globales',
				'Cálculo Ft',
				'Tabla j (Re)',
				'Caracteristicas Dimensionales',
				'Propiedades-Aire',
				'Propiedades-A-AG',
				'Placa Barra'
				));
			
			
			
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
			$potenciaSolicitada = $obj->getActiveSheet()->getCell('N13')->getFormattedValue();
			$equipo =  $obj->getActiveSheet()->getCell('K16')->getCalculatedValue();
			$potenciaDisipada =  $obj->getActiveSheet()->getCell('N18')->getFormattedValue();
			$tSalidaRtdo =  $obj->getActiveSheet()->getCell('N20')->getCalculatedValue();
			$tReservaPotencia =  $obj->getActiveSheet()->getCell('N22')->getCalculatedValue();
			$perdidaCarga =  $obj->getActiveSheet()->getCell('N24')->getCalculatedValue();
			
			/*echo json_encode(array(
				'potenciaSolicitada' => $potenciaSolicitada,
				'equipo' => $equipo,
				'potenciaDisipada' => $potenciaDisipada,
				'tSalidaRtdo' => $tSalidaRtdo,
				'tReservaPotencia' => $tReservaPotencia,
				'perdidaCarga' => $perdidaCarga
			));
			*/
			return $response->setContent(json_encode(array(
				'success' => true, 
			)));
		}catch(Exception $e){
			$response->setContent(json_encode(array(
				'success' => false,
				'msg' => $e->getMessage() 
			)));
			
			return $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
		}    
	}
	
	
}

























