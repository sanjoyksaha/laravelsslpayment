<?php
namespace App\Services;

use GuzzleHttp\Client;

class SSLCommerzService{

    protected $config;
    protected $storeID;
    protected $storePass;
    private $error;

    public function __construct()
    {
        $this->config = config('sslcommerz');
        $this->storeID = $this->config['store_id'];
        $this->storePass = $this->config['store_password'];
    }

    public function orderValidate($postData, $trx_id, $amount, $currency){
        if ($postData == '' && $trx_id == '' && !is_array($postData)) {
            $this->error = "Please provide valid transaction ID and post request data";
            return $this->error;
        }

        return $this->validate($trx_id, $amount, $currency, $postData);
    }

    public function validate($merchant_trans_id, $merchant_trans_amount, $merchant_trans_currency, $post_data){
        if (!empty($merchant_trans_id) && !empty($merchant_trans_amount)) {

            # CALL THE FUNCTION TO CHECK THE RESULT
            $post_data['store_id'] = $this->storeID;
            $post_data['store_pass'] = $this->storePass;

            $val_id = urlencode($post_data['val_id']);
            $store_id = urlencode($this->storeID);
            $store_passwd = urlencode($this->storePass);
            $requested_url = ($this->config['apiDomain'] . $this->config['apiUrl']['order_validate'] . "?val_id=" . $val_id . "&store_id=" . $store_id . "&store_passwd=" . $store_passwd . "&v=1&format=json");
            $client = new Client();

            $response = $client->post($requested_url);
//            dd($response);
            if($response->getStatusCode() == 200) {
                $result = json_decode($response->getBody(), true);
                $status = $result['status'];

                $tran_date = $result['tran_date'];
                $tran_id = $result['tran_id'];
                $val_id = $result['val_id'];
                $amount = $result['amount'];
                $store_amount = $result['store_amount'];
                $bank_tran_id = $result['bank_tran_id'];
                $card_type = $result['card_type'];
                $currency_type = $result['currency_type'];
                $currency_amount = $result['currency_amount'];

                # ISSUER INFO
                $card_no = $result['card_no'];
                $card_issuer = $result['card_issuer'];
                $card_brand = $result['card_brand'];
                $card_issuer_country = $result['card_issuer_country'];
                $card_issuer_country_code = $result['card_issuer_country_code'];

                # API AUTHENTICATION
                $APIConnect = $result['APIConnect'];
                $validated_on = $result['validated_on'];
                $gw_version = $result['gw_version'];

                # GIVE SERVICE
                if ($status == "VALID" || $status == "VALIDATED") {
                    if ($merchant_trans_currency == "BDT") {
                        if (trim($merchant_trans_id) == trim($tran_id) && (abs($merchant_trans_amount - $amount) < 1) && trim($merchant_trans_currency) == trim('BDT')) {
                            return true;
                        } else {
                            # DATA TEMPERED
                            $this->error = "Data has been tempered";
                            return false;
                        }
                    } else {
                        //echo "trim($merchant_trans_id) == trim($tran_id) && ( abs($merchant_trans_amount-$currency_amount) < 1 ) && trim($merchant_trans_currency)==trim($currency_type)";
                        if (trim($merchant_trans_id) == trim($tran_id) && (abs($merchant_trans_amount - $currency_amount) < 1) && trim($merchant_trans_currency) == trim($currency_type)) {
                            return true;
                        } else {
                            # DATA TEMPERED
                            $this->error = "Data has been tempered";
                            return false;
                        }
                    }
                } else {
                    # FAILED TRANSACTION
                    $this->error = "Failed Transaction";
                    return false;
                }
            }
            else {
                # Failed to connect with SSLCOMMERZ
                $this->error = "Failed to connect with SSLCOMMERZ";
                return false;
            }
        } else {
            # INVALID DATA
            $this->error = "Invalid data";
            return false;
        }
    }

    public function make_payment($postData)
    {
        $postData['store_id'] = $this->storeID;
        $postData['store_passwd'] = $this->storeID;
        $postData['shipping_method'] = "No";
        $postData['product_name'] = $this->config['product_name'];
        $postData['product_category'] = "online";
        $postData['product_profile'] = "general";

        $url = $this->config['apiDomain'] . $this->config['apiUrl']['make_payment'];

        $client = new Client();

        try {
            $response = $client->post($url, [
                'form_params' => $postData,
            ]);

            if($response->getStatusCode() == 200) {
                return json_decode($response->getBody(), true);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error occurred: ' . $e->getMessage());
        }
    }
}
