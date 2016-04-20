<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="grupo")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GrupoRepository")
 */
class Grupo
{
     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_grupo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoGrupoPk;   

    /**
     * @ORM\Column(name="nit", type="string", length=20, nullable=true)
     */    
    private $nit;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=120, nullable=true)
     */    
    private $nombre;          
       
    /**
     * @ORM\Column(name="direccion", type="string", length=120, nullable=true)
     */    
    private $direccion;     

    /**
     * @ORM\Column(name="telefono", type="string", length=40, nullable=true)
     */    
    private $telefono;
    
    /**
     * @ORM\Column(name="fecha_arl", type="datetime", nullable=true)
     */    
    private $fechaArl;    
    
    /**
     * @ORM\OneToMany(targetEntity="Visitante", mappedBy="grupoRel")
     */
    protected $visitantesGrupoRel; 

    /**
     * @ORM\OneToMany(targetEntity="Registro", mappedBy="grupoRel")
     */
    protected $registrosGrupoRel;    
    
    /**
     * Get codigoGrupoPk
     *
     * @return integer
     */
    public function getCodigoGrupoPk()
    {
        return $this->codigoGrupoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Grupo
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
        $this->visitantesGrupoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add visitantesGrupoRel
     *
     * @param \AppBundle\Entity\Visitante $visitantesGrupoRel
     *
     * @return Grupo
     */
    public function addVisitantesGrupoRel(\AppBundle\Entity\Visitante $visitantesGrupoRel)
    {
        $this->visitantesGrupoRel[] = $visitantesGrupoRel;

        return $this;
    }

    /**
     * Remove visitantesGrupoRel
     *
     * @param \AppBundle\Entity\Visitante $visitantesGrupoRel
     */
    public function removeVisitantesGrupoRel(\AppBundle\Entity\Visitante $visitantesGrupoRel)
    {
        $this->visitantesGrupoRel->removeElement($visitantesGrupoRel);
    }

    /**
     * Get visitantesGrupoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVisitantesGrupoRel()
    {
        return $this->visitantesGrupoRel;
    }

    /**
     * Add registrosGrupoRel
     *
     * @param \AppBundle\Entity\Registro $registrosGrupoRel
     *
     * @return Grupo
     */
    public function addRegistrosGrupoRel(\AppBundle\Entity\Registro $registrosGrupoRel)
    {
        $this->registrosGrupoRel[] = $registrosGrupoRel;

        return $this;
    }

    /**
     * Remove registrosGrupoRel
     *
     * @param \AppBundle\Entity\Registro $registrosGrupoRel
     */
    public function removeRegistrosGrupoRel(\AppBundle\Entity\Registro $registrosGrupoRel)
    {
        $this->registrosGrupoRel->removeElement($registrosGrupoRel);
    }

    /**
     * Get registrosGrupoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRegistrosGrupoRel()
    {
        return $this->registrosGrupoRel;
    }

    /**
     * Set nit
     *
     * @param string $nit
     *
     * @return Grupo
     */
    public function setNit($nit)
    {
        $this->nit = $nit;

        return $this;
    }

    /**
     * Get nit
     *
     * @return string
     */
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return Grupo
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set fechaArl
     *
     * @param \DateTime $fechaArl
     *
     * @return Grupo
     */
    public function setFechaArl($fechaArl)
    {
        $this->fechaArl = $fechaArl;

        return $this;
    }

    /**
     * Get fechaArl
     *
     * @return \DateTime
     */
    public function getFechaArl()
    {
        return $this->fechaArl;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return Grupo
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }
}
