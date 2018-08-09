<?php

namespace Mbp\FinanzasBundle\Controller;	

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class CitiController extends Controller
{
	/**
     * @Route("/aplicativos/citiVentas", name="mbp_finanzas_txt_citiVentas", options={"expose"=true})
     */
    public function citiVentas()
    {
    	$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpFinanzasBundle:Facturas');
		$response = new Response;
		$kernel = $this->get('kernel');	
		$req = $this->getRequest();
		
		try{
			//$desde=$req->request->get('desde');
            //$hasta=$req->request->get('hasta');
            $desde='01/01/2018';
			$hasta='31/12/2018';
			$desde= \DateTime::createFromFormat('d/m/Y', $desde);
			$hasta= \DateTime::createFromFormat('d/m/Y', $hasta);			
			
            $res = $repo->citiVentasCbtes($desde, $hasta);
            $res2 = $repo->citiVentasAlicuota($desde, $hasta);
            
           
			
            $nombreArchivo="CITI-VENTAS-CBTE.txt";
            $nombreArchivo2="CITI-VENTAS-ALICUOTA.txt";
			
			$basePath = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/txt/');
            $file=fopen($basePath.$nombreArchivo, "w");
            $file2=fopen($basePath.$nombreArchivo2, "w");
			
			foreach ($res as $linea) {				
                $str = $linea['fechaEmision'].
                    $linea['tipoCbteAfip'].
                    $linea['ptoVta'].
                    $linea['fcNroDesde'].
                    $linea['fcNroHasta'].
                    $linea['codDocumento'].
                    $linea['numIdentificacion'].
                    $linea['nombreComprador'].
                    $linea['montoTotal'].
                    $linea['montoNoGrabado'].
                    $linea['percepcionNoCategorizados'].
                    $linea['montoExcento'].
                    $linea['pagoCuentaImpNacionales'].
                    $linea['perIIBB'].
                    $linea['impPercepcionImpMunicipales'].
                    $linea['impInternos'].
                    $linea['moneda'].
                    $linea['tipoCambio'].
                    $linea['cantAlicuotasIVA'].
                    $linea['codigoDeOperacion'].
                    $linea['otrosTributos'].
                    $linea['fechaVencimiento'].
                    PHP_EOL;	
				fwrite($file, $str);
			}
            fclose($file);
            
            foreach ($res2 as $linea) {				
                $str = $linea['tipoCbteAfip'].
                    $linea['ptoVta'].
                    $linea['fcNro'].
                    $linea['netoGrabado'].
                    $linea['alicuotaIVACodigoAfip'].
                    $linea['impuestoLiquidado'].
                    PHP_EOL;	
				fwrite($file2, $str);
			}
			fclose($file2);
							
			return $response->setContent(json_encode(array('success' => true, 'nombreArchivo' => $nombreArchivo, 'nombreArchivo2' => $nombreArchivo2)));
		}catch(\Exception $e){
            throw $e;
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			return $response->setContent(json_encode(array('success' => false, 'msg' => $e->getMessage())));
		}
    } 

	 /**
     * @Route("/aplicativos/servir_txt_retenciones_percepciones", name="mbp_finanzas_txt_retenciones_percepciones_servir", options={"expose"=true})
     */
    public function servir_txt_retenciones_percepciones()
    {
    	$response = new Response;
		$req = $this->getRequest();
		$nombreArchivo=$req->query->get('nombreArchivo');
				
		$kernel = $this->get('kernel');
		$basePath = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/txt/');
		
    	$response = new BinaryFileResponse($basePath.$nombreArchivo);
        $response->trustXSendfileTypeHeader();
		$filename = $nombreArchivo;
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename
        );
		$response->headers->set('Content-type', 'application/zip');
		$response->headers->set('Content-length', filesize($basePath.$nombreArchivo));
		
		$response->deleteFileAfterSend(TRUE);

        return $response;
		
    }
}





















