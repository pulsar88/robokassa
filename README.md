# Fillincode/Robokassa

Пакет для интеграции с робокассой

## Installation

```shell
composer require fillincode/robokassa
```

Необходимо опубликовать конфигурацию пакета

```php
php artisan vendor:publish --provider="Fillincode\Robokassa\RobokassaServiceProvider"
```

## Config

Нужно корректно указать имена переменных из env файла. Пакет логирует создание новых объектов класса, поэтому можно указать, какой канал будет использоваться

```php
[
    'login' => env('ROBOKASSA_LOGIN', 'test_login'),
    'pass_1' => env('ROBOKASSA_PASS_1', 'test_pass1'),
    'pass_2' => env('ROBOKASSA_PASS_2', 'test_pass2'),
    'test_pass_1' => env('ROBOKASSA_TEST_PASS_1', 'test_pass1'),
    'test_pass_2' => env('ROBOKASSA_TEST_PASS_2', 'test_pass2'),
    'is_test' => env('ROBOKASSA_TEST', true),
    
    'log_driver' => 'stack',
];
```

## Methods

getLink. Генерация ссылки для оплаты

```php
$robokassa = new Robokassa($invoice->id, 'Покупка', $sum);
$link = $robokassa->getLink();
```

checkResultCRC. Проверка crc в маршруте /result

```php
$robokassa = new Robokassa($request->get('InvId'), '', $request->get('OutSum'), $request->get('OutSumCurrency'));
$status = $robokassa->checkResultCRC($request->get('SignatureValue'));
```

checkSuccessCRC. Проверка crc в маршруте /success

```php
$robokassa = new Robokassa($request->get('InvId'), '', $request->get('OutSum'), $request->get('OutSumCurrency'));
$status = $robokassa->checkSuccessCRC($crc);
```