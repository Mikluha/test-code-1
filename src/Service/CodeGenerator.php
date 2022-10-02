<?php
declare(strict_types=1);

use UserSettings\Service\CodeGeneratorInterface;

class CodeGenerator implements CodeGeneratorInterface
{
    public function generate(int $digits): int
    {
        return rand(pow(10, $digits-1), pow(10, $digits)-1);
    }
}