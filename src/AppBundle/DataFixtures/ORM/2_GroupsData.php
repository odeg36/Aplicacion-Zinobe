<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Application\Sonata\UserBundle\Entity\Group;

class GroupsData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface {

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    public function getOrder() {
        return 2;
    }

    public function load(ObjectManager $manager) {
        $objects = [
            [
                "name" => "Administrador",
                "roles" => [
                    "ROLE_ADMIN", "ROLE_SUPER_ADMIN"
                ]
            ],
            [
                "name" => "Usuarios de consulta",
                "roles" => [
                    "ROLE_ADMIN"
                ]
            ],
        ];

        $em = $this->container->get('doctrine')->getManager();
        foreach ($objects as $object) {
            $registro = $em->getRepository('ApplicationSonataUserBundle:Group')->findOneBy(array('name' => $object["name"]));
            if (!$registro) {
                $entity = new Group([]);

                $entity->setName($object["name"]);
                $entity->setRoles($object["roles"]);

                $this->setReference("group_" . $object["name"], $entity);
                $manager->persist($entity);
            }
        }
        $manager->flush();
    }

}
