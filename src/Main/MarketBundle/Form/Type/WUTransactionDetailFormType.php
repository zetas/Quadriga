<?php
/**
 * Created by DavidDV <ddv@qubitlogic.net>
 * For project QuadrigaCX
 * Created: 9/21/13
 * <http://[github|bitbucket].com/zetas>
 */

namespace Main\MarketBundle\Form\Type;

use Main\MarketBundle\Form\EventListener\AddLocationFieldSubscriber;
use Main\MarketBundle\Form\EventListener\AddNameFieldSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class WUTransactionDetailFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->addEventSubscriber(new AddNameFieldSubscriber())
            ->addEventSubscriber(new AddLocationFieldSubscriber())
        ;

        $builder
            ->add('amount','money', array('currency' => 'USD'))
            ->add('mtcn', 'text', array('label' => 'MTCN'))
            ->add('confirm', 'submit')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver
            ->setDefaults(array('data_class' => 'Main\MarketBundle\Entity\WUTransactionDetail'))
        ;
    }

    public function getName() {
        return 'transaction_detail_wu';
    }
}