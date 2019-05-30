<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\User\Service\Interfaces\UserRequestDataInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Victorybiz\GeoIPLocation\GeoIPLocation;

/**
 * Class UserRequestData
 * @package App\Model\User\Service
 */
class UserRequestData implements UserRequestDataInterface
{

    protected $requestStack;
    protected $ip;
    protected $geoIPLocation;

    /**
     * UserRequestData constructor.
     * @param RequestStack $requestStack
     * @param GeoIPLocation $geoIPLocation
     */
    public function __construct(RequestStack $requestStack, GeoIPLocation $geoIPLocation)
    {
        $this->requestStack = $requestStack;
        $this->ip = $this->fetchIp();
        $this->geoIPLocation = $geoIPLocation;
    }


    public function getBrowser(): string
    {

        return $this->requestStack->getCurrentRequest()->headers->get('User-Agent');
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    protected function fetchIp()
    {
        $ip = $this->requestStack->getCurrentRequest()->getClientIp();
        if ($ip === '127.0.0.1') {
            $ip = '196.245.163.202';//just for local tests return London IP
        }
        return $ip;
    }

    public function getCountry(): string
    {
        $this->geoIPLocation->setIP($this->ip);
        $country = [$this->geoIPLocation->getPostalCode(), $this->geoIPLocation->getCountry(), $this->geoIPLocation->getCity()];
        return implode(',', array_filter($country)) ?? '';
    }
}
