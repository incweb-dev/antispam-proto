<?php

namespace App\Services;

use App\Models\Fingerprint;
use App\Models\Order;

class SpamScoreService {
    /**
     * Оценить слепок по признакам спама. Значение сохраняется в поле score переданной модели.
     *
     * @param Fingerprint $fingerprint Оцениваемый слепок
     * @return float Итоговая оценка
     */
    public function score(Fingerprint $fingerprint): float {
        $finalScore = array_sum([
            $this->checkLocalId($fingerprint),
            $this->checkOtherOrdersIps($fingerprint),
            $this->checkUserAgent($fingerprint),
            $this->checkSubmitTime($fingerprint),
            $this->checkVisitorHash($fingerprint),
            $this->checkReferrerAndUtm($fingerprint),
            $this->checkWebdriver($fingerprint),
        ]);

        $fingerprint->update(['score' => $finalScore]);

        return $finalScore;
    }

    protected function checkLocalId(Fingerprint $fingerprint): float
    {
        $lastFingerprint = Fingerprint::where('project_id', $fingerprint->project_id)
            ->where('id', '!=', $fingerprint->id)
            ->orderByDesc('id')
            ->value('local_id');

        return $lastFingerprint === $fingerprint->local_id ? 5.0 : 0.0;
    }

    protected function checkOtherOrdersIps(Fingerprint $fingerprint): float {
        return Fingerprint::where('project_id', $fingerprint->project_id)
            ->where('ip', $fingerprint->ip)
            ->where('id', '!=', $fingerprint->id)
            ->exists() ? 2.0 : 0.0;
    }

    protected function checkUserAgent(Fingerprint $fingerprint): float {
        if (is_null($fingerprint->user_agent)) {
            return 0.0;
        }

        return Fingerprint::where('user_agent', 'like', substr($fingerprint->user_agent, 0, 64) . '%')
                ->where('created_at', '>=', now()->subMinutes(10))
                ->where('id', '!=', $fingerprint->id)
                ->exists() ? 1.0 : 0.0;
    }

    protected function checkSubmitTime(Fingerprint $fingerprint): float {
        return $fingerprint->time_to_submit > 3 ? 2.0 : 0.0;
    }

    protected function checkVisitorHash(Fingerprint $fingerprint): float {
        return Fingerprint::where('visitor_hash', $fingerprint->visitor_hash)
            ->where('created_at', '>=', now()->subMinutes(10))
            ->where('id', '!=', $fingerprint->id)
            ->exists() ? 3.0 : 0.0;
    }

    protected function checkReferrerAndUtm(Fingerprint $fingerprint): float {
        if (empty($fingerprint->referrer)) {
            return 1.0;
        }

        $query = parse_url($fingerprint->referrer, PHP_URL_QUERY);
        if (!$query) {
            return 1.0;
        }

        parse_str($query, $params);

        foreach (['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'] as $key) {
            if (!empty($params[$key])) {
                return 0.0;
            }
        }

        return 1.0;
    }

    protected function checkWebdriver(Fingerprint $fingerprint): float {
        return $fingerprint->webdriver ? 0.0 : 4.0;
    }
};