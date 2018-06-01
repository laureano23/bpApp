<?php
namespace Mbp\ArticulosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Mbp\ArticulosBundle\Entity\FormulasRepository;
use Mbp\ArticulosBundle\Entity\FormulasC;
use Mbp\ArticulosBundle\Clases\FormulasClass;

class Formulas2Controller extends Controller
{	

	/*
	 * script para ver articulos pendientes de formular BORRAR LUEGO
	 * */
	public function pendientesDeFormulaAction()
	{
		set_time_limit(0); // 0 = no limits
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpArticulosBundle:FormulasC');
		$repoArt=$em->getRepository('MbpArticulosBundle:Articulos');

		
		$sql="select *, CAST(can_art AS DECIMAL(10,6)) from formulasExportacion fe";
		$stmt = $em->getConnection()->prepare($sql);
    	$stmt->execute();
		$res = $stmt->fetchAll();

		$faltantes=array();
		foreach ($res as $r) {
			$art=$repoArt->findOneByCodigo($r['cod_for']);

			$node=$repo->findOneByIdArt($art);

			if($node==null){
				array_push($faltantes, $r['cod_for']);
			}
		}


		print_r($faltantes);
		return new Response;
	}

	/*
	 * script para importar acomodar costos, BORRAR LUEGO 
	 * */
	public function acomodarCostosAction()
	{
		set_time_limit(0); // 0 = no limits
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpArticulosBundle:FormulasC');
		$repoArt=$em->getRepository('MbpArticulosBundle:Articulos');

		$allNodes=$repo->findAll();

		foreach ($allNodes as $node) {
			$hijos=$repo->childCount($node);

			if($hijos>0){
				$node->getIdArt()->setCosto(0);
				$em->persist($node);
			}
		}

		$em->flush();
	}

	/*
	 * script para importar formulas, BORRAR LUEGO 
	 * */
	public function exportarFormulas2Action()
	{
		set_time_limit(0); // 0 = no limits
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpArticulosBundle:FormulasC');
		$repoArt=$em->getRepository('MbpArticulosBundle:Articulos');

		$sql="select *, CAST(can_art AS DECIMAL(10,6)) from formulasExportacion fe";
		$stmt = $em->getConnection()->prepare($sql);
    	$stmt->execute();
		$res = $stmt->fetchAll();


		$i=0;
		$wasBatched=0;
		$j=0;
		foreach ($res as $r) {

			$padre=false;
			$artPadre=$repoArt->findOneByCodigo($r['cod_for']);
			$artHijo=$repoArt->findOneByCodigo($r['cod_art']);

			if(count($artPadre) == 0 || count($artHijo) == 0){
				continue;
			}

			$padres=array();
			$padre=$repo->findByIdArt($artPadre);
			$padres = $padre;
			if($padre==null){
				$persisted=$em->getUnitOfWork()->getScheduledEntityInsertions();
				foreach ($persisted as $reg) {
					if($reg->getIdArt() == $artPadre){
						array_push($padres, $reg);
					}
				}	
			}

			$padre=new FormulasC;
			$padre->setCantidad(1);
			$padre->setParent($padre);
			$padre->setUnidad("");
			$padre->setIdArt($artPadre);

			array_push($padres, $padre);

			foreach ($padres as $p) {
				//el art padre ya existe en formulas solo creamos el hijo
				$hijo=new FormulasC;
				$hijo->setCantidad($r['can_art']);			
				$hijo->setUnidad($r['uni_for']);
				$hijo->setIdArt($artHijo);
				
				$hijo->setParent($p);
				
				$em->persist($padre);
				$em->persist($hijo);	
			}
			//\Doctrine\Common\Util\Debug::dump($persisted);
			
			//exit;

			if($i==500){
				$em->flush();
        		$em->clear(); // Detaches all objects from Doctrine!	
        		$wasBatched=1;
        		$i=0;
			}
			$i++;
			$j++;
			//if($j==3000) exit;
		}
		$em->flush();
		return new Response;
	}

	/*
	 * RECIBE ID DE ARTICULO Y TRAE LE FORMULA DEL MISMO SOLO EN NIVEL DE PROFUNDIDAD 1
	 * */
	public function formulaslistAction()
	{
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpArticulosBundle:FormulasC');
		$req = $this->getRequest();
		$tipoCambio = $this->get('TipoCambio');
		$tc = (float)$tipoCambio->getTipoCambio();
		$res = $repo->queryFormulaPrimerNivel($req->query->get('art'));
		
		echo json_encode(array(
			'success' => true,
			'data' => $res
		));
		return new Response();
	}

	public function formulasInsertNodoAction(Request $req)
	{
		$em = $this->getDoctrine()->getManager();
		$data = json_decode($req->request->get('data'));
    	$repo = $em->getRepository('MbpArticulosBundle:FormulasC');
    	$repoArt=$em->getRepository('MbpArticulosBundle:Articulos');
    	$artPadreId=$req->request->get('art');
    	$artHijoId=$data->id;

    	$arbolPadre=$repo->findBy(['idArt'=>$artPadreId]);
    	$arbolHijo=$repo->findOneBy(['idArt'=>$data->id, 'level'=>1]);


    	if($arbolPadre==null && $arbolHijo==null){
    		$this->insertPadreNotExistHijoNotExist($artPadreId, $artHijoId, $data->cant);	
    	}

    	if($arbolPadre!=null && $arbolHijo==null){
    		$this->insertPadreExistHijoNotExist($arbolPadre, $artHijoId, $data->cant);	
    	}

    	if($arbolPadre==null && $arbolHijo!=null){
    		$this->insertPadreNotExistHijoExist($artPadreId, $arbolHijo, $data->cant);	
    	}

    	if($arbolPadre!=null && $arbolHijo!=null){
    		$this->insertPadreExistHijoExist($arbolPadre, $arbolHijo);	
    	}

    	$em->flush();
		
		return new Response(
			json_encode(array(
				'success' => true,
				'idFormula' => 1,
			))
		);
	}

	private function insertPadreExistHijoExist($arbolPadre, $arbolHijo){
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpArticulosBundle:FormulasC');
		$childrens=$repo->getChildren($arbolHijo, false, null, 'asc', true);

		foreach ($arbolPadre as $p) {
			$lastAdded=null;
			$this->copiadoRecursivo($childrens, $p, true, $lastAdded, $em);	
		}
	}

	private function insertPadreNotExistHijoExist($idArtPadre, $arbolHijo, $cant){
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpArticulosBundle:FormulasC');
		$repoArt=$em->getRepository('MbpArticulosBundle:Articulos');

		$arbolPadre = new FormulasC;
		$art = $repoArt->find($idArtPadre);
		$arbolPadre->setidArt($art);
		$arbolPadre->setParent($arbolPadre);
		$arbolPadre->setCantidad($cant);
		$arbolPadre->setUnidad($art->getUnidad());
		$em->persist($arbolPadre);
		$childrens=$repo->getChildren($arbolHijo, false, null, 'asc', true);
		
		$lastAdded=null;
		$this->copiadoRecursivo($childrens, $arbolPadre, true, $lastAdded, $em);
	}

	private function insertPadreExistHijoNotExist($arbolPadre, $idArtHijo, $cant){
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpArticulosBundle:FormulasC');
    	$repoArt=$em->getRepository('MbpArticulosBundle:Articulos');
			

		foreach ($arbolPadre as $padre) {
			$arbolHijo = new FormulasC;
			$art = $repoArt->find($idArtHijo);
			$arbolHijo->setidArt($art);
			$arbolHijo->setParent($padre);
			$arbolHijo->setCantidad($cant);
			$arbolHijo->setUnidad($art->getUnidad());
			$em->persist($arbolHijo);	
		}
		
	}

	private function insertPadreNotExistHijoNotExist($idArtPadre, $idArtHijo, $cant){
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpArticulosBundle:FormulasC');
    	$repoArt=$em->getRepository('MbpArticulosBundle:Articulos');
	
		$arbolPadre = new FormulasC;
		$art = $repoArt->find($idArtPadre);
		$arbolPadre->setidArt($art);
		$arbolPadre->setParent($arbolPadre);
		$arbolPadre->setCantidad($cant);
		$arbolPadre->setUnidad($art->getUnidad());
		$em->persist($arbolPadre);
		
		$arbolHijo = new FormulasC;
		$art = $repoArt->find($idArtHijo);
		$arbolHijo->setidArt($art);
		$arbolHijo->setParent($arbolPadre);
		$arbolHijo->setCantidad(1);
		$arbolHijo->setUnidad($art->getUnidad());
		$em->persist($arbolHijo);
		
	}

	private function copiadoRecursivo($arrayNodes, &$parent, $flagLvl, &$lastAdded, $em){
    	foreach ($arrayNodes as $ch) {
    		$nodeN=new FormulasC;
	    	$nodeN->setCantidad($ch->getCantidad());    	
	    	$nodeN->setUnidad($ch->getUnidad());
	    	$nodeN->setidArt($ch->getIdArt());

	    	//el primer nodo siempre se engancha al padre buscado
    		if($flagLvl){
    			$nodeN->setParent($parent);
    			$flagLvl=false;
    			$parent=$nodeN;
    		}else{
	    		if($lastAdded != null && $lastAdded->getLevel()+1 == $ch->getLevel()){
	    			$parent=$lastAdded;
	    		}
	    		$nodeN->setParent($parent);	
	    		$lastAdded=$nodeN;
    		}
    		$em->persist($nodeN);
    	}
    }
	
	public function formulasBorrarNodoAction(Request $req)
	{
		$em = $this->getDoctrine()->getManager();
        $repo=$em->getRepository('MbpArticulosBundle:FormulasC');
        $repoArt=$em->getRepository('MbpArticulosBundle:Articulos');
        $data = json_decode($req->request->get('data'));
        $idPadre=$req->request->get('art');
        
        
        $padres=$repo->findBy(['idArt'=>$idPadre]);

        foreach ($padres as $r) {
            $childrens = $repo->children($r);
            foreach ($childrens as $child) {
                if($child->getIdArt()->getCodigo() == $data->codigo){
                    $em->remove($child);
                }    
            }
            
        }

        $em->flush();

        return new Response;
	}
	
	public function formulasUpdateNodoAction(Request $req)
	{
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpArticulosBundle:FormulasC');
		$data = json_decode($req->request->get('data'));

		$node=$repo->find($data->idFormula);
		$node->setCantidad($data->cant);
		$node->setUnidad($data->unidad);
		$em->persist($node);
		$em->flush();
				
		$resp = new Response(
			json_encode(array(
				'success' => true,
			))
		);
					
		return $resp;		
	}
	
	
	
	/*
	 * RECIBE EL ID DE UN ARTICULO Y DEVULEVE EL ID DE SU FORMULA
	 * */
	public function formulasIdNodoAction(Request $req)
	{	
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('MbpArticulosBundle:FormulasC');
		$repoArt = $em->getRepository('MbpArticulosBundle:Articulos');
		
		$idArticulo = $req->request->get('idArt');
		
		$node=$repo->findOneBy(['idArt'=>$idArticulo]);
		
		$resp;
		if(empty($node)){
			$resp = "";
		}else{
			$resp = $node->getId();
		}
		
		return new Response(
			json_encode(array(
				'success' => true,
				'idNodo' => $resp
			))
		);
	}

	 /**
     * @Route("/closure/borrarTodo")
     */
    public function borrarTodo()
    {
    	$em = $this->getDoctrine()->getManager();
    	$repo=$em->getRepository('MbpArticulosBundle:FormulasC');
    	$repoArt=$em->getRepository('MbpArticulosBundle:Articulos');

    	$nodos=$repo->findAll();

    	foreach ($nodos as $nodo) {
    		$em->remove($nodo);
    	}
    	$em->flush();

    	return new Response;
    }	
}



















