<?php

namespace App\DTOs;

class DocumentDTO
{
    public function __construct(
        public ?string $title,
        public ?string $contents,
        public ?string $categoryName,
        public ?int $financialYear,
    ) {

    }
}
