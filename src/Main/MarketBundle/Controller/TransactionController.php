<?php
/**
 * Created by DavidDV <ddv@qubitlogic.net>
 * For project QuadrigaCX
 * Created: 9/19/13
 * <http://[github|bitbucket].com/zetas>
 */

namespace Main\MarketBundle\Controller;

use Main\MarketBundle\Entity\Transaction;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class TransactionController extends Controller
{
    /**
     * @Route("/transaction/view/{transaction}", name="transaction_view")
     * @Template
     */
    public function viewAction(Transaction $transaction) {
        if ($transaction->getUser() != $this->getUser())
            return $this->redirect($this->generateUrl('fos_user_profile_show'));

        return array('transaction' => $transaction);
    }

    /**
     * @Route("/transaction/confirm/{transaction}", name="transaction_confirm")
     * @Template
     */
    public function confirmAction(Transaction $transaction) {
        if ($transaction->getUser() != $this->getUser())
            return $this->redirect($this->generateUrl('fos_user_profile_show'));

        /*
         * Senders Name
Senders Location
Amount Sent
MTCN
         */

        $form = $this->createFormBuilder(null)
            ->add('name', 'text', array('label' => 'Senders Name'))
            ->add('location','text', array('label' => 'Senders Location'))
            ->add('amount', 'money', array('label' => 'Confirm Amount Sent', 'currency' => 'USD'))
            ->add('mtcn', 'text', array('label' => 'MTCN'))
            ->add('confirm', 'submit')
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
}