<?php
defined('BASEPATH') or exit('No direct script access allowed');

require 'vendor/autoload.php';

use chriskacerguis\RestServer\RestController;

/**
 * Class Attendance
 * Controller for managing the tb_attendance resource (CRUD) using the Attendance_model.
 */
class My_Attendance extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Attendance_model');
        $this->load->model('Shift_model');
        $this->load->helper('base64'); // <-- Helper Base64 harus dimuat
        // Asumsi $this->auth_user sudah diinisialisasi di library lain.
    }

    // --- GET Attendance (Read) ---
    public function index_get()
    {
        $current_user = $this->auth_user;
        $user_id = $current_user->user_id ?? 0;

        if (empty($user_id)) {
            $this->response([
                'status' => false,
                'message' => 'User ID is required'
            ], RestController::HTTP_BAD_REQUEST); // 400 Bad Request
            return;
        }

        $data = $this->Attendance_model->get_attendance_with_details_by_user_id($user_id);
        if (empty($data)) {
            $this->response([
                'status' => false,
                'message' => 'Data not found'
            ], RestController::HTTP_NOT_FOUND); // 404 Not Found
        } else {
            $this->response($data, RestController::HTTP_OK); // 200 OK
        }
    }
}
