<?php

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Parser\IntlLocalizedDecimalParser;

function liraCast($value)
{
    $currencies = new ISOCurrencies();

    $numberFormatter = new \NumberFormatter('try_TRY', \NumberFormatter::DECIMAL);
    $moneyParser = new IntlLocalizedDecimalParser($numberFormatter, $currencies);

    $money = $moneyParser->parse($value, new Currency('TRY'));

    $moneyFormatter = new DecimalMoneyFormatter($currencies);

    return $moneyFormatter->format($money);
}

function ayLiraFormatter($value): float
{
    $intValue = 0;
    $floatValue = 0;
    $helpValue = 1;
    $check = true;

    for ($i = 0;  $i < strlen($value); $i++){

        if ($value[$i] == '.') continue;

        if ($value[$i] == ',')
        {
            $check = false;
            continue;
        }

        if($value[$i] >= '0' && $value[$i] <= '9')
        {
            if ($check) {
                $intValue *= 10;
                $intValue += ord($value[$i]) - 48;
            } else {
                $helpValue *= 10;
                $floatValue += (ord($value[$i]) - 48) / $helpValue;
            }
        }
    }

    return $intValue + $floatValue;
}
