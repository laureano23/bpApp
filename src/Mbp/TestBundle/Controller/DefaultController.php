<?php

namespace Mbp\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Mbp\TestBundle\Entity\Category;
use Mbp\TestBundle\Entity\FormulasTest;

class DefaultController extends Controller
{
    /**
     * @Route("/test/nested")
     */
    public function nested()
    {
    	$em = $this->getDoctrine()->getManager();
    	$repo=$em->getRepository('MbpTestBundle:FormulasTest');
    	$repoArt=$em->getRepository('MbpArticulosBundle:Articulos');

		$parent =new FormulasTest;
		$parent->setCantidad(0);
		$parent->setIdArt($repoArt->findOneBy(array('codigo'=>"33901-01")));

		$nodeChildren = $repo->find(11);

		$nodeChildren->setParent($parent);

		$em->persist($parent);
		$em->persist($nodeChildren);
		$em->flush();
		
		return new Response;
    }

    /**
     * @Route("/test/funciones")
     */
    public function funciones()
    {
    	$em = $this->getDoctrine()->getManager();
    	$repo=$em->getRepository('MbpTestBundle:FormulasTest');
    	$node=$repo->find(1);

    	$query = $repo
		    ->createQueryBuilder('node')
		    ->select('node.id, node.cantidad, node.lft, node.lvl, node.rgt, art.codigo')
		    ->join('node.idArt', 'art')
		    ->orderBy('node.root, node.lft', 'ASC')
		    //->where('node.root = 1')
		    ->getQuery()
		;

		//print_r($query->getArrayResult());
		$options = array('decorate' => true,
		'nodeDecorator' => function ($node) {
                //\Doctrine\Common\Util\Debug::dump($node);
			return $node['codigo'];
            });
		$tree = $repo->buildTree($query->getArrayResult(), $options);

    	

    	print_r($tree);
    	//\Doctrine\Common\Util\Debug::dump($htmlTree);
    	return new Response;
    	
    }

    /**
     * @Route("/test/removeNested")
     */
    public function removeNested()
    {
    	$em = $this->getDoctrine()->getManager();
    	$repo=$em->getRepository('MbpTestBundle:FormulasTest');
    	$repoArt=$em->getRepository('MbpArticulosBundle:Articulos');

		$parent=$repo->find(2);

		
		$em->remove($parent);
		$em->flush();
		//$repo->removeFromTree($parent);

		//$em->clear();
		
		return new Response;
    }

     /**
     * @Route("/test/cargarArbol")
     */
    public function cargarArbol()
    {
    	$em = $this->getDoctrine()->getManager();
    	$repo=$em->getRepository('MbpTestBundle:FormulasTest');
    	$repoArt=$em->getRepository('MbpArticulosBundle:Articulos');

		//$parent =new FormulasTest;
		//$parent->setCantidad(0);
		//$parent->setIdArt($repoArt->findOneBy(array('codigo'=>"33901")));

		$nodeChildren =new FormulasTest;
		$nodeChildren->setCantidad(1);
		$nodeChildren->setIdArt($repoArt->findOneBy(array('codigo'=>"32878")));

		$nodeChildren1 =new FormulasTest;
		$nodeChildren1->setCantidad(1);
		$nodeChildren1->setIdArt($repoArt->findOneBy(array('codigo'=>"MT32586-04")));

		$nodeChildren2 =new FormulasTest;
		$nodeChildren2->setCantidad(1);
		$nodeChildren2->setIdArt($repoArt->findOneBy(array('codigo'=>"PAC15751866078")));		

		$nodeChildren3 =new FormulasTest;
		$nodeChildren3->setCantidad(1);
		$nodeChildren3->setIdArt($repoArt->findOneBy(array('codigo'=>"CNPT014024042")));

		//$nodeChildren->setParent($parent);
		$nodeChildren1->setParent($nodeChildren);
		$nodeChildren2->setParent($nodeChildren);
		$nodeChildren3->setParent($nodeChildren);

		//$em->persist($parent);
		$em->persist($nodeChildren);
		$em->persist($nodeChildren1);
		$em->persist($nodeChildren2);
		$em->persist($nodeChildren3);
		$em->flush();
		
		return new Response;
    }

    /**
     * @Route("/test/asociarArbol")
     */
    public function asociarArbol()
    {
    	$em = $this->getDoctrine()->getManager();
    	$repo=$em->getRepository('MbpTestBundle:FormulasTest');
    	$repoArt=$em->getRepository('MbpArticulosBundle:Articulos');

    	$nodeChildren=$repo->find(1);
		

		$parent =new FormulasTest;
		$parent->setCantidad(1);
		$parent->setIdArt($repoArt->findOneBy(array('codigo'=>"33901")));

		$nodeChildren->setParent($parent);
		

		$em->persist($parent);
		$em->persist($nodeChildren);
		
		$em->flush();
		
		return new Response;
    }
}


