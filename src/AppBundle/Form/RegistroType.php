<?php

// src/AppBundle/Form/RegistrationType.php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class RegistroType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('firstname', null, [
                    'required' => true,
                    'label' => 'label.form.nombre',
                    'constraints' => [
                        new NotNull()
                    ]
                ])
                ->add('pais', null, [
                    'required' => true,
                    'label' => 'label.form.pais',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('p')
                                ->orderBy('p.nombre');
                    },
                    'placeholder' => 'seleccionar.opcion',
                    'constraints' => [
                        new NotNull()
                    ]
                ])
                ->add('plainPassword', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'options' => array(
                        'translation_domain' => 'FOSUserBundle',
                        'attr' => array(
                            'autocomplete' => 'new-password',
                        ),
                    ),
                    'first_options' => array(
                        'label' => 'form.new_password',
                        'constraints' => [
                            new Length([
                                'min' => 6,
                                    ]),
                            new NotNull()
                        ]
                    ),
                    'second_options' => array('label' => 'form.new_password_confirmation'),
                    'invalid_message' => 'fos_user.password.mismatch',
                ))
        ;
    }

    public function getParent() {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix() {
        return 'app_user_registration';
    }

    // For Symfony 2.x
    public function getName() {
        return $this->getBlockPrefix();
    }

}
