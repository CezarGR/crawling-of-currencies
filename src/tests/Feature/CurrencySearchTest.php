<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CurrencySearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_v1_search_currency_by_code()
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

    public function test_v1_search_currencies_by_codes()
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

    public function test_v1_search_currencies_by_codes_duplicate()
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

    public function test_v1_search_currency_by_number()
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

    public function test_v1_search_currencies_by_numbers()
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

    public function test_v1_search_currencies_by_numbers_duplicate()
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

    public function test_v1_serd_request_search_currencies_by_code_null_and_empty()
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

    public function test_v1_serd_request_search_currencies_by_number_null_and_empty()
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

    public function test_v1_serd_request_search_currencies_by_code_with_lowercase_letterss()
    {
        $response = $this->post(
            route('currencies.v1.search'),
            [
                'code_list' => ['brl']
            ]
        );

        $response->assertSuccessful();
        $response->assertJson([
            'message' => 'Informações sobre a moeda retornada com sucesso', 
            "data" => [
                [
                    "name" => "Real",
                    "code" => "BRL",
                    "number" => "986",
                    "symbol" => "R$",
                    "decimal_places" => 2,
                    "locations" => true
                ]
            ]
        ]);
    }

    // V2

    public function test_v2_search_currency_by_code()
    {
        $code = ['EUR'];

        $response = $this->post(
            route('currencies.v2.search'),
            [
                'codes' => $code
            ]
        );

        $response->assertSuccessful();
        $response->assertJson([
            'message' => true, 
            'data' => true
        ]);
    }

    public function test_v2_search_currencies_by_codes()
    {
        $codes = ['EUR','CLF','CLP','CNY','COP','COU','CRC','CUC','CUP','CVE','CZK','DJF','DKK','DOP','DZD','ECS','EGP','ERN','ETB','BRL'];

        $response = $this->post(
            route('currencies.v2.search'),
            [
                'codes' => $codes
            ]
        );

        $response->assertSuccessful();
        $response->assertJson([
            'message' => 'Informações sobre as moedas retornada com sucesso', 
            'data' => true
        ]);
        $response->assertJsonCount(count($codes), 'data');
    }

    public function test_v2_search_currencies_by_codes_duplicate()
    {
        $codes = ['EUR','BRL','EUR','BRL'];

        $response = $this->post(
            route('currencies.v2.search'),
            [
                'codes' => $codes
            ]
        );

        $response->assertSuccessful();
        $response->assertJson([
            'message' => 'Informações sobre as moedas retornada com sucesso', 
            'data' => true
        ]);
        $response->assertJsonCount(2, 'data');
    }

    public function test_v2_search_currency_by_number()
    {
        $number = ['978'];

        $response = $this->post(
            route('currencies.v2.search'),
            [
                'numbers' => $number
            ]
        );

        $response->assertSuccessful();
        $response->assertJson([
            'message' => true, 
            'data' => true
        ]);
    }

    public function test_v2_search_currencies_by_numbers()
    {
        $numbers = ['976','947','756','948','990','152','156','170','970','188','931'];

        $response = $this->post(
            route('currencies.v2.search'),
            [
                'numbers' => $numbers
            ]
        );

        $response->assertSuccessful();
        $response->assertJson([
            'message' => 'Informações sobre as moedas retornada com sucesso', 
            'data' => true
        ]);
        $response->assertJsonCount(count($numbers), 'data');
    }

    public function test_v2_search_currencies_by_numbers_duplicate()
    {
        $numbers = ['976','947','976','947'];

        $response = $this->post(
            route('currencies.v2.search'),
            [
                'numbers' => $numbers
            ]
        );

        $response->assertSuccessful();
        $response->assertJson([
            'message' => 'Informações sobre as moedas retornada com sucesso', 
            'data' => true
        ]);
        $response->assertJsonCount(2, 'data');
    }

    public function test_v2_serd_request_search_currencies_by_code_null_and_empty()
    {
        $response = $this->post(
            route('currencies.v2.search'),
            [
                'codes' => null
            ]
        );

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Um erro inesperado aconteceu.', 
            'erros' => true
        ]);

        $response = $this->post(
            route('currencies.v2.search'),
            [
                'codes' => ''
            ]
        );

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Um erro inesperado aconteceu.', 
            'erros' => true
        ]);
    }

    public function test_v2_serd_request_search_currencies_by_number_null_and_empty()
    {
        $response = $this->post(
            route('currencies.v2.search'),
            [
                'numbers' => null
            ]
        );

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Um erro inesperado aconteceu.', 
            'erros' => true
        ]);

        $response = $this->post(
            route('currencies.v2.search'),
            [
                'numbers' => ''
            ]
        );

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Um erro inesperado aconteceu.', 
            'erros' => true
        ]);
    }

    public function test_v2_serd_request_search_currencies_by_codes_invalids()
    {
        $response = $this->post(
            route('currencies.v2.search'),
            [
                'codes' => ['BRLBRL']
            ]
        );

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Um erro inesperado aconteceu.', 
            'erros' => [
                "codes.0" => [
                    "É necessário que o cógido tenho 3 caracteres"
                ]
            ]
        ]);

        $response = $this->post(
            route('currencies.v2.search'),
            [
                'codes' => ['BR']
            ]
        );

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Um erro inesperado aconteceu.', 
            'erros' => [
                "codes.0" => [
                    "É necessário que o cógido tenho 3 caracteres"
                ]
            ]
        ]);

        $response = $this->post(
            route('currencies.v2.search'),
            [
                'codes' => ['BRL', '123']
            ]
        );

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Um erro inesperado aconteceu.', 
            'erros' => [
                "codes.1" => [
                    "É necessário que o cógido seja composto somente por letras"
                ]
            ]
        ]);
    }

    public function test_v2_serd_request_search_currencies_by_numbers_invalids()
    {
        $response = $this->post(
            route('currencies.v2.search'),
            [
                'numbers' => ['1232']
            ]
        );

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Um erro inesperado aconteceu.', 
            'erros' => [
                "numbers.0" => [
                    "É necessário que o cógido tenho 3 caracteres númerico"
                ]
            ]
        ]);

        $response = $this->post(
            route('currencies.v2.search'),
            [
                'numbers' => ['12']
            ]
        );

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Um erro inesperado aconteceu.', 
            'erros' => [
                "numbers.0" => [
                    "É necessário que o cógido tenho 3 caracteres númerico"
                ]
            ]
        ]);

        $response = $this->post(
            route('currencies.v2.search'),
            [
                'numbers' => ['036', 'BRL']
            ]
        );

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Um erro inesperado aconteceu.', 
            'erros' => [
                "numbers.1" => [
                    "É necessário que a sequência  seja composto somente por números"
                ]
            ]
        ]);
    }

    public function test_v2_serd_request_search_currencies_by_code_with_lowercase_letterss()
    {
        $response = $this->post(
            route('currencies.v2.search'),
            [
                'codes' => ['brl']
            ]
        );

        $response->assertSuccessful();
        $response->assertJson([
            'message' => 'Informações sobre a moeda retornada com sucesso', 
            "data" => [
                [
                    "name" => "Real",
                    "code" => "BRL",
                    "number" => "986",
                    "symbol" => "R$",
                    "decimal_places" => 2,
                    "locations" => true
                ]
            ]
        ]);
    }
}
