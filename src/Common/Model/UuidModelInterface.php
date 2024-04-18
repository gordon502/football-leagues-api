<?php

namespace App\Common\Model;

interface UuidModelInterface
{
    public function getId(): string;

    public function createId(): void;
}
