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
	public function updateProvAction()
    {    	
    	$em = $this->getDoctrine()->getManager();				
		$repoArt = $em->getRepository('MbpArticulosBundle:Articulos');
		$repoProv = $em->getRepository('MbpProveedoresBundle:Proveedor');

		$sql="SELECT * 
			FROM `TABLE 113` prov
			where prov.`prov1` != 0 or prov.`prov2` or prov.`prov3`
			";

        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $res= $stmt->fetchAll();



		foreach ($res as $r) {
			$art=$repoArt->findOneByCodigo($r['cod_art']);

			if($art==null) continue;
			$prov1=$repoProv->findOneByCuit($r['prov1']);


			$prov2=$repoProv->findOneByCuit($r['prov2']);
			$prov3=$repoProv->findOneByCuit($r['prov3']);

			$art->setProvSug1($prov1);
			$art->setProvSug2($prov2);
			$art->setProvSug3($prov3);

			$em->persist($art);

		}
		//print_r($res);
		$em->flush();
		$em->clear();
		return new Response();
    }

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

				
		$repoFormula = $em->getRepository('MbpArticulosBundle:FormulasC');
		$repoArt = $em->getRepository('MbpArticulosBundle:Articulos');
		
		$articulo = $repoArt->find($idArt);
		
		$node=$repoFormula->findOneBy(['idArt' => $idArt]);
		$childs=0;
		if($node!=null){
			$childs=$repoFormula->getChildren($node);
		}
		$costo = 0;
		
		if($childs != 0 && count($childs)>0){
			$costo = $repoFormula->costoEstructuraCompleta($idArt);			
		}else{
			$costo = $articulo->getCosto();
		}
		
		$art = 	$repoArt->detalleArticulo($idArt);;
		
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
		$response = new Response();
		$data = $request->request->get('data');
		
		try{			
			$rep = $em->getRepository('MbpArticulosBundle:Articulos');
			$validator = $this->get('validator');
			$res = $rep->crearArticulo($data, $validator);
			
			
			$response->setContent(json_encode($res));
			
			if(array_key_exists('tipo', $res)){
				$response->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
			}elseif($res['success'] == false){
				$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			}else{
				$response->setStatusCode(Response::HTTP_OK);
			}
			
			return $response;
		}catch(\Exception $e){
			
			return $response->setContent(
				json_encode(array('success' => false, 'msg' => $e->getMessage()))
			);
		}
		
	}
	
	public function articulosdestroyAction()
	{
		$em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
		$response = new Response;
		
		try{
			$data = $request->request->get('data');
			
			$rep = $em->getRepository('MbpArticulosBundle:Articulos');
			$res = $rep->deleteArticulo($data);
			
			return $response->setContent(
				json_encode(array('success' => true))
			);	
		}catch(\Exception $e){
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			return $response->setContent(
				json_encode(array('success' => false, 'msg' => $e->getMessage()))
			);
		}
			
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

	public function DBF_verEstructuraAction()
	{
		$dbfService = $this->get('DBF.class');
		$dbfService->initLoad("formula.dbf", "");
		
		$request = $this->getRequest();
		$codigo = $request->request->get('codigo');
		
		$record = $dbfService->GetNextRecord(true);
		$formula = array();		
		while(($record = $dbfService->GetNextRecord(true)) and !empty($record)) {
	        if($record['COD_FOR'] === $codigo){
	        	array_push($formula, $record);
	        }
	    }
		
		$dbfService->initLoad("ARTICULO.DBF");
		
		foreach ($formula as &$art) {
			while(($record = $dbfService->GetNextRecord(true)) and !empty($record)){
				if($art['COD_ART'] == $record['COD_ART']){
					$art['DESC'] = $record['DES_ART'];
				}
			}
			$dbfService->initLoad("ARTICULO.DBF");	
		}
		
		$i=0;
		
		foreach ($formula as &$art) {
			if(!array_key_exists('DESC', $art)){
				
				unset($formula[$i]);
				
			}			
			$i++;
		}
		
		$resp="";
		foreach ($formula as $f) {
			$resp = $resp."<tr><td>".$f['COD_ART']."</td><td>".$f['DESC']."</td></tr>";
		}
		
		$table="<table>".$resp."</table";
		
		$response = new Response;
		return $response->setContent(json_encode(array(
			'success' => true,
			'data' => $table
		)));
	}

	public function actualizarArticulosBdAction()
	{
		ini_set('max_execution_time', 3000); //300 seconds = 5 minutes
		$dbfService = $this->get('DBF.class');
		$dbfService->initLoad("ARTICULO.DBF", "");
		$em = $this->getDoctrine()->getManager();
		$rep = $em->getRepository('MbpArticulosBundle:Articulos');
		
		try{
			$batch = 0;
			while(($record = $dbfService->GetNextRecord(true)) and !empty($record)){
				
				$art = $rep->findOneByCodigo($record['COD_ART']);
				if($art == NULL){
					$newArt = new Articulos;
					$newArt->setCodigo($record['COD_ART']);
					$newArt->setDescripcion($record['DES_ART']);
	
					$em->persist($newArt);
	
					$batch++;
					if($batch == 100){
						$em->flush();
						$batch = 0;	
					}
					
				}
			
			}	
	
			$em->flush();
	
			$response = new Response;
			$resp = array('success' =>true, 'msg' => "Proceso exitoso");
			return $response->setContent(json_encode($resp));	
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
















