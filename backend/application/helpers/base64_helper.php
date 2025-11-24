<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Menyimpan data Base64 image ke dalam file fisik di server.
 */
function save_base64_image($base64_string, $upload_dir, $prefix = 'img')
{
    // Cek apakah string Base64 kosong
    if (empty($base64_string)) {
        return FALSE;
    }

    // 1. Ekstrak data Base64 murni dan tipe file
    // String Base64 dari Vue akan memiliki format: data:image/png;base64,iVBORw0KGgoAAA...
    if (preg_match('/^data:image\/(\w+);base64,/', $base64_string, $type)) {
        $data = substr($base64_string, strpos($base64_string, ',') + 1);
        $type = strtolower($type[1]); // e.g., png, jpeg
    } else {
        return FALSE; // Format Base64 tidak valid
    }

    // 2. Decode data Base64
    $data = base64_decode($data);

    // 3. Buat nama file unik
    // FCPATH adalah path ke root folder CI3 Anda
    $file_name = $prefix . '_' . time() . '_' . uniqid() . '.' . $type;
    $full_path = FCPATH . $upload_dir . $file_name;

    // 4. Pastikan folder tujuan ada
    if (!is_dir(FCPATH . $upload_dir)) {
        mkdir(FCPATH . $upload_dir, 0777, TRUE); // Buat folder jika belum ada
    }

    // 5. Tulis file ke server
    if (file_put_contents($full_path, $data)) {
        return $file_name; // Kembalikan hanya nama file untuk disimpan di DB
    }

    return FALSE; // Gagal menyimpan file
}
