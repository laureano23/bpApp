<?php

namespace Mbp\TestBundle\Entity;

/**
 * FormulasTestRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FormulasTestRepository extends \Gedmo\Tree\Entity\Repository\NestedTreeRepository
{
	public function agregarNodo($idArtParaFormular, $idArtHijo, $cantidad){
		$em = $this->getEntityManager();
    	$repo=$em->getRepository('MbpTestBundle:FormulasTest');
    	$repoArt=$em->getRepository('MbpArticulosBundle:Articulos');

    	//buscamos el nodo que corresponde
    	$nodeChildren=$repo->findBy(array('idArt' => $idArtHijo));
    	$nodeParent=$repo->findBy(array('idArt' => $idArtParaFormular));

    	if(empty($nodeChildren && $nodeParent)){
    		$this->ambosNodosVacios($idArtParaFormular, $idArtHijo, $cantidad);
    	}

    	if(empty($nodeChildren) && !empty($nodeParent)){
    		$this->nodoPadreNoVacio($nodeParent, $idArtHijo, $cantidad);
    	}

    	if(!empty($nodeChildren) && empty($nodeParent, $idArtHijo)){
    		$this->nodoHijoNoVacio($nodeChildren, $idArtParaFormular, $cantidad)
    	}

    	\Doctrine\Common\Util\Debug::dump($nodeChildren);
    	exit;
    	$hijosQty=$repo->childCount($nodeChildren, true);

    	$fullTree;
    	$parent;
    	if($hijosQty > 0){ //si el hijo es un arbol debemos copiar el arbol entero
    		$fullTreeQuery= $repo
			    ->createQueryBuilder('node')
			    ->select('node.id, node.cantidad, node.lft, node.lvl, node.rgt, art.codigo, art.id as idArt')
			    ->join('node.idArt', 'art')
			    ->orderBy('node.root, node.lft', 'ASC')
			    ->where('node.root = :root')
			    ->setParameter('root', $nodeChildren->getRoot())
			    ->getQuery();

			$fullTree = $repo->buildTree($fullTreeQuery->getArrayResult());
    		$parentFlag=0;
    		$lastChild;
    		$this->arbolRecursivo($fullTree, $parentFlag, $p, $parent, $repoArt, $em, $lastChild);
    	}else{
    		$parent =new FormulasTest;
			$parent->setCantidad(1);
			$parent->setIdArt($repoArt->find($idArtHijo));
    	}
    			

		$parent2 =new FormulasTest;
		$parent2->setCantidad(1);
		$parent2->setIdArt($repoArt->find($idArtParaFormular));

		$parent->setParent($parent2);

		$em->persist($parent);
		$em->persist($parent2);
		
		$em->flush();		
	}

	//existe al menos un nodo con el codigo padre, no hay nodos con codigo de art. hijo
	private function nodoPadreNoVacio(array $nodeParent, $idArtHijo, $cantidad){
		$em = $this->getEntityManager();
    	$repo=$em->getRepository('MbpTestBundle:FormulasTest');
    	$repoArt=$em->getRepository('MbpArticulosBundle:Articulos');

		$child =new FormulasTest;
		$child->setCantidad($cantidad);
		$child->setIdArt($repoArt->find($idArtHijo));

		foreach ($nodeParent as $node) {
			//el nodo es un arbol?
			if($this->esArbol($node)){
				$arbol=$this->getArbolAsArray($node);
				$lastChild;
				$this->arbolRecursivo($arbol, &$flagParent, &$node=null, &$firstParent=null, $repoArt, $em, &$lastChild)	
			}else{
				
			}			
		}
    	$parent =new FormulasTest;
		$parent->setCantidad($cantidad);
		$parent->setIdArt($repoArt->find($idArtParaFormular));

		$child->setParent($parent);

		$em->persist($parent);
		$em->persist($child);

		$em->flush();
	}

	private function getArbolAsArray($node){
		return $repo->children($node);
	}

	private function esArbol($node){
		$cant=$repo->childCount($node)
		if($cant>0){
			return true;
		}
		return false;
	}

	private function ambosNodosVacios($idArtParaFormular, $idArtHijo, $cantidad){
		$em = $this->getEntityManager();
    	$repo=$em->getRepository('MbpTestBundle:FormulasTest');
    	$repoArt=$em->getRepository('MbpArticulosBundle:Articulos');

    	$parent =new FormulasTest;
		$parent->setCantidad($cantidad);
		$parent->setIdArt($repoArt->find($idArtParaFormular));

		$child =new FormulasTest;
		$child->setCantidad($cantidad);
		$child->setIdArt($repoArt->find($idArtParaFormular));

		$child->setParent($parent);

		$em->persist($parent);
		$em->persist($child);

		$em->flush();
	}

	private function arbolRecursivo($arbol, &$flagParent, &$parent=null, &$firstParent=null, $repoArt, $em, &$lastChild)
    {
    	foreach ($arbol as $nodo) {
			$art=$repoArt->find($nodo['idArt']);
			if($flagParent==0){
				$parent=new FormulasTest;
				
				$parent->setCantidad($nodo['cantidad']);    				
				$parent->setIdArt($art);
				$em->persist($parent);
				$firstParent=$parent;
				$flagParent++;
			}else{
				$child=new FormulasTest;
				//print_r($nodo);
				$child->setCantidad($nodo['cantidad']);
				$child->setIdArt($art);
				$child->setParent($parent);
				$em->persist($child);
				if($nodo['lvl'] != ($parent->getLvl() + 1)){ //si la profundidad coincide quiere decir que saltamos de nivel de profundidad y el padre del siguiente nodo es el nodo actual
					//$parent=$lastChild; //aca hay un error
					$child->setParent($lastChild);

				}
			}
			if(!empty($nodo['__children'])){
    			$this->arbolRecursivo($nodo['__children'], $flagParent, $parent, $firstParent, $repoArt, $em, $child);
			}
    	}
    }
}
