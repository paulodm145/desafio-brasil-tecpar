<?php

namespace App\Command;

use App\Entity\Blocks;
use App\Services\HashService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class hashcommand extends Command
{
    private $logger;

    private $hash_service;

    private $entityManager;

    public function __construct(LoggerInterface $logger, HashService $hash_service, EntityManagerInterface $entityManager)
    {
        $this->logger = $logger;
        $this->hash_service = $hash_service;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('MOnta uma listagem de hashs a partir de uma string')
            ->setName('avato:test')
            ->setHelp('avato:test “Ávato” --requests=20')
            ->addArgument('input_string', InputArgument::REQUIRED, 'Informe uma palavra para o hash:')
            ->addOption(
                'requests',
                null,
                InputOption::VALUE_OPTIONAL,
                'This command generate hash starting with 0000',
                false
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputString = $input->getArgument('input_string');
        $quantity = $input->getOption('requests');

        $blocks = [];
        for ($i = 0; $i <= $quantity; ++$i) {
            $hash_service = new HashService();
            $data = $hash_service->hashString($inputString);
            ++$i;

            $em = $this->entityManager;

            $hash = new Blocks();
            $hash->setBatch($data['batch']);
            $hash->setBlockNumber($i);
            $hash->setEnterString($data['string']);
            $hash->setChaves($data['key']);
            $hash->setGenerateHash($data['generated_hash']);
            $hash->setAttempts($data['attempts']);

            $blocks[] = [
                'batch' => $data['batch']->format('Y-m-d H:i:s'),
                'block_number' => $i,
                'string' => $data['string'],
                'key' => $data['key'],
                'generated_hash' => $data['generated_hash'],
                'attempts' => $data['attempts'],
            ];

            $em->persist($hash);
            $em->flush();
            $inputString = $data['generated_hash'];
            --$i;
        }

        echo json_encode($blocks);

        return Command::SUCCESS;
    }
}
