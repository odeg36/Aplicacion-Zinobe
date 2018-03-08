<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Admin;

use Doctrine\ORM\EntityRepository;
use FOS\UserBundle\Model\UserManagerInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class UsuarioAdmin extends AbstractAdmin {
    
    protected $baseRoutePattern = 'usuarios';
    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    /**
     * {@inheritdoc}
     */
    public function getExportFields() {
        // avoid security field to be exported
        return array_filter(parent::getExportFields(), function ($v) {
            return !in_array($v, array('password', 'salt'));
        });
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($user) {
        $this->getUserManager()->updateCanonicalFields($user);
        $this->getUserManager()->updatePassword($user);
    }

    /**
     * @param UserManagerInterface $userManager
     */
    public function setUserManager(UserManagerInterface $userManager) {
        $this->userManager = $userManager;
    }

    /**
     * @return UserManagerInterface
     */
    public function getUserManager() {
        return $this->userManager;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->addIdentifier('username', null, [
                    'label' => 'label.form.nombre.usuario'
                ])
                ->add('email', null, [
                    'label' => 'label.form.email'
                ])
                ->add('groups', null, [
                    'label' => 'label.form.grupos',
                    'sortable' => 'groups.name'
                ])
                ->add('enabled', null, array(
                    'editable' => true,
                    'label' => 'label.form.habilitado'
                ))
                ->add('createdAt', null, [
                    'label' => 'label.form.fecha.creacion'
        ]);
        if ($this->isGranted('ROLE_ALLOWED_TO_SWITCH')) {
            $listMapper
                    ->add('impersonating', 'string', [
                        'label' => 'titulo.impersonating',
                        'template' => 'SonataUserBundle:Admin:Field/impersonating.html.twig'
                    ])
            ;
        }
        $listMapper->add('_action', null, array(
            'actions' => array(
                'show' => array(),
                'edit' => array()
            )
        ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $filterMapper) {
        $filterMapper
                ->add('username', null, [
                    'label' => 'label.form.nombre.usuario'
                ])
                ->add('email', null, [
                    'label' => 'label.form.email'
                ])
                ->add('groups', null, [
                    'label' => 'label.form.grupos'
                ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper
                ->with('General')
                ->add('username', null, [
                    'label' => 'label.form.nombre.usuario'
                ])
                ->add('email', null, [
                    'label' => 'label.form.email'
                ])
                ->end()
                ->with('label.form.grupos')
                ->add('groups', null, [
                    'label' => 'label.form.grupos'
                ])
                ->add('roles', null, [
                    'label' => 'label.form.roles'
                ])
                ->end()
                ->with('label.form.perfil')
                ->add('firstname', null, [
                    'label' => 'label.form.perfil'
                ])
                ->add('lastname', null, [
                    'label' => 'label.form.nombre'
                ])
                ->add('gender', null, [
                    'label' => 'label.form.genero'
                ])
                ->add('phone', null, [
                    'label' => 'label.form.telefono'
                ])
                ->add('createdAt', null, [
                    'label' => 'label.form.fecha.creacion'
                ])
                ->add('enabled', null, array(
                    'label' => 'label.form.habilitado'
                ))
                ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper) {
        // define group zoning
        $formMapper
                ->tab('label.form.usuario')
                ->with('label.form.perfil', array('class' => 'col-md-6'))->end()
                ->with('General', array('class' => 'col-md-6'))->end()
                ->end();

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $formMapper->tab('label.form.seguridad')
                    ->with('label.form.estado', array('class' => 'col-md-4'))->end()
                    ->with('label.form.grupos', array('class' => 'col-md-4'))->end()
                    ->with('label.form.roles', array('class' => 'col-md-12'))->end()
                    ->end();
        }


        // NEXT_MAJOR: Keep FQCN when bumping Symfony requirement to 2.8+.
        if (method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix')) {
            $textType = 'Symfony\Component\Form\Extension\Core\Type\TextType';
            $datePickerType = 'Sonata\CoreBundle\Form\Type\DatePickerType';
            $urlType = 'Symfony\Component\Form\Extension\Core\Type\UrlType';
            $userGenderType = 'Sonata\UserBundle\Form\Type\UserGenderListType';
            $localeType = 'Symfony\Component\Form\Extension\Core\Type\LocaleType';
            $timezoneType = 'Symfony\Component\Form\Extension\Core\Type\TimezoneType';
            $modelType = 'Sonata\AdminBundle\Form\Type\ModelType';
            $securityRolesType = 'Sonata\UserBundle\Form\Type\SecurityRolesType';
        } else {
            $textType = 'text';
            $datePickerType = 'sonata_type_date_picker';
            $urlType = 'url';
            $userGenderType = 'sonata_user_gender';
            $localeType = 'locale';
            $timezoneType = 'timezone';
            $modelType = 'sonata_type_model';
            $securityRolesType = 'sonata_security_roles';
        }
        $object = $this->getSubject();

        $formMapper
                ->tab('label.form.usuario')
                ->with('General')
                ->add('username', null, [
                    'label' => 'label.form.nombre.usuario',
                    'constraints' => [
                        new Assert\NotBlank()
                    ]
                ])
                ->add('email', null, [
                    'label' => 'label.form.email',
                    'constraints' => [
                        new Assert\NotBlank()
                    ]
                ])
        ;

        if (!$object->getId()) {
            $formMapper->add('plainPassword', $textType, array(
                'label' => 'label.form.clave',
                'constraints' => [
                    new Length([
                        'min' => 6,
                            ]),
                    new NotNull()
                ],
                'required' => (!$this->getSubject() || is_null($this->getSubject()->getId())),
            ));
        }
        $formMapper->end()
                ->with('label.form.perfil')
                ->add('firstname', null, array(
                    'required' => false,
                    'label' => 'label.form.nombres'
                ))
                ->add('lastname', null, array(
                    'required' => false,
                    'label' => 'label.form.apellidos'
                ))
                ->add('phone', 'number', array(
                    'required' => false,
                    'label' => 'label.form.telefono'
                ))
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
                ->end()
                ->end();

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $formMapper->tab('label.form.seguridad')
                    ->with('label.form.estado')
                    ->add('enabled', null, [
                        'label' => 'label.form.habilitado',
                        'required' => false
                    ])
                    ->end()
                    ->with('label.form.grupos')
                    ->add('groups', $modelType, array(
                        'required' => true,
                        'expanded' => true,
                        'multiple' => true,
                        'btn_add' => false,
                        'constraints' => [
                            new Assert\Count(array(
                                'min' => 1
                                    ))
                        ]
                    ))
                    ->end()
                    ->with('label.form.roles')
                    ->add('realRoles', $securityRolesType, array(
                        'label' => 'label.form.roles',
                        'multiple' => true,
                        'required' => false,
                    ))
                    ->end()
                    ->end()
            ;
        }
    }

}
