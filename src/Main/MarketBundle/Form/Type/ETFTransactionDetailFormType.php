<?php
/**
 * Created by DavidDV <ddv@qubitlogic.net>
 * For project QuadrigaCX
 * Created: 9/21/13
 * <http://[github|bitbucket].com/zetas>
 */

namespace Main\MarketBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class ETFTransactionDetailFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('senderName')
            ->add('senderBank')
            ->add('senderAccount')
            ->add('amount','money', array('currency' => 'USD'))
            ->add('confirm', 'submit')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver
            ->setDefaults(array('data_class' => 'Main\MarketBundle\Entity\ETFTransactionDetail'))
        ;
    }

    public function getName() {
        return 'transaction_detail_etf';
    }
}