<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Kreait\Firebase\Factory;


class FirebaseController extends Controller
{

    public function index()
    {

        $firebase = (new Factory)
            ->withServiceAccount(__DIR__ . '/firebase_credentials.json')
            ->withDatabaseUri('https://ydentaldb-default-rtdb.firebaseio.com/');
        $database = $firebase->createDatabase();


        //// data -> اسم البيانات او الجدول في قاعدة البياانات
// $data=$database->getReference('data');
//  return $data->getValue();

        $database->getReference('data/sale')
            ->set([
                'amount' => 500,
                'payment_method' => 'paypal'
            ]);
    }
    // var_dump(openssl_get_cert_locations());
}

