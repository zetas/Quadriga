<?php
/**
 * Created by DavidDV <ddv@qubitlogic.net>
 * For project QuadrigaCX
 * Created: 9/26/13
 * <http://[github|bitbucket].com/zetas>
 */

namespace Main\SiteBundle\Services;

use Doctrine\ORM\EntityManager;

class ConfigService {
    protected $em;

    private $configKeys = array(
        'market_percent' => 'Market Fee Percent',
        'transfer_percent' => 'User2User Transfer Fee Percent',
        'wu_name' => 'WU Target Name',
        'wu_address' => 'WU Target Address',
        'wu_city' => 'WU Target City',
        'wu_state' => 'WU Target State',
        'wu_zip' => 'WU Target Zip',
        'etf_bank' => 'ETF Target Bank',
        'etf_routing' => 'ETF Target Routing',
        'etf_account' => 'ETF Target Account'
    );

    public function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
    }

    public function getEm() {
        return $this->em;
    }

    public function getItem($heading=null,$name=null) {
        if ($heading == null && $name == null)
            return false;

        if ($heading == null && $name != null) {
            $config = $this->em->getRepository('MainSiteBundle:Config')
                ->findOneBy(array('name' => $name))
            ;
        } else {
            $config = $this->em->getRepository('MainSiteBundle:Config')
                ->findBy(array('heading' => $heading))
            ;
        }

        return $config;
    }

    public function getConfigFor($for) {

        switch($for) {
            case 'wu':
                $configPull = array(
                    'wu_name',
                    'wu_address',
                    'wu_city',
                    'wu_state',
                    'wu_zip'
                );
                break;
            case 'etf':
                $configPull = array(
                    'etf_bank',
                    'etf_routing',
                    'etf_account'
                );
                break;
            case 'market':
                $configPull = array(
                    'market_percent'
                );
                break;
            case 'transfer':
                $configPull = array(
                    'transfer_percent'
                );
                break;
            default:
                $configPull = array();
                break;
        }
        $config = array();
        if (count($configPull) >0) {

            foreach ($configPull as $cp) {
                $config[$cp] = $this->getItem(null,$this->configKeys[$cp]);
            }
        }

        if (count($config) == 1)
            if (is_array($config))
                return array_shift($config);
            else
                return $config;

        return $config;
    }
}