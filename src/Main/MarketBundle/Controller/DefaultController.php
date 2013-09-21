<?php

namespace Main\MarketBundle\Controller;

use Main\MarketBundle\Entity\Offer;
use Main\MarketBundle\Form\Type\InstantOfferFormType;
use Main\MarketBundle\Form\Type\LimitOfferFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/{type}/{direction}", name="offer")
     * @Template
     */
    public function offerAction($type, $direction) {
        if (($direction != 'buy' && $direction != 'sell') || ($type != "instant" && $type != "limit"))
            return $this->redirect($this->generateUrl('fos_user_profile_show'));

        if ($type == "instant") {
            $oft = new InstantOfferFormType();
        } else {
            $oft = new LimitOfferFormType();
        }

        $form = $this->createForm($oft, new Offer(), array('action' => $this->generateUrl('offer_confirm', array('type' => $type, 'direction' => $direction))));

        return array('form' => $form->createView(), 'type' => $type, 'direction' => $direction);
    }

    /**
     * @Route("/{type}/{direction}/confirm", name="offer_confirm")
     * @Template
     */
    public function confirmAction(Request $request, $type, $direction) {
        if ($type == "instant") {
            $oft = new InstantOfferFormType();
        } else {
            $oft = new LimitOfferFormType();
        }

        $form = $this->createForm($oft, new Offer());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $offer = $form->getData();
            $offer->setUser($this->getUser());
            ($direction == "buy") ? $offer->setIsBuy(true) : $offer->setIsBuy(false);
            ($type == "instant") ? $offer->setIsInstant(true) : $offer->setIsInstant(false);

            $em = $this->getDoctrine()->getManager();
            $em->persist($offer);
            $em->flush();
            return $this->redirect($this->generateUrl('offer', array('type' => $type, 'direction' => $direction)));
            //return $this->redirect($this->generateUrl('fos_user_profile_show'));
        }
        return array('form' => $form);
    }

    /**
     * @Route("/", name="offer_index")
     * @Route("/{max}", name="offer_index_max")
     * @Template()
     */
    public function viewAction($max = 100) {
        $buyOffers = $this->getDoctrine()
            ->getRepository('MainMarketBundle:Offer')
            ->findBy(array('isBuy' => true),array('price' => 'DESC'),$max);

        $sellOffers = $this->getDoctrine()
            ->getRepository('MainMarketBundle:Offer')
            ->findBy(array('isBuy' => false),array('price' => 'ASC'),$max);

        if ($max < 100)
            return $this->render('MainMarketBundle:Default:viewContent.html.twig',array('buyOffers' => $buyOffers, 'sellOffers' => $sellOffers, 'max' => $max));

        return array('buyOffers' => $buyOffers, 'sellOffers' => $sellOffers, 'max' => $max);
    }
}
