<?php

namespace App\Services;

use App\Models\Fingerprint;
use App\Models\Order;
use App\Models\Project;

class FingerprintService {
    /**
     * Добавить новый слепок в базу данных
     *
     * @param Project project Проект, к которому относится слепок
     * @param string $visitor_hash
     * @param string|null $local_id
     * @param string|null $ip
     * @param string|null $user_agent
     * @param string|null $language
     * @param string|null $platform
     * @param string|null $screen
     * @param integer|null $color_depth
     * @param float|null $pixel_ratio
     * @param string|null $timezone
     * @param string|null $referrer
     * @param string|null $connection_type
     * @param integer|null $memory
     * @param integer|null $cores
     * @param boolean|null $webdriver
     * @param integer|null $time_to_submit
     * @return Fingerprint Новый слепок
     */
    public function create(
        Project $project,
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
                'project_token' => $project->id,
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