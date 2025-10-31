<?php
defined('BASEPATH') or exit('No direct script access allowed');

require 'vendor/autoload.php';

use chriskacerguis\RestServer\RestController;

/**
 * Class Shift
 * Controller for managing the tb_shift resource (CRUD) using the Shift_model.
 */
class Shift extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Shift_model');
    }

    /**
     * GET Shift (Read)
     * Retrieves shift data by ID or all shifts.
     * @param int|null $id The ID of the shift to retrieve
     * @return void
     */
    public function index_get($id = null)
    {
        if ($id) {
            $data = $this->Shift_model->get_shift_by_id($id);
            if (empty($data)) {
                $this->response([
                    'status' => false,
                    'message' => 'Data not found'
                ], RestController::HTTP_NOT_FOUND); // 404 Not Found
            } else {
                $this->response($data, RestController::HTTP_OK); // 200 OK
            }
        } else {
            $data = $this->Shift_model->get_all_shifts();
            $this->response($data, RestController::HTTP_OK); // 200 OK
        }
    }


    /**
     * POST Shift (Create)
     * Creates a new shift record.
     * @return void
     */
    public function index_post()
    {
        $name     = $this->post('name');
        $phone    = $this->post('phone');
        $password = $this->post('password');

        // VALIDATION
        if (empty($name) || empty($phone) || empty($password)) {
            $this->response([
                'status' => false,
                'message' => 'All fields (Name, Role ID, Phone, Password) are required.'
            ], RestController::HTTP_BAD_REQUEST); // 400 Bad Request
            return;
        }
        $phone_exist = $this->db->get_where('tb_user', ['phone' => $phone])->num_rows();

        if ($phone_exist > 0) {
            $this->response([
                'status' => false,
                'message' => 'Phone number already exists. Please use a different number.'
            ], 409); // 409 Conflict
            return;
        }

        // PROCESS
        $data = [
            'name' => $name,
            'phone' => $phone,
            'role_id' => 3,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->post('created_by')
        ];

        $insert_id = $this->Shift_model->insert_shift($data);

        if (!$insert_id) {
            $this->response([
                'status' => false,
                'message' => 'Failed to create shift'
            ], RestController::HTTP_INTERNAL_ERROR); // 500 Internal Server Error
        } else {
            $this->response(
                [
                    'status' => true,
                    'message' => 'Shift created successfully',
                    'id' => $insert_id
                ],
                RestController::HTTP_CREATED // 201 Created
            );
        }
    }

    /**
     * PUT Shift (Update)
     * Updates shift data based on ID.
     * @return void
     */
    public function index_put()
    {
        $id = $this->put('id');

        // VALIDATION
        if (empty($id)) {
            $this->response([
                'status' => false,
                'message' => 'ID is required for updating shift'
            ], RestController::HTTP_BAD_REQUEST); // 400 Bad Request
            return;
        }

        $shift_data_exist = $this->Shift_model->get_shift_by_id($id);

        if (!$shift_data_exist) {
            $this->response([
                'status' => false,
                'message' => 'Shift with ID ' . $id . ' not found'
            ], RestController::HTTP_NOT_FOUND); // 404 Not Found
            return;
        }

        // PROCESS
        $data = [
            'name' =>   $this->put('name'),
            'role_id' =>   3,
            'phone' =>   $this->put('phone'),
            'password' =>   $this->put('password'),
            'is_active' => $this->put('is_active'),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->put('updated_by')

        ];
        if (empty($data)) {
            $this->response([
                'status' => false,
                'message' => 'No data provided for update'
            ], RestController::HTTP_BAD_REQUEST); // 400 Bad Request
            return;
        }

        $update = $this->Shift_model->update_shift($id, $data);

        if (!$update) {
            $this->response([
                'status' => false,
                'message' => 'Failed to update shift'
            ], RestController::HTTP_INTERNAL_ERROR); // 500 Internal Server Error
        } else {
            $this->response(
                [
                    'status' => true,
                    'message' => 'Shift updated successfully'
                ],
                RestController::HTTP_OK // 200 OK
            );
        }
    }
    /**
     * DELETE Shift (Delete)
     * Deletes a shift record based on ID.
     * @return void
     */
    public function index_delete()
    {
        $id = $this->delete('id');

        // VALIDATION
        if (empty($id)) {
            $this->response([
                'status' => false,
                'message' => 'ID is required for deleting shift'
            ], RestController::HTTP_BAD_REQUEST);
        }

        $shift_data_exist = $this->Shift_model->get_shift_by_id($id);

        if (!$shift_data_exist) {
            $this->response([
                'status' => false,
                'message' => 'Shift with ID ' . $id . ' not found'
            ], RestController::HTTP_NOT_FOUND); // 404 Not Found
            return;
        }

        // PROCESS
        $delete = $this->Shift_model->delete_shift($id);

        if (!$delete) {
            $this->response([
                'status' => false,
                'message' => 'Failed to delete shift'
            ], RestController::HTTP_INTERNAL_ERROR); // 500 Internal Server Error
        } else {
            $this->response(
                [
                    'status' => true,
                    'message' => 'Shift deleted successfully'
                ],
                RestController::HTTP_OK // 200 OK
            );
        }
    }
}
