<?php

namespace App\Services\v2\Crawling;

use App\DTO\Crawling\CrawlingCurrencyDTO;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Collection;
use Goutte\Client;

class CurrencyCrawlingService 
{
    public const URL_WIKI_CURRENCIES = 'https://pt.wikipedia.org/wiki/ISO_4217';
    public const URL_JUST_MARKETS_CURRENCIES = 'https://justmarkets.com/pt/education/currencies';

    public function __construct()
    {
        //
    }

    public function crawlCurrency(?array $codes, ?array $numbers) : Collection
    {   
        $recordsWiki = $this->crawlCurrencyByWiki($codes, $numbers);
        $recordsJustMarket = $this->crawlCurrencyByJustMarkets($codes, $numbers);

        $currencyDatas = collect([]);
        $recordsWiki->each(function ($record) use (&$currencyDatas, $recordsJustMarket){
            $currencyDatas->push(new CrawlingCurrencyDTO(
                name: data_get($record, 'name'),
                number: data_get($record, 'number'),
                code: data_get($record, 'code'),
                decimalPlaces: data_get($record, 'decimal_places') ?? 0,
                locations: collect(explode(',', data_get($record, 'locations')))
                    ->map(function ($local, $i) use ($record) {
                        return [
                            'location' => $local,
                            'icon' => data_get($record, 'icons')[$i] ?? null
                        ];
                    })
                    ->toArray(),
                symbol: data_get(
                    collect($recordsJustMarket)->where('code', data_get($record, 'code'))->first(),
                    'symbol'
                )
            ));
        });

        return $currencyDatas;
    }

    public function crawlCurrencyByWiki(?array $codes, ?array $numbers) : Collection
    {
        $client = new Client();
        $client->setServerParameters([
            'timeout' => 60
        ]);
        $crawler = $client->request(
            'GET',
            $this::URL_WIKI_CURRENCIES
        );

        $currencies = $crawler
            ->filter('table.sortable tbody tr')
            ->nextAll()
            ->each(function (Crawler $table, $i) {
                return [
                    'code' => ($table->children('td'))->eq(0)->text(),
                    'number' => ($table->children('td'))->eq(1)->text(),
                    'decimal_places' => ($table->children('td'))->eq(2)->text(),
                    'name' => ($table->children('td'))->eq(3)->text(),
                    'locations' => ($table->children('td'))->eq(4)->text(),
                    'icons' => ($table->children('td'))->eq(4)->children('img')->each(function ($item) {
                        return $item->attr('src');
                    }),
                ];
            });
        
        return empty($codes) ? 
            collect($currencies)->whereIn('number', $numbers) :
            collect($currencies)->whereIn('code', $codes);
    }

    public function crawlCurrencyByJustMarkets(?array $codes, ?array $numbers) : Collection
    {
        $client = new Client();
        $client->setServerParameters([
            'timeout' => 60
        ]);
        $crawler = $client->request(
            'GET',
            $this::URL_JUST_MARKETS_CURRENCIES
        );

        $currencies = $crawler
            ->filter('table#js-table-currencies tbody tr')
            ->nextAll()
            ->each(function (Crawler $table, $i) {
                return [
                    'code' => ($table->children('td'))->eq(0)->text(),
                    'symbol' => ($table->children('td'))->eq(1)->text(),
                    'number' => ($table->children('td'))->eq(2)->text(),
                    'name' => ($table->children('td'))->eq(3)->text() 
                ];
            });
        
        return empty($codes) ? 
            collect($currencies)->whereIn('number', $numbers) :
            collect($currencies)->whereIn('code', $codes);  
    }
}