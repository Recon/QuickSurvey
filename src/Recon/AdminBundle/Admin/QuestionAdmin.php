<?php

namespace Recon\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class QuestionAdmin extends Admin
{

    public function configure()
    {
        parent::configure();

        $this->datagridValues['_sort_order'] = 'ASC';
        $this->datagridValues['_sort_by'] = 'position';
    }

    public function getNewInstance()
    {
        $instance = parent::getNewInstance();
        $position = $this->getConfigurationPool()->getContainer()->get('doctrine')->getRepository(get_class($instance))->getMaxPosition() + 1;
        $instance->setPosition($position);

        return $instance;
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
                ->add('id')
                ->add('position')
                ->add('text')
                ->add('description')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->add('id')
                ->add('position')
                ->add('text')
                //->add('description')
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
                ->add('text')
                ->add('position')
                ->add('description')
                ->add('answers', 'sonata_type_collection', [
                    'by_reference' => false,
                    'type_options' => array(
                        // Prevents the "Delete" option from being displayed
                        'delete' => true,
                    )
                        ], ['edit' => 'inline', 'inline' => 'table', 'allow_delete' => true])

        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
                ->add('id')
                ->add('position')
                ->add('text')
                ->add('description')
        ;
    }

    public function prePersist($question)
    {
        $this->preUpdate($question);
    }

    public function preUpdate($question)
    {
        foreach ($question->getAnswers() As $answer) {
            $answer->setQuestion($question);
        }
    }

}
