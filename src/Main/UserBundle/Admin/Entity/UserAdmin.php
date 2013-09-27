<?php
/**
 * Created by DavidDV <ddv@qubitlogic.net>
 * For project QuadrigaCX
 * Created: 9/24/13
 * <http://[github|bitbucket].com/zetas>
 */

namespace Main\UserBundle\Admin\Entity;

use Sonata\AdminBundle\Admin\Admin;
use FOS\UserBundle\Model\UserManagerInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

class UserAdmin extends Admin
{
    protected $userManager;

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
                ->add('username')
                ->add('email')
                ->add('plainPassword', 'text',array('required' => false))
            ->end()
            ->with('Management')
                ->add('locked', null, array('required' => false))
                ->add('expired', null, array('required' => false))
                ->add('enabled', null, array('required' => false))
                ->add('credentialsExpired', null, array('required' => false))
            ->end()
            ->with('QCX Specific')
                ->add('firstName')
                ->add('lastName')
                ->add('phone')
                ->add('pin')
                ->add('btcBalance','money',array('currency' => null))
                ->add('fiatBalance', 'money', array('currency' => 'USD'))
                ->add('verified', 'sonata_type_boolean', array('required' => false, 'transform' => true))
            ->end()
        ;
    }

    //Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('username')
            ->add('verified')
            ->add('lastLogin')
        ;
    }

    //Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('username')
            ->add('email')
            ->add('btcBalance')
            ->add('fiatBalance')
            ->add('verified')
            ->add('lastLogin')
        ;
    }

    public function preUpdate($user)
    {
        $this->getUserManager()->updateCanonicalFields($user);
        $this->getUserManager()->updatePassword($user);
    }

    public function setUserManager(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @return UserManagerInterface
     */
    public function getUserManager()
    {
        return $this->userManager;
    }
}