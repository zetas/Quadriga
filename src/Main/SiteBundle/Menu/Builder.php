<?php
/**
 * Created by DavidDV <ddv@qubitlogic.net>
 * For project QuadrigaCX
 * Created: 9/17/13
 * <http://[github|bitbucket].com/zetas>
 */

namespace Main\SiteBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware {
    private function _getCurrent() {
        $request = $this->container->get('request');
        $requestURI = $request->getRequestUri();
        $baseURL = $request->getBaseUrl();

        ($requestURI == $baseURL) ? $requestURI .= '/' : null;

        return $requestURI;
    }

    public function mainMenu(FactoryInterface $factory, array $options) {
        $menu = $factory->createItem('root');

        $menu->addChild('Home', array('route' => 'homepage'));
        $menu->addChild('About Us', array('route' => 'about_us'));
        $menu->addChild('Open Orders', array('route' => 'offer_index'));
        $menu->addChild('FAQ', array('route' => 'faq'));
        $menu->addChild('Support', array('route' => 'support'));

        $menu->setCurrentUri($this->_getCurrent());

        return $menu;
    }

    public function userMenu(FactoryInterface $factory, array $options) {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'pull-right');

        $security = $this->container->get('security.context');
        $token = $security->getToken();
        $user = $token->getUser();

        if ($token == null || !is_object($user)) {
            $menu->addChild('Sign In |', array('route' => 'fos_user_security_login'));
            $menu->addChild('Sign Up', array('route' => 'fos_user_registration_register'));
        } else {
            $menu->addChild('My Account | ', array('route' => 'fos_user_profile_show'));
            $menu->addChild('Logout', array('route' => 'fos_user_security_logout'));
        }


        $menu->setCurrentUri($this->_getCurrent());

        return $menu;
    }
}