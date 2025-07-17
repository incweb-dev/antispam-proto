<?php

namespace App\Http\Controllers\Api;

use App\Dto\FingerprintScoreDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateFingerprintRequest;
use App\Services\FingerprintService;
use App\Services\ProjectService;
use App\Services\SpamScoreService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ScoreFingerprintController extends Controller
{
    public function __invoke(
        CreateFingerprintRequest $request,
        ProjectService $projectService,
        FingerprintService $fingerprintService,
        SpamScoreService $spamScoreService,
    ): JsonResponse
    {
        $dto = null;
        
        try {
            $project = $projectService->findByToken($request->project_token);

            $newFingerprint = $fingerprintService->create(
                project: $project,
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

            $score = $spamScoreService->score($newFingerprint);

            $projectService->countFingerprints($project);

            $dto = new FingerprintScoreDto(status: Response::HTTP_OK, score: $score, message: 'Fingerprint has been evaluated');
        } catch (ModelNotFoundException $e) {
            $dto = new FingerprintScoreDto(status: Response::HTTP_NOT_FOUND, score: null, message: 'Bad project token');
        } finally {
            return response()->json(data: $dto, status: $dto->status);
        }
    }
}
