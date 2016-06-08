<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

class CrawlPageAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            //->tab('Post')
            ->with('Content', array('class' => 'col-md-9'))
            ->add('title', 'text')
            ->add('source', 'text')
            ->add('body', 'ckeditor', array('autoload' => true))
            ->add('create_time', 'sonata_type_date_picker', array(
                'format'=>'yyyy-MM-dd HH:mm:ss',
                'dp_default_date'        => date('Y-m-d H:i:s'),))
            ->end()

            ->with('Meta data', array('class' => 'col-md-3'))
            ->end();
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('isTec', 'boolean', array('editable' => 'Yes'))
            ->add('isSoup', 'boolean', array('editable' => 'Yes'))
            ->add('isML', 'boolean', array('editable' => 'Yes'))
            ->add('isMath', 'boolean', array('editable' => 'Yes'))
            ->add('isNews', 'boolean', array('editable' => 'Yes'))
            ->add('source')
            ->add('createTime')
            ;
    }

    public function toString($object)
    {
        return $object instanceof BlogPost
            ? $object->getTitle()
            : 'Crawl Page'; // shown in the breadcrumb on the create view
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ;
    }
}
