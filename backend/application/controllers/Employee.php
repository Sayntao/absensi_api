<?php
defined('BASEPATH') or exit('No direct script access allowed');

require 'vendor/autoload.php';

use chriskacerguis\RestServer\RestController;

/**
 * Class Employee
 * Controller for managing the tb_user resource (CRUD) using the Employee_model.
 */
class Employee extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Employee_model');
    }

    /**
     * GET Employee (Read)
     * Retrieves employee data by ID or all employees.
     * @param int|null $id The ID of the employee to retrieve
     * @return void
     */
    public function index_get($id = null)
    {
        if ($id) {
            $data = $this->Employee_model->get_employee_by_id($id);
            if (empty($data)) {
                $this->response([
                    'status' => false,
                    'message' => 'Data not found'
                ], RestController::HTTP_NOT_FOUND); // 404 Not Found
            } else {
                $this->response($data, RestController::HTTP_OK); // 200 OK
            }
        } else {
            $data = $this->Employee_model->get_all_employees();
            $this->response($data, RestController::HTTP_OK); // 200 OK
        }
    }


    /**
     * POST Employee (Create)
     * Creates a new employee record.
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

        $insert_id = $this->Employee_model->insert_employee($data);

        if (!$insert_id) {
            $this->response([
                'status' => false,
                'message' => 'Failed to create employee'
            ], RestController::HTTP_INTERNAL_ERROR); // 500 Internal Server Error
        } else {
            $this->response(
                [
                    'status' => true,
                    'message' => 'Employee created successfully',
                    'id' => $insert_id
                ],
                RestController::HTTP_CREATED // 201 Created
            );
        }
    }

    /**
     * PUT Employee (Update)
     * Updates employee data based on ID.
     * @return void
     */
    public function index_put()
    {
        $id = $this->put('id');

        // VALIDATION
        if (empty($id)) {
            $this->response([
                'status' => false,
                'message' => 'ID is required for updating employee'
            ], RestController::HTTP_BAD_REQUEST); // 400 Bad Request
            return;
        }

        $employee_data_exist = $this->Employee_model->get_employee_by_id($id);

        if (!$employee_data_exist) {
            $this->response([
                'status' => false,
                'message' => 'Employee with ID ' . $id . ' not found'
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

        $update = $this->Employee_model->update_employee($id, $data);

        if (!$update) {
            $this->response([
                'status' => false,
                'message' => 'Failed to update employee'
            ], RestController::HTTP_INTERNAL_ERROR); // 500 Internal Server Error
        } else {
            $this->response(
                [
                    'status' => true,
                    'message' => 'Employee updated successfully'
                ],
                RestController::HTTP_OK // 200 OK
            );
        }
    }
    /**
     * DELETE Employee (Delete)
     * Deletes a employee record based on ID.
     * @return void
     */
    public function index_delete()
    {
        $id = $this->delete('id');

        // VALIDATION
        if (empty($id)) {
            $this->response([
                'status' => false,
                'message' => 'ID is required for deleting employee'
            ], RestController::HTTP_BAD_REQUEST);
        }

        $employee_data_exist = $this->Employee_model->get_employee_by_id($id);

        if (!$employee_data_exist) {
            $this->response([
                'status' => false,
                'message' => 'Employee with ID ' . $id . ' not found'
            ], RestController::HTTP_NOT_FOUND); // 404 Not Found
            return;
        }

        // PROCESS
        $delete = $this->Employee_model->delete_employee($id);

        if (!$delete) {
            $this->response([
                'status' => false,
                'message' => 'Failed to delete employee'
            ], RestController::HTTP_INTERNAL_ERROR); // 500 Internal Server Error
        } else {
            $this->response(
                [
                    'status' => true,
                    'message' => 'Employee deleted successfully'
                ],
                RestController::HTTP_OK // 200 OK
            );
        }
    }
}
