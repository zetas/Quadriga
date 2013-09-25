<?php
/**
 * Created by DavidDV <ddv@qubitlogic.net>
 * For project QuadrigaCX
 * Created: 9/24/13
 * <http://[github|bitbucket].com/zetas>
 */
namespace Main\MarketBundle\Admin\Entity;

use Main\MarketBundle\Entity\ETFTransactionDetail;
use Main\MarketBundle\Entity\WUTransactionDetail;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class TransactionDetailAdmin extends Admin
{


    //Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $subject = $this->getSubject();

        $formMapper->add('amount','text', array('required' => false))
            ->add('name', 'text',array('required' => false))
        ;

        if ($subject instanceof WUTransactionDetail) {
            $formMapper->add('location')
                ->add('mtcn')
            ;
        } elseif ($subject instanceof ETFTransactionDetail) {
            $formMapper->add('bank')
                ->add('account')
            ;
        }
    }


    //Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('transaction')
            ->add('name')
            ->add('amount')
        ;
    }
}