<?php

namespace Mbp\FinanzasBundle\Controller;	

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class AplicativosController extends Controller
{
	public static $codigoRetencion=6;
	public static $codigoPercepcion=7;
	/**
     * @Route("/aplicativos/txt_percepciones", name="mbp_finanzas_txt_percepciones", options={"expose"=true})
     */
    public function txt_percepciones()
    {
    	$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpFinanzasBundle:Facturas');
		$response = new Response;
		$kernel = $this->get('kernel');	
		
		try{
			$desde= new \DateTime("2018-01-01");
			$hasta= new \DateTime("2018-04-30");
			$cuit = $this->container->getParameter('cuit_prod');
			
			$cuit=$cuit = $this->getCuitFormateado();
			
			$res=$repo->createQueryBuilder('f')
				->select("
					f.perIIBB,
					DATE_FORMAT(f.fecha, '%d/%m/%Y') AS fecha,
					CASE WHEN tipo.esFactura = true THEN 'F'
						WHEN tipo.esNotaCredito = true THEN 'C'
						WHEN tipo.esNotaDebito = true THEN 'D'
						ELSE '' END AS tipoCbte,
					CASE WHEN tipo.subTipoA = true THEN 'A'
						WHEN tipo.subTipoB = true THEN 'B'
						ELSE '' AS subTipoCbte,
					LPAD(f.ptoVta, 4, '0') AS ptoVta,
					LPAD(f.fcNro, 8, '0') AS fcNro,					
					CASE WHEN tipo.esNotaCredito = true THEN CONCAT('-', LPAD((f.total - f.iva21 - f.perIIBB), 11, '0'))
						ELSE LPAD((f.total - f.iva21 - f.perIIBB), 12, '0') END AS subTotal,
					CASE WHEN tipo.esNotaCredito = true THEN CONCAT('-', LPAD(f.perIIBB, 10, '0'))
						ELSE LPAD(f.perIIBB, 11, '0') END AS perIIBB,					
					'A' AS finLinea")
				->join('f.tipoId', 'tipo')
				->where('f.fecha BETWEEN :desde AND :hasta')
				->andWhere('f.perIIBB != 0')
				->setParameter('desde', $desde)
				->setParameter('hasta', $hasta)
				->getQuery()
				->getArrayResult();
			
			print_r($res);
			
			$basePath = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/txt/');
			$file=fopen($basePath."percepciones.txt", "w");
			
			foreach ($res as $linea) {				
				$str = $cuit.$linea['fecha'].$linea['tipoCbte'].$linea['subTipoCbte'].$linea['ptoVta'].$linea['fcNro'].$linea['subTotal'].$linea['perIIBB'].$linea['finLinea'].PHP_EOL;	
				fwrite($file, $str);
			}
				
			return $response->setContent(json_encode(array('success' => true)));		
		}catch(\Exception $e){
			
			//throw $e;
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			return $response->setContent(json_encode(array('success' => false, 'msg' => $e->getMessage())));
		}
    } 
    
    
    /**
     * @Route("/aplicativos/txt_retenciones", name="mbp_finanzas_txt_retenciones", options={"expose"=true})
     */
    public function txt_retenciones()
    {
    	
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpProveedoresBundle:TransaccionOPFC');
		$response = new Response;
		$kernel = $this->get('kernel');	
		$req = $this->getRequest();
		$desde=$req->request->get('desde');
		$hasta=$req->request->get('hasta');
		$quincena=$req->request->get('periodo');
		
		try{
			//parametros del form
			$desde= \DateTime::createFromFormat('d/m/Y', $desde);
			$hasta= \DateTime::createFromFormat('d/m/Y', $hasta);
			//
			
			$cuit = $this->getCuitFormateado();
			
			$res=$repo->createQueryBuilder('tr')
				->select("
					DATE_FORMAT(op.emision, '%d/%m/%Y') AS fecha,
					LPAD(fc.sucursal, 4, '0') AS ptoVta,
					LPAD(fc.numFc, 8, '0') AS fcNro,
					LPAD(det.importe, 11, '0') AS retencion,
					'A' AS finLinea")
				->join('tr.ordenPagoImputada', 'op')
				->join('tr.facturaImputada', 'fc')
				->join('op.pagoDetalleId', 'det')
				->join('det.idFormaPago', 'formaPago')
				->where('op.emision BETWEEN :desde AND :hasta')
				->andWhere('formaPago.retencionIIBB = true')
				->setParameter('desde', $desde)
				->setParameter('hasta', $hasta)
				->getQuery()
				->getArrayResult();
			
		
			$nombreArchivo="AR"."-".$this->container->getParameter('cuit_prod')."-".$desde->format("Ym").$quincena."-".self::$codigoPercepcion."-LOTE1";
			
			$basePath = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/txt/');
			$file=fopen($basePath.$nombreArchivo, "w");
			
			foreach ($res as $linea) {				
				$str = $cuit.$linea['fecha'].$linea['ptoVta'].$linea['fcNro'].$linea['retencion'].$linea['finLinea'].PHP_EOL;	
				fwrite($file, $str);
			}
			
			fclose($file);
			
			$nombreZip=$nombreArchivo.md5($nombreArchivo).".zip";
			$zip=new \ZipArchive;
			
			if($zip->open($basePath.$nombreZip, \ZipArchive::CREATE) !== TRUE){
				throw new \Exception("Error al generar ZIP", 1);				
			};
			
			$zip->addFile($basePath.$nombreArchivo, $nombreArchivo.".txt");
			$zip->close();			
							
			return $response->setContent(json_encode(array('success' => true, 'nombreArchivo' => $nombreZip)));		
		}catch(\Exception $e){
			
			throw $e;
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			return $response->setContent(json_encode(array('success' => false, 'msg' => $e->getMessage())));
		}
    }
    
	 /**
     * @Route("/aplicativos/servir_txt_retenciones", name="mbp_finanzas_txt_retenciones_servir", options={"expose"=true})
     */
    public function servir_txt_retenciones()
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

        return $response;
		
    }
    
	
	
    private function getCuitFormateado()
    {
    	/* FORMATEAMOS EL CUIT */
    	$cuit = $this->container->getParameter('cuit_prod');
		$cuit=str_split($cuit);
		array_splice($cuit, 2, 0, "-");
		array_splice($cuit, 11, 0, "-");
		$cuit=implode($cuit);
		
		return $cuit;
    }
}





















