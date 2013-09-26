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

        $transaction = $subject->getTransaction();

        $formMapper
            ->with('General')
                ->add('amount','text', array('required' => false))
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

            if ($transaction->getTransactionType() == 'withdrawal') {
                $formMapper->add('swift')
                    ->add('bankAddress')
                    ->add('bankCity')
                    ->add('bankState')
                    ->add('bankCountry')
                    ->add('bankZip')
                    ->add('address')
                    ->add('city')
                    ->add('state')
                    ->add('country')
                    ->add('zip')
                ;
            }
        }

        $formMapper->add('visible','sonata_type_boolean')
            ->end();
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