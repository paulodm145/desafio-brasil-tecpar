<?php

namespace App\Controller;

use App\Repository\BlocksRepository;
use App\Services\HashService;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Routing\Annotation\Route;

class HashController extends AbstractController
{
    private $hash_service;
    private $kernel;
    private $blocks_repository;

    public function __construct(HashService $hash_Service, KernelInterface $kernel, BlocksRepository $blocks_repository)
    {
        $this->hash_service = $hash_Service;
        $this->kernel = $kernel;
        $this->blocks_repository = $blocks_repository;
    }

    /**
     * @Route("/hash/{input_string}/{attempts}", name="hash_created", methods={"GET"})
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
            return new JsonResponse(['Exceeded request limit'], Response::HTTP_TOO_MANY_REQUESTS, $headers);
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

    /**
     * @Route("/search/{input_string}", name="lists_hash", methods={"GET"})
     */
    public function search(string $input_string, Request $request): JsonResponse
    {
        $page = $request->query->has('page') ? $request->query->getInt('page') : 1;
        $limit = $request->query->has('limit') ? $request->query->getInt('limit') : 10;

        $display_results = $this->blocks_repository->findByInputString($input_string, $page, $limit);

        if (0 === count($display_results)) {
            return new JsonResponse(['NOT FOUND'], 404);
        }

        return new JsonResponse($this->blocks_repository->findByInputString($input_string, $page, $limit), 200);
    }
}
