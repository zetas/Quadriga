<?php
/**
 * Created by DavidDV <ddv@qubitlogic.net>
 * For project QuadrigaCX
 * Created: 9/24/13
 * <http://[github|bitbucket].com/zetas>
 */
namespace Main\MarketBundle\Admin\Entity;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class TransactionAdmin extends Admin
{

    protected $datagridValues = array(
        '_sort_by' => 'created',
        '_sort_order' => 'DESC'
    );

    //Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
                ->add('user', 'sonata_type_model_list')
                ->add('transactionType', 'choice', array(
                    'choices' => array(
                        'market' => 'Market',
                        'deposit' => 'Deposit',
                        'withdrawal' => 'Withdrawal',
                        'transfer' => 'Transfer'
                    )
                ))
                ->add('currency', 'entity', array('class' => 'Main\MarketBundle\Entity\Currency')) //if no type is specified, SonataAdminBundle tries to guess it
                ->add('amount', 'money', array('currency' => null))
                ->add('status', 'choice', array(
                    'choices' => array(
                        'pending' => 'Pending User Confirmation',
                        'confirmed' => 'User Confirmation Complete',
                        'completed' => 'Completed'
                    )
                ))
            ->end()
            ->with('Details')
                ->add('transactionDetail', 'sonata_type_collection', array(), array(
                    'template' => 'template.html.twig',
                    'edit' => 'inline',
                    'inline' => 'table'
                ))
            ->end()
        ;
    }

    //Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('user')
            ->add('transactionType')
            ->add('currency')
            ->add('status')
            ->add('created','doctrine_orm_datetime_range', array('input_type' => 'timestamp'))
        ;
    }

    //Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('user')
            ->add('transactionType', 'choice', array(
                'choices' => array(
                    'market' => 'Market',
                    'deposit' => 'Deposit',
                    'withdrawal' => 'Withdrawal',
                    'transfer' => 'Transfer'
                ),
            ))
            ->add('currency')
            ->add('amount')
            ->add('status', 'choice', array(
                'choices' => array(
                    'pending' => 'Pending User Confirmation',
                    'confirmed' => 'User Confirmation Complete',
                    'completed' => 'Completed'
                )
            ))
            ->add('created')
        ;
    }

    public function getFormTheme()
    {
        return array_merge(
            parent::getFormTheme(),
            array('MainMarketBundle:Admin:admin.theme.html.twig')
        );
    }
}