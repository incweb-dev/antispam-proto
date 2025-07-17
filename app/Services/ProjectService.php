<?php

namespace App\Services;

use App\Models\Project;

/**
 * Сервисный класс для модели Project
 */
class ProjectService {
    /**
     * Сгенерировать уникальный токен для проекта
     *
     * @return string Уникальный токен проекта
     */
    public function generateUniqueToken(): string
    {
        for ($attempts = 0; $attempts < 10; $attempts++) {
            $tokens = [];

            for ($i = 0; $i < 5; $i++) {
                $tokens[] = bin2hex(random_bytes(Project::TOKEN_LENGTH));
            }

            $existingTokens = Project::whereIn('token', $tokens)->select('token')->pluck('token')->toArray();

            $freeTokens = array_values(array_diff($tokens, $existingTokens));

            if(count($freeTokens) > 0) {
                return $freeTokens[0];
            }
        }

        throw new \RuntimeException('Unable to generate unique project token.');
    }

    /**
     * Посчитать слепки, привязанные к проекту
     *
     * @param Project $project Проект
     * @return integer Подсчитанное количество слепков
     */
    public function countFingerprints(Project $project): int {
        $project->update([
            'fingerprints_count' => $project->fingerprints()->count(),
        ]);
        
        return $project->fingerprints_count;
    }

    /**
     * Найти проект по токену
     *
     * @param string $token Токен
     * @return Project Найденный проект
     * @throws ModelNotFoundException Если проект с указанным токеном не найден
     */
    public function findByToken(string $token): Project {
        return Project::where('token', $token)->findOrFail();
    }
};