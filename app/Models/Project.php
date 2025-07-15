<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    public const TOKEN_LENGTH = 5;

    protected $fillable = [
        'token'
    ];

    public function fingerprints(): HasMany
    {
        return $this->hasMany(Fingerprint::class);
    }

    /**
     * Сгенерировать уникальный токен для проекта.
     * При увеличении функционала приложения необходимо перенести этот метод в отдельный класс-действие.
     *
     * @return string Уникальный токен проекта
     */
    static public function generateUniqueToken(): string
    {
        for ($attempts = 0; $attempts < 10; $attempts++) {
            $tokens = [];

            for ($i = 0; $i < 5; $i++) {
                $tokens[] = bin2hex(random_bytes(static::TOKEN_LENGTH));
            }

            $existingTokens = Project::whereIn('token', $tokens)->select('token')->pluck('token')->toArray();

            $freeTokens = array_values(array_diff($tokens, $existingTokens));

            if(count($freeTokens) > 0) {
                return $freeTokens[0];
            }
        }

        throw new \RuntimeException('Unable to generate unique project token.');
    }
}
