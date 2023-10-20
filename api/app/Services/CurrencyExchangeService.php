<?php

namespace App\Services;

use App\Interfaces\ExchangeRateInterface;
use App\Sources\staticExchangeRate;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class CurrencyExchangeService
{
    private ExchangeRateInterface $handler;

    public function __construct($call_source = null)
    {
        $call_source = $call_source ?: new staticExchangeRate();
        $this->handler = $call_source;
    }
    public function exchangeCurrency($source, $target, $amount)
    {
        $rate = $this->handler->exchangeRate();
        if (!isset($rate[$source]) || !isset($rate[$target])) {
            throw new HttpResponseException(response()->json([
                'msg' => 'Invalid source or target currency',
                'amount' => null,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
        }

        if (!$this->isValidNumber($amount)) {
            throw new HttpResponseException(response()->json([
                'msg' => 'Invalid amount',
                'amount' => null,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
        }
        
        $amount = floatval(str_replace(',', '', $amount));

        $convertedAmount = round($amount * $rate[$source][$target], 2);

        $formattedAmount = number_format($convertedAmount, 2);

        return response()->json([
            'msg' => 'success',
            'amount' => $formattedAmount
        ], 200);
    }

    public function isValidNumber($input) {
        $pattern = '/^\d{1,}(,\d{3})*(?:\.\d+)?$/';
        return preg_match($pattern, $input);
    }
}