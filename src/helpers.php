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

function getProductsCount(string $s): int
{
    $count = 0;

    for ($i = 0; $i < strlen($s); $i++)
    {
        if ($s[$i] >= '0' && $s[$i] <= '9'){
            $count *= 10;
            $count += ord($s[$i]) - 48;
        }
    }

    return $count;
}


function ktLiraFormatter(string $s): float
{
    $intValue = $floatValue = $check = 0;
    $helpValue = 1;

    for ($i = 0; $i < strlen($s); $i++){

        if ($s[$i] == ','){

            $check = true;
            continue;

        }

        if ($s[$i] >= '0' && $s[$i] <= '9'){

            if ($check){
                $helpValue *= 10;
                $floatValue *= 10;
                $floatValue += ord($s[$i]) - 48;

            } else {

                $intValue *= 10;
                $intValue += ord($s[$i]) - 48;

            }
        }
    }

    $floatValue /= $helpValue;

    return $intValue + $floatValue;
}
