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
     * @ORM\Column(name="nombre", type="string", length=120, nullable=true)
     */    
    private $nombre;          
       
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
}
