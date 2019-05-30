<?php

declare(strict_types=1);

namespace App\Model\User\Service\Interfaces;

/**
 * Interface UserRequestDataInterface
 * @package App\Model\User\Service
 */
interface UserRequestDataInterface
{

    public function getBrowser(): string;

    public function getIp(): string;

    public function getCountry(): string;

}
