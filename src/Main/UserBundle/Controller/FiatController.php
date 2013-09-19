<?php
/**
 * Created by DavidDV <ddv@qubitlogic.net>
 * For project QuadrigaCX
 * Created: 9/18/13
 * <http://[github|bitbucket].com/zetas>
 */

namespace Main\UserBundle\Controller;

use Main\MarketBundle\Entity\Transaction;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class FiatController extends Controller
{
    /**
     * @Route("/deposit/etf", name="fiat_etf_deposit")
     * @Template
     */
    public function etfDepositAction(Request $request) {
        $form = $this->createFormBuilder(null)
            ->add('amount', 'money', array('currency' => 'USD'))
            ->add('Continue','submit')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $amount = $data['amount'];
            $transaction = new Transaction();
            $transaction->setUser($this->getUser())
                ->setAmount($amount)
                ->setCurrencyType('etf')
                ->setStatus('pending')
                ->setTransactionType('deposit')
            ;

            $em = $this->getDoctrine()->getManager();
            $em->persist($transaction);
            $em->flush();

            return array('amount' => $amount);
        }

        return array('form' => $form->createView());
    }

    /**
     * @Route("/deposit/wu", name="fiat_wu_deposit")
     * @Template
     */
    public function wuDepositAction(Request $request) {
        $form = $this->createFormBuilder(null)
            ->add('amount', 'money', array('currency' => 'USD'))
            ->add('Continue','submit')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $amount = $data['amount'];
            $transaction = new Transaction();
            $transaction->setUser($this->getUser())
                ->setAmount($amount)
                ->setCurrencyType('wu')
                ->setStatus('pending')
                ->setTransactionType('deposit')
            ;

            $em = $this->getDoctrine()->getManager();
            $em->persist($transaction);
            $em->flush();

            return array('amount' => $amount);
        }

        return array('form' => $form->createView());
    }

    /**
     * @Route("/deposit", name="fiat_deposit")
     * @Template
     */
    public function depositAction() {
        return array();
    }

}