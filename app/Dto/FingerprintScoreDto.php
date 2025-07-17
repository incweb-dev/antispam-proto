<?php

namespace App\Dto;

use Illuminate\Contracts\Support\Arrayable;

readonly class FingerprintScoreDto implements Arrayable
{
    public function __construct(
        public bool $status,
        public ?float $score,
        public string $message,
    )
    {
        //
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'score' => $this->score,
            'message' => $this->message,
        ];
    }
};