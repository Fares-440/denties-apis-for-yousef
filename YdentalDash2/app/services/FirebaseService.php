<?php

namespace App\Services;

use Kreait\Firebase\Factory;

class FirebaseService
{
    protected $database;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(env('FIREBASE_CREDENTIALS'));
        $this->database = $factory->createDatabase();
    }

    public function getData($path)
    {
        return $this->database->getReference($path)->getValue();
    }

    public function setData($path, $data)
    {
        $this->database->getReference($path)->set($data);
    }
}
