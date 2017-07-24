<?php

namespace Mbp\ArticulosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Mbp\ArticulosBundle\Entity\ArticulosRepository;
use Mbp\ArticulosBundle\Entity\Articulos;
use Mbp\ArticulosBundle\Clases\FileUploader;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ArticulosController extends Controller
{
    public function articuloslistAction()
    {    	
    	$em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
		
		try{
			$rep = $em->getRepository('MbpArticulosBundle:Articulos');
			$res = $rep->listarArticulos();	
		}catch(\Exception $e){
			throw $e;
		}	
		
		
		return new Response();
    }
	
	public function articuloConCostoAction()
	{
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();
		$idArt = $request->request->get('id');
				
		$repoFormula = $em->getRepository('MbpArticulosBundle:Formulas');
		$repoArt = $em->getRepository('MbpArticulosBundle:Articulos');
		$tipoCambio = $this->get('TipoCambio');
		
		$articulo = $repoArt->find($idArt);
		
		$existeEnFormula = $repoFormula->tieneFormula($articulo);
						
		$costo = 0;
		
		if($existeEnFormula){
			$qb = $repoFormula->costoArticuloConFormula($existeEnFormula);
			
			$costo=0.0;
			foreach ($qb as $rec) {
				if($rec['moneda'] == 1){
					$dolar = $tipoCambio->getTipoCambio();
				}else{
					$dolar = 1;
				}
				$costo = $costo + (float)$rec['costo'] * (float)$rec['cantidad'] * $dolar;
				
			}			
		}else{
			$costo = $articulo->getCosto();
		}
		
		$art = 	$repoArt->detalleArticulo($idArt);
		
		$art[0]['costo'] = $costo;
		
		echo json_encode(array(
			'success' => true,
			'data' => $art
		));
		return new Response();
	}
	
	public function articulosCreateAction()
	{
		$em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
		
		$data = $request->request->get('data');
		
		
				
		$rep = $em->getRepository('MbpArticulosBundle:Articulos');
		$validator = $this->get('validator');
		$res = $rep->crearArticulo($data, $validator);
		
		$response = new Response();
		$response->setContent(json_encode($res));
		
		if(array_key_exists('tipo', $res)){
			$response->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
		}elseif($res['success'] == false){
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
		}else{
			$response->setStatusCode(Response::HTTP_OK);
		}
		
		return $response;
	}
	
	public function articulosdestroyAction()
	{
		$em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
		
		$data = $request->request->get('data');
		
		$rep = $em->getRepository('MbpArticulosBundle:Articulos');
		$res = $rep->deleteArticulo($data);
		
		return new Response();
	}	
	
	public function validartAction()
	{
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();
		
		$cod = $request->request->get('codigo');
		
		
		$rep = $em->getRepository('MbpArticulosBundle:Articulos');
		$res = $rep->validArt($cod);
		
		return new Response();
	}
	
	public function cargarImagenAction()
	{
		$em = $this->getDoctrine()->getManager();
		$response = new Response;
		$rep = $em->getRepository('MbpArticulosBundle:Articulos');
		
		try{
			$request = $this->getRequest();
			$uploaderService = $this->get('uploader');
			
			$file = $request->files->get('rutaPlano');
			$idArt = $data = $request->request->get('idArt');
			
									
			$uploaderService->setFile($file);
			
			$validator = $this->get('validator');
			$violations = $validator->validate($uploaderService);
			
			$errStr="";
			if(count($violations) > 0){
				foreach ($violations as $v) {
					$errStr = $errStr.$v->getMessage()."</br>";
				}				
				throw new \Exception($errStr, 1);
				
			}			
			
			$uploadedFile = $uploaderService->upload($file);
			
			$articulo = $rep->find($idArt);
			//SI EL ARTICULO TENIA IMAGEN CARGADA LA BORRA PARA REEMPLAZARLA CON LA NUEVA
			if($articulo->getNombreImagen() != null){
				$fileAnt = $uploaderService->getTargetDir()."/".$articulo->getNombreImagen();
				unlink($fileAnt);
			}
			$articulo->setNombreImagen($uploadedFile);
			
			$em->persist($articulo);
			$em->flush();			
			
			
			return $response->setContent(
				json_encode(array(
					'success' => true
				))
			);
		}catch(\Exception $e){
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			return $response->setContent(
				json_encode(array(
					'success' => false,
					'msg' => $e->getMessage()
				))
			);
		}	
	}
	
	public function servirImagenArticuloAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$rep = $em->getRepository('MbpArticulosBundle:Articulos');
		
		try{
			$request = $this->getRequest();
			$uploaderService = $this->get('uploader');
			
						
			$fileName = $rep->findOneById($id)->getNombreImagen();
			if($fileName == null){
				throw new \Exception("No existe imagen para este articulo", 1);				
			}
			
			$file = $uploaderService->getTargetDir()."/".$fileName;
			
			$realPath = realpath($this->get('kernel')->getRootDir()."/../web/bundles/mbparticulos/planos");
			
			$mime = mime_content_type($file);
			
			$response = new BinaryFileResponse($file);
	        $response->trustXSendfileTypeHeader();
	        $response->setContentDisposition(
	            ResponseHeaderBag::DISPOSITION_INLINE,
	            $fileName,
	            iconv('UTF-8', 'ASCII//TRANSLIT', $fileName)
	        );
			$response->headers->set('Content-type', $mime);
	
	        return $response;
			
		}catch(\Exception $e){
			$response = new Response;
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			return $response->setContent(
				json_encode(array(
					'success' => false,
					'msg' => $e->getMessage()
				))
			);
		}
	}
}
















