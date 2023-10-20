<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CurrencyExchangeRequest;
use App\Services\CurrencyExchangeService;
use App\Sources\staticExchangeRate;

class CurrencyExchangeController extends Controller
{
    public function exchange(CurrencyExchangeRequest $request) {
        $source = $request->source;
        $target = $request->target;
        $amount = $request->amount;
        $changeRate = new staticExchangeRate();
        $changeService = new CurrencyExchangeService($changeRate);
        return $changeService->exchangeCurrency($source, $target, $amount);
    }
}
