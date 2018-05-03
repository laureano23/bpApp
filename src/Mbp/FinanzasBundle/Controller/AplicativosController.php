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
	public static $periodoPercepcion=0; //para el periodo mensual el codigo es 0
	/**
     * @Route("/aplicativos/txt_percepciones", name="mbp_finanzas_txt_percepciones", options={"expose"=true})
     */
    public function txt_percepciones()
    {
    	$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpFinanzasBundle:Facturas');
		$response = new Response;
		$kernel = $this->get('kernel');	
		$req = $this->getRequest();
		
		try{
			$desde=$req->request->get('desde');
			$hasta=$req->request->get('hasta');
			$desde= \DateTime::createFromFormat('d/m/Y', $desde);
			$hasta= \DateTime::createFromFormat('d/m/Y', $hasta);
			
			
			$res=$repo->createQueryBuilder('f')
				->select("
					CONCAT(CONCAT(CONCAT(CONCAT(SUBSTRING(cliente.cuit, 1, 2), '-'), SUBSTRING(cliente.cuit, 3, 8)), '-'), SUBSTRING(cliente.cuit, 11, 12)) AS cuit,
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
				->join('f.clienteId', 'cliente')
				->where('f.fecha BETWEEN :desde AND :hasta')
				->andWhere('f.perIIBB != 0')
				->setParameter('desde', $desde)
				->setParameter('hasta', $hasta)
				->getQuery()
				->getArrayResult();
			
			
			$nombreArchivo="AR"."-".$this->container->getParameter('cuit_prod')."-".$desde->format("Ym").self::$periodoPercepcion."-".self::$codigoPercepcion."-LOTE1.txt";
			
			$basePath = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/txt/');
			$file=fopen($basePath.$nombreArchivo, "w");
			
			foreach ($res as $linea) {				
				$str = $linea['cuit'].$linea['fecha'].$linea['tipoCbte'].$linea['subTipoCbte'].$linea['ptoVta'].$linea['fcNro'].$linea['subTotal'].$linea['perIIBB'].$linea['finLinea'].PHP_EOL;	
				fwrite($file, $str);
			}
			fclose($file);
							
			return $response->setContent(json_encode(array('success' => true, 'nombreArchivo' => $nombreArchivo)));
		}catch(\Exception $e){
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
			
			$res=$repo->createQueryBuilder('tr')
				->select("
					CONCAT(CONCAT(CONCAT(CONCAT(SUBSTRING(prov.cuit, 1, 2), '-'), SUBSTRING(prov.cuit, 3, 8)), '-'), SUBSTRING(prov.cuit, 11, 12)) AS cuit,
					DATE_FORMAT(op.emision, '%d/%m/%Y') AS fecha,
					LPAD(fc.sucursal, 4, '0') AS ptoVta,
					LPAD(fc.numFc, 8, '0') AS fcNro,
					LPAD(det.importe, 11, '0') AS retencion,
					'A' AS finLinea")
				->join('tr.ordenPagoImputada', 'op')
				->join('op.proveedorId', 'prov')
				->join('tr.facturaImputada', 'fc')
				->join('op.pagoDetalleId', 'det')
				->join('det.idFormaPago', 'formaPago')
				->where('op.emision BETWEEN :desde AND :hasta')
				->andWhere('formaPago.retencionIIBB = true')
				->setParameter('desde', $desde)
				->setParameter('hasta', $hasta)
				->getQuery()
				->getArrayResult();
			
		
			$nombreArchivo="AR"."-".$this->container->getParameter('cuit_prod')."-".$desde->format("Ym").$quincena."-".self::$codigoPercepcion."-LOTE1.txt";
			
			$basePath = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/txt/');
			$file=fopen($basePath.$nombreArchivo, "w");
			
			foreach ($res as $linea) {				
				$str = $linea['cuit'].$linea['fecha'].$linea['ptoVta'].$linea['fcNro'].$linea['retencion'].$linea['finLinea'].PHP_EOL;	
				fwrite($file, $str);
			}
			
					
			fclose($file);
							
			return $response->setContent(json_encode(array('success' => true, 'nombreArchivo' => $nombreArchivo)));		
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





















