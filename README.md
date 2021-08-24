# PagHiper

![version](https://img.shields.io/badge/version-0.2-blue) ![php](https://img.shields.io/badge/php-%20>=%207.4-green)  

Tudo o que você precisa para geração de Boletos da plataforma PagHiper utilizando o PHP

# Instalação

```
composer require interstation/paghiper:dev-main
```

# Exemplos
## Geração de Boleto

```
<?php

include_once('PagHiperController.php');

require('../vendor/autoload.php');


$paghyper = new PagHiperController();
$paghyper->setOrderId('AZ1')
->setPayerEmail('email@email.com')
->setPayerName('Foo Bar')
->setPayerCpfCnpj('01234567890')
->setDaysDueDate('4')
->setItems([
    [
        'item_id' => '1',
        'price_cents' => '1012',
        'quantity' => '1',
        'description' => 'Piscina de Bolinha'
    ]
]);

$result = $paghyper->gerarBoleto();

var_dump($result);
```

> Sinta-se a vontade para colaborar com o projeto