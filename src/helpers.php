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