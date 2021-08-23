<?php namespace Interstation\PagHiper\Tests;

require \dirname(__DIR__).'/vendor/autoload.php';

class PagHiperTest extends \PHPUnit\Framework\TestCase {
	
	protected function assertPreConditions() : void{
		$this->assertTrue(\class_exists('Interstation\PagHiper\PagHiper'));
	}

	public function testarCriacaoDeBoleto(){
		$boleto = new \Interstation\PagHiper\PagHiper();
		$this->assertIsObject($boleto);
		$boleto->setOrderId('AZ1')
		->setPayerEmail('email@email.com')
		->setPayerName('Foo Bar')
		->setPayerCpfCnpj('01234567890')
		->setDaysDueDate('4')
		->setItems([
			[
				'item_id' => '1',
				'price_cents' => '1012',
				'quantity' => '1',
				'description' => 'Ball Pool'
			]
		]);
		$this->assertTrue($boleto->gerarBoleto()['create_request']['result'] != 'reject');
	}
}

