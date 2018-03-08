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

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Validator\Constraints as Assert;

class GrupoAdmin extends AbstractAdmin {

    protected $baseRoutePattern = 'permisos';

    /**
     * {@inheritdoc}
     */
    public function getNewInstance() {
        $class = $this->getClass();

        return new $class('', array());
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->addIdentifier('name', null, [
                    'label' => 'label.form.nombre'
                ])
                ->add('roles', null, [
                    'label' => 'label.form.roles',
                    'template' => 'AppBundle:Grupo/List:roles.html.twig'
                ])
                ->add('_action', null, array(
                    'actions' => array(
                        'edit' => array()
                    )
                ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('name', null, [
                    'label' => 'label.form.nombre'
                ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper) {
        // NEXT_MAJOR: Keep FQCN when bumping Symfony requirement to 2.8+.
        $securityRolesType = method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix') ? 'Sonata\UserBundle\Form\Type\SecurityRolesType' : 'sonata_security_roles';

        $formMapper
                ->add('name', null, [
                    'label' => 'label.form.nombre',
                    'constraints' => [
                        new Assert\NotBlank()
                    ]
                ])
                ->add('roles', $securityRolesType, array(
                    'required' => false,
                    'multiple' => true,
                    'label' => 'label.form.roles',
                    'constraints' => [
                        new Assert\Count(array(
                            'min' => 1
                                ))
                    ]
                ))
        ;
    }

}
