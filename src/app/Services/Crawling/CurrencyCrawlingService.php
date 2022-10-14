<?php

namespace App\Services\Crawling;

use Goutte\Client;
use Illuminate\Support\Collection;
use Symfony\Component\DomCrawler\Crawler;

class CurrencyCrawlingService 
{
    public const URL_IS0 = 'https://pt.wikipedia.org/wiki/ISO_4217';
    public const URL_JUST_MARKETS = 'https://justmarkets.com/pt/education/currencies';

    public function __construct()
    {
        //
    }

    public function crawlCurrencyISOByCodes(array $codes) : Collection
    {
        $client = new Client();
        $client->setServerParameters([
            'timeout' => 60
        ]);
        $crawler = $client->request(
            'GET',
            $this::URL_IS0
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
        
        return collect($currencies)->whereIn('code', $codes);
    }

    public function crawlCurrencyISOByNumbers(array $numbers) : Collection
    {
        $client = new Client();
        $client->setServerParameters([
            'timeout' => 60
        ]);
        $crawler = $client->request(
            'GET',
            $this::URL_IS0
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
        
        return collect($currencies)->whereIn('number', $numbers);
    }

    public function crawlCurrencySymbolByCodes(array $codes) : Collection
    {
        $client = new Client();
        $client->setServerParameters([
            'timeout' => 60
        ]);
        $crawler = $client->request(
            'GET',
            $this::URL_JUST_MARKETS
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
        
        return collect($currencies)->whereIn('code', $codes);
    }

    public function crawlCurrencySymbolByNumbers(array $numbers) : Collection
    {
        $client = new Client();
        $client->setServerParameters([
            'timeout' => 60
        ]);
        $crawler = $client->request(
            'GET',
            $this::URL_JUST_MARKETS
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
        
        return collect($currencies)->whereIn('number', $numbers);
    }
}