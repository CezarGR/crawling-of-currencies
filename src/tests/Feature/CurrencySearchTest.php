<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CurrencySearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_currency_by_code()
    {
        $code = 'EUR';

        $response = $this->post(
            route('currencies.v1.search'),
            [
                'code' => $code
            ]
        );

        $response->assertSuccessful();
        $response->assertJson([
            'message' => true, 
            'data' => true
        ]);
    }

    public function test_search_currencies_by_codes()
    {
        $code = ['EUR','CLF','CLP','CNY','COP','COU','CRC','CUC','CUP','CVE','CZK','DJF','DKK','DOP','DZD','ECS','EGP','ERN','ETB','BRL'];

        $response = $this->post(
            route('currencies.v1.search'),
            [
                'code_list' => $code
            ]
        );

        $response->assertSuccessful();
        $response->assertJson([
            'message' => 'Informações sobre as moedas retornada com sucesso', 
            'data' => true
        ]);
        $response->assertJsonCount(20, 'data');
    }

    public function test_search_currencies_by_codes_duplicate()
    {
        $codes = ['EUR','BRL','EUR','BRL'];

        $response = $this->post(
            route('currencies.v1.search'),
            [
                'code' => $codes
            ]
        );

        $response->assertSuccessful();
        $response->assertJson([
            'message' => 'Informações sobre as moedas retornada com sucesso', 
            'data' => true
        ]);
        $response->assertJsonCount(2, 'data');
    }

    public function test_search_currency_by_number()
    {
        $number = 978;

        $response = $this->post(
            route('currencies.v1.search'),
            [
                'number' => $number
            ]
        );

        $response->assertSuccessful();
        $response->assertJson([
            'message' => true, 
            'data' => true
        ]);
    }

    public function test_search_currencies_by_numbers()
    {
        $numbers = [976,947,756,948,990,152,156,170,970,188,931];

        $response = $this->post(
            route('currencies.v1.search'),
            [
                'number_list' => $numbers
            ]
        );

        $response->assertSuccessful();
        $response->assertJson([
            'message' => 'Informações sobre as moedas retornada com sucesso', 
            'data' => true
        ]);
        $response->assertJsonCount(count($numbers), 'data');
    }

    public function test_search_currencies_by_numbers_duplicate()
    {
        $numbers = [976,947,976,947];

        $response = $this->post(
            route('currencies.v1.search'),
            [
                'number_list' => $numbers
            ]
        );

        $response->assertSuccessful();
        $response->assertJson([
            'message' => 'Informações sobre as moedas retornada com sucesso', 
            'data' => true
        ]);
        $response->assertJsonCount(2, 'data');
    }

    public function test_serd_request_search_currencies_by_code_null_and_empty()
    {
        $response = $this->post(
            route('currencies.v1.search'),
            [
                'code' => null
            ]
        );

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Um erro inesperado aconteceu.', 
            'erros' => true
        ]);

        $response = $this->post(
            route('currencies.v1.search'),
            [
                'code' => ''
            ]
        );

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Um erro inesperado aconteceu.', 
            'erros' => true
        ]);
    }

    public function test_serd_request_search_currencies_by_number_null_and_empty()
    {
        $response = $this->post(
            route('currencies.v1.search'),
            [
                'number' => null
            ]
        );

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Um erro inesperado aconteceu.', 
            'erros' => true
        ]);

        $response = $this->post(
            route('currencies.v1.search'),
            [
                'number' => ''
            ]
        );

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Um erro inesperado aconteceu.', 
            'erros' => true
        ]);
    }
}
