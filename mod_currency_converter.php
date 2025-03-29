<?php
defined('_JEXEC') or die;
$dataFile = __DIR__ . '/exchange_rates.json';
$exchangeRates = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : array("USD"=>74.23,"EUR"=>87.54,"RUB"=>1.02);
require JModuleHelper::getLayoutPath('mod_currency_converter', 'default');
?>
