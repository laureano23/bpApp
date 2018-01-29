<?php
namespace Mbp\ProduccionBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Mbp\ProduccionBundle\Entity\CalculoRad;

use Mbp\ProduccionBundle\Clases\Calculo;

class ReportesController extends Controller
{
	 /**
     * @Route("/produccion/reporteCalculo", name="mbp_produccion_reportecalculo", options={"expose"=true})
     */
	public function reporteCalculoAction() 
	{
		$repo = $this->get('reporteador');
		$calc = $this->get('mbp.calculo');
		$kernel = $this->get('kernel');
		
		/*
		 * Recibo parametros del request 
		 */
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$idArt = $req->request->get('codigo'); 
		$ot = (int)$req->request->get('ot');
		$otCant = (int)$req->request->get('cantidad');
		$cliente = $req->request->get('cliente');
		
		/*
		 * Consulta BD
		 */
		try{
			/*
			 * Busco el objeto en la BD para pasarlo al calculo
			 */			
			$rep = $em->getRepository('MbpProduccionBundle:CalculoRad');
			
			$obj = $rep->findOneByCod($idArt);
			
			/*
             * Paso el obj al calculo
             */
			
			$calc->setTipo($obj->gettipo());
			$calc->setapoyoTapas($obj->getapoyoTapas());
			$calc->setprof($obj->getprof());
			$calc->setanchoPanel($obj->getancho());
			$calc->setpisosManual($obj->getpisosManual());
			$calc->setperfilIntermedio($obj->getperfilInt());
			$calc->setpisosManual7($obj->getpisosManual7());
			$calc->setchapaPisoAdicional($obj->getchapaPiso());
			$calc->setcantChapaPisoAdicional($obj->getcantAdic());
			$calc->setmaxAlt($obj->getmaxAlt());
			$calc->setcantPaneles($obj->getcantPaneles());
			$calc->setabiertoCerrado($obj->getaletaTipo());
			$calc->setaletaVenA($obj->getaletaVenA());
			$calc->setaletaFluA($obj->getaletaFluA());
			
			/*
			 * Configuro reporte
			 */
			$jru = $repo->jru();
			/*
			 * Ruta archivo Jasper
			 */				
			$ruta = $kernel->locateResource('@MbpProduccionBundle/Reportes/calculoRad1.jasper');
			/*
			 * Ruta de destino del PDF
			 */
			$destino = $kernel->locateResource('@MbpProduccionBundle/Resources/public/pdf/').'calculoRad1.pdf';
			/*
			 * Calculos del panel
			 */
			$getCantidadChapaIntermedia = $calc->getCantidadChapaIntermedia();
			$getCantidadChapaPiso = $calc->getCantidadChapaPiso();
			$getCantidadChapaAdicional = $calc->getCantidadChapaAdicional();
			$getCantidadPerfilVen = $calc->getCantidadPerfilVen();
			$getCantidadPerfilFlu = $calc->getCantidadPerfilFlu();
			$getCantidadPerfilAdicional7 = $calc->getCantidadPerfilAdicional7();
	        $getCantidadPerfilFlu = $calc->getCantidadPerfilFlu();
	        $getPisosAletaVen = $calc->getPisosAletaVen();
			$getPisosAletaAdicional7 = $calc->getPisosAletaAdicional7();
			$getCantidadAletaFlu = $calc->getCantidadAletaFlu();
			$getCantidadRecorteFlu = $calc->getCantidadRecorteFlu();
			/*
			 * Busco el codigo del articulo			 * 
			 */		
			$artRepo = $em->getRepository('MbpArticulosBundle:Articulos');
			$articulo = $artRepo->findOneById($idArt);
			
			//Parametros HashMap
			$param = $repo->getJava('java.util.HashMap');
			$param->put('codigo',$articulo->getCodigo());
			$param->put('tipo', $calc->getTipoPanel());
			$param->put('ot', $ot);
			$param->put('otCant', $otCant);
			$param->put('cliente', $cliente);
	        
			$param->put('chIntEsp', $calc->getEspesorChapaIntermedia());
			$param->put('chIntC', $getCantidadChapaIntermedia['totalChapaIntermedia']);
			$param->put('chIntL', $calc->getLargoChapaIntermedia());
			$param->put('chIntA', $calc->getAnchoChapaIntermedia());
	        
			$param->put('chPisoE', $calc->getEspesorChapaPiso());
			$param->put('chPisoC', $getCantidadChapaPiso['totalChapasPiso']);
			$param->put('chPisoL', $calc->getLargoChapaPiso());
			$param->put('chPisoA', $calc->getAnchoChapaPiso());
	        
			$param->put('chAdE', $calc->getEspesorChapaAdicional());
			$param->put('chAdC', $getCantidadChapaAdicional['totalChapaPisoAd']);
			$param->put('chAdL', $calc->getLargoChapaAdicional());
			$param->put('chAdA', $calc->getAnchoChapaAdicional());
	        
			$param->put('perfilLadoVen', $calc->getTipoPerfilVen());
			$param->put('chPer1C', $getCantidadPerfilVen['cantidadTotalPerfilVen']);
			$param->put('chPer1L', $calc->getLargoPerfilVen());
	        
			$param->put('perfilAd', $calc->getTipoPerfilAdicional7());		
			$param->put('chPer2C', $getCantidadPerfilAdicional7['totalPerfilAd']);
			$param->put('chPer2L', $calc->getLargoPerfilAdicional7());
	        
			$param->put('perfilLadoFluido', $calc->getTipoPerfilFlu());        
			$param->put('chPer3C', $getCantidadPerfilFlu['totalPerfilesFluido']);
			$param->put('chPer3L', $calc->getLargoPerfilFlu());
			       
	        
	        $param->put('aletaVentilador', $calc->getTipoAletaVen());
			$param->put('aleta10E', $calc->getEspesorAletaVen());
			$param->put('aleta10C', $getPisosAletaVen['pisosAletaVenTotal']);
			$param->put('aleta10A', $calc->getAnchoAletaVen());
			$param->put('aleta10L', $calc->getLargoAletaVen());
			$param->put('aletaTipo', $calc->getTipoDeAleta()); 
	        
			$param->put('aleta7E', $calc->getEspesorPisoAdcional7());
			$param->put('aleta7C', $getPisosAletaAdicional7['totalAletaAdicional']);
			$param->put('aleta7L', $calc->getLargoPisoAdicional7());
			$param->put('aleta7A', $calc->getAnchoPisoAdicional7());
			
			$param->put('aletaFluido', $calc->getTipoAletaFlu());
			$param->put('aceiteE', $calc->getEspesorAletaFlu());
			$param->put('aceiteC', $getCantidadAletaFlu['cantidadTotalAletaFlu']);
			$param->put('aceiteL', $calc->getLargoAletaFlu());
			$param->put('aceiteA', $calc->getAnchoAletaFlu());
			
			$param->put('recorteFluido', $calc->getTipoRecorteFlu());
			$param->put('recorteE', $calc->getEspesorRecorteFlu());
			$param->put('recorteC', $getCantidadRecorteFlu['totalRecorteFlu']);
			$param->put('recorteL', $calc->getLargoRecorteFlu());
			$param->put('recorteA', $calc->getAnchoRecorteFlu());
			
			
			
			//Exportamos el reporte
			$jru->runReportEmpty($ruta, $destino, $param);
			
			
			echo json_encode(
					array(
						'success'=> true,
						'reporte' => $destino,		
					)
				);
				
			return new Response();	
		}catch(\Doctrine\ORM\ORMException $e){
				$this->get('logger')->error($e->getMessage());
		}
	}


	/**
     * @Route("/produccion/reporteCalculoPdf", name="mbp_produccion_reporteArmadoPanelPDF", options={"expose"=true})
     */	
	public function reporteCalculoPdfAction()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpProduccionBundle/Resources/public/pdf/').'calculoRad1.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'calculoRad1.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-type', 'application/pdf');

        return $response;
	}
	
	/**
     * @Route("/produccion/reporteArmado", name="mbp_produccion_reporteArmadoPanel", options={"expose"=true})
     */	
	public function reporteArmadoAction()
	{
		//Llamada a los servicios
		$calc = $this->get('mbp.calculo');
		$repo = $this->get('reporteador');
		$kernel = $this->get('kernel');
		
		/*
		 * Recibo parametros del request 
		 */
		$em = $this->getDoctrine()->getManager();
		$req = $this->getRequest();
		$idArt = $req->request->get('codigo'); 
		$ot = (int)$req->request->get('ot');
		$otCant = (int)$req->request->get('cantidad');
		$cliente = $req->request->get('cliente');
        
        //Busco el articulo
        $repArt = $em->getRepository('MbpArticulosBundle:Articulos');
        $art = $repArt->findOneById($idArt);
        
		
		/*
		 * Consulta BD
		 */
		try{
			/*
			 * Busco el objeto en la BD para pasarlo al calculo
			 */			
			$rep = $em->getRepository('MbpProduccionBundle:CalculoRad');
			
			$obj = $rep->findOneByCod($idArt);
            
            
			/*
             * Paso el obj al calculo
             */
			
			$calc->setTipo($obj->gettipo());
			$calc->setapoyoTapas($obj->getapoyoTapas());
			$calc->setprof($obj->getprof());
			$calc->setanchoPanel($obj->getancho());
			$calc->setpisosManual($obj->getpisosManual());
			$calc->setperfilIntermedio($obj->getperfilInt());
			$calc->setpisosManual7($obj->getpisosManual7());
			$calc->setchapaPisoAdicional($obj->getchapaPiso());
			$calc->setcantChapaPisoAdicional($obj->getcantAdic());
			$calc->setmaxAlt($obj->getmaxAlt());
			$calc->setcantPaneles($obj->getcantPaneles());
			$calc->setabiertoCerrado($obj->getaletaTipo());
			$calc->setaletaVenA($obj->getaletaVenA());
			$calc->setaletaFluA($obj->getaletaFluA());
			
			//Nuevo objeto de repo
			$jru = $repo->jru();
			
			//Ruta del .jasper
			$ruta = $kernel->locateResource('@MbpProduccionBundle/Reportes/ArmadoPanel.jasper');
			
			//Ruta de destino
			$destino = $kernel->locateResource('@MbpProduccionBundle/Resources/public/pdf/').'ArmadoPanel.pdf';
			
			//Guardo en variables parametros con Arrays
			$ancho = $calc->getAnchoPaneles();
			$cantPaneles = $calc->getPisosAletaVen();
			$getCantidadChapaAdicional = $calc->getCantidadChapaAdicional();
			$getCantidadChapaPiso = $calc->getCantidadChapaPiso();
			$getPisosAletaVen = $calc->getPisosAletaVen();
			$getCantidadPerfilVen = $calc->getCantidadPerfilVen();
			$getPisosAletaAdicional7 = $calc->getPisosAletaAdicional7();
			$getCantidadPerfilAdicional7 = $calc->getCantidadPerfilAdicional7();
			$getCantidadAletaFlu = $calc->getCantidadAletaFlu();
			$getCantidadRecorteFlu = $calc->getCantidadRecorteFlu();
			$getCantidadPerfilFlu = $calc->getCantidadPerfilFlu();
			$getCantidadChapaIntermedia = $calc->getCantidadChapaIntermedia();
			
			
			
			//Parametros de reportes
			$param = $repo->getJava('java.util.HashMap');
			//Imagen del logo
			$param->put('imageDir', $kernel->getRootDir().'/../web/bundles/mbpsencha/images/');
			//Encabezado
			$param->put('codigo', $art->getCodigo());
			$param->put('tipo', $calc->getTipoPanel());
			$param->put('apoyoTapas', $obj->getapoyoTapas());
			$param->put('prof', $obj->getprof());
			$param->put('ot', $ot);
			$param->put('otCantidad', $otCant);
			$param->put('cliente', $cliente);
			
			$param->put('anchoP1', $ancho['anchoP1']);
			$param->put('anchoP2', $ancho['anchoP2']);
			$param->put('anchoP3', $ancho['anchoP3']);
			$param->put('anchoP4', $ancho['anchoP4']);
			$param->put('pisosP1', $cantPaneles['pisosAletaVenP1']);
			$param->put('pisosP2', $cantPaneles['pisosAletaVenP2']);
			$param->put('pisosP3', $cantPaneles['pisosAletaVenP3']);
			$param->put('pisosP4', $cantPaneles['pisosAletaVenP4']);
			$param->put('chapaAdE', $calc->getEspesorChapaAdicional());
			$param->put('chapaPisoE', $calc->getEspesorChapaPiso());
			$param->put('chapaIntE', $calc->getEspesorChapaIntermedia());
			//Cantidad chapa Adicional
			$param->put('chapaAdP1', $getCantidadChapaAdicional['cantP1PisoAd']);
			$param->put('chapaAdP2', $getCantidadChapaAdicional['cantP2PisoAd']);
			$param->put('chapaAdP3', $getCantidadChapaAdicional['cantP3PisoAd']);
			$param->put('chapaAdP4', $getCantidadChapaAdicional['cantP4PisoAd']);
			//Cantidad chapa de piso
			$param->put('chapaPisoP1', $getCantidadChapaPiso['cantP1Piso'] > 0 ? 1 : 0);
			$param->put('chapaPisoP2', $getCantidadChapaPiso['cantP2Piso'] > 0 ? 1 : 0);
			$param->put('chapaPisoP3', $getCantidadChapaPiso['cantP3Piso'] > 0 ? 1 : 0);
			$param->put('chapaPisoP4', $getCantidadChapaAdicional['totalChapaPisoAd'] >= 1 ? 0 : 1);
			//Cantidad chapa intermedia
			$param->put('chapaInterTapaInfP1', $intInfP1 = ($getCantidadChapaAdicional['cantP1PisoAd'] + $getCantidadChapaPiso['cantP1Piso']) > 0 ? 1 : 0);
			$param->put('chapaInterTapaInfP2', $intInfP2 = ($getCantidadChapaAdicional['cantP2PisoAd'] + $getCantidadChapaPiso['cantP2Piso']) > 0 ? 1 : 0);
			$param->put('chapaInterTapaInfP3', $intInfP3 = ($getCantidadChapaAdicional['cantP3PisoAd'] + $getCantidadChapaPiso['cantP3Piso']) > 0 ? 1 : 0);
			$param->put('chapaInterTapaInfP4', $intInfP4 = ($getCantidadChapaAdicional['cantP4PisoAd'] + $getCantidadChapaPiso['cantP4Piso']) > 0 ? 1 : 0);
			//Cantidad aleta de ventilador
			$param->put('aletaVenP1', $getPisosAletaVen['pisosAletaVenP1']);
			$param->put('aletaVenP2', $getPisosAletaVen['pisosAletaVenP2']);
			$param->put('aletaVenP3', $getPisosAletaVen['pisosAletaVenP3']);
			$param->put('aletaVenP4', $getPisosAletaVen['pisosAletaVenP4']);
			//Cantidad de perfil ventilador
			$param->put('perfilVenP1', $getCantidadPerfilVen['cantPerfilVenP1']);
			$param->put('perfilVenP2', $getCantidadPerfilVen['cantPerfilVenP2']);
			$param->put('perfilVenP3', $getCantidadPerfilVen['cantPerfilVenP3']);
			$param->put('perfilVenP4', $getCantidadPerfilVen['cantPerfilVenP4']);
			//Cantidad de aleta de 7mm adicional
			$param->put('aletaAdP1', $getPisosAletaAdicional7['pisosP1Aleta7']);
			$param->put('aletaAdP2', $getPisosAletaAdicional7['pisosP2Aleta7']);
			$param->put('aletaAdP3', $getPisosAletaAdicional7['pisosP3Aleta7']);
			$param->put('aletaAdP4', $getPisosAletaAdicional7['pisosP4Aleta7']);
			//Cantidad de perfil de 7mm adicional
			$param->put('perfilAdP1', $getCantidadPerfilAdicional7['cantP1PerfilAd7']);
			$param->put('perfilAdP2', $getCantidadPerfilAdicional7['cantP2PerfilAd7']);
			$param->put('perfilAdP3', $getCantidadPerfilAdicional7['cantP3PerfilAd7']);
			$param->put('perfilAdP4', $getCantidadPerfilAdicional7['cantP4PerfilAd7']);
			//Cantidad de aleta fluido
			$param->put('aletaFluP1', $getCantidadAletaFlu['cantidadP1AletaFlu']);
			$param->put('aletaFluP2', $getCantidadAletaFlu['cantidadP2AletaFlu']);
			$param->put('aletaFluP3', $getCantidadAletaFlu['cantidadP3AletaFlu']);
			$param->put('aletaFluP4', $getCantidadAletaFlu['cantidadP4AletaFlu']);
			//Cantidad de recorte fluido
			$param->put('recorteFluP1', $getCantidadRecorteFlu['cantP1RecorteFlu']);
			$param->put('recorteFluP2', $getCantidadRecorteFlu['cantP2RecorteFlu']);
			$param->put('recorteFluP3', $getCantidadRecorteFlu['cantP3RecorteFlu']);
			$param->put('recorteFluP4', $getCantidadRecorteFlu['cantP4RecorteFlu']);
			//Cantidad de perfil fluido
			$param->put('perfilFluP1', $getCantidadPerfilFlu['perfilesP1Fluido']);
			$param->put('perfilFluP2', $getCantidadPerfilFlu['perfilesP2Fluido']);
			$param->put('perfilFluP3', $getCantidadPerfilFlu['perfilesP3Fluido']);
			$param->put('perfilFluP4', $getCantidadPerfilFlu['perfilesP4Fluido']);
			//Perfil Piso
			$param->put('perfilPiso', $calc->getPerfilIntStatus() == 1 ? 3 : 2);
			//Cantidad chapa intermedia tapa superior
			$param->put('chapaIntTapaSupP1', $intSupP1 = ($getCantidadChapaAdicional['cantP1PisoAd'] + $getCantidadChapaPiso['cantP1Piso']) > 0 ? 1 : 0);
			$param->put('chapaIntTapaSupP2', $intSupP2 = ($getCantidadChapaAdicional['cantP2PisoAd'] + $getCantidadChapaPiso['cantP2Piso']) > 0 ? 1 : 0);
			$param->put('chapaIntTapaSupP3', $intSupP3 = ($getCantidadChapaAdicional['cantP3PisoAd'] + $getCantidadChapaPiso['cantP3Piso']) > 0 ? 1 : 0);
			$param->put('chapaIntTapaSupP4', $intSupP4 = ($getCantidadChapaAdicional['cantP4PisoAd'] + $getCantidadChapaPiso['cantP4Piso']) > 0 ? 1 : 0);
			//Cantidad de chapa intermedia por panel
			$param->put('chapaIntP1', $getCantidadChapaIntermedia['cantidadP1ChapaIntermedia'] - $intInfP1 - $intSupP1);
			$param->put('chapaIntP2', $getCantidadChapaIntermedia['cantidadP2ChapaIntermedia'] - $intInfP2 - $intSupP2);
			$param->put('chapaIntP3', $getCantidadChapaIntermedia['cantidadP3ChapaIntermedia'] - $intInfP3 - $intSupP3);
			$param->put('chapaIntP4', $getCantidadChapaIntermedia['cantidadP4ChapaIntermedia'] - $intInfP4 - $intSupP4);
			//Cantidad chapa de piso en tapa superior
			$param->put('chapaPisoSupP1', $getCantidadChapaAdicional['totalChapaPisoAd'] == 2 ? 0 : 1);
			$param->put('chapaPisoSupP2', $getCantidadChapaPiso['cantP2Piso'] > 0 ? 1 : 0);
			$param->put('chapaPisoSupP3', $getCantidadChapaPiso['cantP3Piso'] > 0 ? 1 : 0);
			$param->put('chapaPisoSupP4', $getCantidadChapaPiso['cantP4Piso'] > 0 ? 1 : 0);
			//Cantidad chapa de piso adicional en tapa superior
			$param->put('chapaAdTapaSupP1', $getCantidadChapaAdicional['totalChapaPisoAd'] <= 1 ? 0 : 1);
			$param->put('chapaAdTapaSupP2', 0);
			$param->put('chapaAdTapaSupP3', 0);
			$param->put('chapaAdTapaSupP4', 0);
			
			//Exportamos el reporte
			$jru->runReportEmpty($ruta, $destino, $param);
			
			echo json_encode(
					array(
						'success'=> true,
						'reporte' => $destino,						
					)
				);
			
			$response = new Response(); 
			$response->headers->set('Content-type', 'application/pdf');
			return $response;
		}catch(\Doctrine\ORM\ORMException $e){
				$this->get('logger')->error($e->getMessage());
		}		
	}
	
	/**
     * @Route("/produccion/reporteArmadoPdf", name="mbp_produccion_reporteArmadoPanelPDF", options={"expose"=true})
     */	
	public function reporteArmadoPdfAction()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpProduccionBundle/Resources/public/pdf/').'ArmadoPanel.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$response->headers->set('Content-type', 'application/pdf');
		$filename = 'ArmadoPanel.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		
		return $response;
	}
	
	/**
     * @Route("/produccion/generarOt", name="mbp_produccion_generarOt", options={"expose"=true})
     */
    public function generarOt()
    {
        /*
		 * PARAMETROS
		 */
		$ot = $this->getRequest()->request->get('ot');
		$response = new Response;

		try {
			$reporteador = $this->get('reporteador');
			$kernel = $this->get('kernel');
			
			
			/*
			 * Configuro reporte
			 */
			$jru = $reporteador->jru();
			
			/*
			 * Ruta archivo Jasper
			 */				
					
			$ruta = $kernel->locateResource('@MbpProduccionBundle/Reportes/OT.jrxml');
			$rutaLogo = $reporteador->getRutaLogo($kernel);
			
			/*
			 * Ruta de destino del PDF
			 */
			$destino = $kernel->locateResource('@MbpProduccionBundle/Resources/public/pdf/').'OT.pdf';
			
			
			//Parametros HashMap
			$param = $reporteador->getJava('java.util.HashMap');
			$param->put('ot', $ot);
			$param->put('rutaLogo', $rutaLogo);
			
			$conn = $reporteador->getJdbc();
			
			$sql = "
				SELECT
				     Ot.`ot` AS Ot_ot,
				     Ot.`cantidad` AS Ot_cantidad,
				     Ot.`fechaProg` AS Ot_fechaProg,
				     Ot.`observaciones` AS Ot_observaciones,
				     Ot.`anulada` AS Ot_anulada,
				     Ot.`idCodigo` AS Ot_idCodigo,
				     Ot.`idUsuario` AS Ot_idUsuario,
				     Ot.`fechaEmision` AS Ot_fechaEmision,
				     Ot.`aprobado` AS Ot_aprobado,
				     Ot.`rechazado` AS Ot_rechazado,
				     articulos.`codigo` AS articulos_codigo,
				     articulos.`descripcion` AS articulos_descripcion,
				     articulos.`unidad` AS articulos_unidad,
				     articulos.`presentacion` AS articulos_presentacion,
				     articulos.`idArticulos` AS articulos_idArticulos,
				     articulos.`stock` AS articulos_stock,
				     Sectores.`id` AS Sectores_id,
				     Sectores.`descripcion` AS Sectores_descripcion,
				     Ot.`sectorId` AS Ot_sectorId,
				     Ot.`sectorEmisor` AS Ot_sectorEmisor,
				     Sectores_A.`id` AS Sectores_A_id,
				     Sectores_A.`descripcion` AS Sectores_A_descripcion,
				     cliente.`idCliente` AS cliente_idCliente,
				     cliente.`rsocial` AS cliente_rsocial,
				     users.`id` AS users_id,
				     users.`username` AS users_username,
				     Ot.`clienteId` AS Ot_clienteId,
				     PedidoClientesDetalle.`id` AS PedidoClientesDetalle_id,
				     pedidoId_detalleId.`pedidoId` AS pedidoId_detalleId_pedidoId,
				     pedidoId_detalleId.`detalleId` AS pedidoId_detalleId_detalleId,
				     PedidoClientes.`id` AS PedidoClientes_id,
				     PedidoClientes.`oc` AS PedidoClientes_oc,
				     Ot_Pedidos.`otId` AS Ot_Pedidos_otId,
				     Ot_Pedidos.`pedidoId` AS Ot_Pedidos_pedidoId
				FROM
				     `articulos` articulos INNER JOIN `Ot` Ot ON articulos.`idArticulos` = Ot.`idCodigo`
				     INNER JOIN `Sectores` Sectores ON Ot.`sectorId` = Sectores.`id`
				     INNER JOIN `Sectores` Sectores_A ON Ot.`sectorEmisor` = Sectores_A.`id`
				     LEFT OUTER JOIN `cliente` cliente ON Ot.`clienteId` = cliente.`idCliente`
				     INNER JOIN `users` users ON Ot.`idUsuario` = users.`id`
				     INNER JOIN `Ot_Pedidos` Ot_Pedidos ON Ot.`ot` = Ot_Pedidos.`otId`
				     INNER JOIN `PedidoClientesDetalle` PedidoClientesDetalle ON Ot_Pedidos.`pedidoId` = PedidoClientesDetalle.`id`
				     INNER JOIN `pedidoId_detalleId` pedidoId_detalleId ON PedidoClientesDetalle.`id` = pedidoId_detalleId.`detalleId`
				     INNER JOIN `PedidoClientes` PedidoClientes ON pedidoId_detalleId.`pedidoId` = PedidoClientes.`id`
				     AND users.`id` = PedidoClientes.`usuarioId`
				WHERE
				     Ot.`ot` = $ot
			";
			
			$jru->runPdfFromSql($ruta, $destino, $param, $sql, $conn->getConnection());

			
			return $response->setContent(
					json_encode(
						array(
							'success'=> true,	
						)
					)
				);
		} catch (\Exception $e) {
			$response->setContent(
				json_encode(
					array(
						'success'=> false,	
						'msg' => $e->getMessage()
					)
				)
			);

			return $response->setStatusCode($response::HTTP_INTERNAL_SERVER_ERROR);
		}
    }
    
    /**
     * @Route("/produccion/verOt", name="mbp_produccion_verOt", options={"expose"=true})
     */
	public function verOt()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpProduccionBundle/Resources/public/pdf/').'OT.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'OT.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-Type', 'application/pdf');

        return $response;
	}
	
	/**
     * @Route("/produccion/reporteOTPorSector", name="mbp_produccion_reporteOTPorSector", options={"expose"=true})
     */
    public function reporteOTPorSector()
    {
        /*
		 * PARAMETROS
		 */
		$desde = $this->getRequest()->request->get('desde');
		$hasta = $this->getRequest()->request->get('hasta');
		$response = new Response;

		try {
			$reporteador = $this->get('reporteador');
			$kernel = $this->get('kernel');
			
			
			/*
			 * Configuro reporte
			 */
			$jru = $reporteador->jru();
			
			/*
			 * Ruta archivo Jasper
			 */				
					
			$ruta = $kernel->locateResource('@MbpProduccionBundle/Reportes/OrdenesPorSector.jrxml');
			$rutaLogo = $reporteador->getRutaLogo($kernel);
			
			/*
			 * Ruta de destino del PDF
			 */
			$destino = $kernel->locateResource('@MbpProduccionBundle/Resources/public/pdf/').'OrdenesPorSector.pdf';
			
			
			$desde = \DateTime::createFromFormat('d/m/Y', $desde);
			$hasta = \DateTime::createFromFormat('d/m/Y', $hasta);
			//Parametros HashMap
			$param = $reporteador->getJava('java.util.HashMap');
			$param->put('desde', $desde->format("Y-m-d"));
			$param->put('hasta', $hasta->format("Y-m-d"));
			
			$conn = $reporteador->getJdbc(); 
			
			/*
			 * FECHA OUTPUT FORMATO SQL PARA CONSULTA
			 */		
			$fechaDesdeSql = $desde->format('Y-m-d');
			$fechaHastaSql = $hasta->format('Y-m-d');
			
			$sql = "
				SELECT
				     ot.`ot` AS ot_ot,
				     ot.`cantidad` AS ot_cantidad,
				     ot.`fechaEmision` AS ot_fechaEmision,
				     ot.`fechaProg` AS ot_fechaProg,
				     ot.`observaciones` AS ot_observaciones,
				     ot.`anulada` AS ot_anulada,
				     ot.`idCodigo` AS ot_idCodigo,
				     ot.`sectorEmisor` AS ot_sectorEmisor,
				     ot.`idUsuario` AS ot_idUsuario,
				     ot.`aprobado` AS ot_aprobado,
				     ot.`rechazado` AS ot_rechazado,
				     ot.`sectorId` AS ot_sectorId,
				     ot.`otExterna` AS ot_otExterna,
				     ot.`estado` AS ot_estado,
				     sectores.`id` AS sectores_id,
				     sectores.`costoMin` AS sectores_costoMin,
				     sectores.`descripcion` AS sectores_descripcion,
				     sectores.`piso` AS sectores_piso,
				     sectores.`nave` AS sectores_nave,
				     sectores.`tiempo` AS sectores_tiempo,
				     articulos.`codigo` AS articulos_codigo,
				     articulos.`descripcion` AS articulos_descripcion,
				     articulos.`idArticulos` AS articulos_idArticulos,
				     articulos.`unidad` AS articulos_unidad,
				     articulos.`costo` AS articulos_costo,
				     articulos.`precio` AS articulos_precio,
				     articulos.`moneda` AS articulos_moneda,
				     articulos.`iva` AS articulos_iva,
				     articulos.`familiaId` AS articulos_familiaId,
				     articulos.`subFamiliaId` AS articulos_subFamiliaId,
				     articulos.`monedaPrecio` AS articulos_monedaPrecio,
				     articulos.`stock` AS articulos_stock,
				     articulos.`presentacion` AS articulos_presentacion,
				     articulos.`utilidadPretendida` AS articulos_utilidadPretendida,
				     articulos.`fechaPrecio` AS articulos_fechaPrecio,
				     articulos.`type` AS articulos_type,
				     articulos.`caudal` AS articulos_caudal,
				     articulos.`peso` AS articulos_peso,
				     articulos.`voltage` AS articulos_voltage,
				     articulos.`corriente` AS articulos_corriente,
				     articulos.`potencia` AS articulos_potencia,
				     articulos.`presion` AS articulos_presion,
				     articulos.`nombreImagen` AS articulos_nombreImagen,
				     sectores_A.`id` AS sectores_A_id,
				     sectores_A.`costoMin` AS sectores_A_costoMin,
				     sectores_A.`descripcion` AS sectores_A_descripcion,
				     sectores_A.`piso` AS sectores_A_piso,
				     sectores_A.`nave` AS sectores_A_nave,
				     sectores_A.`tiempo` AS sectores_A_tiempo,
				     sectores_A.`id` AS sectores_A_id,
				     sectores_A.`costoMin` AS sectores_A_costoMin,
				     sectores_A.`descripcion` AS sectores_A_descripcion,
				     sectores_A.`piso` AS sectores_A_piso,
				     sectores_A.`nave` AS sectores_A_nave,
				     sectores_A.`tiempo` AS sectores_A_tiempo
				FROM
				     `Sectores` sectores RIGHT OUTER JOIN `Ot` ot ON sectores.`id` = ot.`sectorEmisor`
				     LEFT OUTER JOIN `articulos` articulos ON ot.`idCodigo` = articulos.`idArticulos`
				     LEFT OUTER JOIN `Sectores` sectores_A ON ot.`sectorId` = sectores_A.`id`
			    WHERE ot.`fechaProg` BETWEEN '$fechaDesdeSql' AND '$fechaHastaSql'
				ORDER BY
				     sectores_A.`id` ASC,
				     ot.`fechaProg` ASC
				
			";
			
			$jru->runPdfFromSql($ruta, $destino, $param, $sql, $conn->getConnection());
			
			return $response->setContent(
					json_encode(
						array(
							'success'=> true,	
						)
					)
				);
		} catch (\Exception $e) {
			throw $e;
			$response->setContent(
				json_encode(
					array(
						'success'=> false,	
						'msg' => $e->getMessage()
					)
				)
			);

			return $response->setStatusCode($response::HTTP_INTERNAL_SERVER_ERROR);
		}
    }
    
    /**
     * @Route("/produccion/verOTPorSector", name="mbp_produccion_verOTPorSector", options={"expose"=true})
     */
	public function verOTPorSector()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpProduccionBundle/Resources/public/pdf/').'OrdenesPorSector.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'OrdenesPorSector.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-Type', 'application/pdf');

        return $response;
	}
	
	/**
     * @Route("/produccion/reporteOTPorSectorFiltrado", name="mbp_produccion_reporteOTPorSectorFiltrado", options={"expose"=true})
     */
    public function reporteOTPorSectorFiltrado()
    {
        /*
		 * PARAMETROS
		 */
		$desde = $this->getRequest()->request->get('desde');
		$hasta = $this->getRequest()->request->get('hasta');
		$sectorId = $this->getRequest()->request->get('sector');
		$response = new Response;

		try {
			$reporteador = $this->get('reporteador');
			$kernel = $this->get('kernel');
			
			
			/*
			 * Configuro reporte
			 */
			$jru = $reporteador->jru();
			
			/*
			 * Ruta archivo Jasper
			 */				
					
			$ruta = $kernel->locateResource('@MbpProduccionBundle/Reportes/OrdenesPorSector2.jrxml');
			$rutaLogo = $reporteador->getRutaLogo($kernel);
			
			/*
			 * Ruta de destino del PDF
			 */
			$destino = $kernel->locateResource('@MbpProduccionBundle/Resources/public/pdf/').'OrdenesPorSector2.pdf';
			
			
			$desde = \DateTime::createFromFormat('d/m/Y', $desde);
			$hasta = \DateTime::createFromFormat('d/m/Y', $hasta);
			//Parametros HashMap
			$param = $reporteador->getJava('java.util.HashMap');
			$param->put('desde', $desde->format("Y-m-d"));
			$param->put('hasta', $hasta->format("Y-m-d"));
			$param->put('sectorId', $sectorId);
			
			$conn = $reporteador->getJdbc(); 
			
			/*
			 * FECHA OUTPUT FORMATO SQL PARA CONSULTA
			 */		
			$fechaDesdeSql = $desde->format('Y-m-d');
			$fechaHastaSql = $hasta->format('Y-m-d');
			
			$sql = "
				SELECT
				     ot.`ot` AS ot_ot,
				     ot.`cantidad` AS ot_cantidad,
				     ot.`fechaEmision` AS ot_fechaEmision,
				     ot.`fechaProg` AS ot_fechaProg,
				     ot.`observaciones` AS ot_observaciones,
				     ot.`anulada` AS ot_anulada,
				     ot.`idCodigo` AS ot_idCodigo,
				     ot.`sectorEmisor` AS ot_sectorEmisor,
				     ot.`idUsuario` AS ot_idUsuario,
				     ot.`aprobado` AS ot_aprobado,
				     ot.`rechazado` AS ot_rechazado,
				     ot.`sectorId` AS ot_sectorId,
				     ot.`otExterna` AS ot_otExterna,
				     ot.`estado` AS ot_estado,
				     sectores.`id` AS sectores_id,
				     sectores.`costoMin` AS sectores_costoMin,
				     sectores.`descripcion` AS sectores_descripcion,
				     sectores.`piso` AS sectores_piso,
				     sectores.`nave` AS sectores_nave,
				     sectores.`tiempo` AS sectores_tiempo,
				     articulos.`codigo` AS articulos_codigo,
				     articulos.`descripcion` AS articulos_descripcion,
				     articulos.`idArticulos` AS articulos_idArticulos,
				     articulos.`unidad` AS articulos_unidad,
				     articulos.`costo` AS articulos_costo,
				     articulos.`precio` AS articulos_precio,
				     articulos.`moneda` AS articulos_moneda,
				     articulos.`iva` AS articulos_iva,
				     articulos.`familiaId` AS articulos_familiaId,
				     articulos.`subFamiliaId` AS articulos_subFamiliaId,
				     articulos.`monedaPrecio` AS articulos_monedaPrecio,
				     articulos.`stock` AS articulos_stock,
				     articulos.`presentacion` AS articulos_presentacion,
				     articulos.`utilidadPretendida` AS articulos_utilidadPretendida,
				     articulos.`fechaPrecio` AS articulos_fechaPrecio,
				     articulos.`type` AS articulos_type,
				     articulos.`caudal` AS articulos_caudal,
				     articulos.`peso` AS articulos_peso,
				     articulos.`voltage` AS articulos_voltage,
				     articulos.`corriente` AS articulos_corriente,
				     articulos.`potencia` AS articulos_potencia,
				     articulos.`presion` AS articulos_presion,
				     articulos.`nombreImagen` AS articulos_nombreImagen,
				     sectores_A.`id` AS sectores_A_id,
				     sectores_A.`costoMin` AS sectores_A_costoMin,
				     sectores_A.`descripcion` AS sectores_A_descripcion,
				     sectores_A.`piso` AS sectores_A_piso,
				     sectores_A.`nave` AS sectores_A_nave,
				     sectores_A.`tiempo` AS sectores_A_tiempo,
				     sectores_A.`id` AS sectores_A_id,
				     sectores_A.`costoMin` AS sectores_A_costoMin,
				     sectores_A.`descripcion` AS sectores_A_descripcion,
				     sectores_A.`piso` AS sectores_A_piso,
				     sectores_A.`nave` AS sectores_A_nave,
				     sectores_A.`tiempo` AS sectores_A_tiempo
				FROM
				     `Sectores` sectores RIGHT OUTER JOIN `Ot` ot ON sectores.`id` = ot.`sectorEmisor`
				     LEFT OUTER JOIN `articulos` articulos ON ot.`idCodigo` = articulos.`idArticulos`
				     LEFT OUTER JOIN `Sectores` sectores_A ON ot.`sectorId` = sectores_A.`id`
				WHERE ot.`fechaProg` BETWEEN '$fechaDesdeSql' AND '$fechaHastaSql'
				AND sectores_A.`id` = $sectorId
				ORDER BY
				     sectores_A.`id` ASC,
				     ot.`fechaProg` ASC
				
			";
			
			$jru->runPdfFromSql($ruta, $destino, $param, $sql, $conn->getConnection());
			
			return $response->setContent(
					json_encode(
						array(
							'success'=> true,	
						)
					)
				);
		} catch (\Exception $e) {
			$response->setContent(
				json_encode(
					array(
						'success'=> false,	
						'msg' => $e->getMessage()
					)
				)
			);

			return $response->setStatusCode($response::HTTP_INTERNAL_SERVER_ERROR);
		}
    }
    
    /**
     * @Route("/produccion/verOTPorSectorFiltrado", name="mbp_produccion_verOTPorSectorFiltrado", options={"expose"=true})
     */
	public function verOTPorSectorFiltrado()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpProduccionBundle/Resources/public/pdf/').'OrdenesPorSector2.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'OrdenesPorSector2.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-Type', 'application/pdf');

        return $response;
	}
	
	/**
     * @Route("/produccion/reporteOTPorClientes", name="mbp_produccion_reporteOTPorClientes", options={"expose"=true})
     */
    public function reporteOTPorClientes()
    {
        /*
		 * PARAMETROS
		 */
		$desde = $this->getRequest()->request->get('desde');
		$hasta = $this->getRequest()->request->get('hasta');
		$clienteId1 = $this->getRequest()->request->get('cliente1');
		$clienteId2 = $this->getRequest()->request->get('cliente2');
		$response = new Response;

		try {
			$reporteador = $this->get('reporteador');
			$kernel = $this->get('kernel');
			
			
			/*
			 * Configuro reporte
			 */
			$jru = $reporteador->jru();
			
			/*
			 * Ruta archivo Jasper
			 */				
					
			$ruta = $kernel->locateResource('@MbpProduccionBundle/Reportes/OrdenesPorCliente.jrxml');
			$rutaLogo = $reporteador->getRutaLogo($kernel);
			
			/*
			 * Ruta de destino del PDF
			 */
			$destino = $kernel->locateResource('@MbpProduccionBundle/Resources/public/pdf/').'reporteOTPorClientes.pdf';
			
			
			$desde = \DateTime::createFromFormat('d/m/Y', $desde);
			$hasta = \DateTime::createFromFormat('d/m/Y', $hasta);
			//Parametros HashMap
			$param = $reporteador->getJava('java.util.HashMap');
			$param->put('desde', $desde->format("Y-m-d"));
			$param->put('hasta', $hasta->format("Y-m-d"));
			$param->put('idCliente', $clienteId1);
			$param->put('idCliente2', $clienteId2);
			
			$conn = $reporteador->getJdbc(); 
			
			/*
			 * FECHA OUTPUT FORMATO SQL PARA CONSULTA
			 */		
			$fechaDesdeSql = $desde->format('Y-m-d');
			$fechaHastaSql = $hasta->format('Y-m-d');
			
			$sql = "
			SELECT
			     ot.`ot` AS ot_ot,
			     ot.`cantidad` AS ot_cantidad,
			     ot.`fechaEmision` AS ot_fechaEmision,
			     ot.`fechaProg` AS ot_fechaProg,
			     ot.`observaciones` AS ot_observaciones,
			     ot.`anulada` AS ot_anulada,
			     ot.`idCodigo` AS ot_idCodigo,
			     ot.`sectorEmisor` AS ot_sectorEmisor,
			     ot.`idUsuario` AS ot_idUsuario,
			     ot.`aprobado` AS ot_aprobado,
			     ot.`rechazado` AS ot_rechazado,
			     ot.`sectorId` AS ot_sectorId,
			     ot.`otExterna` AS ot_otExterna,
			     ot.`estado` AS ot_estado,
			     sectores.`id` AS sectores_id,
			     sectores.`costoMin` AS sectores_costoMin,
			     sectores.`descripcion` AS sectores_descripcion,
			     sectores.`piso` AS sectores_piso,
			     sectores.`nave` AS sectores_nave,
			     sectores.`tiempo` AS sectores_tiempo,
			     articulos.`codigo` AS articulos_codigo,
			     articulos.`descripcion` AS articulos_descripcion,
			     articulos.`idArticulos` AS articulos_idArticulos,
			     articulos.`unidad` AS articulos_unidad,
			     articulos.`costo` AS articulos_costo,
			     articulos.`precio` AS articulos_precio,
			     articulos.`moneda` AS articulos_moneda,
			     articulos.`iva` AS articulos_iva,
			     articulos.`familiaId` AS articulos_familiaId,
			     articulos.`subFamiliaId` AS articulos_subFamiliaId,
			     articulos.`monedaPrecio` AS articulos_monedaPrecio,
			     articulos.`stock` AS articulos_stock,
			     articulos.`presentacion` AS articulos_presentacion,
			     articulos.`utilidadPretendida` AS articulos_utilidadPretendida,
			     articulos.`fechaPrecio` AS articulos_fechaPrecio,
			     articulos.`type` AS articulos_type,
			     articulos.`caudal` AS articulos_caudal,
			     articulos.`peso` AS articulos_peso,
			     articulos.`voltage` AS articulos_voltage,
			     articulos.`corriente` AS articulos_corriente,
			     articulos.`potencia` AS articulos_potencia,
			     articulos.`presion` AS articulos_presion,
			     articulos.`nombreImagen` AS articulos_nombreImagen,
			     ot.`clienteId` AS ot_clienteId,
			     cliente.`idCliente` AS cliente_idCliente,
			     cliente.`rsocial` AS cliente_rsocial
			FROM
			     `Sectores` sectores RIGHT OUTER JOIN `Ot` ot ON sectores.`id` = ot.`sectorEmisor`
			     LEFT OUTER JOIN `articulos` articulos ON ot.`idCodigo` = articulos.`idArticulos`
			     RIGHT OUTER JOIN `cliente` cliente ON ot.`clienteId` = cliente.`idCliente`
			WHERE
			     ot.`fechaProg` BETWEEN '$fechaDesdeSql' AND '$fechaHastaSql' AND
			     ot.`clienteId`  BETWEEN '$clienteId1' AND '$clienteId2'
			ORDER BY
			     cliente.`idCliente` ASC,
			     ot.`fechaProg` ASC     
			";
			
			$jru->runPdfFromSql($ruta, $destino, $param, $sql, $conn->getConnection());
			
			return $response->setContent(
					json_encode(
						array(
							'success'=> true,	
						)
					)
				);
		} catch (\Exception $e) {
			throw $e;
			$response->setContent(
				json_encode(
					array(
						'success'=> false,	
						'msg' => $e->getMessage()
					)
				)
			);

			return $response->setStatusCode($response::HTTP_INTERNAL_SERVER_ERROR);
		}
    }
    
    /**
     * @Route("/produccion/verOTPorCliente", name="mbp_produccion_verOTPorCliente", options={"expose"=true})
     */
	public function verOTPorCliente()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpProduccionBundle/Resources/public/pdf/').'reporteOTPorClientes.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'reporteOTPorClientes.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-Type', 'application/pdf');

        return $response;
	}
	
	/**
     * @Route("/produccion/reporteHistoricoOT", name="mbp_produccion_reporteHistoricoOT", options={"expose"=true})
     */
    public function reporteHistoricoOT()
    {
        /*
		 * PARAMETROS
		 */
		$desde = $this->getRequest()->request->get('desde');
		$hasta = $this->getRequest()->request->get('hasta');
		$codigo1 = $this->getRequest()->request->get('codigo1');
		$codigo2 = $this->getRequest()->request->get('codigo2');
		$response = new Response;

		try {
			$reporteador = $this->get('reporteador');
			$kernel = $this->get('kernel');
			
			
			/*
			 * Configuro reporte
			 */
			$jru = $reporteador->jru();
			
			/*
			 * Ruta archivo Jasper
			 */				
					
			$ruta = $kernel->locateResource('@MbpProduccionBundle/Reportes/HistoricoOt.jrxml');
			$rutaLogo = $reporteador->getRutaLogo($kernel);
			
			/*
			 * Ruta de destino del PDF
			 */
			$destino = $kernel->locateResource('@MbpProduccionBundle/Resources/public/pdf/').'HistoricoOt.pdf';
			
			
			$desde = \DateTime::createFromFormat('d/m/Y', $desde);
			$hasta = \DateTime::createFromFormat('d/m/Y', $hasta);
			//Parametros HashMap
			$param = $reporteador->getJava('java.util.HashMap');
			$param->put('desde', $desde->format("Y-m-d"));
			$param->put('hasta', $hasta->format("Y-m-d"));
			$param->put('codigo1', $codigo1);
			$param->put('codigo2', $codigo2);
			
			$conn = $reporteador->getJdbc(); 
			
			/*
			 * FECHA OUTPUT FORMATO SQL PARA CONSULTA
			 */		
			$fechaDesdeSql = $desde->format('Y-m-d');
			$fechaHastaSql = $hasta->format('Y-m-d');
			
			$sql = "
			SELECT
			     articulos.`codigo` AS articulos_codigo,
			     articulos.`descripcion` AS articulos_descripcion,
			     articulos.`idArticulos` AS articulos_idArticulos,
			     articulos.`unidad` AS articulos_unidad,
			     Ot.`ot` AS Ot_ot,
			     Ot.`cantidad` AS Ot_cantidad,
			     Ot.`fechaEmision` AS Ot_fechaEmision,
			     Ot.`fechaProg` AS Ot_fechaProg,
			     Ot.`idCodigo` AS Ot_idCodigo,
			     Ot.`sectorEmisor` AS Ot_sectorEmisor,
			     Ot.`idUsuario` AS Ot_idUsuario,
			     Ot.`aprobado` AS Ot_aprobado,
			     Ot.`rechazado` AS Ot_rechazado,
			     Ot.`sectorId` AS Ot_sectorId,
			     Ot.`estado` AS Ot_estado,
			     Ot.`clienteId` AS Ot_clienteId,
			     cliente.`idCliente` AS cliente_idCliente,
			     cliente.`denominacion` AS cliente_denominacion,
			     cliente.`rsocial` AS cliente_rsocial,
			     Sectores.`id` AS Sectores_id,
			     Sectores.`costoMin` AS Sectores_costoMin,
			     Sectores.`descripcion` AS Sectores_descripcion,
			     Sectores.`piso` AS Sectores_piso,
			     Sectores.`nave` AS Sectores_nave,
			     Sectores.`tiempo` AS Sectores_tiempo,
			     Ot.`anulada` AS Ot_anulada
			FROM
			     `articulos` articulos INNER JOIN `Ot` Ot ON articulos.`idArticulos` = Ot.`idCodigo`
			     LEFT OUTER JOIN `cliente` cliente ON Ot.`clienteId` = cliente.`idCliente`
			     INNER JOIN `Sectores` Sectores ON Ot.`sectorId` = Sectores.`id`
			WHERE
			     articulos.`codigo` BETWEEN '$codigo1' AND '$codigo2' AND
			     Ot.`fechaEmision` BETWEEN '$fechaDesdeSql' AND '$fechaHastaSql' AND
			     Ot.`anulada` = 0
			ORDER BY
			     articulos.`codigo` ASC
			";
			
			$jru->runPdfFromSql($ruta, $destino, $param, $sql, $conn->getConnection());
			
			return $response->setContent(
					json_encode(
						array(
							'success'=> true,	
						)
					)
				);
		} catch (\Exception $e) {
			$response->setContent(
				json_encode(
					array(
						'success'=> false,	
						'msg' => $e->getMessage()
					)
				)
			);

			return $response->setStatusCode($response::HTTP_INTERNAL_SERVER_ERROR);
		}
    }
    
    /**
     * @Route("/produccion/verHistoricoOt", name="mbp_produccion_verHistoricoOt", options={"expose"=true})
     */
	public function verHistoricoOt()
	{
		$kernel = $this->get('kernel');	
		$basePath = $kernel->locateResource('@MbpProduccionBundle/Resources/public/pdf/').'HistoricoOt.pdf';
		$response = new BinaryFileResponse($basePath);
        $response->trustXSendfileTypeHeader();
		$filename = 'reporteOTPorClientes.pdf';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
		$response->headers->set('Content-Type', 'application/pdf');

        return $response;
	}
}
















