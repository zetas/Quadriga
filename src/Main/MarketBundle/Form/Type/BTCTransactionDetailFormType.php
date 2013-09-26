<?php
/**
 * Created by DavidDV <ddv@qubitlogic.net>
 * For project QuadrigaCX
 * Created: 9/25/13
 * <http://[github|bitbucket].com/zetas>
 */

namespace Main\MarketBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class BTCTransactionDetailFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('btcAddress','text', array('label' => 'Bitcoin Address'))
            ->add('amount','money', array('currency' => null))
            ->add('pin','text',array('mapped' => false))
            ->add('confirm', 'submit')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver
            ->setDefaults(array('data_class' => 'Main\MarketBundle\Entity\BTCTransactionDetail'))
        ;
    }

    public function getName() {
        return 'transaction_detail_btc';
    }
}
