<?php

namespace Mbp\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Mbp\WebBundle\Entity\Categorias;

class ProductosController extends Controller
{
	/**
     * @Route("/productos", name="productos")
     * @Template()
     */
    public function productosAction()
    {
    	$em = $this->getDoctrine()->getManager('web');
    	$repo = $em->getRepository('MbpWebBundle:Categorias');
    	$req = $this->getRequest();
    	$categoria = $repo->find($req->query->get('cat')); 


        return $this->render('MbpWebBundle:Default:productos.html.twig', array('categorias' => $categoria));
    }

    /**
     * @Route("/productos-por-categoria", name="productos-por-categoria")
     * @Template()
     */
    public function productosPorCategoriaAction()
    {
        $em = $this->getDoctrine()->getManager('web');
        $repo = $em->getRepository('MbpWebBundle:Categorias');
        $req = $this->getRequest();
        $cat = $req->query->get('cat');
        $subCat = $req->query->get('sub');
        
        $categoria = $repo->listarCatSub($cat, $subCat); 

        return $this->render('MbpWebBundle:Default:productosSubcategoria.html.twig', array(
                'categorias' => $categoria,
            ));
    }

    /**
     * @Route("/productos-comerciales", name="productosComerciales")
     * @Template()
     */
    public function productosComercialesAction()
    {
    	$em = $this->getDoctrine()->getManager('web');
    	$repo = $em->getRepository('MbpWebBundle:TiposRadiadores');
    	$req = $this->getRequest();
    	
        
        
        $tipos = $repo->findAll();            
        return $this->render('MbpWebBundle:Default:radiadoresComerciales.html.twig', array(
            'tipos' => $tipos,

            ));        

        
    }

    /**
     * @Route("/listar-aplicaciones", name="listarAplicaciones", options={"expose"=true})
     * @Template()
     */
    public function listarTiposAction()
    {
    	$em = $this->getDoctrine()->getManager('web');
    	$repo = $em->getRepository('MbpWebBundle:TiposRadiadores');
        $repoRadCom = $em->getRepository('MbpWebBundle:RadiadoresComerciales');
    	$req = $this->getRequest();
    	$tipo = $req->query->get('tipo');
    	$aplicaciones = $repo->listarAplicaciones($tipo);
    	
        $lista = $repoRadCom->listarRadPorTipo($tipo);
        
        $path = $this->get('templating.helper.assets')->getUrl('bundles/mbpweb/images/catalogo/');

    	return new Response(json_encode(array(
    		'aplicaciones' => $aplicaciones,
            'lista' => $lista,
            'pathImg' => $path
    		)
    	));
    }

    /**
     * @Route("/listar-marcas-aplicacion", name="listarMarcasAplicacion", options={"expose"=true})
     * @Template()
     */
    public function listarMarcasSubCatAction()
    {
    	$em = $this->getDoctrine()->getManager('web');
    	$repo = $em->getRepository('MbpWebBundle:MarcasRadiadores');
    	$repoRadCom = $em->getRepository('MbpWebBundle:RadiadoresComerciales');
    	$req = $this->getRequest();
    	$aplicacion = $req->query->get('aplicacion');
        $tipo = $req->query->get('tipo');
    	$marcas = $repo->marcasPorAplicaciones($aplicacion);

    	$lista = $repoRadCom->listarRadPorAplicacion($aplicacion);

        $path = $this->get('templating.helper.assets')->getUrl('bundles/mbpweb/images/catalogo/');

        if($aplicacion == 'all'){
            return new Response(json_encode(array(
                'marcas' => $marcas,
                'lista' => $repoRadCom->listarRadPorTipo($tipo),
                'pathImg' => $path
                )
            ));    
        }
    	
    	return new Response(json_encode(array(
    		'marcas' => $marcas,
    		'lista' => $lista,
            'pathImg' => $path
    		)
    	));
    }

     /**
     * @Route("/filtrar-marca", name="filtrarPorMarca", options={"expose"=true})
     * @Template()
     */
    public function filtrarPorMarcaAction()
    {
        $em = $this->getDoctrine()->getManager('web');
        $req = $this->getRequest();
        $repoRadCom = $em->getRepository('MbpWebBundle:RadiadoresComerciales');        
        $tipo = $req->query->get('tipo');
        $aplicacion = $req->query->get('aplicacion');
        $marca = $req->query->get('marca');

        $path = $this->get('templating.helper.assets')->getUrl('bundles/mbpweb/images/catalogo/');

        if($marca == 'all'){            
            return new Response(json_encode(array(
                'lista' => $repoRadCom->listarRadPorAplicacion($aplicacion),
                'pathImg' => $path
                )
            ));
        }

        $lista = $repoRadCom->filtrarPorMarca($tipo, $aplicacion, $marca);

        
        return new Response(json_encode(array(
            'lista' => $lista,
            'pathImg' => $path
            )
        ));
    }
}