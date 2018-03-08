<?php

namespace AppBundle\Handler;

use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Listener responsible to change the redirection at the end of the password resetting
 */
class RegistroHandler implements EventSubscriberInterface {

    private $router;
    private $container;
    private $session;

    public function __construct(UrlGeneratorInterface $router, ContainerInterface $container, Session $session) {
        $this->router = $router;
        $this->container = $container;
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents() {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => ['onRegistrationSuccess', -10],
            FOSUserEvents::REGISTRATION_INITIALIZE => ['onRegistrationInitialize', -10],
        );
    }

    public function onRegistrationInitialize(GetResponseUserEvent $event) {
        $usuario = $this->container->get('security.token_storage')->getToken()->getUser();
        if (is_object($usuario)) {
            $translation = $this->container->get('translator');
            $this->session->getFlashBag()->add('error', $translation->trans('sonata_user_already_authenticated', [], 'SonataUserBundle'));
            $url = $this->router->generate('sonata_admin_dashboard');
        }
    }

    public function onRegistrationSuccess(FormEvent $event) {
        $user = $event->getForm()->getData();

        $user->setEnabled(true);
        $em = $this->container->get('doctrine')->getManager();
        $grupo = $em->getRepository('ApplicationSonataUserBundle:Group')->findOneByName('Usuarios de consulta');
        $user->addGroup($grupo);
        $em->persist($user);
        $em->flush();
        $url = $this->router->generate('fos_user_registration_confirmed');
        $event->setResponse(new RedirectResponse($url));
    }

}
