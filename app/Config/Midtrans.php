<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
// PERBAIKAN: Tambahkan use statement untuk mengimpor class Config dari Midtrans
use Midtrans\Config;

class Midtrans extends BaseConfig
{
    /**
     * Kunci Server Midtrans Anda.
     * @var string
     */
    public $serverKey;

    /**
     * Kunci Klien Midtrans Anda.
     * @var string
     */
    public $clientKey;

    /**
     * Setel ke true untuk Produksi, false untuk Sandbox.
     * @var bool
     */
    public $isProduction;

    public function __construct()
    {
        parent::__construct();

        // Ambil konfigurasi dari file .env
        $this->serverKey = getenv('midtrans.serverKey');
        $this->clientKey = getenv('midtrans.clientKey');
        $this->isProduction = (bool) getenv('midtrans.isProduction');

        // Atur konfigurasi Midtrans secara global untuk library
        // PERBAIKAN: Gunakan nama class yang sudah di-import
        Config::$serverKey = $this->serverKey;
        Config::$isProduction = $this->isProduction;
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }
}