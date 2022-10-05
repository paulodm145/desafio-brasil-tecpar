<?php

namespace App\Services;

use App\Entity\Blocks;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;


class HashService
{

    public function hashString(string $word): array
    {
        $texto = "";
        $data = new \DateTime();
        $lista = [];

        $i = 0;
        for($i = 0 ; $i<= 1000000; $i++)
        {
            $chave = $this->generate_key(8);
            $hash = md5($word.$chave);
            $inicioHash = substr($hash, 0, 4);

            if ($inicioHash === "0000") {
                $lista[] = [
                    "batch" => $data,
                    "string" => $word,
                    "key" => $chave,
                    "generated_hash" => $hash,
                    "attempts" =>$i
                ];
                break;
            }
        }
        return $lista ? $lista[0] : [];
    }

    public function generate_key(int $tamanho): string
    {
        $key = str_shuffle("abcdefghijklmnopqrstuvyxwzABCDEFGHIJKLMNOPQRSTUVYXWZ");
        return substr(str_shuffle($key),0,$tamanho);
    }

}