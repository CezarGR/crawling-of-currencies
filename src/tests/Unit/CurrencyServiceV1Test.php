<?php

namespace Tests\Unit;

use App\Services\v1\Currency\CurrencyService;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Str;

class CurrencyServiceTest extends TestCase
{    
    public function test_convert_numbers_int_to_string()
    {
        $numbers = [51, 32, 36];
        $result = (new CurrencyService)->convertNumbersIntToString($numbers);

        $this->assertTrue($result instanceof Collection);
        $this->assertTrue(gettype($result->first()) === 'string');
        $this->assertCount(count($numbers), $result);
    }
}
