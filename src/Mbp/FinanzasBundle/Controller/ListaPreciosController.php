<?php

namespace Mbp\FinanzasBundle\Controller;	

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Mbp\FinanzasBundle\Entity\ListaPrecios;
use Mbp\FinanzasBundle\Entity\ListaPreciosDetalle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ListaPreciosController extends Controller
{
    /**
     * @Route("/listarArticulosPrecios", name="mbp_finanzas_lista_listarArticulosPrecios", options={"expose"=true})
     */
    public function listarArticulosPrecios()
    {
        $response=new Response;

        try{
            $em = $this->getDoctrine()->getEntityManager();
            $repo=$em->getRepository('MbpFinanzasBundle:ListaPrecios');
            $listas=$repo->listarArticulosPrecios();


            return $response->setContent(
                \json_encode(array('success'=>true, 'data'=>$listas))
            );
        }catch(\Exception $e){
            $response->setContent(
                \json_encode(array('success'=>false, 'msg'=>$e->getMessage()))
            );
            return $response->setStatusCode(500);
        }
    }

    /**
     * @Route("/listarListas", name="mbp_finanzas_lista_listarListas", options={"expose"=true})
     */
    public function listarListas()
    {
        $response=new Response;

        try{
            $em = $this->getDoctrine()->getEntityManager();
            $repo=$em->getRepository('MbpFinanzasBundle:ListaPrecios');
            $listas=$repo->dameListas();


            return $response->setContent(
                \json_encode(array('success'=>true, 'data'=>$listas))
            );
        }catch(\Exception $e){
            $response->setContent(
                \json_encode(array('success'=>false, 'msg'=>$e->getMessage()))
            );
            return $response->setStatusCode(500);
        }
    }

	/**
     * @Route("/borrarLista", name="mbp_finanzas_lista_borrarLista", options={"expose"=true})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function borrarLista()
    {
        $response=new Response;

        try{
            $em = $this->getDoctrine()->getEntityManager();
            $data=json_decode($this->getRequest()->request->get('data'));
            
            $repo=$em->getRepository('MbpFinanzasBundle:ListaPrecios');
            $lista=$repo->find($data->id);

            $em->remove($lista);
            $em->flush();

            return $response->setContent(
                \json_encode(array('success'=>true))
            );
        }catch(\Exception $e){
            $response->setContent(
                \json_encode(array('success'=>false, 'msg'=>$e->getMessage()))
            );
            return $response->setStatusCode(500);
        }
    }

	/**
     * @Route("/nuevaLista", name="mbp_finanzas_lista_nueva", options={"expose"=true})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function nuevaLista()
    {
        $response=new Response;

        try{
            $em = $this->getDoctrine()->getEntityManager();
            $lista=null;
            $data=json_decode($this->getRequest()->request->get('data'));

            if($data->id > 0){
                $repo=$em->getRepository('MbpFinanzasBundle:ListaPrecios');
                $lista=$repo->find($data->id);
            }else{
                $lista=new ListaPrecios;
            }

            $lista->setDescripcion($data->descripcion);
            $moneda=null;
            if($data->moneda==='ARS'){
                $lista->setMoneda(0);
            }else{
                $lista->setMoneda(1);
            }
            $em->persist($lista);
            $em->flush();

            return $response->setContent(
                \json_encode(array('success'=>true, 'data'=>array('id'=>$lista->getId())))
            );
        }catch(\Exception $e){
            $response->setContent(
                \json_encode(array('success'=>false, 'msg'=>$e->getMessage()))
            );
            return $response->setStatusCode(500);
        }
    }

    /**
     * @Route("/agregarDetalle", name="mbp_finanzas_lista_agregarDetalle", options={"expose"=true})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function agregarDetalle()
    {
        $response=new Response;

        try{
            $em = $this->getDoctrine()->getEntityManager();            
            $data=json_decode($this->getRequest()->request->get('data'));
            $idLista=json_decode($this->getRequest()->request->get('idLista'));
            $repo=$em->getRepository('MbpFinanzasBundle:ListaPrecios');
            $repoDetalle=$em->getRepository('MbpFinanzasBundle:ListaPreciosDetalle');
            $repoArticulos=$em->getRepository('MbpArticulosBundle:Articulos');
            $lista=$repo->find($idLista);

            $detalle=null;
            if($data->id > 0){
                $detalle=$repoDetalle->find($data->id);
            }else{
                $detalle=new ListaPreciosDetalle;
                $detalle->setListaId($lista);
            }

            $art=$repoArticulos->findOneByCodigo($data->codigo);

            $detalle->setPrecio($data->precio);
            $detalle->setArticuloId($art);
            $em->persist($detalle);
            $em->flush();

            return $response->setContent(
                \json_encode(array('success'=>true, 'data'=>array('id'=>$detalle->getId())))
            );
        }catch(\Exception $e){
            $response->setContent(
                \json_encode(array('success'=>false, 'msg'=>$e->getMessage()))
            );
            return $response->setStatusCode(500);
        }
    }

    /**
     * @Route("/listarDetalles", name="mbp_finanzas_lista_listarDetalles", options={"expose"=true})
     */
    public function listarDetalles()
    {
        $response=new Response;

        try{
            $em = $this->getDoctrine()->getEntityManager();
            $repo=$em->getRepository('MbpFinanzasBundle:ListaPreciosDetalle');
            $idLista=json_decode($this->getRequest()->query->get('idLista'));
            $listas=$repo->dameDetalles($idLista);

            return $response->setContent(
                \json_encode(array('success'=>true, 'data'=>$listas))
            );
        }catch(\Exception $e){
            $response->setContent(
                \json_encode(array('success'=>false, 'msg'=>$e->getMessage()))
            );
            return $response->setStatusCode(500);
        }
    }

    /**
     * @Route("/borrarDetalle", name="mbp_finanzas_lista_borrarDetalle", options={"expose"=true})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function borrarDetalle()
    {
        $response=new Response;

        try{
            $em = $this->getDoctrine()->getEntityManager();
            $data=json_decode($this->getRequest()->request->get('data'));
            
            $repo=$em->getRepository('MbpFinanzasBundle:ListaPreciosDetalle');
            $detalle=$repo->find($data->id);

            $em->remove($detalle);
            $em->flush();

            return $response->setContent(
                \json_encode(array('success'=>true))
            );
        }catch(\Exception $e){
            $response->setContent(
                \json_encode(array('success'=>false, 'msg'=>$e->getMessage()))
            );
            return $response->setStatusCode(500);
        } 
    }

}
