<?php

namespace Main\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/verify", name="user_verify")
     * @Template
     */
    public function verifyAction() {
        return array();
    }

    /**
     * @Route("/withdraw", name="user_withdraw")
     * @Template
     */
    public function withdrawAction() {
        return array();
    }
}
