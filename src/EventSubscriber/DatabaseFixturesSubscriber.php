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
            foreach ([20000, 25000, 30000] as $port) {
                $server = new Server();
                $server->setPort($port);
                $server->setContainerName('TeamSpeak3Container' . $port);
                $this->entityManager->persist($server);
            }

            $config = new Config();
            $config->setConfigKey('fixtures-loaded');
            $config->setConfigValue('-');
            $this->entityManager->persist($config);

            $this->entityManager->flush();
        }
    }
}
