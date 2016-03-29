<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="visitante")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VisitanteRepository")
 */
class Visitante
{
     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_visitante_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoVisitantePk;
    
    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=25, nullable=true)
     */    
    private $numeroIdentificacion;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=120, nullable=true)
     */    
    private $nombre;          
       
    /**
     * @ORM\OneToMany(targetEntity="Registro", mappedBy="visitanteRel")
     */
    protected $registrosVisitanteRel;    
    

    /**
     * Get codigoVisitantePk
     *
     * @return integer
     */
    public function getCodigoVisitantePk()
    {
        return $this->codigoVisitantePk;
    }

    /**
     * Set numeroIdentificacion
     *
     * @param string $numeroIdentificacion
     *
     * @return Visitante
     */
    public function setNumeroIdentificacion($numeroIdentificacion)
    {
        $this->numeroIdentificacion = $numeroIdentificacion;

        return $this;
    }

    /**
     * Get numeroIdentificacion
     *
     * @return string
     */
    public function getNumeroIdentificacion()
    {
        return $this->numeroIdentificacion;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Visitante
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->registrosVisitanteRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add registrosVisitanteRel
     *
     * @param \AppBundle\Entity\Registro $registrosVisitanteRel
     *
     * @return Visitante
     */
    public function addRegistrosVisitanteRel(\AppBundle\Entity\Registro $registrosVisitanteRel)
    {
        $this->registrosVisitanteRel[] = $registrosVisitanteRel;

        return $this;
    }

    /**
     * Remove registrosVisitanteRel
     *
     * @param \AppBundle\Entity\Registro $registrosVisitanteRel
     */
    public function removeRegistrosVisitanteRel(\AppBundle\Entity\Registro $registrosVisitanteRel)
    {
        $this->registrosVisitanteRel->removeElement($registrosVisitanteRel);
    }

    /**
     * Get registrosVisitanteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRegistrosVisitanteRel()
    {
        return $this->registrosVisitanteRel;
    }
}
