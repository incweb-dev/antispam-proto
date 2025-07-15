<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateFingerprintRequest;
use App\Services\FingerprintService;
use App\Services\SpamScoreService;
use Illuminate\Http\JsonResponse;

class ScoreFingerprintController extends Controller
{
    public function __invoke(CreateFingerprintRequest $request, FingerprintService $fingerprintService, SpamScoreService $spamScoreService): JsonResponse
    {
        $newFingerprint = $fingerprintService->create(
            project_token: $request->project_token,
            visitor_hash: $request->visitor_hash,
            local_id: $request->local_id,
            ip: $request->ip,
            user_agent: $request->user_agent,
            language: $request->language,
            platform: $request->platform,
            screen: $request->screen,
            color_depth: $request->color_depth,
            pixel_ratio: $request->pixel_ratio,
            timezone: $request->timezone,
            referrer: $request->referrer,
            connection_type: $request->connection_type,
            memory: $request->memory,
            cores: $request->cores,
            webdriver: $request->webdriver,
            time_to_submit: $request->time_to_submit,
        );

        $score = $spamScoreService->calculate($newFingerprint);

        return response()->json(['score' => $score]);
    }
}
