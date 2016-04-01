<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="cargo")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CargoRepository")
 */
class Cargo
{
     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cargo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCargoPk;   
    
    /**
     * @ORM\Column(name="nombre", type="string", length=120, nullable=true)
     */    
    private $nombre;          
       
    /**
     * @ORM\OneToMany(targetEntity="Visitante", mappedBy="cargoRel")
     */
    protected $visitantesCargoRel; 

    /**
     * @ORM\OneToMany(targetEntity="Registro", mappedBy="cargoRel")
     */
    protected $registrosCargoRel;    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->visitantesCargoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->registrosCargoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoCargoPk
     *
     * @return integer
     */
    public function getCodigoCargoPk()
    {
        return $this->codigoCargoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Cargo
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
     * Add visitantesCargoRel
     *
     * @param \AppBundle\Entity\Visitante $visitantesCargoRel
     *
     * @return Cargo
     */
    public function addVisitantesCargoRel(\AppBundle\Entity\Visitante $visitantesCargoRel)
    {
        $this->visitantesCargoRel[] = $visitantesCargoRel;

        return $this;
    }

    /**
     * Remove visitantesCargoRel
     *
     * @param \AppBundle\Entity\Visitante $visitantesCargoRel
     */
    public function removeVisitantesCargoRel(\AppBundle\Entity\Visitante $visitantesCargoRel)
    {
        $this->visitantesCargoRel->removeElement($visitantesCargoRel);
    }

    /**
     * Get visitantesCargoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVisitantesCargoRel()
    {
        return $this->visitantesCargoRel;
    }

    /**
     * Add registrosCargoRel
     *
     * @param \AppBundle\Entity\Registro $registrosCargoRel
     *
     * @return Cargo
     */
    public function addRegistrosCargoRel(\AppBundle\Entity\Registro $registrosCargoRel)
    {
        $this->registrosCargoRel[] = $registrosCargoRel;

        return $this;
    }

    /**
     * Remove registrosCargoRel
     *
     * @param \AppBundle\Entity\Registro $registrosCargoRel
     */
    public function removeRegistrosCargoRel(\AppBundle\Entity\Registro $registrosCargoRel)
    {
        $this->registrosCargoRel->removeElement($registrosCargoRel);
    }

    /**
     * Get registrosCargoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRegistrosCargoRel()
    {
        return $this->registrosCargoRel;
    }
}
