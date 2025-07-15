<?php

namespace App\Services;

use App\Models\Fingerprint;
use App\Models\Order;
use App\Models\Project;

class FingerprintService {
    public function create(
        string $project_token,
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
        return Fingerprint::create(
            [
                'project_token' => Project::where('token', $project_token)->select('id')->firstOrFail()->id,
                'visitor_hash' => $visitor_hash,
                'local_id' => $local_id,
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
};