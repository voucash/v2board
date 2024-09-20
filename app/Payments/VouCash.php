<?php

/**
 * 自己写别抄，抄NMB抄
 */
namespace App\Payments;
use App\Models\Order;

class VouCash {
    public function __construct($config)
    {
        $this->config = $config;
    }

    public function form()
    {
        return [
            'alias' => [
                'label' => '备注',
                'description' => '随便填，不能为空',
                'type' => 'input',
            ],
        ];
    }

    public function pay($order)
    {
        // $realorder = Order::where('trade_no', $order['trade_no'])->first();

        // var_dump($realorder);
        $price = (int)($order['total_amount'] / 100);
        $trade_no = $order['trade_no'];
        return [
            'type' => 1, // 0:qrcode 1:url
            'data' => "https://voucash.com/api/payment?amount=$price&order_id=$trade_no&currency=CNY&notify_url=".$order['notify_url']
        ];
    }

    public function notify($params)
    {

        $order = Order::where('trade_no', $params['order_id'])->first();
        if ($order && $order->status == 0) {
            
            $raw_post_data = file_get_contents('php://input');
            file_put_contents('/tmp/ipn.log', $raw_post_data);
            $ch = curl_init("https://voucash.com/api/payment/verify");
        
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $raw_post_data);
            curl_setopt($ch, CURLOPT_SSLVERSION, 6);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            // curl_setopt($ch, CURLOPT_CAINFO, $tmpfile);
            curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
            $res = curl_exec($ch);
            $info = curl_getinfo($ch);
            $http_code = $info['http_code'];
        
        
            if ( ! ($res)) {
                $errno = curl_errno($ch);
                $errstr = curl_error($ch);
                curl_close($ch);
                echo "connect error";
            }
        
            
            if ($http_code != 200) {
                curl_close($ch);
                echo "server response error";
            }
        
            curl_close($ch);
        
            if ($res == "verified" && $params['amount'] >= (int)($order['total_amount'] / 100)) {
                // echo "ok";
                // return true;
                return [
                    'trade_no' => $params['order_id'],
                    'callback_no' => $params['voucher']
                ];
            }
        }
        return false;
    }
}
