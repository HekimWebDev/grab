<?php

namespace Domains\Prices\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Money\Currency;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Parser\DecimalMoneyParser;
use Money\Parser\IntlLocalizedDecimalParser;
use NumberFormatter;

class Money implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        /*$money =  new \Money\Money($value, new Currency('TRY'));
        $currencies = new ISOCurrencies();
        $moneyFormatter = new DecimalMoneyFormatter($currencies);
        return $moneyFormatter;*/
        return $value;
    }

    public function set($model, string $key, $value, array $attributes): array
    {
        if (is_float($value)) {
            return [$key => $value];
        } 

        $currencies = new ISOCurrencies();

        $numberFormatter = new \NumberFormatter('try_TRY', \NumberFormatter::DECIMAL);
        $moneyParser = new IntlLocalizedDecimalParser($numberFormatter, $currencies);

        $money = $moneyParser->parse($value, new Currency('TRY'));

        $moneyFormatter = new DecimalMoneyFormatter($currencies);

        return [
            $key => $moneyFormatter->format($money)
        ];
    }
}
