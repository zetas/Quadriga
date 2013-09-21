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
class WUTransactionDetail extends TransactionDetail
{

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $senderLocation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $mtcn;

    /**
     * Set senderLocation
     *
     * @param string $senderLocation
     * @return WUTransactionDetail
     */
    public function setSenderLocation($senderLocation)
    {
        $this->senderLocation = $senderLocation;

        return $this;
    }

    /**
     * Get senderLocation
     *
     * @return string
     */
    public function getSenderLocation()
    {
        return $this->senderLocation;
    }

    /**
     * Set mtcn
     *
     * @param string $mtcn
     * @return WUTransactionDetail
     */
    public function setMtcn($mtcn)
    {
        $this->mtcn = $mtcn;

        return $this;
    }

    /**
     * Get mtcn
     *
     * @return string
     */
    public function getMtcn()
    {
        return $this->mtcn;
    }
}