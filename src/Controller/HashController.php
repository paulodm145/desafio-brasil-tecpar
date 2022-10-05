<?php

namespace App\Controller;

use App\Entity\Blocks;
use App\Services\HashService;
use PHPUnit\Util\Json;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

use Symfony\Component\RateLimiter\RateLimiterFactory;

class HashController extends AbstractController
{
    private $hash_service;
    private $kernel;

    public function __construct(HashService $hash_Service, KernelInterface $kernel)
    {
        $this->hash_service = $hash_Service;
        $this->kernel = $kernel;
    }

    /**
     * @Route("/hash/{input_string}/{attempts}", name="cliente", methods={"GET"})
     */
    public function index(string $input_string, int $attempts, RateLimiterFactory $anonymousApiLimiter): Response
    {
        $request = new Request();
        $limiter = $anonymousApiLimiter->create($request->getClientIp());
        $limit = $limiter->consume();
        $headers = [
            'X-RateLimit-Remaining' => $limit->getRemainingTokens(),
            'X-RateLimit-Retry-After' => $limit->getRetryAfter()->getTimestamp(),
            'X-RateLimit-Limit' => $limit->getLimit(),
        ];

        if (false === $limit->isAccepted()) {
            return new JsonResponse(["Limite de requisições excedido"],Response::HTTP_TOO_MANY_REQUESTS, $headers);
        }

        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'avato:test',
            'input_string' => $input_string,
            '--requests' => $attempts,
        ]);
        $output = new BufferedOutput();
        $application->run($input, $output);
        $content = $output->fetch();

        return new Response($content);
    }
}
