# PagHiperController
Controlador de uso público da plataforma PagHiper para geração de boletos.

# Exemplo de Uso

## Geração de Boleto

```<?php

use AZ\controller\PagHiperController;

require('../vendor/autoload.php');


$paghyper = new PagHiperController();
$paghyper->setOrderId('AZ1')
->setPayerEmail('mxhugoxm@gmail.com')
->setPayerName('Hugo Henrique')
->setPayerCpfCnpj('01234567890')
->setDaysDueDate('4')
->setItems([
    [
        'item_id' => '1',
        'price_cents' => '1012',
        'quantity' => '1',
        'description' => 'Piscina de Bolinha'
    ]
])
->setToken();

$result = $paghyper->gerarBoleto();

var_dump($result);```
