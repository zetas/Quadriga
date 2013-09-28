<?php
/**
 * Created by DavidDV <ddv@qubitlogic.net>
 * For project QuadrigaCX
 * Created: 9/18/13
 * <http://[github|bitbucket].com/zetas>
 */

namespace Main\UserBundle\Services;

use Doctrine\ORM\EntityManager;

class BitcoinService {
    private $em;

    private $secret;
    private $apiRoot;
    private $address;
    private $callback;


    public function __construct(EntityManager $entityManager, $secret, $apiRoot, $callback ) {
        $this->em = $entityManager;
        $this->secret = $secret;
        $this->apiRoot = $apiRoot;
        $this->callback = $callback;
        $this->address = $this->getDestinationAddress();
    }

    public function getDestinationAddress() {
        $data = $this->em->getRepository('MainSiteBundle:Config')
            ->findOneBy(array('name' => 'Bitcoin Address'))
        ;

        $address = $data->getValue();

        return $address;
    }

    public function getEm() {
        return $this->em;
    }

    public function getNewAddress($userID) {

        $tokenContent = $this->secret."|".$userID;

        $token = base64_encode(($tokenContent));
        $callbackURL = urlencode($this->callback.$token);

        $params = "method=create&address=".$this->address."&shared=false&callback=".$callbackURL;

        $response = file_get_contents($this->apiRoot."?".$params);

        $object = json_decode($response);

        if (!$object || !is_object($object))
            return false;

        return $object->input_address;
    }

    public function validateDestination($address) {
        if ($this->address == $address)
            return true;

        return false;
    }

    public function validateToken($token) {
        $decoded = base64_decode($token);

        $decodedParts = explode('|',$decoded);

        if ($decodedParts[0] == $this->secret)
            return $decodedParts[1];

        return false;
    }

    public function validateCallback($token, $address) {
        if (!$user = $this->validateToken($token))
            return false;

        if (!$this->validateDestination($address))
            return false;

        return $user;
    }

}