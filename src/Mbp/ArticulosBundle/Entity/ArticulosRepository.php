<?php
namespace Mbp\ArticulosBundle\Entity;
use Doctrine\ORM\EntityRepository;
use Mbp\FinanzasBundle\Clases;

class ArticulosRepository extends EntityRepository
{	
	public function listarArticulos()
	{
		$em = $this->getEntityManager();
		$repoArt = $em->getRepository('MbpArticulosBundle:Articulos');

		$art = 	$repoArt->createQueryBuilder('a')
						->select("a.id, a.descripcion, a.codigo, a.unidad, a.iva, f.id AS familia,
						 sf.id AS subFamilia, a.precio, a.nombreImagen,
						 a.rutaServer,
						CASE WHEN a.moneda = false THEN 'p' ELSE 'd' END AS moneda,
						CASE WHEN a.monedaPrecio = false THEN 'p' ELSE 'd' END AS monedaPrecio,
						a.requiereControl,
						a.peso,
						DATE_FORMAT(a.vigenciaPrecio, '%d/%m/%Y') vigenciaPrecio,
						pr1.id as provSug1,
						pr2.id as provSug2,
						pr3.id as provSug3")
						->leftJoin('a.familiaId', 'f')
						->leftJoin('a.subFamiliaId', 'sf')
						->leftJoin('a.provSug1', 'pr1')
						->leftJoin('a.provSug2', 'pr2')
						->leftJoin('a.provSug3', 'pr3')
						->andWhere('a.inactivo = 0')
						->getQuery();
						
		$res = $art->getArrayResult();
		
		$total = count($res);
				
		if(!isset($res)){
			echo json_encode(array(
				'msg'=>'No hay resultados'
			));
		}else{
			echo json_encode(array(
				'data' => $res,
				'total_art' => $total
			));
		}
	}
	
	/*
	 * CONSULTA PARA LA TABLA DE ARTICULOS
	 * */
	public function detalleArticulo($idArt)
	{
		$em = $this->getEntityManager();
		$repoArt = $em->getRepository('MbpArticulosBundle:Articulos');
		
		$art = 	$repoArt->createQueryBuilder('a')
						->select("a.id, a.descripcion, a.codigo, a.unidad, a.iva, f.id AS familia,
						 sf.id AS subFamilia, a.precio, a.nombreImagen,
						 a.rutaServer,
						CASE WHEN a.moneda = false THEN 'p' ELSE 'd' END AS moneda,
						CASE WHEN a.monedaPrecio = false THEN 'p' ELSE 'd' END AS monedaPrecio,
						a.requiereControl,
						a.peso,
						DATE_FORMAT(a.vigenciaPrecio, '%d/%m/%Y') vigenciaPrecio,
						pr1.id as provSug1,
						pr2.id as provSug2,
						pr3.id as provSug3")
						->leftJoin('a.familiaId', 'f')
						->leftJoin('a.subFamiliaId', 'sf')
						->leftJoin('a.provSug1', 'pr1')
						->leftJoin('a.provSug2', 'pr2')
						->leftJoin('a.provSug3', 'pr3')
						->where('a.id = :art')
						->andWhere('a.inactivo = 0')
						->setParameter('art', $idArt)
						->getQuery()
						->getResult();
		
		return $art;
	}
	
	/*BUSCA EN LA FORMULA DEL ARTICULO Y CALCULA SU COSTO, O EN SU DEFECTO TRAER EL VALOR DE LA TABLA
	 * */
	public function costoArticulo($idArticulo)
	{
		$em = $this->getEntityManager();
		$repoFormula = $em->getRepository('MbpArticulosBundle:Formulas');
		$repoArt = $em->getRepository('MbpArticulosBundle:Articulos');
		$tipoCambio = $this->get('TipoCambio');
		
		$articulo = $repoArt->find($idArticulo);
		
		$existeEnFormula = $repoFormula->createQueryBuilder('f')
						->select('f.id')
						->where('f.idArt = :art')
						->andWhere('f.cant = 0')
						->setParameter('art', $articulo)
						->getQuery()
						->getResult();
						
		$costo = 0;
		if(!empty($existeEnFormula)){
			$qb = $repoFormula->createQueryBuilder('f')
					->select('art.descripcion, art.codigo, art.costo AS costo, art.moneda, padre.cant AS cantidad, art.nombreImagen')
					->from('MbpArticulosBundle:Formulas', 'padre')
					->from('MbpArticulosBundle:Formulas', 'nodo')				
					->leftJoin('padre.idArt', 'art')
					->where('padre.lft > nodo.lft')
					->andWhere('nodo.lft < padre.rgt')
					->andWhere('nodo.id = :idPadre')
					->setParameter('idPadre', $existeEnFormula[0]['id'])
					->groupBy('art.codigo')
					->getQuery()
					->getResult();	
			
			$costo=0;
			foreach ($qb as $rec) {
				if($rec->getMoneda() == 1){
					$dolar = $tipoCambio;
				}else{
					$dolar = 1;
				}
				$costo = $costo + $rec['costo'] * $rec['cantidad'] * $tipoCambio;
			}			
		}else{
			$costo = $articulo->getCosto();
		}
		
		$art = 	$repoArt->createQueryBuilder('a')
						->select('a.id, a.descripcion, a.codigo, a.unidad, a.moneda')
						->where('a.id = :art')
						->setParameter('art', $idArticulo)
						->getQuery()
						->getResult();
		
	}
	
	public function crearArticulo($artic, $validator)
	{
		$data = json_decode($artic);
		$em = $this->getEntityManager();
		$repoArt = $em->getRepository('MbpArticulosBundle:Articulos');
		$repoFamilia = $em->getRepository('MbpArticulosBundle:Familia');
		$repoSubFamilia = $em->getRepository('MbpArticulosBundle:SubFamilia');
		$repoFormulas=$em->getRepository('MbpArticulosBundle:FormulasC');
		$repoProveedor=$em->getRepository('MbpProveedoresBundle:Proveedor');
		
		try{
			//BUSCA FAMILIA Y SUB FAMILIA
			$fam = $repoFamilia->find($data->familia);
			$subFam = $repoSubFamilia->find($data->subFamilia);
			
			$art;
			if($data->id > 0){
				$art = $repoArt->find($data->id);
			}else{
				$art = new Articulos();	
			}			
			$art->setcodigo($data->codigo)
			->setdescripcion($data->descripcion)
			->setunidad($data->unidad)			
			->setPeso($data->peso)
			->setMoneda($data->moneda == 'p' ? 0 : 1)
			->setFamiliaId($fam)
			->setSubFamiliaId($subFam)
			->setPrecio($data->precio)
			->setMonedaPrecio($data->monedaPrecio == 'p' ? 0 : 1)
			->setRutaServer($data->rutaServer)
			->setProvSug1($repoProveedor->find($data->provSug1))
			->setProvSug2($repoProveedor->find($data->provSug2))
			->setProvSug3($repoProveedor->find($data->provSug3))
			->setRequiereControl($data->requiereControl == "on" ? 1 : 0);

			$data->vigenciaPrecio != "" ? 
				$art->setVigenciaPrecio(\DateTime::createFromFormat("d/m/Y", $data->vigenciaPrecio))
				:"";

			//el costo lo seteamos si no tenemos formula
			$nodo=$repoFormulas->findOneByidArt($art);
			if($nodo!=null && $repoFormulas->childCount($nodo) > 0){
					
			}else{
				$art->setcosto($data->costo);	
			}
			
			
			$errors = $validator->validate($art);
			if(count($errors) > 0){
				$errList = array();
				foreach ($errors as $error) {
					$errList[$error->getPropertyPath()] = $error->getMessage();
				}
				
				return array(
					'success' => false,
					'errors' => $errList,
					'tipo' => 'validacion'
					);	
			}
			$em->persist($art);
			$em->flush();
			
			return array(
				'success' => true,
				'data' => array(
					'id' => $art->getId(),
					'codigo' => $art->getCodigo()
				)
			);	
		}catch(\Exception $e){
			return array(
				'success' => false,
				'msg' => $e->getMessage()
				);	
		}
					
	}
	
	public function deleteArticulo($art)
	{
		$em = $this->getEntityManager();
		$data = json_decode($art);
		$id = $data->id;
		
		$res = $em->getRepository('MbpArticulosBundle:Articulos')->find($id);
		$res->setInactivo(1);
				
		$em->persist($res);
		$em->flush();		
	}
		
	
	public function validArt($cod)
	{
		$em = $this->getEntityManager();
		$dql = 'SELECT a FROM MbpArticulosBundle:Articulos a WHERE a.codigo = :codigo';		
		
		$q = $em->createQuery($dql);
			$q->setParameter('codigo', $cod);
		$res = $q->getResult();		
		
		if($res){
			echo json_encode(array(
				'success' => false,
				'msg' => 'Este articulo ya existe'
			));
		}else{
			echo json_encode(array(
				'success' => true,
				
		));
		}
	}
}
