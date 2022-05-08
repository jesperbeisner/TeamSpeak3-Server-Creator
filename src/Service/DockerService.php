<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Server;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class DockerService
{
    public function __construct(
        private readonly ParameterBagInterface $parameterBag,
    ) {}

    public function getApiKeyAndToken(Server $server): array
    {
        $projectDir = $this->parameterBag->get('kernel.project_dir');
        $credentialFile = "$projectDir/var/{$server->getContainerName()}.txt";

        $process = Process::fromShellCommandline(
            "docker logs {$server->getContainerName()} 2> $credentialFile"
        );

        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $content = file_get_contents($credentialFile);

        unlink($credentialFile);

        $apiKey = null;
        $token = null;

        foreach (explode("\n", $content) as $textLine) {
            if (str_contains($textLine, 'apikey')) {
                $apiKey = trim($textLine);
                $apiKey = str_replace('apikey= "', '', $apiKey);
                $apiKey = str_replace('"', '', $apiKey);
            }

            if (str_contains($textLine, 'token')) {
                $token = trim($textLine);
                $token = str_replace('token=', '', $token);
            }
        }

        if (null === $apiKey || null === $token) {
            throw new Exception('ApiKey or Token not found, something went wrong!');
        }

        return [$apiKey, $token];
    }

    public function removeTeamSpeakContainer(Server $server): void
    {
        $process = new Process(['docker', 'rm', '-f', $server->getContainerName()]);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }

    public function startTeamSpeakContainer(Server $server): void
    {
        $webQueryPort = $server->getPort() + 80;

        $process = new Process([
            "docker", "run", "-d",
            "--name", $server->getContainerName(),
            "-p", "{$server->getPort()}:9987/udp",
            "-p", "$webQueryPort:10080",
            "-e", "TS3SERVER_LICENSE=accept",
            "-e", "TS3SERVER_QUERY_PROTOCOLS=raw,ssh,http,https",
            "teamspeak:3.13.6",
        ]);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }
}
