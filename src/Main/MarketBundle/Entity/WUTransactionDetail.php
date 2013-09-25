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
    protected $location;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $mtcn;

    /**
     * Set location
     *
     * @param string $location
     * @return WUTransactionDetail
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
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