<?php

namespace Main\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/faq", name="faq")
     * @Template
     */
    public function faqAction() {
        return array();
    }

    /**
     * @Route("/about", name="about_us")
     * @Template
     */
    public function aboutAction() {
        return array();
    }

    /**
     * @Route("/support", name="support")
     * @Template
     */
    public function supportAction() {
        return array();
    }

    /**
     * @Route("/offers", name="offer_index")
     * @Route("/offers/{max}", name="offer_index_max")
     * @Template()
     */
    public function viewAction($max = 100) {
        $buyOffers = $this->getDoctrine()
            ->getRepository('MainMarketBundle:Offer')
            ->findBy(array('isBuy' => true, 'active' => true),array('price' => 'DESC'),$max);

        $sellOffers = $this->getDoctrine()
            ->getRepository('MainMarketBundle:Offer')
            ->findBy(array('isBuy' => false, 'active' => true),array('price' => 'ASC'),$max);

        if ($max < 100)
            return $this->render('MainSiteBundle:Default:viewContent.html.twig',array('buyOffers' => $buyOffers, 'sellOffers' => $sellOffers, 'max' => $max));

        return array('buyOffers' => $buyOffers, 'sellOffers' => $sellOffers, 'max' => $max);
    }
}
