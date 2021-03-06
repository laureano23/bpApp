<?php

namespace Mbp\WebBundle\Entity;

/**
 * CategoriasRepository 
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoriasRepository extends \Doctrine\ORM\EntityRepository
{

	//LISTADO POR CATEGORIA Y SUBCATEGORIA
	public function listarCategorias($locale)
	{
		$em = $this->getEntityManager();
		$repo = $em->getRepository('MbpWebBundle:Categorias');

		$res = $repo->createQueryBuilder('cat')
			->select('cat.descripcion, cat.id, cat.titulo, cat.imagen, cat.esComercial')			
			->getQuery()
			->setHint( \Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER,
            	'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker')
			->setHint(
	            \Gedmo\Translatable\TranslatableListener::HINT_TRANSLATABLE_LOCALE,
	            $locale)
			->getResult();

		return $res;
	}

	//LISTADO POR CATEGORIA Y SUBCATEGORIA
	public function listarCatSub($categoria, $subCategoria)
	{
		$em = $this->getEntityManager();
		$repo = $em->getRepository('MbpWebBundle:Categorias');

		$subCategorias[0] = $subCategoria;

		$res = $repo->createQueryBuilder('cat')			
			->select('cat, sub')
			->join('cat.subCategoria', 'sub')
			->where('cat.id = :cat')
			->andWhere('sub.id IN (:subCat)')
			->setParameter('cat', $categoria)
			->setParameter('subCat', $subCategoria)  
			->getQuery()
			->getOneOrNullResult();

		return $res;
	} 

	public function todos(){
		$em = $this->getEntityManager();
		$repo = $em->getRepository('MbpWebBundle:Categorias');

		$res = $repo->createQueryBuilder('cat')		
			->select('cat')
			->getQuery()
			->getResult();

		return $res;
	} 

	//LISTAR SUB CATEGORIAS
	public function listarSubCategorias($cat){
		$em = $this->getEntityManager();
		$repo = $em->getRepository('MbpWebBundle:SubCategoria');

		$res = $repo->createQueryBuilder('sub')		
			->select('sub')
			->where('sub.categoria = :cat')
			->setParameter('cat', $cat)
			->getQuery()
			->getArrayResult();

		return $res;
	}
}
