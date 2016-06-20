<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

class BlogPostAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            //->tab('Post')
            ->with('Content', array('class' => 'col-md-9'))
            ->add('title', 'text')
            ->add('image', 'sonata_type_model', array(
                'property' => 'name'
            ))
            ->add('body', 'ckeditor', array('autoload' => true))
            ->add('create_time', 'sonata_type_date_picker', array(
                'format'=>'yyyy-MM-dd HH:mm:ss',
                'dp_default_date'        => date('Y-m-d H:i:s'),))
            ->end()

            //->tab('Publish Options')
            ->with('Meta data', array('class' => 'col-md-3'))
            ->add('subject', 'sonata_type_model', array(
                'class' => 'AppBundle\Entity\Subject',
                'property' => 'name',
            ))
            ->end()

            ->with('Meta data', array('class' => 'col-md-3'))
            ->add('tags', null, array(
                'class' => 'AppBundle\Entity\Tag',
                'property' => 'name',
            ))
            ->end()

            ->with('Meta data', array('class' => 'col-md-3'))
            ->add('category', 'sonata_type_model', array(
                'class' => 'AppBundle\Entity\Category',
                'property' => 'name',
            ))
            ->end();
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('id', 'url', array(
                'route' => array(
                    'name' => 'blog_show',
                    'absolute' => true,
                    'identifier_parameter_name' => 'blogId'
                ))
            )
            ->add('subject.name')
            ->add('category.name')
            ->add('image.name')
            ->add('createTime')
            ;
    }

    public function toString($object)
    {
        return $object instanceof BlogPost
            ? $object->getTitle()
            : 'Blog Post'; // shown in the breadcrumb on the create view
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('category', null, array(), 'entity', array(
                'class'    => 'AppBundle\Entity\Category',
                'property' => 'name',
            ))
            ;
    }
}
