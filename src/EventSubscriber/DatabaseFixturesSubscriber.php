<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\Config;
use App\Entity\Server;
use App\Repository\ConfigRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class DatabaseFixturesSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'checkDatabaseFixtures',
        ];
    }

    public function checkDatabaseFixtures(RequestEvent $event): void
    {
        /** @var ConfigRepository $configRepository */
        $configRepository = $this->entityManager->getRepository(Config::class);

        // Not found means the fixtures were not loaded before
        if (null === $configRepository->findOneBy(['configKey' => 'fixtures-loaded'])) {
            foreach ([20000, 21000, 22000, 23000, 24000, 25000, 26000, 27000, 28000, 29000] as $port) {
                $server = new Server();
                $server->setPort($port);
                $server->setContainerName($this->createContainerName());
                $this->entityManager->persist($server);
            }

            $config = new Config();
            $config->setConfigKey('fixtures-loaded');
            $config->setConfigValue('-');
            $this->entityManager->persist($config);

            $this->entityManager->flush();
        }
    }

    private function createContainerName(): string
    {
        $characters = '1234567890';
        $characters .= 'abcdefghijklmnopqrstuvwxyz';
        $characters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $containerName = '';

        for ($i = 0; $i < 32; $i++) {
            $containerName .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $containerName;
    }
}
