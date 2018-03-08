<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity
 *
 * @ORM\Table(name="pais")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PaisRepository")
 */
class Pais {

    public function __toString() {
        return $this->nombre ?: '';
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255, unique=true)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="sigla", type="string", length=255, unique=true)
     */
    private $sigla;

    /**
     * @ORM\OneToMany(targetEntity="Application\Sonata\UserBundle\Entity\User", mappedBy="pais")
     */
    private $usuarios;

    /**
     * Constructor
     */
    public function __construct() {
        $this->usuarios = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nombre.
     *
     * @param string $nombre
     *
     * @return Pais
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre.
     *
     * @return string
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Add usuario.
     *
     * @param \Application\Sonata\UserBundle\Entity\User $usuario
     *
     * @return Pais
     */
    public function addUsuario(\Application\Sonata\UserBundle\Entity\User $usuario) {
        $this->usuarios[] = $usuario;

        return $this;
    }

    /**
     * Remove usuario.
     *
     * @param \Application\Sonata\UserBundle\Entity\User $usuario
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeUsuario(\Application\Sonata\UserBundle\Entity\User $usuario) {
        return $this->usuarios->removeElement($usuario);
    }

    /**
     * Get usuarios.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsuarios() {
        return $this->usuarios;
    }

    /**
     * Set sigla.
     *
     * @param string $sigla
     *
     * @return Pais
     */
    public function setSigla($sigla) {
        $this->sigla = $sigla;

        return $this;
    }

    /**
     * Get sigla.
     *
     * @return string
     */
    public function getSigla() {
        return $this->sigla;
    }

}
