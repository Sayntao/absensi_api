<?php
// application/libraries/Jwt_lib.php

defined('BASEPATH') or exit('No direct script access allowed');

// Memanggil class dari namespace Composer
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

class Jwt_lib
{

    protected $CI;
    protected $key;
    protected $alg;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->config->load('jwt', TRUE);
        $this->key = $this->CI->config->item('jwt_key', 'jwt');
        $this->alg = $this->CI->config->item('jwt_alg', 'jwt');
    }

    /**
     * Membuat token JWT
     */
    public function encode_token($data, $exp_time = 86400)
    {
        $iat = time();
        $exp = $iat + $exp_time;

        $token = [
            'iat'  => $iat,
            'exp'  => $exp,
            'data' => $data
        ];

        return JWT::encode($token, $this->key, $this->alg);
    }

    /**
     * Mendekode dan memverifikasi token
     */
    public function decode_token($token)
    {
        try {
            // Key class diperlukan untuk JWT v6+
            $decoded = JWT::decode($token, new Key($this->key, $this->alg));
            return $decoded->data;
        } catch (\Exception $e) {
            // Bisa berupa ExpiredException, SignatureInvalidException, dll.
            return FALSE;
        }
    }
}
