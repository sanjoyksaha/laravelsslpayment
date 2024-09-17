<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\SslCommerz\SslCommerzNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class Payment extends Controller
{

    public function exampleEasyCheckout()
    {
        return view('exampleEasycheckout');
    }

    public function exampleHostedCheckout()
    {
        return view('exampleHosted');
    }

//    public function index(Request $request)
//    {
//        # Here you have to receive all the order data to initate the payment.
//        # Let's say, your oder transaction informations are saving in a table called "orders"
//        # In "orders" table, order unique identity is "transaction_id". "status" field contain status of the transaction, "amount" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.
//
//        $post_data = array();
//        $post_data['total_amount'] = '10'; # You cant not pay less than 10
//        $post_data['currency'] = "BDT";
//        $post_data['tran_id'] = uniqid(); // tran_id must be unique
//
//        # CUSTOMER INFORMATION
//        $post_data['cus_name'] = 'Customer Name';
//        $post_data['cus_email'] = 'customer@mail.com';
//        $post_data['cus_add1'] = 'Customer Address';
//        $post_data['cus_add2'] = "";
//        $post_data['cus_city'] = "";
//        $post_data['cus_state'] = "";
//        $post_data['cus_postcode'] = "";
//        $post_data['cus_country'] = "Bangladesh";
//        $post_data['cus_phone'] = '8801XXXXXXXXX';
//        $post_data['cus_fax'] = "";
//
//        # SHIPMENT INFORMATION
//        $post_data['ship_name'] = "Store Test";
//        $post_data['ship_add1'] = "Dhaka";
//        $post_data['ship_add2'] = "Dhaka";
//        $post_data['ship_city'] = "Dhaka";
//        $post_data['ship_state'] = "Dhaka";
//        $post_data['ship_postcode'] = "1000";
//        $post_data['ship_phone'] = "";
//        $post_data['ship_country'] = "Bangladesh";
//
//        $post_data['shipping_method'] = "NO";
//        $post_data['product_name'] = "Computer";
//        $post_data['product_category'] = "Goods";
//        $post_data['product_profile'] = "physical-goods";
//
//        # OPTIONAL PARAMETERS
//        $post_data['value_a'] = "ref001";
//        $post_data['value_b'] = "ref002";
//        $post_data['value_c'] = "ref003";
//        $post_data['value_d'] = "ref004";
//
//        #Before  going to initiate the payment order status need to insert or update as Pending.
//        $update_product = DB::table('orders')
//            ->where('transaction_id', $post_data['tran_id'])
//            ->updateOrInsert([
//                'name' => $post_data['cus_name'],
//                'email' => $post_data['cus_email'],
//                'phone' => $post_data['cus_phone'],
//                'amount' => $post_data['total_amount'],
//                'status' => 'Pending',
//                'address' => $post_data['cus_add1'],
//                'transaction_id' => $post_data['tran_id'],
//                'currency' => $post_data['currency']
//            ]);
//
//        $sslc = new SslCommerzNotification();
//        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
//        $payment_options = $sslc->makePayment($post_data, 'checkout');
//
//        if (!is_array($payment_options)) {
////            print_r($payment_options);
//            $dt = json_decode($payment_options);
//            if($dt->status = 'success'){
////                echo $dt->data;
//                return redirect($dt->data);
//            }
////            $payment_options = array();
//        }
//
//    }

//    public function payViaAjax(Request $request)
//    {
//
//        # Here you have to receive all the order data to initate the payment.
//        # Lets your oder trnsaction informations are saving in a table called "orders"
//        # In orders table order uniq identity is "transaction_id","status" field contain status of the transaction, "amount" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.
//
//        $post_data = array();
//        $post_data['total_amount'] = '10'; # You cant not pay less than 10
//        $post_data['currency'] = "BDT";
//        $post_data['tran_id'] = uniqid(); // tran_id must be unique
//
//        # CUSTOMER INFORMATION
//        $post_data['cus_name'] = 'Customer Name';
//        $post_data['cus_email'] = 'customer@mail.com';
//        $post_data['cus_add1'] = 'Customer Address';
//        $post_data['cus_add2'] = "";
//        $post_data['cus_city'] = "";
//        $post_data['cus_state'] = "";
//        $post_data['cus_postcode'] = "";
//        $post_data['cus_country'] = "Bangladesh";
//        $post_data['cus_phone'] = '8801XXXXXXXXX';
//        $post_data['cus_fax'] = "";
//
//        # SHIPMENT INFORMATION
//        $post_data['ship_name'] = "Store Test";
//        $post_data['ship_add1'] = "Dhaka";
//        $post_data['ship_add2'] = "Dhaka";
//        $post_data['ship_city'] = "Dhaka";
//        $post_data['ship_state'] = "Dhaka";
//        $post_data['ship_postcode'] = "1000";
//        $post_data['ship_phone'] = "";
//        $post_data['ship_country'] = "Bangladesh";
//
//        $post_data['shipping_method'] = "NO";
//        $post_data['product_name'] = "Computer";
//        $post_data['product_category'] = "Goods";
//        $post_data['product_profile'] = "physical-goods";
//
//        # OPTIONAL PARAMETERS
//        $post_data['value_a'] = "ref001";
//        $post_data['value_b'] = "ref002";
//        $post_data['value_c'] = "ref003";
//        $post_data['value_d'] = "ref004";
//
//
//        #Before  going to initiate the payment order status need to update as Pending.
//        $update_product = DB::table('orders')
//            ->where('transaction_id', $post_data['tran_id'])
//            ->updateOrInsert([
//                'name' => $post_data['cus_name'],
//                'email' => $post_data['cus_email'],
//                'phone' => $post_data['cus_phone'],
//                'amount' => $post_data['total_amount'],
//                'status' => 'Pending',
//                'address' => $post_data['cus_add1'],
//                'transaction_id' => $post_data['tran_id'],
//                'currency' => $post_data['currency']
//            ]);
//
//        $sslc = new SslCommerzNotification();
//        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
//        $payment_options = $sslc->makePayment($post_data, 'checkout', 'json');
//
//        if (!is_array($payment_options)) {
//            print_r($payment_options);
//            $payment_options = array();
//        }
//
//    }

    public function index(Request $request)
    {
//        dd(config('sslcommerz.apiCredentials.store_id'));
        $post_data = array();
//        $post_data['store_id'] = config('sslcommerz.apiCredentials.store_id'); # You cant not pay less than 10
//        $post_data['store_passwd'] = config('sslcommerz.apiCredentials.store_id'); # You cant not pay less than 10
        $data = [
            'store_id' => "myarr66e9a6b3ca481",
            'store_passwd' => "myarr66e9a6b3ca481@ssl",
            'total_amount' => '10',
            'currency' => 'BDT',
            'tran_id' => uniqid(),
            'success_url' => 'http://127.0.0.1:8000/payment/success',
            'fail_url' => 'http://127.0.0.1:8000/payment/fail',
            'cancel_url' => 'http://127.0.0.1:8000/payment/cancel',
            'ipn_url' => 'http://127.0.0.1:8000/ipn_listen',

            'cus_name' => 'Customer Name',
            'cus_email' => 'cust@yahoo.com',
            'cus_add1' => 'Dhaka',
            'cus_add2' => 'Dhaka',
            'cus_city' => 'Dhaka',
            'cus_state' => 'Dhaka',
            'cus_postcode' => '1000',
            'cus_country' => 'Bangladesh',
            'cus_phone' => '01711111111',
            'cus_fax' => '01711111111',
            'ship_name' => 'Customer Name',
            'ship_add1' => 'Dhaka',
            'ship_add2' => 'Dhaka',
            'ship_city' => 'Dhaka',
            'ship_state' => 'Dhaka',
            'ship_postcode' => '1000',
            'ship_country' => 'Bangladesh',
            'multi_card_name' => 'mastercard,visacard,amexcard',
            'shipping_method' => "NO",
            'product_name' => "Computer",
            'product_category' => "Goods",
            'product_profile' => "physical-goods",

            'value_a' => 'ref001_A',
            'value_b' => 'ref002_B',
            'value_c' => 'ref003_C',
            'value_d' => 'ref004_D',
        ];

        $update_product = DB::table('orders')
            ->where('transaction_id', $data['tran_id'])
            ->updateOrInsert([
                'name' => $data['cus_name'],
                'email' => $data['cus_email'],
                'phone' => $data['cus_phone'],
                'amount' => $data['total_amount'],
                'status' => 'Pending',
                'address' => $data['cus_add1'],
                'transaction_id' => $data['tran_id'],
                'currency' => $data['currency']
            ]);

        $url = "https://sandbox.sslcommerz.com/gwprocess/v4/api.php";
//        dd($url);
//        dd($data);
//        $client = new Client();
//        $response = Http::post($url, $data);
//
//        $body = $response->body();
//        return $body;


        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url );
        curl_setopt($handle, CURLOPT_TIMEOUT, 30);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($handle, CURLOPT_POST, 1 );
        curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC


        $content = curl_exec($handle );

        $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        if($code == 200 && !( curl_errno($handle))) {
            curl_close( $handle);
            $sslcommerzResponse = $content;
        } else {
            curl_close( $handle);
            echo "FAILED TO CONNECT WITH SSLCOMMERZ API";
            exit;
        }

# PARSE THE JSON RESPONSE
        $sslcz = json_decode($sslcommerzResponse, true );

//        dd($sslcz);
        if(isset($sslcz['GatewayPageURL']) && $sslcz['GatewayPageURL']!="" ) {
            # THERE ARE MANY WAYS TO REDIRECT - Javascript, Meta Tag or Php Header Redirect or Other
            # echo "<script>window.location.href = '". $sslcz['GatewayPageURL'] ."';</script>";
            echo "<meta http-equiv='refresh' content='0;url=".$sslcz['GatewayPageURL']."'>";
            # header("Location: ". $sslcz['GatewayPageURL']);
            exit;
        } else {
            echo "JSON Data parsing error!";
        }
    }

    public function success(Request $request)
    {
        echo "Transaction is Successful";

        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');

        $sslc = new SslCommerzNotification();

        #Check order status in order tabel against the transaction id or order id.
        $order_details = DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'amount')->first();

        if ($order_details->status == 'Pending') {
            $validation = $sslc->orderValidate($request->all(), $tran_id, $amount, $currency);
//            $url = "https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php?val_id=".$tran_id."&store_id=myarr66e9a6b3ca481&store_passwd=myarr66e9a6b3ca481@ssl&format=json";

            if ($validation) {
                /*
                That means IPN did not work or IPN URL was not set in your merchant panel. Here you need to update order status
                in order table as Processing or Complete.
                Here you can also sent sms or email for successfull transaction to customer
                */
                $update_product = DB::table('orders')
                    ->where('transaction_id', $tran_id)
                    ->update(['status' => 'Processing']);

                echo "<br >Transaction is successfully Completed";
            }
        } else if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {
            /*
             That means through IPN Order status already updated. Now you can just show the customer that transaction is completed. No need to udate database.
             */
            echo "Transaction is successfully Completed";
        } else {
            #That means something wrong happened. You can redirect customer to your product page.
            echo "Invalid Transaction";
        }


    }

    public function fail(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_details = DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'amount')->first();

        if ($order_details->status == 'Pending') {
            $update_product = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->update(['status' => 'Failed']);
            echo "Transaction is Falied";
        } else if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {
            echo "Transaction is already Successful";
        } else {
            echo "Transaction is Invalid";
        }

    }

    public function cancel(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_details = DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'amount')->first();

        if ($order_details->status == 'Pending') {
            $update_product = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->update(['status' => 'Canceled']);
            echo "Transaction is Cancel";
        } else if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {
            echo "Transaction is already Successful";
        } else {
            echo "Transaction is Invalid";
        }


    }

    public function ipn(Request $request)
    {
        #Received all the payement information from the gateway
        if ($request->input('tran_id')) #Check transation id is posted or not.
        {

            $tran_id = $request->input('tran_id');
            $ipn = DB::table('ipn')->insert(['tran_id' => $tran_id, 'status' => json_encode($request->all(), true)]);

            #Check order status in order tabel against the transaction id or order id.
            $order_details = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->select('transaction_id', 'status', 'currency', 'amount')->first();

            if ($order_details->status == 'Pending') {
                $sslc = new SslCommerzNotification();
                $validation = $sslc->orderValidate($request->all(), $tran_id, $order_details->amount, $order_details->currency);
                if ($validation == TRUE) {
                    /*
                    That means IPN worked. Here you need to update order status
                    in order table as Processing or Complete.
                    Here you can also sent sms or email for successful transaction to customer
                    */
                    $update_product = DB::table('orders')
                        ->where('transaction_id', $tran_id)
                        ->update(['status' => 'Processing']);



                    echo "Transaction is successfully Completed";
                }
            } else if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {

                #That means Order status already updated. No need to udate database.

                echo "Transaction is already successfully Completed";
            } else {
                #That means something wrong happened. You can redirect customer to your product page.

                echo "Invalid Transaction";
            }
        } else {
            echo "Invalid Data";
        }
    }

}
