<?php
declare(strict_types = 1);
namespace App\Model\Common;

interface Serializable
{
    public function toArray(): array;
}
