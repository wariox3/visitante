<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CargoRepository extends EntityRepository {
    
    public function ListaDql($nombre = "") {        
        $dql  = "SELECT c FROM AppBundle:Cargo c WHERE c.codigoCargoPk <> 0 ";
        if($nombre != "" ) {
            $dql .= " AND c.nombre LIKE '%" . $nombre . "%'";
        }        
        $dql .= " ORDER BY c.nombre";
        return $dql;
    }
}