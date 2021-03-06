<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Table(name="visitante")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VisitanteRepository")
 * @DoctrineAssert\UniqueEntity(fields={"numeroIdentificacion"},message="Ya existe este número de identificación")
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
     * @ORM\Column(name="codigo", type="string", length=9, nullable=true, unique=true)
     */    
    private $codigo;    
    
    /**
     * @ORM\Column(name="codigo_grupo_fk", type="integer")
     */    
    private $codigoGrupoFk;    
    
    /**
     * @ORM\Column(name="codigo_cargo_fk", type="integer", nullable=true)
     */    
    private $codigoCargoFk;    
    
    /**
     * @ORM\Column(name="fecha_arl", type="datetime", nullable=true)
     */    
    private $fechaArl;    
    
    /**
     * @ORM\ManyToOne(targetEntity="Grupo", inversedBy="visitantesGrupoRel")
     * @ORM\JoinColumn(name="codigo_grupo_fk", referencedColumnName="codigo_grupo_pk")
     */
    protected $grupoRel;    

    /**
     * @ORM\ManyToOne(targetEntity="Cargo", inversedBy="visitantesCargoRel")
     * @ORM\JoinColumn(name="codigo_cargo_fk", referencedColumnName="codigo_cargo_pk")
     */
    protected $cargoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="Registro", mappedBy="visitanteRel")
     */
    protected $registrosVisitanteRel;    
    


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->registrosVisitanteRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set codigo
     *
     * @param string $codigo
     *
     * @return Visitante
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set codigoGrupoFk
     *
     * @param integer $codigoGrupoFk
     *
     * @return Visitante
     */
    public function setCodigoGrupoFk($codigoGrupoFk)
    {
        $this->codigoGrupoFk = $codigoGrupoFk;

        return $this;
    }

    /**
     * Get codigoGrupoFk
     *
     * @return integer
     */
    public function getCodigoGrupoFk()
    {
        return $this->codigoGrupoFk;
    }

    /**
     * Set codigoCargoFk
     *
     * @param integer $codigoCargoFk
     *
     * @return Visitante
     */
    public function setCodigoCargoFk($codigoCargoFk)
    {
        $this->codigoCargoFk = $codigoCargoFk;

        return $this;
    }

    /**
     * Get codigoCargoFk
     *
     * @return integer
     */
    public function getCodigoCargoFk()
    {
        return $this->codigoCargoFk;
    }

    /**
     * Set fechaArl
     *
     * @param \DateTime $fechaArl
     *
     * @return Visitante
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
     * Set grupoRel
     *
     * @param \AppBundle\Entity\Grupo $grupoRel
     *
     * @return Visitante
     */
    public function setGrupoRel(\AppBundle\Entity\Grupo $grupoRel = null)
    {
        $this->grupoRel = $grupoRel;

        return $this;
    }

    /**
     * Get grupoRel
     *
     * @return \AppBundle\Entity\Grupo
     */
    public function getGrupoRel()
    {
        return $this->grupoRel;
    }

    /**
     * Set cargoRel
     *
     * @param \AppBundle\Entity\Cargo $cargoRel
     *
     * @return Visitante
     */
    public function setCargoRel(\AppBundle\Entity\Cargo $cargoRel = null)
    {
        $this->cargoRel = $cargoRel;

        return $this;
    }

    /**
     * Get cargoRel
     *
     * @return \AppBundle\Entity\Cargo
     */
    public function getCargoRel()
    {
        return $this->cargoRel;
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
