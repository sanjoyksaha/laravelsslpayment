<?php

namespace App\Http\Controllers;

use App\Services\SSLCommerzService;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    private $config;
    private $store_id;
    private $store_password;
    private $sandbox;

    public function __construct()
    {
        $this->config = config('sslcommerz');
        $this->store_id = $this->config['store_id'];
        $this->store_password = $this->config['store_password'];
        $this->sandbox = $this->config['sandbox'];
    }

    public function initiatePayment(Request $request)
    {
        $client = new Client();
        $trx_id = uniqid();
        $data = [
            'store_id' => $this->store_id,
            'store_passwd' => $this->store_password,
            'total_amount' => $request->amount,  // Payment Amount
            'currency' => $this->config['currency'],
            'tran_id' => $trx_id,  // Unique Transaction ID
            'success_url' => url($this->config['success_url']),
            'fail_url' => url($this->config['fail_url']),
            'cancel_url' => url($this->config['cancel_url']),
            'ipn_url' => url($this->config['ipn_url']),
            "shipping_method" => "No",
            "product_name" => $this->config['product_name'],
            "product_category" => "online",
            "product_profile" => "general",
            'cus_name' => $request->name,
            'cus_email' => $request->email ?: 'sanjoyksaha92@gmail.com',
            'cus_add1' => $request->address ?: "Test",
            'cus_city' => $request->city ?: "Test",
            'cus_postcode' => $request->postcode ?: '2023',
            'cus_country' => $request->country ?: "Bangladesh" ,
            'cus_phone' => $request->phone ?: "0123456789",
        ];

        $Insert = DB::table('orders')->insert([
            'transaction_id' => $data['tran_id'],
            'customer_name' => $data['cus_name'],
            'amount' => $data['total_amount'],
            'currency' => $data['currency'],
            'status' => 'Pending',
            'payment_method' => 'SSLCommerz',
            'payment_status' => 'Initiated',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $url = $this->config['apiDomain'] . $this->config['apiUrl']['make_payment'];

        try {
            $response = $client->post($url, [
                'form_params' => $data,
            ]);

            $result = json_decode($response->getBody(), true);
//            dd($result);

            if ($result['status'] === 'SUCCESS') {
                return redirect($result['GatewayPageURL']);
            } else {
                return back()->with('error', 'Failed to initiate payment');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error occurred: ' . $e->getMessage());
        }
    }

    // Handle successful payment
    public function getPaymentStatus(Request $request)
    {
//        dd($request->all());
        $tran_id = $request['tran_id'];
        $amount = $request['amount'];
        $currency = $request['currency'];
        $status = $request['status'];

        #Check order status in order tabel against the transaction id or order id.
        $order_details = DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'amount', 'payment_status')->first();
        $msg = '';
        if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {
            /*
             That means through IPN Order status already updated. Now you can just show the customer that transaction is completed. No need to udate database.
             */
            $msg = "Transaction is successfully Completed";
        }

        $order_status = '';
        $payment_status = '';
        if ($order_details->payment_status == 'Initiated') {
            if ($status == 'VALID') {
                $service = new SSLCommerzService();
                $validate = $service->orderValidate($request->all(), $tran_id, $amount, $currency);
                if ($validate) {
                    $order_status = 'Processing';
                    $payment_status = 'Success';
                    $msg = "Transaction is successfully Completed";
                }
            } else if ($status == 'FAILED') {
                if ($order_details->status == 'Pending') {
                    $order_status = 'Pending';
                    $payment_status = 'Failed';
                    $msg = "Transaction is Falied";
                }
            } else if ($status == 'CANCELLED') {
                if ($order_details->status == 'Pending') {
                    $order_status = 'Pending';
                    $payment_status = 'Cancelled';
                    $msg = "Transaction is Cancelled";
                }
            }
        }
        else {
            $msg = 'Invalid Transaction';
        }

        DB::table('ssl_log')->insert([
            'transaction_id' => $tran_id,
            'amount' => $amount,
            'status' => $status,
            'data' => json_encode($request->all(), true),
            'created_at' => date('Y-m-d h:i:s')
        ]);

        $update_product = DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->update([
                'status' => $order_status,
                'payment_status' => $payment_status,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        return view('final_status', compact('msg'));

    }

    // Handle failed payment
    public function paymentFail(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_details = DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'amount')->first();

        if ($order_details->status == 'Pending') {
            $update_product = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->update(['status' => 'Pending', 'payment_status' => 'Failed', 'updated_at' => date('Y-m-d H:i:s')]);
            echo "Transaction is Falied";
        } else if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {
            echo "Transaction is already Successful";
        } else {
            echo "Transaction is Invalid";
        }
    }

    // Handle cancelled payment
    public function paymentCancel(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_details = DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'amount')->first();

        if ($order_details->status == 'Pending') {
            $update_product = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->update(['status' => 'Pending', 'payment_status' => 'Cancelled', 'updated_at' => date('Y-m-d H:i:s')]);
            echo "Transaction is Cancelled";
        } else if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {
            echo "Transaction is already Successful";
        } else {
            echo "Transaction is Invalid";
        }
    }

    public function IPN(Request $request)
    {
        if ($request->input('tran_id')) #Check transation id is posted or not.
        {

            $tran_id = $request->input('tran_id');
            $ipn = DB::table('ipn')->insert(['tran_id' => $tran_id]);

            #Check order status in order tabel against the transaction id or order id.
            $order_details = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->select('transaction_id', 'status', 'currency', 'amount', 'payment_status')->first();

            if ($order_details->status == 'Pending') {
                $sslc = new SSLCommerzService();
                $validation = $sslc->orderValidate($request->all(), $tran_id, $order_details->amount, $order_details->currency);
                if ($validation) {
                    /*
                    That means IPN worked. Here you need to update order status
                    in order table as Processing or Complete.
                    Here you can also sent sms or email for successful transaction to customer
                    */
                    $update_product = DB::table('orders')
                        ->where('transaction_id', $tran_id)
                        ->update(['status' => 'Processing', 'payment_status' => 'Success', 'updated_at' => date('Y-m-d H:i:s')]);

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
