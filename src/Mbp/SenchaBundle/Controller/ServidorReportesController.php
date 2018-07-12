<?php
namespace Mbp\SenchaBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Response;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ServidorReportesController extends Controller
{	
	/**
     * @Route("/servirReportePDF", name="mbp_reporteador_servirPdf", options={"expose"=true})
     */
	public function servirReportePDF(Request $req)
	{	
		$kernel = $this->get('kernel');	
		$nombreReporte=$req->query->get('nombreReporte');
		$ruta=$req->query->get('ruta');
		$basePath = $kernel->locateResource($ruta).$nombreReporte;
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $nombreReporte,
            iconv('UTF-8', 'ASCII//TRANSLIT', $nombreReporte)
        );
		$response->headers->set('Content-type', 'application/pdf');

        return $response;
	}
}
