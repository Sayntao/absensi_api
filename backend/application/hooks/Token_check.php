<?php
// application/hooks/Token_check.php

defined('BASEPATH') or exit('No direct script access allowed');

class Token_check
{
    protected $CI;

    public function __construct()
    {
        // ✅ Perbaikan 1: HANYA ambil instance CI di constructor.
        // Penghilangan $this->CI->load->library() dari sini mencegah error 'load on null'
        $this->CI = &get_instance();
    }

    public function verify_jwt()
    {
        // ✅ Perbaikan 2: Muat Library di awal fungsi hook
        // Ini memastikan $this->CI->load tersedia
        $this->CI->load->library('jwt_lib');

        // --- 1. Definisi Rute yang Dikecualikan (Public) ---
        $public_routes = [
            'auth/login',
            'auth/register',
            'corshook/setcorsheaders',
        ];

        $uri_string = $this->CI->uri->ruri_string();
        if (in_array($uri_string, $public_routes)) {
            return;
        }

        // --- 2. Ambil Header Authorization (Logika Kuat) ---
        $header = $this->CI->input->get_request_header('Authorization');

        // ✅ Perbaikan 3: Jika CI gagal mengambil header (masalah di XAMPP/Apache), 
        // kita coba ambil secara manual dari server globals.
        if (empty($header)) {
            if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
                $header = $_SERVER['HTTP_AUTHORIZATION'];
            } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                $header = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
            }
        }

        // --- 3. Verifikasi Format Token (Bearer) ---
        // Jika header kosong atau format tidak sesuai 'Bearer token', kirim 401
        if (!$header || !preg_match('/Bearer\s(\S+)/', $header, $matches)) {
            $this->unauthorized_response('Token tidak ditemukan atau format tidak valid.');
        }

        $token = $matches[1];
        $user_data = $this->CI->jwt_lib->decode_token($token);

        // --- 4. Verifikasi Keabsahan Token (Signature & Expired) ---
        if ($user_data === FALSE) {
            $this->unauthorized_response('Token tidak valid atau kadaluarsa.');
        }

        // --- 5. Token Valid: Simpan Data Pengguna ke instance CI ---
        $this->CI->auth_user = $user_data;
    }

    /**
     * Fungsi untuk mengirim respon UNAUTHORIZED (401) dan menghentikan eksekusi
     */
    protected function unauthorized_response($message)
    {
        $this->CI->output
            ->set_status_header(401)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'error',
                'message' => $message
            ]))
            ->_display();
        exit;
    }
}
