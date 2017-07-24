<?php
namespace Mbp\ArticulosBundle\Clases; 

use Mbp\ArticulosBundle\Entity\FormulasRepository;
use Mbp\ArticulosBundle\Entity\Formulas;
use Doctrine\ORM\EntityManager;

class FormulasClass
{
	private $idNodoFormula;
	private $idArtFormulado;
	private $idArt;
	private $cantidad;
	private $em;
	private $tc;
	
	public function __construct($idNodoFormula, $idArtFormulado, $idArt, $cantidad, EntityManager $em, $tc)
	{
		$this->idNodoFormula = $idNodoFormula;
		$this->idArtFormulado = $idArtFormulado;
		$this->idArt = $idArt;
		$this->cantidad = $cantidad;
		$this->em = $em;
		$this->tc = $tc;
	}
	
	public function formulasInsert()
	{
		$repo = $this->em->getRepository('MbpArticulosBundle:Formulas');
						
		$es_form_padre = $this->es_formula($this->idArt);
		$es_form_hijo = $this->es_formula($this->idArtFormulado);
		
		if($es_form_hijo == false && $es_form_padre == FALSE){
			return $this->nuevo_nodo_sin_formula();			
		}
		
		if($es_form_hijo == true && $es_form_padre == FALSE){
			return $this->hijoFormula_padreNo();			
		}
		
		if($es_form_hijo == false && $es_form_padre == true){
			return $this->padreFormula_hijoNo();			
		}
		
		if($es_form_hijo == true && $es_form_padre == true){
			return $this->padreFormula_hijoFormula();			
		}		
	}
	
	public function formulasUpdate()
	{
		$repo = $this->em->getRepository('MbpArticulosBundle:Formulas');
		
		//BUSCAMOS NODO A EDITAR
		$nodo = $repo->find($this->idNodoFormula);
		//BUSCAMOS PADRE DEL NODO A EDITAR
		$padre = $repo->busca_nodo_padre($nodo->getId());
		
		//BUSCA TODOS LOS NODOS DONDE APARECE EL PADRE
		$nodosPadre = $repo->busca_nodo_con_art($padre->getIdArt()->getId());
		
		//TODO LO QUE SIGUE A CONTINUACION ES UNA TRANSACCION
		$this->em->getConnection()->beginTransaction();
		
		try{
			//SI NO HAY PADRES EDITAMOS SOLO EL NODO ENVIADO
			if($nodosPadre == null){
				//EDITAMOS VALORES
				$nodo->setCant($data->cant);
				$this->em->persist($nodo);
				$this->em->flush();
				
				return;
			}
			
			//SI HAY PADRES EDITAMOS TODAS SUS FORMULAS
			foreach ($nodosPadre as $np) {
				$estructuraPadre = $repo->formulasEstrucutraMateriales($np);
				//POR CADA FORMULA OBTENIDA EDITAMOS EL ARTICULO EN CUESTION
				foreach($estructuraPadre as $nodoEs){
					if($nodoEs->getIdArt()->getId() == $nodo->getIdArt()->getId()){
						$nodoEs->setCant($this->cantidad);
						$this->em->persist($nodo);
					}
				}
			}
			
			//EDITAMOS VALORES
			$nodo->setCant($this->cantidad);
			$this->em->persist($nodo);
			$this->em->flush();
			$this->em->getConnection()->commit();	
		}catch(Exception $e){
			$this->em->getConnection()->rollBack();
			return $e->getMessage();
		}
		
		return 1;
	}
	
	public function borrarNodo()
	{
		$repo = $this->em->getRepository('MbpArticulosBundle:Formulas');		
		$repoArt = $this->em->getRepository('MbpArticulosBundle:Articulos');
		
		//BUSCAMOS ARTICULOS
		$art = $repoArt->findById($this->idArt);
		
		//NODO A ELIMINAR
		$nodo_a_eliminar = $repo->find($this->idNodoFormula);
		$padre_nod_eliminar = $repo->busca_nodo_padre($nodo_a_eliminar->getId());
		
		$abuelo = $repo->busca_nodo_ascendentes($padre_nod_eliminar->getId());
		
		//TODO LO QUE SIGUE A CONTINUACION ES UNA TRANSACCION
		$this->em->getConnection()->beginTransaction();
		
		try{
			if($abuelo == 0 && $nodo_a_eliminar->getCant() == 0){
				//SI ENTRA ACA ES PORQUE ESTAMOS EN LA FORMULA MADRE Y HAY QUE BORRAR EL ITEM DE TODAS LAS FORMULAS
				$item = $repo->findByIdArt($art);
				foreach ($item as $rec) {
					$nodos = $repo->formulasEstrucutraMateriales($rec->getId());
					if($nodos != null){
						foreach ($nodos as $nodo) {
							if($nodo->getIdArt()->getId() == $nodo_a_eliminar->getIdArt()->getId()){
								$this->em->remove($nodo);
								$this->em->flush();
								$this->orden_nodo_chld_abajo_delete($nodo->getLft(), $nodo->getRgt());	
							}						
						}	
					}
								
				}
				//COMPRUEBA SI EL PADRE QUEDO HUERFANO (SIN HIJOS)
				$formula = $repo->formulaslist($padre_nod_eliminar->getIdArt()->getId(), $this->tc);
				if(empty($formula)){
					$this->em->remove($padre_nod_eliminar);
					$this->em->flush();
					$this->orden_nodo_chld_abajo_delete($padre_nod_eliminar->getLft(), $padre_nod_eliminar->getRgt());
				}
			}elseif($this->es_formula($art[0]->getId()) == TRUE){
				$nodos = $repo->formulasEstrucutraMateriales($this->idNodoFormula);
				foreach ($nodos as $nodo) {
					$this->em->remove($nodo);
					$this->em->flush();
					$this->orden_nodo_chld_abajo_delete($nodo->getLft(), $nodo->getRgt());
				}
				
				//COMPRUEBA SI EL PADRE QUEDO HUERFANO (SIN HIJOS)
				$formula = $repo->formulaslist($padre_nod_eliminar->getIdArt()->getId(), $this->tc);
				if(empty($formula)){
					$this->em->remove($padre_nod_eliminar);
					$this->em->flush();
					$this->orden_nodo_chld_abajo_delete($padre_nod_eliminar->getLft(), $padre_nod_eliminar->getRgt());
				}
			}else{
				
				$this->em->remove($nodo_a_eliminar);
				$this->em->flush();
				$this->orden_nodo_chld_abajo_delete($nodo_a_eliminar->getLft(), $nodo_a_eliminar->getRgt());
				
				//COMPRUEBA SI EL PADRE QUEDO HUERFANO (SIN HIJOS)
				$formula = $repo->formulaslist($padre_nod_eliminar->getIdArt()->getId(), $this->tc);
				if(empty($formula)){
					$this->em->remove($padre_nod_eliminar);
					$this->em->flush();
					$this->orden_nodo_chld_abajo_delete($padre_nod_eliminar->getLft(), $padre_nod_eliminar->getRgt());
				}
			}
			$this->em->getConnection()->commit();	
		}catch(Exception $e){
			$this->em->getConnection()->rollBack();
			return $e->getMessage();
		}	
		return 1;	
	}
	
	/*
	 * EL NODO PADRE ES UNA FORMULA Y EL NODO HIJO TAMBIEN
	 * */
	protected function padreFormula_hijoFormula()
	{
		$repo = $this->em->getRepository('MbpArticulosBundle:Formulas');
		$repoArt = $this->em->getRepository('MbpArticulosBundle:Articulos');
		
		//TODOS LOS ARTICULOS PADRE FORMULA
		$nodo_a_formular = $repo->busca_nodo_con_art($this->idArt);
				
		//BUSCA NODO PADRE CON ESTRUCTURA A INSERTAR
		$nodo_padre = $repo->busca_nodo_padre_formula($this->idArtFormulado);
				
		//FORMULA HIJO
		$hijo_formula = $repo->formulasEstrucutraMateriales($nodo_padre->getId());
		
		//TODO LO QUE SIGUE A CONTINUACION ES UNA TRANSACCION
		$this->em->getConnection()->beginTransaction();
		
		try{
			foreach ($nodo_a_formular as $nodo) {
				//DIFERENCIA NODOS LFT
				$dif = $hijo_formula[0]->getLft() - ($nodo->getLft() + 1);
				
				$i=0;
				$auxNodo;
				foreach ($hijo_formula as $hijo) {
					$nuevo_nodo = new Formulas();
					$nuevo_nodo->setIdArt($hijo->getIdArt());
					$nuevo_nodo->setLft($hijo->getLft() - $dif);				
					$nuevo_nodo->setRgt($hijo->getRgt() - $dif);
									
					$i++;
					$nuevo_nodo->setCant($hijo->getCant());
					
					$this->em->persist($nuevo_nodo);
					$this->em->persist($nodo);
					
					$auxNodo = $nuevo_nodo;
					
					$this->em->flush();
									
					$this->orden_nodo_lft_abajo($nuevo_nodo->getLft(), $nuevo_nodo->getRgt(), $nodo->getLft(), $nuevo_nodo->getId());
					
					/*
					 * UNA VEZ QUE ORDENO CORRO 2 POSICIONES LOS NODOS ENTONCES DEBO COMPENSAR LA DIFERENCIA SI LA
					 * FORMULA A INSERTAR ESTA DELANTE DEL ARTICULO FORMULADO
					 * */
					if($hijo->getLft() > $nodo->getLft()){
						$dif = $dif + 2;	
					}				
				}						
			}
			$this->em->getConnection()->commit();	
		}catch(Exception $e){
			$this->em->getConnection()->rollBack();
			return $e->getMessage();
		}	
		return $nodo_padre->getId();			
	}
	
	/*
	 * ORDENAMIENTO A PARTIR DE NODO IZQUIERO HACIA ABAJO CUANDO INSERTAMOS UN NODO
	 * */
	protected function orden_nodo_lft_abajo($lft, $rgt, $padreLft, $nodoId)
	{
		$repo = $this->em->getRepository('MbpArticulosBundle:Formulas');
		
		$qb = $repo->createQueryBuilder('f')
			->select('')
			->where('f.lft >= :lftComienzo')
			->orWhere('f.lft = :lftPadre')
			->setParameter('lftComienzo', $lft)
			->setParameter('lftPadre', $padreLft)
			->getQuery()
			->getResult();
				
		foreach ($qb as $nodo) {
			
			if($nodo->getId() == $nodoId){
				//EL NODO A PARTIR DEL CUAL ORDENO NO DEBE SER MODIFICADO
			}elseif($nodo->getLft() == $padreLft){
				//EL NODO PADRE SOLO DEBE MODIFICAR SU LADO DERECHO			
				$nodo->setRgt($nodo->getRgt() + 2); 
				}else{
						$nodo->setLft($nodo->getLft() + 2);
						$nodo->setRgt($nodo->getRgt() + 2);
					}			
			
			$this->em->persist($nodo);
		}
		
		$this->em->flush();
	} 
	
	/*
	 * NODO PADRE ES UNA FORMULA, NODO HIJO NO LO ES
	 * */
	protected function padreFormula_hijoNo()
	{		
		$repo = $this->em->getRepository('MbpArticulosBundle:Formulas');
		$repoArt = $this->em->getRepository('MbpArticulosBundle:Articulos');
		
		$art_a_insertar = $repoArt->find($this->idArtFormulado);
		
		$nodos_a_formular = $repo->busca_nodo_con_art($this->idArt);
		
		//TODO LO QUE SIGUE A CONTINUACION ES UNA TRANSACCION
		$this->em->getConnection()->beginTransaction();
		
		try{
			foreach ($nodos_a_formular as $nodo) {
				$nuevo_nodo = new Formulas();
				$nuevo_nodo->setIdArt($art_a_insertar);
				$nuevo_nodo->setLft($nodo->getLft() + 1);
				$nuevo_nodo->setRgt($nuevo_nodo->getLft() + 1);
				$nuevo_nodo->setCant($this->cantidad);
							
				$this->em->persist($nuevo_nodo);
				$this->em->persist($nodo);
							
				$this->ordenar_nodos_hacia_abajo($nuevo_nodo->getLft());
			}		
			$this->em->flush();
			$this->em->getConnection()->commit();	
		}catch(Exception $e){
			$this->em->getConnection()->rollBack();
			return $e->getMessage();
		}
		
		$padre = $repo->busca_nodo_padre_formula($this->idArt);
		return $padre->getId();
	}
	
	/*
	 * NODO HIJO ES UNA FORMULA Y NODO PADRE NO LO ES
	 * */
	protected function hijoFormula_padreNo()
	{
		$repo = $this->em->getRepository('MbpArticulosBundle:Formulas');
		$repoArt = $this->em->getRepository('MbpArticulosBundle:Articulos');
		
		/*CREO EL ARTICULO PADRE*/		
		$nodo_con_formula = $repo->busca_nodo_padre_formula($this->idArtFormulado);
		
		$formula_hijo = $repo->formulasEstrucutraMateriales($nodo_con_formula->getId());
		
		//BUSCA TODOS LOS NODOS DONDE SE ENCUENTRA EL ARTICULO PADRE
		$nodos_art_padre = $repo->busca_nodo_con_art($this->idArt);
		
		//TODO LO QUE SIGUE A CONTINUACION ES UNA TRANSACCION
		$this->em->getConnection()->beginTransaction();
		
		try{
			$padre = $this->crear_nodo_padre($this->idArt);
						
			
			$dif_lft = $padre->getLft() - $formula_hijo[0]->getLft();
			$flag_cantidad = 0;
			$flag_padre = 0;
			$auxPadre;
						
			foreach ($formula_hijo as $hijo) {
				$nodo = new Formulas();
				$nodo->setLft($hijo->getLft() + $dif_lft + 1);
				$nodo->setRgt($hijo->getRgt() + $dif_lft + 1);
				$nodo->setIdArt($hijo->getIdArt());
				
				if($flag_cantidad == 0){
					$nodo->setCant($this->cantidad);
				}else{
					$nodo->setCant($hijo->getCant());
				}
				
				$auxPadre = $hijo;
				
				$padre->setRgt($nodo->getRgt() + 2);
				$this->em->persist($nodo);
				$this->em->persist($padre);
				
				$flag_cantidad = 1;
				$flag_padre = 1;
			}
			$this->em->flush();	
			
			/*if(!empty($nodos_art_padre)){
				$this->padreFormula_hijoFormula();
			}*/
			
			if($nodos_art_padre != null){
				foreach ($nodos_art_padre as $nodo) {
					//DIFERENCIA NODOS LFT
					$dif = $formula_hijo[0]->getLft() - ($nodo->getLft() + 1);
					
					$flag=0;	//FLAG PARA AL PRIMER NODO PONERLE LA CANTIDAD CORRECTA
					$auxNodo;
					foreach ($formula_hijo as $hijo) {
						$nuevo_nodo = new Formulas();
						$nuevo_nodo->setIdArt($hijo->getIdArt());
						$nuevo_nodo->setLft($hijo->getLft() - $dif);				
						$nuevo_nodo->setRgt($hijo->getRgt() - $dif);
						
						//LA CANTIDAD DEL PRIMER NODO ES CERO PORQUE VIENE DE UNA FORMULA PADRE, ESTO LO EVITAMOS CON EL FLAG
						$flag == 0 ? $nuevo_nodo->setCant($this->cantidad) : $nuevo_nodo->setCant($hijo->getCant());				
						$flag = 1;
						
						
						$this->em->persist($nuevo_nodo);
						$this->em->persist($nodo);
						
						$auxNodo = $nuevo_nodo;
						
						$this->em->flush();
										
						$this->orden_nodo_lft_abajo($nuevo_nodo->getLft(), $nuevo_nodo->getRgt(), $nodo->getLft(), $nuevo_nodo->getId());
						
						/*
						 * UNA VEZ QUE ORDENO CORRO 2 POSICIONES LOS NODOS ENTONCES DEBO COMPENSAR LA DIFERENCIA SI LA
						 * FORMULA A INSERTAR ESTA DELANTE DEL ARTICULO FORMULADO
						 * */
						if($hijo->getLft() > $nodo->getLft()){
							$dif = $dif + 2;	
						}				
					}
				}		
			}
			
						
			
				
			$this->em->getConnection()->commit();	
		}catch(Exception $e){
			$this->em->getConnection()->rollBack();
			return $e->getMessage();
		}
		return $padre->getId();
	}

	
	
	/*
	 * INSERTA UN NODO NUEVO CUANDO PADRE E HIJO NO TIENEN FORMULA
	 * PARAMETROS: ID PADRE, ID HIJO
	 * */
	protected function nuevo_nodo_sin_formula()
	{
		$repo = $this->em->getRepository('MbpArticulosBundle:Formulas');
		$repoArt = $this->em->getRepository('MbpArticulosBundle:Articulos');
		$art = $repoArt->find($this->idArtFormulado);		
		
		//TODO LO QUE SIGUE A CONTINUACION ES UNA TRANSACCION
		$this->em->getConnection()->beginTransaction();
		
		try{
			//SI EL ARTICULO NO SE ENCUENTRA EN NINGUN NODO CREO UN NODO NUEVO
			$padre;
			if($this->es_formula($this->idArt) == false){
				$padre = $this->crear_nodo_padre($this->idArt);
				$hijo = new Formulas();
				$hijo->setIdArt($art);
				$hijo->setLft($padre->getLft() + 1);
				$hijo->setRgt($hijo->getLft() + 1);
				$hijo->setCant($this->cantidad);
				$padre->setRgt($hijo->getRgt() + 1);
				
				$this->em->persist($hijo);
				$this->em->persist($padre);
							
				$this->em->flush();
			}	
			
			
			//BUSCO CADA NODO DONDE SE ENCUENTRE EL ARTICULO
			$nodos = $repo->busca_nodo_con_art($this->idArt);
			foreach ($nodos as $rec) {
				if($rec->getId() != $padre->getId()){
					$nuevo_nodo2 = new Formulas();
					$nuevo_nodo2->setLft($rec->getLft() + 1);
					$nuevo_nodo2->setRgt($nuevo_nodo2->getLft() + 1);
					$nuevo_nodo2->setIdArt($art);
					$nuevo_nodo2->setCant($this->cantidad);
					$rec->setRgt($nuevo_nodo2->getRgt() + 1);
					$this->em->persist($nuevo_nodo2);
					$this->em->persist($rec);
							
					$this->ordenar_nodos_hacia_abajo($nuevo_nodo2->getLft());
					$this->em->flush();
				}	
			}
			
			$this->em->getConnection()->commit();			
		}catch(Exception $e){
			$this->em->getConnection()->rollBack();
			return $e->getMessage();
		}
			
		return $padre->getId();
	}
	
	/*
	 * CREA UN NODO PADRE CON CANTIDAD = 0
	 * PARAMETROS: LFT, RGT, ID ARTICULO PADRE
	 * */	
	protected function crear_nodo_padre($idArtPadre)
	{
		$repo = $this->em->getRepository('MbpArticulosBundle:Formulas');
		$repoArt = $this->em->getRepository('MbpArticulosBundle:Articulos');
		
		$art = $repoArt->find($idArtPadre);
		$nodo_der_max = $this->nodo_derecho_maximo();
		
		$nodo_padre = new Formulas();
		$nodo_padre->setLft($nodo_der_max + 1);
		$nodo_padre->setRgt($nodo_padre->getLft() + 1);
		$nodo_padre->setIdArt($art);
		$nodo_padre->setCant(0);
		
		$this->em->persist($nodo_padre);
		$this->em->flush();
		
		return $nodo_padre;
	}
	
	/*
	 * RETORNA EL MAXIMO NODO DERECHO
	 * */
	protected function nodo_derecho_maximo()
	{
		$repo = $this->em->getRepository('MbpArticulosBundle:Formulas');
		
		$qb = $repo->createQueryBuilder('f')
				->select('MAX(f.rgt)')
				->getQuery()
				->getResult();
		
		return $qb[0][1];
	}

	/*
	 * FUNCION PARA ORDENAR HACIA ABAJO TODOS LOS NODOS
	 * PARAMETROS: LFT DEL NODO DESDE EL CUAL SE EMPEZARA A ORDENAR
	 * */
	protected function ordenar_nodos_hacia_abajo($lftComienzo)
	{
		$repo = $this->em->getRepository('MbpArticulosBundle:Formulas');
		
		$nodoComienzo = $repo->findByLft($lftComienzo-1);
		
		$nodoComienzo = $nodoComienzo[0];
				
		$qb = $repo->createQueryBuilder('f')
			->select('')
			->where('f.lft > :lftComienzo')
			->orWhere('f.rgt > :lftComienzo')
			->setParameter('lftComienzo', $lftComienzo)
			->getQuery()
			->getResult();
		
		foreach ($qb as $nodo) {
			if($nodo->getLft() >= $lftComienzo){
				$nodo->setLft($nodo->getLft() + 2);
			}
			$nodo->setRgt($nodo->getRgt() + 2);
			$this->em->persist($nodo);
		}
		
		$this->em->flush();
	}
	
	

	/*PARAMETRO: ID ARTICULO A FORMULAR
	 * RETORNA: VERDADERO O FALSO SI EL NODO TIENE FORMULA
	 * */
	protected function es_formula($id_art_a_formular)
	{
		$repo = $this->em->getRepository('MbpArticulosBundle:Formulas');
		$repoArt = $this->em->getRepository('MbpArticulosBundle:Articulos');
		
		$art = $repoArt->find($id_art_a_formular);
		
		$qb = $repo->createQueryBuilder('f')
			->select()
			->where('f.cant = 0')
			->andWhere('f.idArt = :idArt')
			->setParameter('idArt', $art)
			->getQuery()
			->getResult();
		
		if(!empty($qb)){
			return true;
			
		}else{
			return false;			
		}
	}
	
	
	/*
	 * ORDENAMIENTO DE UN NODO Y SUS HIJOS PARA DELETE
	 * */
	protected function orden_nodo_chld_abajo_delete($lft, $rgt)
	{
		$repo = $this->em->getRepository('MbpArticulosBundle:Formulas');
		
		$width = $rgt - $lft + 1;
				
		$qb1 = $repo->createQueryBuilder('f')
			->select('')
			->where('f.lft > :myLft')
			->andWhere('f.lft < :myRgt')
			->setParameter('myLft', $lft)
			->setParameter('myRgt', $rgt)
			->getQuery()
			->getResult();
		
		foreach ($qb1 as $nodo) {
			$nodo->setRgt($nodo->getRgt() - 1);
			$this->em->persist($nodo);
			$this->em->flush();
		}
		
		$qb2 = $repo->createQueryBuilder('f')
			->select('')
			->where('f.rgt > :myRgt')
			->setParameter('myRgt', $rgt)
			->getQuery()
			->getResult();
		
		foreach ($qb2 as $nodo) {
			$nodo->setRgt($nodo->getRgt() - 2);
			$this->em->persist($nodo);
			$this->em->flush();
		}
		
		$qb3 = $repo->createQueryBuilder('f')
			->select('')
			->where('f.lft > :myRgt')
			->setParameter('myRgt', $rgt)
			->getQuery()
			->getREsult();
		
		foreach ($qb3 as $nodo) {
			$nodo->setLft($nodo->getLft() - 2);
			$this->em->persist($nodo);
			$this->em->flush();
		}
	}
}
