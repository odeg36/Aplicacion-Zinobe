<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Application\Sonata\UserBundle\Entity\User;

class ConstraintsNombreValidator extends ConstraintValidator {

    protected $object;
    protected $em;
    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        $this->em = $container->get("doctrine")->getManager();
    }

    public function initialize(\Symfony\Component\Validator\Context\ExecutionContextInterface $context) {
        parent::initialize($context);

        $this->object = $context->getRoot()->getData();
    }

    public function validate($value, Constraint $constraint) {
        if (!$value) {
            return true;
        }

        foreach ($this->object->getVentas() as $venta){
            $nombreBrayan = explode(trim(User::NOMBRE_BRAYAN), trim($venta->getCliente()->getFullname()));
            $nombreJulieth = explode(trim(User::NOMBRE_JULIETH), trim($venta->getCliente()->getFullname()));
            
            if(count($nombreBrayan) > 1){
                $this->context->buildViolation($constraint->message, ["%nombre%" => User::NOMBRE_BRAYAN])
                    ->atPath('cliente')
                    ->addViolation();

                return true;
            }elseif (count($nombreJulieth) > 1) {
                $this->context->buildViolation($constraint->message, ["%nombre%" => User::NOMBRE_JULIETH])
                    ->atPath('cliente')
                    ->addViolation();

                return true;
            }
        }
    }
}
