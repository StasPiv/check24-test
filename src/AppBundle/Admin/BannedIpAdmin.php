<?php
/**
 * Created by PhpStorm.
 * User: stas
 * Date: 15.01.19
 * Time: 11:51
 */

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BannedIpAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('ip', TextType::class);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('ip');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('ip');
    }
}