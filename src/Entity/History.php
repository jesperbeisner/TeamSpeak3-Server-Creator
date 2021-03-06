<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\HistoryRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HistoryRepository::class)]
class History
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(type: Types::INTEGER)]
    private int $serverPort;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTime $containerCreated;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTime $containerRemoved;

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

    public function getContainerCreated(): DateTime
    {
        return $this->containerCreated;
    }

    public function setContainerCreated(DateTime $containerCreated): void
    {
        $this->containerCreated = $containerCreated;
    }

    public function getContainerRemoved(): DateTime
    {
        return $this->containerRemoved;
    }

    public function setContainerRemoved(DateTime $containerRemoved): void
    {
        $this->containerRemoved = $containerRemoved;
    }
}
