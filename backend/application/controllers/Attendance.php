<?php
defined('BASEPATH') or exit('No direct script access allowed');

require 'vendor/autoload.php';

use chriskacerguis\RestServer\RestController;

/**
 * Class Attendance
 * Controller for managing the tb_attendance resource (CRUD) using the Attendance_model.
 */
class Attendance extends RestController
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
    public function index_get($id = null)
    {
        if ($id) {
            $data = $this->Attendance_model->get_attendance_with_details($id);
            if (empty($data)) {
                $this->response([
                    'status' => false,
                    'message' => 'Data not found'
                ], RestController::HTTP_NOT_FOUND); // 404 Not Found
            } else {
                $this->response($data, RestController::HTTP_OK); // 200 OK
            }
        } else {
            $data = $this->Attendance_model->get_attendance_with_details();
            $this->response($data, RestController::HTTP_OK); // 200 OK
        }
    }


    // --- POST Attendance (Create) / Absen Masuk ---
    public function index_post()
    {
        $current_user = $this->auth_user;
        $user_id = $current_user->user_id ?? 0;
        $shift_id = $current_user->shift_id ?? 0;
        $date = date('Y-m-d');
        $check_in_time = date('H:i:s');

        // --- START: MODIFIKASI UNTUK BASE64 ---
        $base64_image = $this->post('check_in_image'); // Ambil Base64 dari Vue
        $upload_dir = 'uploads/absensi/'; // Folder penyimpanan foto

        // Simpan gambar Base64 menjadi file
        $file_name = save_base64_image($base64_image, $upload_dir, 'masuk');

        if (!$file_name) {
            // Jika gagal menyimpan foto (Base64 invalid / error)
            $this->response([
                'status' => false,
                'message' => 'Failed to process check-in photo. Please try again.'
            ], RestController::HTTP_BAD_REQUEST); // 400 Bad Request
            return;
        }
        // --- END: MODIFIKASI UNTUK BASE64 ---


        if ($check_in_time > $this->Shift_model->get_shift_by_id($shift_id)->check_in_time) {
            $check_in_status = 'Late';
        } else {
            $check_in_status = 'On Time';
        }

        if (empty($user_id) || empty($shift_id) || empty($date) || empty($check_in_time)) {
            $attendance_status = 'Absent';
        } else {
            $attendance_status = 'Present';
        }

        // PROCESS
        $data = [
            'user_id' => $user_id,
            'shift_id' => $shift_id,
            'date' => $date,
            'check_in_time' => $check_in_time,
            'check_in_status' => $check_in_status,
            'check_in_image' => $file_name, // <-- Simpan NAMA FILE
            'attendance_status' => $attendance_status,
        ];
        $insert_id = $this->Attendance_model->insert_attendance($data);

        if (!$insert_id) {
            $this->response([
                'status' => false,
                'message' => 'Failed to create attendance'
            ], RestController::HTTP_INTERNAL_ERROR); // 500 Internal Server Error
        } else {
            $this->response(
                [
                    'status' => true,
                    'message' => 'Attendance created successfully',
                    'id' => $insert_id
                ],
                RestController::HTTP_CREATED // 201 Created
            );
        }
    }

    // --- PUT Attendance (Update) / Absen Pulang ---
    public function index_put($id)
    {
        // VALIDATION ID
        if (empty($id)) {
            $this->response([
                'status' => false,
                'message' => 'ID is required for updating attendance'
            ], RestController::HTTP_BAD_REQUEST); // 400 Bad Request
            return;
        }



        // --- START: MODIFIKASI UNTUK BASE64 ---
        $base64_image = $this->put('check_out_image'); // Ambil Base64 dari Vue
        $upload_dir = 'uploads/absensi/'; // Folder penyimpanan foto

        // Simpan gambar Base64 menjadi file
        $file_name = save_base64_image($base64_image, $upload_dir, 'pulang');

        if (!$file_name) {
            // Jika gagal menyimpan foto
            $this->response([
                'status' => false,
                'message' => 'Absen Pulang Gagal: Failed to process check-out photo.'
            ], RestController::HTTP_BAD_REQUEST); // 400 Bad Request
            return;
        }
        // --- END: MODIFIKASI UNTUK BASE64 ---

        $current_user = $this->auth_user;
        $shift_id = $current_user->shift_id ?? 0;
        $check_out_time = date('H:i:s');

        $shift_data = $this->Shift_model->get_shift_by_id($shift_id);
        $check_out_status = 'Unknown'; // <-- Tentukan nilai default yang aman

        // Cek status pulang cepat
        if ($check_out_time <  $shift_data->check_out_time) {
            $check_out_status = 'going Early';
        } else {
            $check_out_status = 'On Time';
        }

        // PROCESS 
        $data = [
            'check_out_time' => $check_out_time,
            'check_out_status' => $check_out_status,
            'check_out_image' => $file_name // <-- Simpan NAMA FILE
        ];

        // Cek apakah ada data yang dikirim (meski di kasus ini selalu ada)
        if (empty($data)) {
            $this->response([
                'status' => false,
                'message' => 'No data provided for update'
            ], RestController::HTTP_BAD_REQUEST); // 400 Bad Request
            return;
        }

        $update = $this->Attendance_model->update_attendance($id, $data);

        if (!$update) {
            $this->response([
                'status' => false,
                'message' => 'Failed to update attendance'
            ], RestController::HTTP_INTERNAL_ERROR); // 500 Internal Server Error
        } else {
            $this->response(
                [
                    'status' => true,
                    'message' => 'Attendance updated successfully'
                ],
                RestController::HTTP_OK // 200 OK
            );
        }
    }

    // --- DELETE Attendance (Delete) ---
    public function index_delete($id)
    {
        // VALIDATION
        if (empty($id)) {
            $this->response([
                'status' => false,
                'message' => 'ID is required for deleting attendance'
            ], RestController::HTTP_BAD_REQUEST);
        }

        $attendance_data_exist = $this->Attendance_model->get_attendance_by_id($id);

        if (!$attendance_data_exist) {
            $this->response([
                'status' => false,
                'message' => 'Attendance with ID ' . $id . ' not found'
            ], RestController::HTTP_NOT_FOUND); // 404 Not Found
            return;
        }

        // PROCESS
        $delete = $this->Attendance_model->delete_attendance($id);

        if (!$delete) {
            $this->response([
                'status' => false,
                'message' => 'Failed to delete attendance'
            ], RestController::HTTP_INTERNAL_ERROR); // 500 Internal Server Error
        } else {
            $this->response(
                [
                    'status' => true,
                    'message' => 'Attendance deleted successfully'
                ],
                RestController::HTTP_OK // 200 OK
            );
        }
    }
}
