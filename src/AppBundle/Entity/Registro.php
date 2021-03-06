<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="registro")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RegistroRepository")
 */
class Registro
{
     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_registro_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoRegistroPk;
    
    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer")
     */    
    private $codigoClienteFk;    
    
    /**
     * @ORM\Column(name="codigo_visitante_fk", type="integer")
     */    
    private $codigoVisitanteFk;    
    
    /**
     * @ORM\Column(name="codigo_grupo_fk", type="integer")
     */    
    private $codigoGrupoFk;
    
    /**
     * @ORM\Column(name="codigo_cargo_fk", type="integer", nullable=true)
     */    
    private $codigoCargoFk;     
    
    /**
     * @ORM\Column(name="fecha_entrada", type="datetime", nullable=true)
     */    
    private $fechaEntrada;
    
    /**
     * @ORM\Column(name="fecha_salida", type="datetime", nullable=true)
     */    
    private $fechaSalida;     
    
    /**     
     * @ORM\Column(name="estado_entrada", type="boolean")
     */    
    private $estadoEntrada = FALSE;
    
    /**     
     * @ORM\Column(name="estado_salida", type="boolean")
     */    
    private $estadoSalida = FALSE;    

    /**
     * @ORM\Column(name="fecha_arl", type="datetime", nullable=true)
     */    
    private $fechaArl;    

    /**     
     * @ORM\Column(name="arl_vencida", type="boolean")
     */    
    private $arlVencida = FALSE;
    
    /**
     * @ORM\ManyToOne(targetEntity="Cliente", inversedBy="registrosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="Visitante", inversedBy="registrosVisitanteRel")
     * @ORM\JoinColumn(name="codigo_visitante_fk", referencedColumnName="codigo_visitante_pk")
     */
    protected $visitanteRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="Grupo", inversedBy="registrosGrupoRel")
     * @ORM\JoinColumn(name="codigo_grupo_fk", referencedColumnName="codigo_grupo_pk")
     */
    protected $grupoRel;    

    /**
     * @ORM\ManyToOne(targetEntity="Cargo", inversedBy="resgistrosCargoRel")
     * @ORM\JoinColumn(name="codigo_cargo_fk", referencedColumnName="codigo_cargo_pk")
     */
    protected $cargoRel;    


    /**
     * Get codigoRegistroPk
     *
     * @return integer
     */
    public function getCodigoRegistroPk()
    {
        return $this->codigoRegistroPk;
    }

    /**
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return Registro
     */
    public function setCodigoClienteFk($codigoClienteFk)
    {
        $this->codigoClienteFk = $codigoClienteFk;

        return $this;
    }

    /**
     * Get codigoClienteFk
     *
     * @return integer
     */
    public function getCodigoClienteFk()
    {
        return $this->codigoClienteFk;
    }

    /**
     * Set codigoVisitanteFk
     *
     * @param integer $codigoVisitanteFk
     *
     * @return Registro
     */
    public function setCodigoVisitanteFk($codigoVisitanteFk)
    {
        $this->codigoVisitanteFk = $codigoVisitanteFk;

        return $this;
    }

    /**
     * Get codigoVisitanteFk
     *
     * @return integer
     */
    public function getCodigoVisitanteFk()
    {
        return $this->codigoVisitanteFk;
    }

    /**
     * Set codigoGrupoFk
     *
     * @param integer $codigoGrupoFk
     *
     * @return Registro
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
     * Set fechaEntrada
     *
     * @param \DateTime $fechaEntrada
     *
     * @return Registro
     */
    public function setFechaEntrada($fechaEntrada)
    {
        $this->fechaEntrada = $fechaEntrada;

        return $this;
    }

    /**
     * Get fechaEntrada
     *
     * @return \DateTime
     */
    public function getFechaEntrada()
    {
        return $this->fechaEntrada;
    }

    /**
     * Set fechaSalida
     *
     * @param \DateTime $fechaSalida
     *
     * @return Registro
     */
    public function setFechaSalida($fechaSalida)
    {
        $this->fechaSalida = $fechaSalida;

        return $this;
    }

    /**
     * Get fechaSalida
     *
     * @return \DateTime
     */
    public function getFechaSalida()
    {
        return $this->fechaSalida;
    }

    /**
     * Set estadoEntrada
     *
     * @param boolean $estadoEntrada
     *
     * @return Registro
     */
    public function setEstadoEntrada($estadoEntrada)
    {
        $this->estadoEntrada = $estadoEntrada;

        return $this;
    }

    /**
     * Get estadoEntrada
     *
     * @return boolean
     */
    public function getEstadoEntrada()
    {
        return $this->estadoEntrada;
    }

    /**
     * Set estadoSalida
     *
     * @param boolean $estadoSalida
     *
     * @return Registro
     */
    public function setEstadoSalida($estadoSalida)
    {
        $this->estadoSalida = $estadoSalida;

        return $this;
    }

    /**
     * Get estadoSalida
     *
     * @return boolean
     */
    public function getEstadoSalida()
    {
        return $this->estadoSalida;
    }

    /**
     * Set fechaArl
     *
     * @param \DateTime $fechaArl
     *
     * @return Registro
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
     * Set arlVencida
     *
     * @param boolean $arlVencida
     *
     * @return Registro
     */
    public function setArlVencida($arlVencida)
    {
        $this->arlVencida = $arlVencida;

        return $this;
    }

    /**
     * Get arlVencida
     *
     * @return boolean
     */
    public function getArlVencida()
    {
        return $this->arlVencida;
    }

    /**
     * Set clienteRel
     *
     * @param \AppBundle\Entity\Cliente $clienteRel
     *
     * @return Registro
     */
    public function setClienteRel(\AppBundle\Entity\Cliente $clienteRel = null)
    {
        $this->clienteRel = $clienteRel;

        return $this;
    }

    /**
     * Get clienteRel
     *
     * @return \AppBundle\Entity\Cliente
     */
    public function getClienteRel()
    {
        return $this->clienteRel;
    }

    /**
     * Set visitanteRel
     *
     * @param \AppBundle\Entity\Visitante $visitanteRel
     *
     * @return Registro
     */
    public function setVisitanteRel(\AppBundle\Entity\Visitante $visitanteRel = null)
    {
        $this->visitanteRel = $visitanteRel;

        return $this;
    }

    /**
     * Get visitanteRel
     *
     * @return \AppBundle\Entity\Visitante
     */
    public function getVisitanteRel()
    {
        return $this->visitanteRel;
    }

    /**
     * Set grupoRel
     *
     * @param \AppBundle\Entity\Grupo $grupoRel
     *
     * @return Registro
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
     * Set codigoCargoFk
     *
     * @param integer $codigoCargoFk
     *
     * @return Registro
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
     * Set cargoRel
     *
     * @param \AppBundle\Entity\Cargo $cargoRel
     *
     * @return Registro
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
}
