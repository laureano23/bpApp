<?php

namespace Mbp\ArticulosBundle\Entity;
use Gedmo\Tree\Entity\Repository\ClosureTreeRepository;

/**
 * FormulasCRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FormulasCRepository extends \Gedmo\Tree\Entity\Repository\ClosureTreeRepository
{
	public function queryFormulaCompleta($nodo)
	{
		$em = $this->getEntityManager();
    	$repo=$em->getRepository('MbpArticulosBundle:FormulasC');
    	
    	$dql = "SELECT node.id, art.codigo, node.unidad
    			FROM Mbp\TestBundle\Entity\FormulasClosure c
    			INNER JOIN c.descendant node 
    			INNER JOIN node.idArt art
    			WHERE c.ancestor = :node AND c.descendant <> :node";

    	$qb = $em->createQuery($dql);
    	$qb->setParameter('node',$nodo);

    	return $qb->getArrayResult();

	}

    public function queryFormulaPrimerNivel($idArt){
        $em = $this->getEntityManager();
        $repo=$em->getRepository('MbpArticulosBundle:FormulasC');
        $repoArt=$em->getRepository('MbpArticulosBundle:Articulos');

        $node=$repo->findOneBy(['idArt' => $idArt]);
        
        $dql = "SELECT node.id as idFormula, art.codigo, art.id as idArt, node.unidad, art.descripcion, node.cantidad as cant, art.moneda, art.costo
                FROM Mbp\ArticulosBundle\Entity\FormulasClosure c
                INNER JOIN c.descendant node 
                INNER JOIN node.idArt art
                WHERE c.ancestor = :node AND c.descendant <> :node
                AND c.depth=1";

        $qb = $em->createQuery($dql);
        $qb->setParameter('node',$node);

        return $qb->getArrayResult();        
    }

    public function costoEstructuraCompleta($idArt){
        $em = $this->getEntityManager();
        $repo=$em->getRepository('MbpArticulosBundle:FormulasC');
        $repoArt=$em->getRepository('MbpArticulosBundle:Articulos');

        $art=$repoArt->find($idArt);

        $node=$repo->findOneBy(['idArt' => $art]);
        $idNodo=$node->getId();

        $sql="SELECT
            sum(
                case when art.moneda=0
                then art.costo*node.cantidad
                else art.costo*node.cantidad*params.dolarOficial end
            ) as sumCosto,
            node.id
        from
            (select *, c.descendant anterior, c.depth depthP
            from FormulasClosure c
            where c.ancestor = $idNodo) as a,
        FormulasClosure p, FormulasC node, articulos art, ParametrosFinanzas params
        where p.ancestor = a.anterior
        and p.descendant = node.id
        and node.idArt = art.idArticulos
        group by a.anterior
        limit 1
        ";

        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $res= $stmt->fetchAll(); 
        return $res[0]['sumCosto'];
    }

}
