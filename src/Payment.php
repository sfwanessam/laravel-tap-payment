<?php
namespace Essam\TapPayment;
use Request;

class Payment extends Reference implements Tap{
  protected $CARD_SET = false;



  public function __construct($config = []){
    foreach ($this->REQUIRED_CONFIG_VARS as $parm => $req_status) {
      if (key_exists($parm,$config)) {
        $this->CONFIG_VARS[$parm] = $config[$parm];
      }else{
        if($req_status){
          throw new \InvalidArgumentException("InvalidArgumentException $parm field");
        }
      }
    }

  }





  public function card($data){
    $this->cardValidator($data);
    $IP = Request::ip();
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.tap.company/v2/tokens",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "{\"card\":{\"number\": ".$this->CARD_VARS['number']." ,\"exp_month\":".$this->CARD_VARS['exp_month'].",\"exp_year\":".$this->CARD_VARS['exp_year'].",\"cvc\":".$this->CARD_VARS['cvc'].",\"name\":\"".$this->CARD_VARS['name']."\",\"address\":{\"country\":\" ".$this->CARD_VARS['country']." \",\"line1\":\" ".$this->CARD_VARS['line1']." \",\"city\":\"".$this->CARD_VARS['city']."\",\"street\":\"".$this->CARD_VARS['street']."\",\"avenue\":\"".$this->CARD_VARS['avenue']."\"}},\"client_ip\":\"".$IP."\"}",
      CURLOPT_HTTPHEADER => array(
        "authorization: Bearer ".$this->CONFIG_VARS['secret_api_Key']." ",
        "content-type: application/json"
      ),
    ));



    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      throw new \InvalidArgumentException("InvalidArgumentException  $err");
    } else {
      $json_response = json_decode($response);
      if (isset($json_response->errors) && is_array($json_response->errors) && count($json_response->errors) > 0) {
        throw new \InvalidArgumentException("Error : ".$json_response->errors[0]->code." ");
      }

      if (isset($json_response->object) && $json_response->object == "token") {
        $this->CHARGE_VARS['source']['id'] = $json_response->id;
        $CARD_SET = true;
      }

    }
  }




  public function charge($data = [],$redirect = true){
    $this->chargeValidator($data);
    $curl = curl_init();
    if($this->CARD_SET){
      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.tap.company/v2/charges",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\"amount\":".$this->CHARGE_VARS['amount'].",\"currency\":\"".$this->CHARGE_VARS['currency']."\",\"threeDSecure\":".$this->CHARGE_VARS['threeDSecure'].",\"save_card\":".(string)$this->CHARGE_VARS['save_card'].",\"description\":\"".$this->CHARGE_VARS['description']."\",
          \"statement_descriptor\":\"".$this->CHARGE_VARS['statement_descriptor']."\",\"metadata\":{\"udf1\":\"".$this->CHARGE_VARS['metadata']['udf1']."\",
          \"udf2\":\"".$this->CHARGE_VARS['metadata']['udf2']."\"},\"reference\":{\"transaction\":\"".$this->CHARGE_VARS['reference']['transaction']."\",
          \"order\":\"".$this->CHARGE_VARS['reference']['order']."\"},\"receipt\":{\"email\":".$this->CHARGE_VARS['receipt']['email'].",
          \"sms\":".$this->CHARGE_VARS['receipt']['sms']."},\"customer\":{\"first_name\":\"".$this->CHARGE_VARS['customer']['first_name']."\",
          \"middle_name\":\"".$this->CHARGE_VARS['customer']['middle_name']."\",\"last_name\":\"".$this->CHARGE_VARS['customer']['last_name']."\",
          \"email\":\"".$this->CHARGE_VARS['customer']['email']."\",\"phone\":{\"country_code\":\"".$this->CHARGE_VARS['customer']['phone']['country_code']."\",
          \"number\":\"".$this->CHARGE_VARS['customer']['phone']['number']."\"}},
          \"source\":{\"object\":\"token\",\"id\":\"".$this->CHARGE_VARS['source']['id']."\"},\"post\":{\"url\":\"".$this->CHARGE_VARS['post']['url']."\"},
          \"redirect\":{\"url\":\"".$this->CHARGE_VARS['redirect']['url']."\"}}",
        CURLOPT_HTTPHEADER => array(
          "authorization: Bearer ".$this->CONFIG_VARS['secret_api_Key']." ",
          "content-type: application/json"
        ),
      ));
    }else{
      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.tap.company/v2/charges",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\"amount\":".$this->CHARGE_VARS['amount'].",\"currency\":\"".$this->CHARGE_VARS['currency']."\",\"threeDSecure\":".$this->CHARGE_VARS['threeDSecure'].",\"save_card\":".(string)$this->CHARGE_VARS['save_card'].",\"description\":\"".$this->CHARGE_VARS['description']."\",
          \"statement_descriptor\":\"".$this->CHARGE_VARS['statement_descriptor']."\",\"metadata\":{\"udf1\":\"".$this->CHARGE_VARS['metadata']['udf1']."\",
          \"udf2\":\"".$this->CHARGE_VARS['metadata']['udf2']."\"},\"reference\":{\"transaction\":\"".$this->CHARGE_VARS['reference']['transaction']."\",
          \"order\":\"".$this->CHARGE_VARS['reference']['order']."\"},\"receipt\":{\"email\":".$this->CHARGE_VARS['receipt']['email'].",
          \"sms\":".$this->CHARGE_VARS['receipt']['sms']."},\"customer\":{\"first_name\":\"".$this->CHARGE_VARS['customer']['first_name']."\",
          \"middle_name\":\"".$this->CHARGE_VARS['customer']['middle_name']."\",\"last_name\":\"".$this->CHARGE_VARS['customer']['last_name']."\",
          \"email\":\"".$this->CHARGE_VARS['customer']['email']."\",\"phone\":{\"country_code\":\"".$this->CHARGE_VARS['customer']['phone']['country_code']."\",
          \"number\":\"".$this->CHARGE_VARS['customer']['phone']['number']."\"}},\"merchant\":{\"id\":\" ".$this->CHARGE_VARS['merchant']['id']."\"},
          \"source\":{\"id\":\"".$this->CHARGE_VARS['source']['id']."\"},\"post\":{\"url\":\"".$this->CHARGE_VARS['post']['url']."\"},
          \"redirect\":{\"url\":\"".$this->CHARGE_VARS['redirect']['url']."\"}}",
        CURLOPT_HTTPHEADER => array(
          "authorization: Bearer ".$this->CONFIG_VARS['secret_api_Key']." ",
          "content-type: application/json"
        ),
      ));
    }

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      throw new \Exception("Exception  $err");
    } else {
      $json_response = json_decode($response);
      if (isset($json_response->errors) && is_array($json_response->errors) && count($json_response->errors) > 0) {
        throw new \Exception("Error : ".$json_response->errors[0]->code."");
      }
      if (isset($json_response->object) && $json_response->object == "charge" && isset($json_response->transaction->url)) {
        if($redirect){
          return redirect($json_response->transaction->url);
        }
        return $json_response;
      }else{
        throw new \Exception("Error : ".$json_response." ");
      }
    }

  }

  public function getCharge($charge_id){
    if ($charge_id != null) {
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.tap.company/v2/charges/$charge_id",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "{}",
        CURLOPT_HTTPHEADER => array(
          "authorization: Bearer ".$this->CONFIG_VARS['secret_api_Key']." ",
        ),
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

      if ($err) {
        throw new \Exception("Exception  $err");
      } else {
        $json_response = json_decode($response);
        if (isset($json_response->errors) && is_array($json_response->errors) && count($json_response->errors) > 0) {
          throw new \Exception("Error : ".$json_response->errors[0]->code." ");
        }
        if (isset($json_response->object) && $json_response->object == "charge" && isset($json_response->id)) {
          return $json_response;
        }else{
          throw new \Exception("Error : ".$response." ");
        }
      }
    }
    return false;
  }

  public function chargesList($options = array()){
    $this->chargesListValidator($options);
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.tap.company/v2/charges/list",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "{\"period\":{\"date\":{\"from\":".$this->CHARGES_FILTER['period']['date']['from'].",\"to\":".$this->CHARGES_FILTER['period']['date']['to']."}},\"status\":\" ".$this->CHARGES_FILTER['status']." \",\"limit\":".$this->CHARGES_FILTER['limit']."}",
      CURLOPT_HTTPHEADER => array(
        "authorization: Bearer ".$this->CONFIG_VARS['secret_api_Key']." ",
        "content-type: application/json"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      throw new \Exception("Exception  $err");
    } else {
      $json_response = json_decode($response);
      if (isset($json_response->errors) && is_array($json_response->errors) && count($json_response->errors) > 0) {
        throw new \Exception("Error : ".$json_response->errors[0]->code." ");
      }
      if (isset($json_response->object_type) && $json_response->object_type == "list") {
        return $json_response;
      }else{
        throw new \Exception("Error : ".$response." ");
      }
    }

    return false;
  }

  public function refund($data = []){
    $this->refungValidator($data);
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.tap.company/v2/refunds",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "{\"charge_id\":\"".$this->REFUND_VARS['charge_id']."\",\"amount\":".$this->REFUND_VARS['amount'].",\"currency\":\"".$this->REFUND_VARS['currency']."\",\"description\":\"".$this->REFUND_VARS['description']."\",\"reason\":\"".$this->REFUND_VARS['reason']."\",
        \"reference\":{\"merchant\":\"".$this->REFUND_VARS['reference']['merchant']."\"},\"metadata\":{\"udf1\":\"".$this->REFUND_VARS['metadata']['udf1']."\",\"udf2\":\"".$this->REFUND_VARS['metadata']['udf2']."\"},\"post\":{\"url\":\"".$this->REFUND_VARS['post']['url']."\"}}",
      CURLOPT_HTTPHEADER => array(
        "authorization: Bearer ".$this->CONFIG_VARS['secret_api_Key']." ",
        "content-type: application/json"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      throw new \Exception("Exception  $err");
    } else {
      $json_response = json_decode($response);
      if (isset($json_response->errors) && is_array($json_response->errors) && count($json_response->errors) > 0) {
        throw new \Exception("Error : ".$response." ");
      }
      if (isset($json_response->object) && $json_response->object == "refund") {
        return $json_response;
      }else{
        throw new \Exception("Error : ".$response." ");
      }
    }

    return false;

  }

  public function getRefund($refund_id){
    if ($refund_id == null) {
      throw new \InvalidArgumentException("InvalidArgumentException refund_id required");
    }
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.tap.company/v2/refunds/$refund_id",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_POSTFIELDS => "{}",
      CURLOPT_HTTPHEADER => array(
        "authorization: Bearer ".$this->CONFIG_VARS['secret_api_Key']." ",
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      throw new \Exception("Exception  $err");
    } else {
      $json_response = json_decode($response);
      if (isset($json_response->errors) && is_array($json_response->errors) && count($json_response->errors) > 0) {
        throw new \Exception("Error : ".$json_response->errors[0]->code." ");
      }
      if (isset($json_response->object) && $json_response->object == "refund") {
        return $json_response;
      }else{
        throw new \Exception("Error : ".$response." ");
      }
    }

    return false;
  }

  public function refundList($options = []){
    $this->refundsListValidator($options);
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.tap.company/v2/refunds/list",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "{\"period\":{\"date\":{\"from\":".$this->REFUNDS_FILTER['period']['date']['from'].",\"to\":".$this->REFUNDS_FILTER['period']['date']['to']."}},\"starting_after\":\"\",\"limit\":".$this->REFUNDS_FILTER['limit']."}",
      CURLOPT_HTTPHEADER => array(
        "authorization: Bearer ".$this->CONFIG_VARS['secret_api_Key']." ",
        "content-type: application/json"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      throw new \Exception("Exception  $err");
    } else {
      $json_response = json_decode($response);
      if (isset($json_response->errors) && is_array($json_response->errors) && count($json_response->errors) > 0) {
        throw new \Exception("Error : ".$json_response->errors[0]->code." ");
      }
      if (isset($json_response->object) && $json_response->object == "list") {
        return $json_response;
      }else{
        throw new \Exception("Error : ".$response." ");
      }
    }

    return false;
  }
}
