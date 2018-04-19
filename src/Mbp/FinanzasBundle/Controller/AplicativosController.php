<?php

namespace Mbp\FinanzasBundle\Controller;	

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AplicativosController extends Controller
{
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
			
			/* FORMATEAMOS EL CUIT */
			$cuit=str_split($cuit);
			array_splice($cuit, 2, 0, "-");
			array_splice($cuit, 11, 0, "-");
			$cuit=implode($cuit);
			
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
					f.ptoVta,
					f.fcNro,
					CASE WHEN tipo.esNotaCredito = true THEN (f.total - f.iva21 - f.perIIBB)*-1
						ELSE f.total - f.iva21 - f.perIIBB END AS subTotal,
					CASE WHEN tipo.esNotaCredito = true THEN f.perIIBB*-1
						ELSE f.perIIBB END AS perIIBB,					
					'A' AS finLinea")
				->join('f.tipoId', 'tipo')
				->where('f.fecha BETWEEN :desde AND :hasta')
				->andWhere('f.perIIBB != 0')
				->setParameter('desde', $desde)
				->setParameter('hasta', $hasta)
				->getQuery()
				->getArrayResult();
				
			$basePath = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/txt/');
			$file=fopen($basePath."percepciones.txt", "w");
			
			
			
			foreach ($res as $linea) {
				$ptoVta=sprintf("%04d", $linea['ptoVta']);
				$fcNro=sprintf("%08d", $linea['fcNro']);
				$subTotal=0;
				print_r($linea['subTotal']."<br>");
				if($linea['subTotal'] < 0){
					$subTotal=sprintf("%013s", $linea['subTotal']*-1);
					$subTotal=str_split($subTotal);					
					$subTotal[0]="-";					
					$subTotal=implode($subTotal);					
				}else{
					$subTotal=sprintf("%013s", $linea['subTotal']);
				} 
				
				$percepcion=0;
				if($linea['perIIBB'] < 0){
					$percepcion=sprintf("%013s", $linea['perIIBB']*-1);
					$percepcion=str_split($percepcion);					
					$percepcion[0]="-";					
					$percepcion=implode($percepcion);					
				}else{
					$percepcion=sprintf("%013s", $linea['perIIBB']);
				}
				$str = $cuit.$linea['fecha'].$linea['tipoCbte'].$linea['subTipoCbte'].$ptoVta.$fcNro."-".$subTotal."-".$percepcion.$linea['finLinea'].PHP_EOL;	
				fwrite($file, $str);
			}
			
			//print_r($file);
				
			return $response->setContent(json_encode(array('success' => true)));		
		}catch(\Exception $e){
			
			throw $e;
			return $response->setContent(json_encode(array('success' => false, 'msg' => $e->getMessage())));
		}
    } 
}





















