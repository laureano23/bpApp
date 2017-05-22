<?php

namespace Mbp\PersonalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Mbp\PersonalBundle\Clases\LiquidacionEnLote\LiquidacionEnLote;

/*
 * CLASE PARA LIQUIDAR SUELDOS EN LOTE, A PARTIR DE LOS DATOS QUE ARROJA EL SISTEMA HORARIO GESVR
 * DESDE UNA PLANILLA EXCEL
 * */
class LiquidacionEnLoteController extends Controller
{	
	/**
     * @Route("/LiquidarEnLote", name="mbp_personal_LiquidarEnLote", options={"expose"=true})
     */    
	public function LiquidarEnLote()
	{
		$request = $this->getRequest();
		$periodo = $request->request->get('periodo');
		$response = new Response;
		$em = $this->getDoctrine()->getManager();
		
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpPersonalBundle/Resources/doc/').'fichadas.xlsx';
		$phpExcelService = $this->get('phpexcel');
		
		
		
		$liquidacion = new LiquidacionEnLote($basePath, $phpExcelService, 2);
		$liquidacion->ValidarPeriodo();
		
		//ERRORES EN LA COLECCION DE EMPLEADOS
		$errores = $liquidacion->getEmpleadosCollection()->getError();
		//ERRORES DEL LOTE DE LIQUIDACION
		$erroresLote = $liquidacion->getErrores();
		if(!empty($errores) || !empty($erroresLote)){
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			return $response->setContent(
				json_encode(
					array(
						'success' => false,
						'msg' => array(
							'errorColeccion' => $errores,
							'errorLote' => $erroresLote
						)
					)
				)
			);			
		}
		
		
		
		
		
		return new Response();
	}
	
	
}