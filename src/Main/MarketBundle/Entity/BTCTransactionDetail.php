<?php
/**
 * Created by DavidDV <ddv@qubitlogic.net>
 * For project QuadrigaCX
 * Created: 9/25/13
 * <http://[github|bitbucket].com/zetas>
 */

namespace Main\MarketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Main\MarketBundle\Entity\TransactionDetail;

/**
 * @ORM\Entity
 */
class BTCTransactionDetail extends TransactionDetail
{

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $btcAddress;


    /**
     * Set btcAddress
     *
     * @param string $btcAddress
     * @return BTCTransactionDetail
     */
    public function setBtcAddress($btcAddress)
    {
        $this->btcAddress = $btcAddress;
    
        return $this;
    }

    /**
     * Get btcAddress
     *
     * @return string 
     */
    public function getBtcAddress()
    {
        return $this->btcAddress;
    }
}