<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GrupoRepository extends EntityRepository {
    
    public function ListaDql() {        
        $dql  = "SELECT g FROM AppBundle:Visitante v WHERE v.codigoVisitantePk <> 0 ";
        $dql .= "ORDER BY v.nombre";
        return $dql;
    }
}