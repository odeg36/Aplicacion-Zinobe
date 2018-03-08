<?php

/**
 * This file is part of the <name> project.
 *
 * (c) <yourname> <youremail>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\UserBundle\Entity;

use Application\Sonata\UserBundle\Document\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Table(name="usuario")
 * @ORM\Entity(repositoryClass="Application\Sonata\UserBundle\Repository\UserRepository")
 * @ORM\InheritanceType("JOINED")
 */
class User extends BaseUser {

    public function __toString() {
        if (trim($this->getFullname())) {
            return $this->getFullname();
        } else if ($this->getUsername()) {
            return $this->getUsername();
        }

        return '';
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", nullable=true)
     */
    protected $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", nullable=true)
     */
    protected $lastname;

    /**
     * @ORM\ManyToMany(targetEntity="Application\Sonata\UserBundle\Entity\Group")
     * @ORM\JoinTable(name="usuario_grupo",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     * @Serializer\Exclude
     */
    protected $groups;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Pais", inversedBy="usuarios")
     * @ORM\JoinColumn(name="pais_id", referencedColumnName="id", nullable=true)
     */
    private $pais;

    /**
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("names")
     */
    public function first_name() {
        return $this->getFirstname() . " " . $this->getLastname();
    }


    /**
     * Set pais.
     *
     * @param \AppBundle\Entity\Pais|null $pais
     *
     * @return User
     */
    public function setPais(\AppBundle\Entity\Pais $pais = null)
    {
        $this->pais = $pais;

        return $this;
    }

    /**
     * Get pais.
     *
     * @return \AppBundle\Entity\Pais|null
     */
    public function getPais()
    {
        return $this->pais;
    }
}
