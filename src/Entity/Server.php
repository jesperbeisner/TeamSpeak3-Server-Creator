<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ServerRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServerRepository::class)]
class Server
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(type: Types::INTEGER)]
    private int $port;

    #[ORM\Column(type: Types::STRING)]
    private string $containerName;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTime $containerCreated = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $serverAdminToken = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $apiKey = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function setPort(int $port): void
    {
        $this->port = $port;
    }

    public function getContainerName(): string
    {
        return $this->containerName;
    }

    public function setContainerName(string $containerName): void
    {
        $this->containerName = $containerName;
    }

    public function getContainerCreated(): ?DateTime
    {
        return $this->containerCreated;
    }

    public function setContainerCreated(?DateTime $containerCreated): void
    {
        $this->containerCreated = $containerCreated;
    }

    public function getServerAdminToken(): ?string
    {
        return $this->serverAdminToken;
    }

    public function setServerAdminToken(?string $serverAdminToken): void
    {
        $this->serverAdminToken = $serverAdminToken;
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function setApiKey(?string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }
}
