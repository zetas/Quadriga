<?php
/**
 * Created by DavidDV <ddv@qubitlogic.net>
 * For project QuadrigaCX
 * Created: 9/26/13
 * <http://[github|bitbucket].com/zetas>
 */

namespace Main\MarketBundle\Admin\Entity;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class CurrencyAdmin extends Admin
{

    //Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name',null,array('read_only' => true))
            ->add('type',null,array('read_only' => true))
            ->add('tla', null,array('read_only' => true, 'label' => 'Currency Code'))
            ->add('symbol', null, array('read_only' => true))
            ->add('depositFlatFee')
            ->add('depositPercentFee')
            ->add('withdrawFlatFee')
            ->add('withdrawPercentFee')
        ;
    }


    //Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('type')
            ->add('tla',null,array('label' => 'Currency Code'))
            ->add('symbol')
            ->add('depositFlatFee')
            ->add('depositPercentFee')
            ->add('withdrawFlatFee')
            ->add('withdrawPercentFee')
        ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('create');
    }

}