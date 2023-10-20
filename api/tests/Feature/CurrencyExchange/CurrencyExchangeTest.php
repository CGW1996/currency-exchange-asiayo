<?php

namespace Tests\Feature\CurrencyExchange;

use Tests\TestCase;
use App\Services\CurrencyExchangeService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Testing\TestResponse;

class CurrencyExchangeTest extends TestCase
{
    public function test_currency_exchange_valid_source_target()
    {
        $service = new CurrencyExchangeService();

        $response = $service->exchangeCurrency('TWD', 'JPY', '1,000');
        $testRes = new TestResponse($response);
        $testRes->assertStatus(200)
            ->assertJson(['msg' => 'success', 'amount' => "3,669.00"]);
    }

    public function test_currency_exchange_invalid_source()
    {
        $service = new CurrencyExchangeService();

        $this->expectException(HttpResponseException::class);
        $response = $service->exchangeCurrency('CNY', 'TWD', 100);
        $response->assertStatus(422)
            ->assertJson(['msg' => 'Invalid source or target currency', 'amount' => null]);
    }

    public function test_currency_exchange_invalid_target()
    {
        $service = new CurrencyExchangeService();
        $this->expectException(HttpResponseException::class);
        $response = $service->exchangeCurrency('KRW', 'CNY', 100);
        $response->assertStatus(422)
            ->assertJson(['msg' => 'Invalid source or target currency', 'amount' => null]);
    }

    public function test_currency_exchange_invalid_source_target()
    {
        $service = new CurrencyExchangeService();
        $this->expectException(HttpResponseException::class);
        $response = $service->exchangeCurrency('TWD', 'CNY', 100);
        $response->assertStatus(422)
            ->assertJson(['msg' => 'Invalid source or target currency', 'amount' => null]);
    }

    public function test_currency_exchange_invalid_amount_string()
    {
        $service = new CurrencyExchangeService();
        $this->expectException(HttpResponseException::class);
        $response = $service->exchangeCurrency('TWD', 'JPY', 'not_number');
        $testRes = new TestResponse($response);
        $testRes->assertStatus(422)
            ->assertJson(['msg' => 'Invalid amount', 'amount' => null]);
    }

    public function test_currency_exchange_invalid_number()
    {
        $service = new CurrencyExchangeService();
        $this->expectException(HttpResponseException::class);

        $response = $service->exchangeCurrency('TWD', 'JPY', '100,00');
        $testRes = new TestResponse($response);
        $testRes->assertStatus(422)
            ->assertJson(['msg' => 'Invalid amount', 'amount' => null]);
    }

    public function test_currency_exchange_float()
    {
        $service = new CurrencyExchangeService();
        $result = $service->exchangeCurrency('USD', 'TWD', 1.11);
        $testRes = new TestResponse($result);
        $testRes->assertStatus(200)
            ->assertJson(['msg' => 'success', 'amount' => '33.79']);
    }

    public function test_currency_exchange_integer()
    {
        $service = new CurrencyExchangeService();
        $result = $service->exchangeCurrency('TWD', 'JPY', 1000);
        $testRes = new TestResponse($result);
        $testRes->assertStatus(200)
            ->assertJson(['msg' => 'success', 'amount' => '3,669.00']);
    }

    public function test_currency_exchange_string_integer()
    {
        $service = new CurrencyExchangeService();
        $result = $service->exchangeCurrency('TWD', 'JPY', '1,234');
        $testRes = new TestResponse($result);
        $testRes->assertStatus(200)
            ->assertJson(['msg' => 'success', 'amount' => '4,527.55']);
    }

    public function test_currency_exchange_string_float()
    {
        $service = new CurrencyExchangeService();
        $result = $service->exchangeCurrency('TWD', 'JPY', '1,234.56');
        $testRes = new TestResponse($result);
        $testRes->assertStatus(200)
            ->assertJson(['msg' => 'success', 'amount' => '4,529.60']);
    }
}