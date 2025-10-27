<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CorsHook
{

    public function setCorsHeaders()
    {
        // Izinkan semua domain (Untuk Development)
        // Ganti '*' dengan domain frontend Anda (misalnya: http://localhost:8080)
        header("Access-Control-Allow-Origin: *");

        // Izinkan method (GET, POST, PUT, DELETE, OPTIONS)
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

        // Izinkan header (Penting untuk JWT dan Content-Type)
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        // Tangani permintaan preflight (OPTIONS)
        // Browser mengirim OPTIONS request sebelum PUT/POST/DELETE
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit(0);
        }
    }
}
