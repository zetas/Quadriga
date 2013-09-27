<?php
/**
 * Created by DavidDV <ddv@qubitlogic.net>
 * For project QuadrigaCX
 * Created: 9/24/13
 * <http://[github|bitbucket].com/zetas>
 */
namespace Main\MarketBundle\Admin\Entity;

use Main\MarketBundle\Entity\BTCTransactionDetail;
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
                ->add('amount','text', array('required' => false))

        ;

        if ($subject instanceof WUTransactionDetail) {
            $formMapper
                ->add('name', 'text',array('required' => false))
                ->add('location',null, array('required' => false))
                ->add('mtcn', null, array('required' => false))
            ;
        } elseif ($subject instanceof BTCTransactionDetail) {

            $formMapper->add('btcAddress', 'text', array('label' => 'Receiving Bitcoin Address'));

        } elseif ($subject instanceof ETFTransactionDetail) {
            $formMapper
                ->add('name', 'text',array('required' => false))
                ->add('bank')
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

        $formMapper->add('visible','sonata_type_boolean',array('transform' => true));
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