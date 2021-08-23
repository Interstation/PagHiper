<?php namespace Interstation\PagHiper;

/**
 * Classe para geração de Boletos do PagHiper
 * 
 * @author Hugo Henrique <mxhugoxm@gmail.com>
 * @version 0.0.1
 * @access public
 * 
 * @property const API_KEY *Chave de API do PagHypen
 * @property const TOKEN *Token da sua conta no PagHyper
 * @property String $payerName *Nome do Pagador
 * @property String $payerEmail *Email do Pagador
 * @property String $payerCpfCnpj *CPF/CNPJ do Pagador
 * @property String $daysDueDate *Dias até o vencimento
 * @property String $typeBankSlip *Tipo de impressão
 * @property String $orderId *Id do Pedido
 * @property Array $items *Item do carrinho
 * @property String $payerPhone Telefone pessoal ou residencial do Pagador *Apenas números
 * @property String $payerStreet Rua do Pagador
 * @property String $payerNumber Número da casa do Pagador
 * @property String $payerComplement Complemento do Pagador
 * @property String $payerDistrict Distrito do Pagador
 * @property String $payerCity Cidade do Pagador
 * @property String $payerState Estado do Pagador
 * @property String $payerZipCode CEP do Pagador *Apenas números
 * @property String $discountCents Disconto em Centavos
 * @property String $shippingPriceCents Preço do Frete em Centavos
 * @property String $shippingMethods Método de envio. Exemplos: PAC, SEDEX...
 * @property Bool $fixedDescription Frase da descrição do boleto
 * @property Bool $perDayInterest Juros por Atraso
 * @property String $latePaymentFine Percentual da Muta
 * @property String $notificationUrl URL de notificação
 * @property String $token Token de Solicitação do PagHyper
 * @property String $notificationId;
 * @property String $transactionId;
 */
class PagHiper
{

    private const API_KEY = "";
    private const TOKEN = "";
    private String $payerName = '';
    private String $payerEmail = '';
    private String $payerCpfCnpj = '';
    private String $payerPhone = '';
    private String $payerStreet = '';
    private String $payerNumber = '';
    private String $payerComplement = '';
    private String $payerDistrict = '';
    private String $payerCity = '';
    private String $payerState = '';
    private String $payerZipCode = '';
    private String $discountCents = '';
    private String $shippingPriceCents = ''; // Frete
    private String $shippingMethods = ''; // Meio de envio
    private String $fixedDescription = '';
    private String $daysDueDate = '';
    private Bool $perDayInterest = false;
    private String $latePaymentFine = '';
    private String $typeBankSlip = 'boletoA4';
    private array $items;
    private String $notificationUrl = 'http://www.seusite.com/api/notify/paghiper';
    private String $token = "{}";
    private String $orderId;
    private String $notificationId;
    private String $transactionId;
    
    /**
	 * Get Headers for the CURL Requests
	 * 
	 * @return array
	 */
	public function getHeaders() : array {
        return array(
            "Accept: application/json",
            "Accept-Charset: UTF-8",
            "Accept-Encoding: application/json",
            "Content-Type: application/json;charset=UTF-8"
        );
    }


    public function getNotificationToken()
    {
		try {
			return \json_encode(
				array(
					"apiKey" => $this::API_KEY,
					"token" => $this::TOKEN,
					"notification_id" => $this->getNotificationId(),
					"transaction_id" => $this->getTransactionId()
				)
			);
		} catch (\Exception $exception){
			throw new \Error($exception->getMessage());
		} 
        

    }

    public function obterStatusDoBoleto()
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.paghiper.com/transaction/notification/",
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => $this->getHeaders(),
            CURLOPT_POSTFIELDS => $this->getNotificationToken(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false
        ]);
        $result = curl_exec($curl);
        return json_decode($result, true);
    }

    public function gerarBoleto()
    {
		$curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.paghiper.com/transaction/create/',
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => $this->getHeaders(),
            CURLOPT_POSTFIELDS => $this->getToken(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false
        ]);
        $result = curl_exec($curl);
        return json_decode($result, true);
    }

    public function setToken()
    {
        $json = [
            "apiKey" => $this::API_KEY,
            "order_id" => $this->getOrderId(),
            "payer_email" => $this->getPayerEmail(),
            "payer_name" => $this->getPayerName(),
            "payer_cpf_cnpj" => $this->getPayerCpfCnpj(),
            "payer_phone" => $this->getPayerName(),
            "payer_street" => $this->getPayerStreet(),
            "payer_number" => $this->getPayerNumber(),
            "payer_complement" => $this->getPayerComplement(),
            "payer_district" => $this->getPayerDistrict(),
            "payer_city" => $this->getPayerCity(),
            "payer_state" => $this->getPayerState(),
            "payer_zip_code" => $this->getPayerZipCode(),
            "notification_url" => $this->getNotificationUrl(),
            "discount_cents" => $this->getDiscountCents(),
            "shipping_price_cents" => $this->getShippingPriceCents(),
            "shipping_methods" => $this->getShippingMethods(),
            "fixed_description" => $this->getFixedDescription(),
            "type_bank_slip" => $this->getTypeBankSlip(),
            "days_due_date" => $this->getDaysDueDate(),
            "late_payment_fine" => $this->getLatePaymentFine(),
            "per_day_interest" => $this->getPerDayInterest(),
            "items" => $this->getItems()
        ];
        $this->token = json_encode($json, JSON_UNESCAPED_SLASHES);
        return $this;
    }


    /**
     * @return  mixed
     */
    public function getPayerName()
    {
        return $this->payerName;
    }

    /**
     * @param   mixed  $payerName  
     * @return  self
     */
    public function setPayerName($payerName)
    {
        $this->payerName = $payerName;
        return $this;
    }

    /**
     * @return  mixed
     */
    public function getPayerEmail()
    {
        return $this->payerEmail;
    }

    /**
     * @param   mixed  $payerEmail  
     * @return  self
     */
    public function setPayerEmail($payerEmail)
    {
        $this->payerEmail = $payerEmail;
        return $this;
    }

    /**
     * @return  mixed
     */
    public function getPayerCpfCnpj()
    {
        return $this->payerCpfCnpj;
    }

    /**
     * @param   mixed  $payerCpfCnpj  
     * @return  self
     */
    public function setPayerCpfCnpj($payerCpfCnpj)
    {
        $this->payerCpfCnpj = $payerCpfCnpj;
        return $this;
    }

    /**
     * @return  mixed
     */
    public function getPayerPhone()
    {
        return $this->payerPhone;
    }

    /**
     * @param   mixed  $payerPhone  
     * @return  self
     */
    public function setPayerPhone($payerPhone)
    {
        $this->payerPhone = $payerPhone;
        return $this;
    }

    /**
     * @return  mixed
     */
    public function getPayerStreet()
    {
        return $this->payerStreet;
    }

    /**
     * @param   mixed  $payerStreet  
     * @return  self
     */
    public function setPayerStreet($payerStreet)
    {
        $this->payerStreet = $payerStreet;
        return $this;
    }

    /**
     * @return  mixed
     */
    public function getPayerNumber()
    {
        return $this->payerNumber;
    }

    /**
     * @param   mixed  $payerNumber  
     * @return  self
     */
    public function setPayerNumber($payerNumber)
    {
        $this->payerNumber = $payerNumber;
        return $this;
    }

    /**
     * @return  mixed
     */
    public function getPayerComplement()
    {
        return $this->payerComplement;
    }

    /**
     * @param   mixed  $payerComplement  
     * @return  self
     */
    public function setPayerComplement($payerComplement)
    {
        $this->payerComplement = $payerComplement;
        return $this;
    }

    /**
     * @return  mixed
     */
    public function getPayerDistrict()
    {
        return $this->payerDistrict;
    }

    /**
     * @param   mixed  $payerDistrict  
     * @return  self
     */
    public function setPayerDistrict($payerDistrict)
    {
        $this->payerDistrict = $payerDistrict;
        return $this;
    }

    /**
     * @return  mixed
     */
    public function getPayerCity()
    {
        return $this->payerCity;
    }

    /**
     * @param   mixed  $payerCity  
     * @return  self
     */
    public function setPayerCity($payerCity)
    {
        $this->payerCity = $payerCity;
        return $this;
    }

    /**
     * @return  mixed
     */
    public function getPayerState()
    {
        return $this->payerState;
    }

    /**
     * @param   mixed  $payerState  
     * @return  self
     */
    public function setPayerState($payerState)
    {
        $this->payerState = $payerState;
        return $this;
    }

    /**
     * @return  mixed
     */
    public function getPayerZipCode()
    {
        return $this->payerZipCode;
    }

    /**
     * @param   mixed  $payerZipCode  
     * @return  self
     */
    public function setPayerZipCode($payerZipCode)
    {
        $this->payerZipCode = $payerZipCode;
        return $this;
    }

    /**
     * @return  mixed
     */
    public function getDiscountCents()
    {
        return $this->discountCents;
    }

    /**
     * @param   mixed  $discountCents  
     * @return  self
     */
    public function setDiscountCents($discountCents)
    {
        $this->discountCents = $discountCents;
        return $this;
    }

    /**
     * @return  mixed
     */
    public function getShippingPriceCents()
    {
        return $this->shippingPriceCents;
    }

    /**
     * @param   mixed  $shippingPriceCents  
     * @return  self
     */
    public function setShippingPriceCents($shippingPriceCents)
    {
        $this->shippingPriceCents = $shippingPriceCents;
        return $this;
    }

    /**
     * @return  mixed
     */
    public function getShippingMethods()
    {
        return $this->shippingMethods;
    }

    /**
     * @param   mixed  $shippingMethods  
     * @return  self
     */
    public function setShippingMethods($shippingMethods)
    {
        $this->shippingMethods = $shippingMethods;
        return $this;
    }

    /**
     * @return  mixed
     */
    public function getFixedDescription()
    {
        return $this->fixedDescription;
    }

    /**
     * @param   mixed  $fixedDescription  
     * @return  self
     */
    public function setFixedDescription($fixedDescription)
    {
        $this->fixedDescription = $fixedDescription;
        return $this;
    }

    /**
     * @return  mixed
     */
    public function getDaysDueDate()
    {
        return $this->daysDueDate;
    }

    /**
     * @param   mixed  $daysDueDate  
     * @return  self
     */
    public function setDaysDueDate($daysDueDate)
    {
        $this->daysDueDate = $daysDueDate;
        return $this;
    }

    /**
     * @return  mixed
     */
    public function getTypeBankSlip()
    {
        return $this->typeBankSlip;
    }

    /**
     * @param   mixed  $typeBankSlip  
     * @return  self
     */
    public function setTypeBankSlip($typeBankSlip)
    {
        $this->typeBankSlip = $typeBankSlip;
        return $this;
    }

    /**
     * @return  mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param   mixed  $items  
     * @return  self
     */
    public function setItems($items)
    {
        $this->items = $items;
		$this->setToken();
        return $this;
    }

    /**
     * @return  mixed
     */
    public function getNotificationUrl()
    {
        return $this->notificationUrl;
    }

    /**
     * @param   mixed  $notificationUrl  
     * @return  self
     */
    public function setNotificationUrl($notificationUrl)
    {
        $this->notificationUrl = $notificationUrl;
        return $this;
    }

    /**
     * @return  mixed
     */
    public function getLatePaymentFine()
    {
        return $this->latePaymentFine;
    }

    /**
     * @param   mixed  $latePaymentFine  
     * @return  self
     */
    public function setLatePaymentFine($latePaymentFine)
    {
        $this->latePaymentFine = $latePaymentFine;
        return $this;
    }

    /**
     * @return  mixed
     */
    public function getPerDayInterest()
    {
        return $this->perDayInterest;
    }

    /**
     * @param   mixed  $perDayInterest  
     * @return  self
     */
    public function setPerDayInterest($perDayInterest)
    {
        $this->perDayInterest = $perDayInterest;
        return $this;
    }

    /**
     * @return  mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return  mixed
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param   mixed  $orderId  
     * @return  self
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
        return $this;
    }

	/**
	 * @return  mixed
	 */
	public function getNotificationId(){
	    return $this->notificationId;
	}

	/**
	 * @param   mixed  $notificationId  
	 * @return  self
	 */
	public function setNotificationId($notificationId){
	    $this->notificationId = $notificationId;
		return $this;
	}

	/**
	 * @return  mixed
	 */
	public function getTransactionId(){
	    return $this->transactionId;
	}

	/**
	 * @param   mixed  $transactionId  
	 * @return  self
	 */
	public function setTransactionId($transactionId){
	    $this->transactionId = $transactionId;
		return $this;
	}
}
