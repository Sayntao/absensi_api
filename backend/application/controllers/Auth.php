<?php
// application/controllers/Auth.php

defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Load required libraries and models
        $this->load->library('jwt_lib');
        $this->load->model('User_model');
    }

    /**
     * Endpoint: /auth/login
     * Menangani proses login dan pembuatan token JWT
     */
    public function login()
    {
        // 1. Get data from Request Body
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        // Validate required fields
        if (!$username || !$password) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Username dan password wajib diisi.'
                ]));
        }

        // Verify credentials against database
        $user = $this->User_model->verify_credentials($username, $password);

        if ($user) {
            // 2. Prepare token payload
            $payload = [
                'user_id'  => $user->id,
                'username' => $user->username,
                'role_id'  => $user->role_id,
                'shift_id' => $user->shift_id
            ];

            // 3. Encode token (valid for 2 hours)
            $token = $this->jwt_lib->encode_token($payload, 7200);

            // 4. Send success response
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'success',
                    'message' => 'Login berhasil',
                    'user' => [
                        'id' => $user->id,
                        'username' => $user->username,
                        'role' => $user->role_name,
                        'shift_id' => $user->shift_id
                    ],
                    'token' => $token
                ]));
        } else {
            // 5. Send failure response
            $this->output
                ->set_status_header(401)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Username atau password salah.'
                ]));
        }
    }

    /**
     * Endpoint: /auth/profile
     * Endpoint yang terproteksi, memerlukan JWT yang valid.
     */
    public function profile()
    {
        // Data pengguna ($this->auth_user) tersedia karena Hook sudah memverifikasinya.
        // Jika token tidak valid, Hook akan menghentikan request sebelum sampai ke sini.

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'message' => 'Akses terproteksi berhasil. Data dari Token:',
                'user' => $this->auth_user // Mengambil data yang disimpan oleh Hook
            ]));
    }


    public function logout()
    {
        // Karena JWT bersifat stateless, logout biasanya dilakukan di sisi klien
        // dengan menghapus token yang disimpan. Namun, Anda bisa mengimplementasikan
        // blacklist token di server jika diperlukan.

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'message' => 'Logout berhasil (token dihapus di sisi klien).'
            ]));
    }
}
