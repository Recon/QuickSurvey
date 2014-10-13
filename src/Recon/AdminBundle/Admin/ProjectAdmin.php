<?php

namespace Recon\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ProjectAdmin extends Admin
{

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
                ->add('id')
                ->add('name')
                ->add('isCompleted')
                ->add('referenceUrl')
                ->add('clientName')
                ->add('clientEmail')
                ->add('slug')

        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->add('id')
                ->add('name')
                ->add('isCompleted')
                ->add('referenceUrl')
                ->add('clientName')
                ->add('clientEmail')
                ->add('slug')
                ->add('_action', 'actions', array(
                    'actions' => array(
                        'show' => array(),
                        'edit' => array(),
                        'delete' => array(),
                    )
                ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                //->add('id')
                ->add('name')
                ->add('isCompleted', 'checkbox', ['required' => false])
                ->add('referenceUrl')
                ->add('clientName')
                ->add('clientEmail')
                //->add('slug')
                ->add('questions', 'sonata_type_model', ['multiple' => true, 'expanded' => true, 'by_reference' => false])
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
                ->add('id')
                ->add('name')
                ->add('isCompleted')
                ->add('referenceUrl')
                ->add('clientName')
                ->add('clientEmail')
                ->add('slug')
        ;
    }

}
