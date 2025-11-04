<?php
defined('BASEPATH') or exit('No direct script access allowed');

require 'vendor/autoload.php';

use chriskacerguis\RestServer\RestController;

/**
 * Class User
 * Controller for managing the tb_user resource (CRUD) using the User_model.
 */
class User extends RestController
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');
	}

	/**
	 * GET User (Read)
	 * Retrieves user data by ID or all users.
	 * Menggunakan fungsi join dari User_model: get_users_with_role() dan get_user_with_join_by_id().
	 * @param int|null $id The ID of the user to retrieve
	 * @return void
	 */
	public function index_get($id = null)
	{
		if ($id) {
			$data = $this->User_model->get_user_with_join_by_id($id);
			if (empty($data)) {
				$this->response([
					'status' => false,
					'message' => 'Data not found'
				], RestController::HTTP_NOT_FOUND); // 404 Not Found
			} else {
				$this->response($data, RestController::HTTP_OK); // 200 OK
			}
		} else {
			$data = $this->User_model->get_users_with_role();
			$this->response($data, RestController::HTTP_OK); // 200 OK
		}
	}


	/**
	 * POST User (Create)
	 * Creates a new user record.
	 * @return void
	 */
	public function index_post()
	{
		$name     = $this->post('name');
		$role_id  = $this->post('role_id');
		$phone    = $this->post('phone');
		$password = $this->post('password');
		$shift_id = $this->post('shift_id');


		// VALIDATION
		if (empty($name) || empty($role_id) || empty($phone) || empty($password) || empty($shift_id)) {
			$this->response([
				'status' => false,
				'message' => 'All fields (Name, Role ID, Phone, Password, Shift ID) are required.'
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
		$role_exist = $this->db->get_where('tb_role', ['id' => $role_id])->num_rows();

		if ($role_exist === 0) {
			$this->response([
				'status' => false,
				'message' => 'Role with ID ' . $role_id . ' not found'
			], RestController::HTTP_NOT_FOUND); // 404 Not Found
			return;
		}

		// PROCESS
		$data = [
			'name' => $name,
			'role_id' => $role_id,
			'shift_id' => $shift_id,
			'phone' => $phone,
			'password' => password_hash($password, PASSWORD_BCRYPT),
			'is_active' => 1,
			'created_at' => date('Y-m-d H:i:s'),
			'created_by' => $this->post('created_by') // Default 1 jika tidak ada created_by
		];

		$insert_id = $this->User_model->insert_user($data);

		if (!$insert_id) {
			$this->response([
				'status' => false,
				'message' => 'Failed to create user'
			], RestController::HTTP_INTERNAL_ERROR); // 500 Internal Server Error
		} else {
			$this->response(
				[
					'status' => true,
					'message' => 'User created successfully',
					'id' => $insert_id
				],
				RestController::HTTP_CREATED // 201 Created
			);
		}
	}

	/**
	 * PUT User (Update)
	 * Updates user data based on ID.
	 * @return void
	 */
	public function index_put($id)
	{
		$name     = $this->put('name');
		$role_id  = $this->put('role_id');
		$phone    = $this->put('phone');
		$password = $this->put('password');
		$shift_id = $this->put('shift_id');
		// VALIDATION
		if (empty($id)) {
			$this->response([
				'status' => false,
				'message' => 'ID is required for updating user'
			], RestController::HTTP_BAD_REQUEST); // 400 Bad Request
			return;
		}

		if (empty($name) || empty($role_id) || empty($phone) || empty($shift_id)) {
			$this->response([
				'status' => false,
				'message' => 'All fields (Name, Role ID, Phone, Shift ID) are required.'
			], RestController::HTTP_BAD_REQUEST); // 400 Bad Request
			return;
		}

		$user_data_exist = $this->User_model->get_user_by_id($id);

		if (!$user_data_exist) {
			$this->response([
				'status' => false,
				'message' => 'User with ID ' . $id . ' not found'
			], RestController::HTTP_NOT_FOUND); // 404 Not Found
			return;
		}

		$role_exist = $this->db->get_where('tb_role', ['id' => $role_id])->num_rows();

		if ($role_exist === 0) {
			$this->response([
				'status' => false,
				'message' => 'Role with ID ' . $role_id . ' not found'
			], RestController::HTTP_NOT_FOUND); // 404 Not Found
			return;
		}

		if (!empty($id)) {
			$this->db->where('id !=', $id);
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
			'role_id' => $role_id,
			'shift_id' => $shift_id,
			'phone' => $phone,
			'password' => password_hash($password, PASSWORD_BCRYPT),
			'is_active' => $this->put('is_active'),
			'updated_at' => date('Y-m-d H:i:s'),
			'updated_by' => $this->put('updated_by') // Default 1 jika tidak ada created_by
		];

		if (empty($data)) {
			$this->response([
				'status' => false,
				'message' => 'No data provided for update'
			], RestController::HTTP_BAD_REQUEST); // 400 Bad Request
			return;
		}

		$update = $this->User_model->update_user($id, $data);

		if (!$update) {
			$this->response([
				'status' => false,
				'message' => 'Failed to update user'
			], RestController::HTTP_INTERNAL_ERROR); // 500 Internal Server Error
		} else {
			$this->response(
				[
					'status' => true,
					'message' => 'User updated successfully'
				],
				RestController::HTTP_OK // 200 OK
			);
		}
	}
	/**
	 * DELETE User (Delete)
	 * Deletes a user record based on ID.
	 * @return void
	 */
	public function index_delete($id)
	{

		// VALIDATION
		if (empty($id)) {
			$this->response([
				'status' => false,
				'message' => 'ID is required for deleting user'
			], RestController::HTTP_BAD_REQUEST);
		}

		$user_data_exist = $this->User_model->get_user_by_id($id);

		if (!$user_data_exist) {
			$this->response([
				'status' => false,
				'message' => 'User with ID ' . $id . ' not found'
			], RestController::HTTP_NOT_FOUND); // 404 Not Found
			return;
		}

		// PROCESS
		$delete = $this->User_model->delete_user($id);

		if (!$delete) {
			$this->response([
				'status' => false,
				'message' => 'Failed to delete user'
			], RestController::HTTP_INTERNAL_ERROR); // 500 Internal Server Error
		} else {
			$this->response(
				[
					'status' => true,
					'message' => 'User deleted successfully'
				],
				RestController::HTTP_OK // 200 OK
			);
		}
	}
}
