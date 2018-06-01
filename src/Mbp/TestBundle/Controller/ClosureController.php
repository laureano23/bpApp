<?php

namespace Mbp\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Mbp\TestBundle\Entity\FormulasC;

class ClosureController extends Controller
{

    /**
     * @Route("/closure/borrarNodo")
     */
    public function borrarNodo()
    {

        $em = $this->getDoctrine()->getManager();
        $repo=$em->getRepository('MbpTestBundle:FormulasC');
        $repoArt=$em->getRepository('MbpArticulosBundle:Articulos');

        //buscamos por el codigo/letra
        $res=$repo->findByLetra("G");

        //borramos la letra C de todos los arboles
        foreach ($res as $r) {
            $childrens = $repo->children($r);
            foreach ($childrens as $child) {
                if($child->getLetra() == "I"){
                    $em->remove($child);
                }    
            }
            
        }

        $em->flush();

        //\Doctrine\Common\Util\Debug::dump($res);

        return new Response;
    }


	 /**
     * @Route("/closure/desasociarNodo")
     */
    public function desasociarNodo()
    {
    	$em = $this->getDoctrine()->getManager();
    	$repo=$em->getRepository('MbpTestBundle:FormulasC');
    	$repoArt=$em->getRepository('MbpArticulosBundle:Articulos');

    	$nodo=$repo->findOneByLetra("A");

    	$nodo->setParent(null);
    	$em->persist($nodo);
    	$em->flush();
    }

    /**
     * @Route("/closure/newClosure2")
     */
    public function newClosure2()
    {
    	$em = $this->getDoctrine()->getManager();
    	$repo=$em->getRepository('MbpTestBundle:FormulasC');
    	$repoArt=$em->getRepository('MbpArticulosBundle:Articulos');

    	$node1=new FormulasC;
    	$node1->setCantidad(1);    	
    	$node1->setLetra("G"); 
    	$node1->setidArt($repoArt->findOneByCodigo("31000")); 

		$node2=new FormulasC;
    	$node2->setCantidad(1);    	
    	$node2->setLetra("H"); 
    	$node2->setidArt($repoArt->findOneByCodigo("10031")); 

    	$node3=new FormulasC;
    	$node3->setCantidad(1);    	
    	$node3->setLetra("I");     	
    	$node3->setidArt($repoArt->findOneByCodigo("12031")); 

    	$node1->setParent($node1);
    	$node2->setParent($node1);
    	$node3->setParent($node1);

    	
    	$em->persist($node1);
    	$em->persist($node2);
    	$em->persist($node3);

    	$em->flush();

    	return new Response;
    }

    /**
     * @Route("/closure/newClosure")
     */
    public function newClosure()
    {
    	$em = $this->getDoctrine()->getManager();
    	$repo=$em->getRepository('MbpTestBundle:FormulasC');
    	$repoArt=$em->getRepository('MbpArticulosBundle:Articulos');

    	$node1=new FormulasC;
    	$node1->setCantidad(1);    	
    	$node1->setLetra("A"); 
    	$node1->setidArt($repoArt->findOneByCodigo("15050")); 

		$node2=new FormulasC;
    	$node2->setCantidad(1);    	
    	$node2->setLetra("B"); 
    	$node2->setidArt($repoArt->findOneByCodigo("60511")); 

    	$node3=new FormulasC;
    	$node3->setCantidad(1);    	
    	$node3->setLetra("C");     	
    	$node3->setidArt($repoArt->findOneByCodigo("F60504")); 

    	$node1->setParent($node1);
    	$node2->setParent($node1);
    	$node3->setParent($node1);

    	$node4=new FormulasC;
    	$node4->setCantidad(1);    	
    	$node4->setLetra("D");
    	$node4->setidArt($repoArt->findOneByCodigo("30520"));

    	$node5=new FormulasC;
    	$node5->setCantidad(1);    	
    	$node5->setLetra("E");
    	$node5->setidArt($repoArt->findOneByCodigo("F30520"));

    	$node6=new FormulasC;
    	$node6->setCantidad(1);    	
    	$node6->setLetra("F");
    	$node6->setidArt($repoArt->findOneByCodigo("HORA DE CENTROS"));


    	$node4->setParent($node4);
    	$node5->setParent($node4);
    	$node6->setParent($node4);

    	$em->persist($node1);
    	$em->persist($node2);
    	$em->persist($node3);
    	$em->persist($node4);
    	$em->persist($node5);
    	$em->persist($node6);

    	$em->flush();

    	return new Response;
    }

     /**
     * @Route("/closure/asociarArboles2")
     */
    public function asociarArboles2()
    {
    	$em = $this->getDoctrine()->getManager();
    	$repo=$em->getRepository('MbpTestBundle:FormulasC');
    	$repoArt=$em->getRepository('MbpArticulosBundle:Articulos');

    	$arbolPadre=$repo->findOneByLetra("G");
    	$arbolHijo=$repo->findOneByLetra("A");

    	$childrens=$repo->getChildren($arbolHijo, false, null, 'asc', true);

    	//\Doctrine\Common\Util\Debug::dump($childrens);
    	//exit;
    	$lastAdded=null;
    	$this->copiadoRecursivo($childrens, $arbolPadre, true, $lastAdded, $em);

    	//$arbolHijo->setParent($arbolPadre);

    	//$em->persist($arbolHijo);

    	$em->flush();

    	return new Response;
    }


    private function copiadoRecursivo($arrayNodes, &$parent, $flagLvl, &$lastAdded, $em){
    	foreach ($arrayNodes as $ch) {
    		$nodeN=new FormulasC;
	    	$nodeN->setCantidad(1);    	
	    	$nodeN->setLetra($ch->getLetra());
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

     /**
     * @Route("/closure/asociarArboles")
     */
    public function asociarArboles()
    {
    	$em = $this->getDoctrine()->getManager();
    	$repo=$em->getRepository('MbpTestBundle:FormulasC');
    	$repoArt=$em->getRepository('MbpArticulosBundle:Articulos');

    	$arbolPadre=$repo->findOneByLetra("D");
    	$arbolHijo=$repo->findOneByLetra("A");

    	$arbolHijo->setParent($arbolPadre);

    	$em->persist($arbolHijo);

    	$em->flush();

    	return new Response;
    }


     /**
     * @Route("/closure/getChildren")
     */
    public function getChildren()
    {
    	$em = $this->getDoctrine()->getManager();
    	$repo=$em->getRepository('MbpTestBundle:FormulasC');
    	$repoArt=$em->getRepository('MbpArticulosBundle:Articulos');

    	$node=$repo->findOneBy(['letra' => 'A', 'level' => 1]);

    	$qb=$repo->childrenQueryBuilder($node);
    	$res=$repo->queryFormulaCompleta($node);

		print_r($res);

    	return new Response;
    }

     /**
     * @Route("/closure/borrarTodo")
     */
    public function borrarTodo()
    {
        set_time_limit(0); // 0 = no limits
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


     /**
     * @Route("/closure/getAlltree")
     */
    public function getAlltree()
    {
    	$em = $this->getDoctrine()->getManager();
    	$repo=$em->getRepository('MbpArticulosBundle:FormulasC');
    	$repoArt=$em->getRepository('MbpArticulosBundle:Articulos');

    	$options = array('decorate' => true,
		'nodeDecorator' => function ($node) {
                //\Doctrine\Common\Util\Debug::dump($node);
			return $node['id'];
            });
    	$node=$repo->childrenHierarchy(null, false, $options);
    	

		print_r($node);

    	return new Response;
    }
}


