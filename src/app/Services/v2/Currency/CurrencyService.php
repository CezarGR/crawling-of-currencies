<?php

namespace App\Services\v2\Currency;

use App\DTO\Crawling\CrawlingCurrencyDTO;
use App\Services\v2\Crawling\CurrencyCrawlingService;
use App\Repositories\Currency\CurrencyRepository;
use Illuminate\Support\Collection;
use App\DTO\Currency\CurrencyDTO;
use InvalidArgumentException;
use App\Models\Currency;
use Exception;

class CurrencyService 
{
    protected CurrencyCrawlingService $service;

    public function __construct()
    {
        $this->service = new CurrencyCrawlingService();
    }

    public function search(?array $codes, ?array $numbers) 
    {
        return match (true) {
            (! empty($codes)) => $this->searchByCode($codes), 
            (! empty($numbers)) => $this->searchByNumber($numbers),
            default => throw new InvalidArgumentException('Argumento inválido, é necessário o parâmetro codes ou numbers')
        };
    }

    public function searchByCode(array $codes) 
    {
        $codes = array_map( 'strtoupper', $codes);
        $currencies = Currency::useCache()
            ->whereInCache('code', $codes)
            ->getCache()
            ->map(fn ($currency) => CurrencyDTO::fromCurrencyModel($currency));

        if (! $currencies->isEmpty() && $currencies->count() == count($codes)) {
            return $currencies;
        }

        $codes = array_diff($codes ?? [], $currencies->pluck('code')->toArray());
        $crawlingCurrencyDTOs = $this->service->crawlCurrency($codes, null);

        throw_if(
            $crawlingCurrencyDTOs->isEmpty(),
            new Exception(
                'Erro ao obter informações utilizando o código ISO como parâmetro')
        );
        
        $currencyDTOs = $this->createCurrencyAndLocation($crawlingCurrencyDTOs);

        $currencyDTOs = $currencies->isEmpty() ?
            collect($currencyDTOs) :
            collect($currencyDTOs)->push(...$currencies);
                
        return $currencyDTOs;
    }

    public function searchByNumber(array $numbers) 
    {
        $currencies = Currency::useCache()
            ->whereInCache('number', $numbers)
            ->getCache()
            ->map(fn ($currency) => CurrencyDTO::fromCurrencyModel($currency));

        if (! $currencies->isEmpty() && $currencies->count() == count($numbers)) {
            return $currencies;
        }

        $numbers = array_diff($numbers ?? [], $currencies->pluck('number')->toArray());

        $crawlingCurrencyDTOs = $this->service->crawlCurrency(null, $numbers);

        throw_if(
            $crawlingCurrencyDTOs->isEmpty(),
            new Exception(
                'Erro ao obter informações utilizando o números ISO como parâmetro')
        );
        
        $currencyDTOs = $this->createCurrencyAndLocation($crawlingCurrencyDTOs);

        $currencyDTOs = $currencies->isEmpty() ?
            collect($currencyDTOs) :
            collect($currencyDTOs)->push(...$currencies);
                
        return $currencyDTOs;
    }

    private function createCurrencyAndLocation(Collection $crawlingCurrencyDTOs) : Collection
    {
        $dtos = collect([]);
        $crawlingCurrencyDTOs->each(function (CrawlingCurrencyDTO $item) use (&$dtos) {
            $dtos->push(CurrencyDTO::fromCrawlingCurrencyDTO($item));
        });

        (new CurrencyRepository)->insert($dtos);

        return $dtos;
    }

    public function listCurrencies() : Collection
    {
        return (new CurrencyRepository)->list();
    }
}