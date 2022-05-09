<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\StartRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StartRepository::class)]
class Start
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(type: Types::INTEGER)]
    private int $serverPort;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTime $created;

    public function __construct()
    {
        $this->created = new DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getServerPort(): int
    {
        return $this->serverPort;
    }

    public function setServerPort(int $serverPort): void
    {
        $this->serverPort = $serverPort;
    }

    public function getCreated(): DateTime
    {
        return $this->created;
    }

    public function setCreated(DateTime $created): void
    {
        $this->created = $created;
    }
}
