<?php
declare(strict_types=1);

namespace UserSettings\Service;

interface CodeGeneratorInterface
{
    public function generate(int $digits): int;
}