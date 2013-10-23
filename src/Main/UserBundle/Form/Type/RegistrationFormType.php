<?php
/**
 * Created by DavidDV <ddv@qubitlogic.net>
 * For project QuadrigaCX
 * Created: 9/20/13
 * <http://[github|bitbucket].com/zetas>
 */

namespace Main\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        // add your custom field
        $builder->add('firstName')
            ->add('lastName')
        ;
        parent::buildForm($builder, $options);

        $builder
            ->add('company')
            ->add('city')
            ->add('state')
            ->add('zip')
            ->add('country','country',array(
                'preferred_choices' => array('US', 'CA'),
                'empty_value' => 'COUNTRY:'
            ))
            ->add('address')
            ->add('phone')
            ->add('pin')
        ;
    }

    public function getName()
    {
        return 'main_user_registration';
    }
}