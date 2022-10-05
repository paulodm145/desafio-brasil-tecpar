<?php

namespace App\Entity;

use App\Repository\BlocksRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BlocksRepository::class)]
class Blocks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $batch = null;

    #[ORM\Column]
    private ?int $blockNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $enterString = null;

    #[ORM\Column(length: 255)]
    private ?string $generateHash = null;

    #[ORM\Column(length: 255)]
    private ?string $attempts = null;

    #[ORM\Column(length: 255)]
    private ?string $chaves = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBatch(): ?\DateTimeInterface
    {
        return $this->batch;
    }

    public function setBatch(\DateTimeInterface $batch): self
    {
        $this->batch = $batch;

        return $this;
    }

    public function getBlockNumber(): ?int
    {
        return $this->blockNumber;
    }

    public function setBlockNumber(int $blockNumber): self
    {
        $this->blockNumber = $blockNumber;

        return $this;
    }

    public function getEnterString(): ?string
    {
        return $this->enterString;
    }

    public function setEnterString(string $enterString): self
    {
        $this->enterString = $enterString;

        return $this;
    }

    public function getGenerateHash(): ?string
    {
        return $this->generateHash;
    }

    public function setGenerateHash(string $generateHash): self
    {
        $this->generateHash = $generateHash;

        return $this;
    }

    public function getAttempts(): ?string
    {
        return $this->attempts;
    }

    public function setAttempts(string $attempts): self
    {
        $this->attempts = $attempts;

        return $this;
    }

    public function getChaves(): ?string
    {
        return $this->chaves;
    }

    public function setChaves(string $chaves): self
    {
        $this->chaves = $chaves;

        return $this;
    }
}
