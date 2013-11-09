<?php
/**
 * Created by DavidDV <ddv@qubitlogic.net>
 * For project Quadriga
 * Created: 11/8/13
 * <http://[github|bitbucket].com/zetas>
 */

namespace Main\SiteBundle\Twig;

class LastPriceExtension extends \Twig_Extension
{
    private $cache;

    public function __construct($cache) {
        $this->cache = $cache;
    }

    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('last_price',array($this, 'getLastPrice')),
        );
    }

    public function getLastPrice() {

        $price = $this->cache->fetch('lastPrice');

        if ($price !== false)
            return unserialize($price);

        return '0';
    }

    public function getName()
    {
        return 'last_price';
    }

}