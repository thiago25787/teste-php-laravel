<?php

namespace App\Http\Requests;

use App\Enums\CategoryEnum;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Closure;
use Illuminate\Support\Str;

class DocumentRequest extends AbstractRequest
{
    public function rules(): array
    {
        return [
            'financial_year' => ['required', 'integer'],
            'category' => ['required', 'string'],
            'contents' => ['required', 'string', 'max:1500'],
            'title' => ['required', 'max:60', $this->validateTitleByCategory()],
        ];
    }

    private function validateTitleByCategory(): Closure
    {
        return function (string $item, string $input, Closure $fail) {
            $categoryName = Str::lower($this->get('category'));
            $inputLowerCase = Str::lower($input);
            $errorMessage = __('Existe algum registro inválido');
            if ($categoryName == Str::lower(CategoryEnum::REMESSA->name())) {
                if (!Str::contains($inputLowerCase, 'semestre', true)) {
                    $fail($errorMessage . ' em ' . CategoryEnum::REMESSA->name());
                }
            }

            if ($categoryName == Str::lower(CategoryEnum::REMESSA_PARCIAL->name())) {
                if (!Str::contains($inputLowerCase, $this->getMonths(), true)) {
                    $fail($errorMessage . ' em ' . CategoryEnum::REMESSA_PARCIAL->name());
                }
            }
        };
    }

    private function getMonths(): array
    {
        Carbon::setLocale('pt_BR');
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $date = CarbonImmutable::create(null, $i, 1);
            $months[] = Str::lower($date->translatedFormat('F'));
        }

        return $months;
    }
}
