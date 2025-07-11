<?php

namespace App\Services;

use App\Models\Fingerprint;
use App\Models\Order;

class FingerprintService {
    public function createOrUpdate(
        string $visitor_hash,
        ?string $local_id,
        ?string $ip,
        ?string $user_agent,
        ?string $language,
        ?string $platform,
        ?string $screen,
        ?int $color_depth,
        ?float $pixel_ratio,
        ?string $timezone,
        ?string $referrer,
        ?string $connection_type,
        ?int $memory,
        ?int $cores,
        ?bool $webdriver,
        ?int $time_to_submit,
    ): Fingerprint {
        return Fingerprint::firstOrCreate(
            [
                'visitor_hash' => $visitor_hash,
                'local_id' => $local_id,
            ],

            [
                'ip' => $ip,
                'user_agent' => $user_agent,
                'language' => $language,
                'platform' => $platform,
                'screen' => $screen,
                'color_depth' => $color_depth,
                'pixel_ratio' => $pixel_ratio,
                'timezone' => $timezone,
                'referrer' => $referrer,
                'connection_type' => $connection_type,
                'memory' => $memory,
                'cores' => $cores,
                'webdriver' => $webdriver,
                'time_to_submit' => $time_to_submit,
            ],
        );
    }

    public function bindToOrder(Fingerprint $fingerprint, Order $order): void {
        if($order->fingerprint_id !== $fingerprint->id) {
            $order->update(['fingerprint_id' => $fingerprint->id]);
        }
    }

    public function findByHash(string $hash): ?Fingerprint {
        return Fingerprint::where('visitor_hash', $hash)->first();
    }
};