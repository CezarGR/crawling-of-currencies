<?php

namespace App\Services\Currency;

use App\Services\Crawling\CurrencyCrawlingService;
use App\Repositories\Currency\CurrencyRepository;
use App\DTO\Currency\CurrencyLocationDTO;
use Illuminate\Support\Collection;
use App\DTO\Currency\CurrencyDTO;
use InvalidArgumentException;
use Illuminate\Support\Str;
use App\Models\Currency;
use Exception;

class CurrencyService 
{
    public function search(?array $codes, ?array $numbers) 
    {
        $service = new CurrencyCrawlingService();
        return match (true) {
            (! empty($codes)) => $this->searchByCode($codes, $service), 
            (! empty($numbers)) => $this->searchByNumber($numbers, $service),
            default => throw new InvalidArgumentException()
        };
    }

    private function searchByCode(array $codes, CurrencyCrawlingService $service) 
    {
        $currencies = Currency::useCache()
            ->whereInCache('code', $codes)
            ->getCache()
            ->map(fn ($currency) => CurrencyDTO::fromCurrencyModel($currency));

        if (! $currencies->isEmpty() && $currencies->count() == count($codes)) {
            return $currencies;
        }

        $codes = array_diff($codes, $currencies->pluck('code')->toArray());
        $currencyDetails = $service->crawlCurrencyISOByCodes($codes);
        $currencySymbols = $service->crawlCurrencySymbolByCodes($codes);

        throw_if(
            $currencyDetails->isEmpty(),
            new Exception('Erro ao procurar informações utilizando o código como parâmetro')
        );
        
        $currencyEntities = $this->createCurrencyAndLocation(
            $currencyDetails,
            $currencySymbols
        );

        $currencyEntities = $currencies->isEmpty() ?
            collect($currencyEntities) :
            collect($currencyEntities)->push(...$currencies);
                
        return $currencyEntities;
    }

    private function searchByNumber(array $numbers, CurrencyCrawlingService $service) 
    {
        $numbers = $this->convertNumbersIntToString($numbers);
        
        $currencies = Currency::useCache()
            ->whereInCache('number', $numbers->toArray())
            ->getCache()
            ->map(fn ($currency) => CurrencyDTO::fromCurrencyModel($currency));

        if (! $currencies->isEmpty() && $currencies->count() == count($numbers)) {
            return $currencies;
        }

        $numbers = array_diff($numbers->toArray(), $currencies->pluck('number')->toArray());
        $currencyDetails = $service->crawlCurrencyISOByNumbers($numbers);
        $currencySymbols = $service->crawlCurrencySymbolByNumbers($numbers);

        throw_if(
            $currencyDetails->isEmpty(),
            new Exception('Erro ao procurar informacoes da moeda pelo código')
        );
        
        $currencyEntities = $this->createCurrencyAndLocation(
            $currencyDetails,
            $currencySymbols
        );

        if (! $currencies->isEmpty()) {
            $currencyEntities->push(...$currencies);
        }
  
        return $currencyEntities;
    }

    private function createCurrencyAndLocation(Collection $currencyDetails, Collection $currencySymbols) : Collection
    {
        $dtos = collect([]);
        $currencyDetails->each(function ($item) use (&$dtos, $currencySymbols) {
            $dtos->push(new CurrencyDTO(
                name: data_get($item, 'name'),
                number: data_get($item, 'number'),
                code: data_get($item, 'code'),
                decimalPlaces: data_get($item, 'decimal_places'),
                symbol: data_get(
                    collect($currencySymbols)->where('code', data_get($item, 'code'))->first(),
                    'symbol'
                ),
                locations: collect(explode(',', data_get($item, 'locations')))
                    ->map(fn ($local, $i) => new CurrencyLocationDTO(
                        location: str_replace(' ', '', $local),
                        icon: data_get($item, 'icons')[$i] ?? null,
                    ))
            ));
        });

        (new CurrencyRepository)->insert($dtos);

        return $dtos;
    }

    public function convertNumbersIntToString(array $numbers) : Collection
    {
        return collect($numbers)->map(fn ($num) => Str::padLeft($num, 3, '0'));
    }
}