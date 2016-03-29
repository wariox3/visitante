<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="cliente")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClienteRepository")
 */
class Cliente
{
     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cliente_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoClientePk;
    
    /**
     * @ORM\Column(name="nit", type="string", length=25, nullable=true)
     */    
    private $nit;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=120, nullable=true)
     */    
    private $nombre;          
       
    /**
     * @ORM\OneToMany(targetEntity="Registro", mappedBy="clienteRel")
     */
    protected $registrosClienteRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="ClienteRel")
     */
    protected $usuariosClienteRel; 
    
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

    /**
     * Get codigoClientePk
     *
     * @return integer
     */
    public function getCodigoClientePk()
    {
        return $this->codigoClientePk;
    }

    /**
     * Set nit
     *
     * @param string $nit
     *
     * @return Cliente
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
     * Add registrosClienteRel
     *
     * @param \AppBundle\Entity\Registro $registrosClienteRel
     *
     * @return Cliente
     */
    public function addRegistrosClienteRel(\AppBundle\Entity\Registro $registrosClienteRel)
    {
        $this->registrosClienteRel[] = $registrosClienteRel;

        return $this;
    }

    /**
     * Remove registrosClienteRel
     *
     * @param \AppBundle\Entity\Registro $registrosClienteRel
     */
    public function removeRegistrosClienteRel(\AppBundle\Entity\Registro $registrosClienteRel)
    {
        $this->registrosClienteRel->removeElement($registrosClienteRel);
    }

    /**
     * Get registrosClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRegistrosClienteRel()
    {
        return $this->registrosClienteRel;
    }

    /**
     * Add usuariosClienteRel
     *
     * @param \AppBundle\Entity\User $usuariosClienteRel
     *
     * @return Cliente
     */
    public function addUsuariosClienteRel(\AppBundle\Entity\User $usuariosClienteRel)
    {
        $this->usuariosClienteRel[] = $usuariosClienteRel;

        return $this;
    }

    /**
     * Remove usuariosClienteRel
     *
     * @param \AppBundle\Entity\User $usuariosClienteRel
     */
    public function removeUsuariosClienteRel(\AppBundle\Entity\User $usuariosClienteRel)
    {
        $this->usuariosClienteRel->removeElement($usuariosClienteRel);
    }

    /**
     * Get usuariosClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsuariosClienteRel()
    {
        return $this->usuariosClienteRel;
    }
}
