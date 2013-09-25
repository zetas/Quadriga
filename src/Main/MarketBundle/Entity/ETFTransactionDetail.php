<?php
/**
 * Created by DavidDV <ddv@qubitlogic.net>
 * For project QuadrigaCX
 * Created: 9/19/13
 * <http://[github|bitbucket].com/zetas>
 */

namespace Main\MarketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Main\MarketBundle\Entity\TransactionDetail;

/**
 * @ORM\Entity
 */
class ETFTransactionDetail extends TransactionDetail
{

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $bank;

    /**
     * @ORM\Column(type="integer")
     */
    protected $account;


    /**
     * Set bank
     *
     * @param string $bank
     * @return ETFTransactionDetail
     */
    public function setBank($bank)
    {
        $this->bank = $bank;

        return $this;
    }

    /**
     * Get bank
     *
     * @return string
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Set account
     *
     * @param integer $account
     * @return ETFTransactionDetail
     */
    public function setAccount($account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return integer
     */
    public function getAccount()
    {
        return $this->account;
    }
}