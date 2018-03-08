<?php

namespace AppBundle\DataFixtures\ORM;

use Application\Sonata\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UsersData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface {

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
        return 1;
    }

    public function load(ObjectManager $manager) {
        $users = array(
            array(
                'name' => "administrador",
                'email' => "admin@correo.com",
                'password' => '1q2w3e4r5t',
                'superadmin' => true,
            ),
        );
        $em = $this->container->get('doctrine')->getManager();
        foreach ($users as $user) {
            $registro = $em->getRepository('ApplicationSonataUserBundle:User')->findOneBy(array('email' => $user["email"]));
            if (!$registro) {
                $entity = new User();

                $entity->setEmail($user["email"]);
                $entity->setEmailCanonical($user["email"]);
                $entity->setUsername($user["name"]);
                $entity->setUsernameCanonical($user["name"]);
                $entity->setFirstname($user["name"]);

                $password = $this->container->get('security.password_encoder')
                        ->encodePassword($entity, $user["password"]);

                $entity->setPassword($password);
                $entity->setSuperAdmin($user["superadmin"]);
                $entity->setEnabled(true);

                $manager->persist($entity);
            }
        }
        $manager->flush();
    }

}
