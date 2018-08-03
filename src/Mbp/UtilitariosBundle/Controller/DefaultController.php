<?php

namespace Mbp\UtilitariosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Mbp\UtilitariosBundle\Entity\Viaje;

class DefaultController extends Controller
{
    /**
     * @Route("/hojaDeRuta/nuevoDestino", name="mbp_hojaDeRuta_nuevoDestino", options={"expose"=true})     
     */
    public function nuevoDestino(Request $req)
    {
        $response=new Response;
        $em = $this->getDoctrine()->getManager();
        $repo=$em->getRepository('MbpUtilitariosBundle:Viaje');  
        try{

            $viaje;
            $data=json_decode($req->request->get('data'));
            if($data->idViaje > 0){
                $viaje=$repo->find($data->idViaje);
                $viaje->setEstado($data->estado);
            }else{
                $viaje=new Viaje;
                $viaje->setEstado("Pendiente");
            }
            $viaje->setFechaEmision(new \DateTime());
            $viaje->setFechaDesde(\DateTime::createFromFormat('d/m/Y', $data->fechaDesde));
            $viaje->setFechaHasta(\DateTime::createFromFormat('d/m/Y', $data->fechaHasta));
            $viaje->setNombre($data->nombre);
            $viaje->setDomicilio($data->domicilio);
            $viaje->setHorarios($data->horarios);
            $viaje->setDescripcion($data->descripcion);
            $viaje->setEmisor($this->getUser());
            
            $em->persist($viaje);
            $em->flush();

            $data->idViaje=$viaje->getIdViaje();

            return $response->setContent(
                json_encode(array('success'=>true, 'data'=>$data))
            );
        }catch(\Exception $e){
            $response->setContent(
                json_encode(array('success'=>false, 'msg'=>$e->getMessage()))
            );

            return $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/hojaDeRuta/listarDestinos", name="mbp_hojaDeRuta_listarDestinos", options={"expose"=true})     
     */
    public function listarDestinos()
    {
        $response=new Response;
        $em = $this->getDoctrine()->getManager();
        $repo=$em->getRepository('MbpUtilitariosBundle:Viaje');        
        try{
            $data = $repo->createQueryBuilder('v')
                ->select("v.idViaje,
                    DATE_FORMAT(v.fechaDesde, '%d/%m/%Y') as fechaDesde,
                    DATE_FORMAT(v.fechaEmision, '%d/%m/%Y') as fechaEmision,
                    DATE_FORMAT(v.fechaHasta, '%d/%m/%Y') as fechaHasta,
                    v.nombre,
                    v.domicilio,
                    v.horarios,
                    v.descripcion,
                    v.estado,
                    v.autorizado,
                    usuario.username as emisor") 
                ->join('v.emisor', 'usuario')               
                ->getQuery()
                ->getArrayResult();

            return $response->setContent(
                json_encode(array('success'=>true, 'data'=>$data))
            );
        }catch(\Exception $e){
            $response->setContent(
                json_encode(array('success'=>false, 'msg'=>$e->getMessage()))
            );

            return $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/hojaDeRuta/anularDestino", name="mbp_hojaDeRuta_anularDestino", options={"expose"=true})     
     */
    public function anularDestino(Request $req)
    {
        $response=new Response;
        $em = $this->getDoctrine()->getManager();
        $repo=$em->getRepository('MbpUtilitariosBundle:Viaje');        
        try{
            $data=json_decode($req->request->get('data'));
            $destino=$repo->find($data->idViaje);

            $em->remove($destino);
            $em->flush();

            return $response->setContent(
                json_encode(array('success'=>true))
            );
        }catch(\Exception $e){
            $response->setContent(
                json_encode(array('success'=>false, 'msg'=>$e->getMessage()))
            );

            return $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
