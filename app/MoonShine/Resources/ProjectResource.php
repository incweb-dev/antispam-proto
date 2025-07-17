<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Project;
use App\Services\ProjectService;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Laravel\Fields\Relationships\HasMany;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;

/**
 * @extends ModelResource<Project>
 */
class ProjectResource extends ModelResource
{
    protected string $model = Project::class;

    protected string $title = 'Проекты';

    protected bool $createInModal = true;
 
    protected bool $editInModal = true;
    
    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make(label: 'Название', column: 'name'),
            Text::make(label: 'Токен', column: 'token')->copy(),
            Number::make(label: 'Кол-во слепков', column: 'fingerprints_count'),
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
                Text::make(label: 'Название', column: 'name')->required(),
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
            Text::make(label: 'Название', column: 'name'),
            Text::make(label: 'Токен', column: 'token')->copy(),
            Number::make(label: 'Кол-во слепков', column: 'fingerprints_count'),
            HasMany::make(label: 'Слепки:', relationName: 'fingerprints', resource: FingerprintResource::class),
        ];
    }

    /**
     * @param Project $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [
            'name' => 'unique:projects,name,'.$item->id,
        ];
    }

    protected function beforeCreating(mixed $item): mixed
    {
        $item->token = (new ProjectService)->generateUniqueToken();

        return $item;
    }
}
