<?php
/**
 * Created by DavidDV <ddv@qubitlogic.net>
 * For project QuadrigaCX
 * Created: 9/19/13
 * <http://[github|bitbucket].com/zetas>
 */

namespace Main\MarketBundle\Controller;

use Main\MarketBundle\Entity\Transaction;
use Main\MarketBundle\Entity\WUTransactionDetail;
use Main\MarketBundle\Form\Type\WUTransactionDetailFormType;
use Main\MarketBundle\Entity\ETFTransactionDetail;
use Main\MarketBundle\Form\Type\ETFTransactionDetailFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

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
    public function confirmAction(Transaction $transaction, Request $request) {
        if ($transaction->getUser() != $this->getUser())
            return $this->redirect($this->generateUrl('fos_user_profile_show'));

        if ($transaction->getCurrency()->getName() == 'Western Union') {
            $wutd = new WUTransactionDetail();
            $wutd->setTransaction($transaction);
            $form = $this->createForm(new WUTransactionDetailFormType(), $wutd);
        } else {
            $etftd = new ETFTransactionDetail();
            $etftd->setTransaction($transaction);
            $form = $this->createForm(new ETFTransactionDetailFormType(), $etftd);
        }

        $form->handleRequest($request);

        if ($form->isValid()) {
            $td = $form->getData();
            ($transaction->getAmount() <= $td->getAmount()) ? $transaction->setStatus('confirmed') : null;

            $em = $this->getDoctrine()->getManager();
            $em->persist($td);
            $em->flush();

            return $this->redirect($this->generateUrl('fos_user_profile_show'));
        }

        return array('form' => $form->createView(), 'transaction' => $transaction);
    }
}