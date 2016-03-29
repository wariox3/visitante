<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RegistroRepository extends EntityRepository {
    
    public function ListaDql($boolEstadoEntrada = "", $boolEstadoSalida = "") {        
        $dql  = "SELECT r FROM AppBundle:Registro r WHERE r.codigoRegistroPk <> 0 ";
        if($boolEstadoEntrada == 1 ) {
            $dql .= " AND r.estadoEntrada = 1";
        }
        if($boolEstadoEntrada == "0") {
            $dql .= " AND r.estadoEntrada = 0";
        }        
        if($boolEstadoSalida == 1 ) {
            $dql .= " AND r.estadoSalida = 1";
        }
        if($boolEstadoSalida == "0") {
            $dql .= " AND r.estadoSalida = 0";
        }        
        return $dql;
    }
}