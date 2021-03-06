<?php

namespace Mbp\ArticulosBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Mbp\ArticulosBundle\Entity\Articulos;

/**
 * FormulasRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FormulasRepository extends \Doctrine\ORM\EntityRepository
{
	
	/*
	 * BUSCA SI EL ARTICULO TIENE FORMULA
	 * */
	public function tieneFormula($articulo)
	{
		$em = $this->getEntityManager();
				
		$repoFormula = $em->getRepository('MbpArticulosBundle:Formulas');
		$existeEnFormula = $repoFormula->createQueryBuilder('f')
						->select('f.id')
						->where('f.idArt = :art')
						->andWhere('f.cant = 0')
						->setParameter('art', $articulo)
						->getQuery()
						->getResult();
		
		if(empty($existeEnFormula)){ return false; }
		return $existeEnFormula;
	}
	
	/*
	 * COSTO DE LA FORMULA PARA LA TABLA DE ARTICULOS
	 * */
	public function costoArticuloConFormula($existeEnFormula)
	{
		$em = $this->getEntityManager();
				
		$repoFormula = $em->getRepository('MbpArticulosBundle:Formulas');
		$qb = $repoFormula->createQueryBuilder('f')
					->select('art.descripcion, art.codigo, art.costo AS costo, art.moneda, padre.cant AS cantidad')
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
		return $qb;
	}
	
	/*
	 * BUSCA TODOS LOS NODOS DONDE APAREZCA EL ARTICULO
	 * PARAMETROS: ID ARTICULO
	 * */
	public function busca_nodo_con_art($idArticulo)
	{
		$em = $this->getEntityManager();
		$repo = $em->getRepository('MbpArticulosBundle:Formulas');
		$repoArt = $em->getRepository('MbpArticulosBundle:Articulos');
		
		$art = $repoArt->find($idArticulo);
		
		$qb = $repo->createQueryBuilder('f')
				->select()
				->where('f.idArt = :idArt')
				->setParameter('idArt', $art)
				->getQuery()
				->getResult();
		
		if(empty($qb)){
			return null;
		}		
		
		return $qb;
	}
	
	/* RETORNA EL PRIMER NIVEL DE LA ESTRUCTURA */
	public function formulasEstrucutraMateriales($idPadre)
	{
		$em = $this->getEntityManager();
		$repo = $em->getRepository('MbpArticulosBundle:Formulas');
		   		
		$qb = $em->createQueryBuilder('f')
				->select('nodo')
				->from('MbpArticulosBundle:Formulas', 'padre')
				->from('MbpArticulosBundle:Formulas', 'nodo')				
				->join('nodo.idArt', 'art')
				->where('nodo.lft >= padre.lft')
				->andWhere('nodo.lft < padre.rgt')
				->andWhere('padre.id = :idPadre')
				->setParameter('idPadre', $idPadre)
				->groupBy('nodo.lft')
				->orderBy('nodo.lft')
				->getQuery()
				->getResult();
		
		if(empty($qb)) return null;
						
		return $qb;
	}
	
	/*
	 * RECIBE ID DE ARTICULO Y TRAE LE FORMULA DEL MISMO SOLO EN NIVEL DE PROFUNDIDAD 1
	 * */
	public function formulasList($idArt, $tc=1)
	{
		$em = $this->getEntityManager();
		$repoArt = $em->getRepository('MbpArticulosBundle:Articulos');		
		
			
		$sql = "
			SELECT *
			FROM
			(SELECT 	
				node.id AS idFormula,
				node.cant,
				(COUNT(parent.id) - (sub_tree.depth + 1)) AS depth,
				articulos.idArticulos AS id,
				articulos.descripcion,
				articulos.codigo,
				articulos.unidad,
				articulos.moneda,
				articulos.nombreImagen,
				articulos.costo as costo
				FROM `articulos` articulos INNER JOIN `Formulas` node ON articulos.`idArticulos` = node.`idArt`,
			        Formulas AS parent,
			        Formulas AS sub_parent,
			        (
						SELECT node.id, (COUNT(parent.id) - 1) AS depth, articulos.idArticulos
						FROM `articulos` articulos INNER JOIN `Formulas` node ON articulos.`idArticulos` = node.`idArt`,
						Formulas AS parent
						WHERE node.lft BETWEEN parent.lft AND parent.rgt
						AND articulos.idArticulos = $idArt
						GROUP BY node.id
						ORDER BY node.lft
			        )AS sub_tree
			WHERE node.lft BETWEEN parent.lft AND parent.rgt
			        AND node.lft BETWEEN sub_parent.lft AND sub_parent.rgt
			        AND sub_parent.id = sub_tree.id
			        AND sub_parent.cant = 0
			GROUP BY node.id
			HAVING depth = 1
			ORDER BY node.lft) AS x			
			
			INNER JOIN
			
			(SELECT 	node.id AS idFormula,
				articulos.idArticulos AS id,
				articulos.codigo,
				SUM(CASE 
					WHEN articulos.moneda = 0
					THEN articulos.costo
					ELSE articulos.costo * $tc
					END) * parent.cant AS costo
				FROM `articulos` articulos INNER JOIN `Formulas` node ON articulos.`idArticulos` = node.`idArt`,
			        Formulas AS parent,
			        Formulas AS sub_parent,
			        (
						SELECT node.id, (COUNT(parent.id) - 1) AS depth
						FROM `articulos` articulos INNER JOIN `Formulas` node ON articulos.`idArticulos` = node.`idArt`,
						Formulas AS parent
						WHERE node.lft BETWEEN parent.lft AND parent.rgt
						AND articulos.idArticulos = $idArt
						GROUP BY node.id
						ORDER BY node.lft
			        )AS sub_tree
			WHERE node.lft BETWEEN parent.lft AND parent.rgt
			        AND node.lft BETWEEN sub_parent.lft AND sub_parent.rgt
			        AND sub_parent.id = sub_tree.id
			GROUP BY parent.id
			ORDER BY node.lft) AS y ON x.idFormula = y.idFormula
			GROUP BY y.codigo
		";
		
		
		$stmt = $em->getConnection()->prepare($sql);
    	$stmt->execute();
		$res = $stmt->fetchAll();
					
		return $res;
	}
	
	/*
	 * BUSCA NODO Y RETORNA CANTIDAD DE NODOS ASCENDENTES EN LA FORMULAS
	 * */
	public function busca_nodo_ascendentes($idNodoHijo)
	{
		$em = $this->getEntityManager();
		$repo = $em->getRepository('MbpArticulosBundle:Formulas');
		
		$qb = $em->createQueryBuilder('f')
				->select('parent')
				->from('MbpArticulosBundle:Formulas', 'nodo')
				->from('MbpArticulosBundle:Formulas', 'parent')
				->where('nodo.lft > parent.lft')
				->andWhere('nodo.lft < parent.rgt')
				->andWhere('nodo.id = :id')
				->setParameter('id', $idNodoHijo)
				->getQuery()
				->getResult();
				
			
		return count($qb);
	} 

	/*
	 * BUSCA NODO PADRE SEGUN ID NODO HIJO
	 * */
	public function busca_nodo_padre($idNodoHijo)
	{
		$em = $this->getEntityManager();
		$repo = $em->getRepository('MbpArticulosBundle:Formulas');
		
		$qb = $em->createQueryBuilder('f')
				->select('parent')
				->from('MbpArticulosBundle:Formulas', 'nodo')
				->from('MbpArticulosBundle:Formulas', 'parent')
				->where('nodo.lft > parent.lft')
				->andWhere('nodo.lft < parent.rgt')
				->andWhere('nodo.id = :id')
				->setParameter('id', $idNodoHijo)
				->getQuery()
				->getResult();
				
		//RETORNA EL ULTIMO ELEMENTO DEL ARRAY EL CUAL ES EL PADRE INMEDIATO SUPERIOR
		if(empty($qb)) return null;
		
		return end($qb);
	} 

	/*
	 * BUSCA NODO PADRE (QUE TIENE FORMULA)
	 * */	
	public function busca_nodo_padre_formula($idArt)
	{
		$em = $this->getEntityManager();
		$repo = $em->getRepository('MbpArticulosBundle:Formulas');
		$repoArt = $em->getRepository('MbpArticulosBundle:Articulos');
		
		$art = $repoArt->find($idArt);
		
		$qb = $repo->createQueryBuilder('f')
				->select()
				->where('f.idArt = :idArt')
				->andWhere('f.cant = 0')
				->setParameter('idArt', $art)
				->getQuery()
				->getResult();
			
		if(empty($qb)){
			return null;
		}
		
		return $qb[0];
	}
	
	/*
	 * SEGUN ID DE ARTICULO BUSCA Y RETORNA SU ESTRUCTURA COMPLETA
	 * */
	public function estructuraCompleta($idNodo=null, $tc)
	{
		$em = $this->getEntityManager();
		$repoArt = $em->getRepository('MbpArticulosBundle:Articulos');		
		
		if($idNodo == null){return "";}
		
		$sql = "
			SELECT *
			FROM
			(SELECT 	node.id,
				node.lft,
				node.rgt,
				node.cant,
				node.idArt,
				(COUNT(parent.id) - (sub_tree.depth + 1)) AS depth,
				articulos.descripcion,
				articulos.codigo,
				articulos.moneda,
				articulos.nombreImagen,
				articulos.costo as costo
				FROM `articulos` articulos INNER JOIN `Formulas` node ON articulos.`idArticulos` = node.`idArt`,
			        Formulas AS parent,
			        Formulas AS sub_parent,
			        (
						SELECT node.id, (COUNT(parent.id) - 1) AS depth
						FROM Formulas AS node,
						Formulas AS parent
						WHERE node.lft BETWEEN parent.lft AND parent.rgt
						AND node.id = $idNodo
						GROUP BY node.id
						ORDER BY node.lft
			        )AS sub_tree
			WHERE node.lft BETWEEN parent.lft AND parent.rgt
			        AND node.lft BETWEEN sub_parent.lft AND sub_parent.rgt
			        AND sub_parent.id = sub_tree.id
			GROUP BY node.id
			ORDER BY node.lft) AS x
			
			INNER JOIN
			
			(SELECT 	node.id,
				SUM(
					CASE
						WHEN articulos.moneda = 0
							THEN articulos.costo * node.cant
						ELSE articulos.costo * node.cant * $tc
						END) AS sumCosto,
				SUM(
					CASE
						WHEN articulos.moneda = 0
							THEN articulos.costo * node.cant
						ELSE articulos.costo * node.cant * $tc
						END) * parent.cant AS sumCostoPadre
				FROM `articulos` articulos INNER JOIN `Formulas` node ON articulos.`idArticulos` = node.`idArt`,
			        Formulas AS parent,
			        Formulas AS sub_parent,
			        (
						SELECT node.id, (COUNT(parent.id) - 1) AS depth
						FROM Formulas AS node,
						Formulas AS parent
						WHERE node.lft BETWEEN parent.lft AND parent.rgt
						AND node.id = $idNodo
						GROUP BY node.id
						ORDER BY node.lft
			        )AS sub_tree
			WHERE node.lft BETWEEN parent.lft AND parent.rgt
			        AND node.lft BETWEEN sub_parent.lft AND sub_parent.rgt
			        AND sub_parent.id = sub_tree.id
			GROUP BY parent.id
			ORDER BY node.lft ASC
			/*LIMIT 1, 1000  PARA SACAR DEL RESULTADO EL NODO PADRE */
			) AS y ON x.id = y.id
		";
		
		
		$stmt = $em->getConnection()->prepare($sql);
    	$stmt->execute();
		$res = $stmt->fetchAll();
					
		return $res;
	} 
	
}
