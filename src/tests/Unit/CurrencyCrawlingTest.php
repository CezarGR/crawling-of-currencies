<?php

namespace Tests\Unit;

use App\Services\Crawling\CurrencyCrawlingService;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class CurrencyCrawlingTest extends TestCase
{    
    public function test_currency_crawling_iso_by_codes()
    {
        $codes = ['EUR','BRL','EUR','BRL'];
        $result =(new CurrencyCrawlingService)->crawlCurrencyISOByCodes($codes);

        $this->assertTrue($result instanceof Collection);
        $this->assertTrue($result->count() == 2);
    }

    public function test_currency_symbol_crawling_by_codes()
    {
        $codes = ['EUR','BRL','EUR','BRL'];
        $result =(new CurrencyCrawlingService)->crawlCurrencySymbolByCodes($codes);

        $this->assertTrue($result instanceof Collection);
        $this->assertTrue($result->count() == 2);
        $this->assertContains('BRL', $result->pluck('code'));
        $this->assertContains('EUR', $result->pluck('code'));
    }

    public function test_currency_symbol_crawling_by_text_number_start_with_zero()
    {
        $numbers = ['051', '032', '036'];
        $result =(new CurrencyCrawlingService)->crawlCurrencySymbolByNumbers($numbers);

        $this->assertTrue($result instanceof Collection);
        $this->assertTrue($result->count() == count($numbers));
        $this->assertContains('ARS', $result->pluck('code'));
        $this->assertContains('AMD', $result->pluck('code'));
        $this->assertContains('AUD', $result->pluck('code'));
    }
}
