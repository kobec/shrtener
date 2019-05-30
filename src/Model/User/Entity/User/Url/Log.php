<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User\Url;

use App\Model\User\Entity\User\Url;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="user_url_log")
 */
class Log
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Url
     * @ORM\ManyToOne(targetEntity="App\Model\User\Entity\User\Url", inversedBy="logs")
     * @ORM\JoinColumn(name="user_url_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $url;

    /**
     * @var string
     * @ORM\Column(type="user_url_log_ip",length=32, nullable=true)
     */
    private $ip;

    /**
     * @var string
     * @ORM\Column(type="user_url_log_browser",length=255, nullable=true)
     */
    private $browser;

    /**
     * @var string
     * @ORM\Column(type="user_url_log_country",length=255, nullable=true)
     */
    private $country;

    /**
     * @var DateTime $created
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var DateTime $updated
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    protected $updatedAt;


    public function __construct(Url $url, Ip $ip, Browser $browser, Country $country)
    {
        $this->url = $url;
        $this->ip = $ip;
        $this->browser = $browser;
        $this->country = $country;
    }


    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps(): void
    {
        $dateTimeNow = new DateTime('now');
        $this->setUpdatedAt($dateTimeNow);
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt($dateTimeNow);
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
