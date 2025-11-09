<?php
// application/config/jwt.php

defined('BASEPATH') or exit('No direct script access allowed');

// GANTI INI DENGAN STRING RAHASIA YANG SANGAT PANJANG DAN ACAL!
$config['jwt_key'] = 'Kunci_Rahasia_API_Anda_Yang_Sangat_Sulit_Ditebak';
$config['jwt_alg'] = 'HS256'; // Algoritma standar untuk signature