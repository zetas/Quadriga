<?php
/**
 * Created by DavidDV <ddv@qubitlogic.net>
 * For project QuadrigaCX
 * Created: 9/21/13
 * <http://[github|bitbucket].com/zetas>
 */

namespace Main\MarketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Currency
 *
 * @ORM\Table("currency")
 * @ORM\Entity
 */
class Currency
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $tla;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $symbol;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Currency
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Currency
     */
    public function setType($type)
    {
        $types = array('fiat', 'digital');

        if (!in_array($type,$types))
            die("Invalid type for currency");

        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set tla
     *
     * @param string $tla
     * @return Currency
     */
    public function setTla($tla)
    {
        $this->tla = $tla;
    
        return $this;
    }

    /**
     * Get tla
     *
     * @return string 
     */
    public function getTla()
    {
        return $this->tla;
    }

    /**
     * Set symbol
     *
     * @param string $symbol
     * @return Currency
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;
    
        return $this;
    }

    /**
     * Get symbol
     *
     * @return string 
     */
    public function getSymbol()
    {
        return $this->symbol;
    }
}