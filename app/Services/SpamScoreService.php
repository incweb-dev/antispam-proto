<?php

namespace App\Services;

use App\Models\Fingerprint;
use App\Models\Order;

class SpamScoreService {
    public function calculate(Fingerprint $fingerprint): float {
        return array_sum([
            $this->checkLocalId($fingerprint),
            $this->checkOtherOrdersIps($fingerprint),
            $this->checkUserAgent($fingerprint),
            $this->checkSubmitTime($fingerprint),
            $this->checkVisitorHash($fingerprint),
            $this->checkReferrerAndUtm($fingerprint),
            $this->checkWebdriver($fingerprint),
        ]);
    }

    protected function checkLocalId(Fingerprint $fingerprint): float
    {
        $lastOrderLocalId = Order::latest()->with('fingerprint')->first()?->fingerprint?->local_id;

        return $lastOrderLocalId === $fingerprint->local_id ? 5.0 : 0.0;
    }

    protected function checkOtherOrdersIps(Fingerprint $fingerprint): float {
        return Order::whereHas('fingerprint',
            fn ($query) => $query->where('ip', $fingerprint->ip)
        )->exists() ? 2.0 : 0.0;
    }

    protected function checkUserAgent(Fingerprint $fingerprint): float {
        return Fingerprint::where('user_agent', 'like', substr($fingerprint->user_agent, 0, 64) . '%')
                ->where('created_at', '>=', now()->subMinutes(10))
                ->exists() ? 1.0 : 0.0;
    }

    protected function checkSubmitTime(Fingerprint $fingerprint): float {
        return $fingerprint->submit_time > 3 ? 2.0 : 0.0;
    }

    protected function checkVisitorHash(Fingerprint $fingerprint): float {
        return Fingerprint::where('visitor_hash', $fingerprint->visitor_hash)
            ->where('created_at', '<=', now()->subMinutes(10))
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