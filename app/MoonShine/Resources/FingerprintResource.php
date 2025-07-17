<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Fingerprint;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Laravel\Enums\Action;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;

/**
 * @extends ModelResource<Fingerprint>
 */
class FingerprintResource extends ModelResource
{
    protected string $model = Fingerprint::class;

    protected string $title = 'Слепки';
    
    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make(label: 'Visitor Hash', column: 'visitor_hash'),
            Text::make(label: 'IP', column: 'ip'),
            Number::make(label: 'Оценка', column: 'score'),
            Date::make(label: 'Дата', column: 'created_at')->format('d.m.Y H:i:s'),
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make(),
            ])
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
            ID::make(),
            BelongsTo::make(label: 'Проект', relationName: 'project', resource: ProjectResource::class),
            Text::make(label: 'Visitor Hash', column: 'visitor_hash'),
            Text::make(label: 'Local ID', column: 'local_id'),
            Text::make(label: 'IP', column: 'ip'),
            Text::make(label: 'User Agent', column: 'user_agent'),
            Text::make(label: 'Language', column: 'language'),
            Text::make(label: 'Platform', column: 'platform'),
            Text::make(label: 'Screen', column: 'screen'),
            Text::make(label: 'Color Depth', column: 'color_depth'),
            Text::make(label: 'Pixel Ratio', column: 'pixel_ratio'),
            Text::make(label: 'Timezone', column: 'timezone'),
            Text::make(label: 'Referrer', column: 'referrer'),
            Text::make(label: 'Connection Type', column: 'connection_type'),
            Text::make(label: 'Memory', column: 'memory'),
            Text::make(label: 'Cores', column: 'cores'),
            Text::make(label: 'Webdriver', formatted: fn($fingerprint) => $fingerprint->webdriver ? 'Да' : 'Нет'),
            Number::make(label: 'Время отправки (сек.)', column: 'time_to_submit'),
            Number::make(label: 'Оценка', column: 'score'),
        ];
    }

    /**
     * @param Fingerprint $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }

    protected function activeActions(): ListOf
    {
        return parent::activeActions()->except(Action::CREATE, Action::UPDATE);
    }
}
