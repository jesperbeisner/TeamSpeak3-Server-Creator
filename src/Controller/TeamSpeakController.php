<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\History;
use App\Entity\Server;
use App\Repository\ServerRepository;
use App\Service\DockerService;
use App\Service\TeamSpeakService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TeamSpeakController extends AbstractController
{
    #[Route('/teamspeak-create/{port<\d+>}', name: 'teamspeak-create', methods: ['POST'])]
    public function teamspeakCreate(
        int $port,
        ParameterBagInterface $parameterBag,
        DockerService $dockerService,
        HttpClientInterface $httpClient,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        set_time_limit(60);

        /** @var ServerRepository $serverRepository */
        $serverRepository = $entityManager->getRepository(Server::class);

        if (null === $server = $serverRepository->findOneBy(['port' => $port])) {
            return $this->json(['message' => 'Specified port is not allowed'], 400);
        }

        if (null !== $server->getContainerCreated()) {
            $history = new History();
            $history->setServerId($server->getId());
            $history->setContainerCreated($server->getContainerCreated());
            $history->setContainerRemoved(new DateTime());

            $server->setContainerCreated(null);

            $entityManager->persist($history);
        }

        $dockerService->removeTeamSpeakContainer($server);
        sleep(1);
        $dockerService->startTeamSpeakContainer($server);
        sleep(10);
        [$apiKey, $serverAdminToken] = $dockerService->getApiKeyAndToken($server);

        $server->setContainerCreated(new DateTime());
        $server->setApiKey($apiKey);
        $server->setServerAdminToken($serverAdminToken);

        $teamSpeakService = new TeamSpeakService((string) ($server->getPort() + 80), $apiKey, $httpClient);

        $serverLayout = require $parameterBag->get('kernel.project_dir') . '/server-layout.php';

        $spacerId = 0;
        foreach ($serverLayout as $channel) {
            $channelName = $channel['name'];

            if ($channel['spacer'] === true) {
                $channelName = "[*spacer$spacerId]" . $channelName;
                $spacerId++;
            }

            if ($channel['center'] === true) {
                $channelName = "[cspacer$spacerId]" . $channelName;
                $spacerId++;
            }

            $channelId = $teamSpeakService->createChannel(urlencode($channelName), $channel['max_clients']);
            sleep(1);

            if ($channel['talk_power'] > 0) {
                $teamSpeakService->setTalkPower($channelId, $channel['talk_power']);
                sleep(1);
            }
        }

        $entityManager->flush();

        return $this->json([
            'url' => "teamspeak.jesperbeisner.dev:{$server->getPort()}",
            'server-admin-token' => $serverAdminToken,
            'created' => $server->getContainerCreated()->format('d.m.Y H:i:s'),
        ]);
    }

    #[Route('/teamspeak-remove/{port<\d+>}', name: 'teamspeak-remove', methods: ['POST'])]
    public function teamspeakRemove(int $port, DockerService $dockerService, EntityManagerInterface $entityManager): JsonResponse
    {
        /** @var ServerRepository $serverRepository */
        $serverRepository = $entityManager->getRepository(Server::class);

        if (null === $server = $serverRepository->findOneBy(['port' => $port])) {
            return $this->json(['message' => 'Specified port is not allowed'], 400);
        }

        if (null !== $server->getContainerCreated()) {
            $history = new History();
            $history->setServerId($server->getId());
            $history->setContainerCreated($server->getContainerCreated());
            $history->setContainerRemoved(new DateTime());

            $server->setContainerCreated(null);

            $entityManager->persist($history);
        }

        $entityManager->flush();

        $dockerService->removeTeamSpeakContainer($server);

        return $this->json(['message' => 'Success']);
    }
}
