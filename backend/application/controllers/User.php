<?php
defined('BASEPATH') or exit('No direct script access allowed');

require 'vendor/autoload.php';

use chriskacerguis\RestServer\RestController;

/**
 * Class User
 * Controller for managing the tb_user resource (CRUD)
 */
class User extends RestController
{

	/**
	 * GET User (Read)
	 * Retrieves user data by ID or all users.
	 * @param int|null $id The ID of the user to retrieve
	 * @return void
	 */
	public function index_get($id = null)
	{
		if ($id) {
			$data = $this->db->get_where('tb_user', ['id' => $id])->result();
			if (empty($data)) {
				$this->response([
					'status' => false,
					'message' => 'Data not found'
				], RestController::HTTP_NOT_FOUND); // 404 Not Found
			} else {
				$this->response($data, RestController::HTTP_OK); // 200 OK
			}
		} else {
			$data = $this->db->get('tb_user')->result();
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

		// VALIDATION 
		if (empty($name) || empty($role_id) || empty($phone) || empty($password)) {
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
			], 409); // 409 Conflict (Data duplikat)
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
			'phone' => $phone,
			'password' => password_hash($password, PASSWORD_BCRYPT),
			'is_active' => 1,
			'created_at' => date('Y-m-d H:i:s'),
			'created_by' => $this->post('created_by')
		];
		$insert = $this->db->insert('tb_user', $data);
		if (!$insert) {
			$this->response([
				'status' => false,
				'message' => 'Failed to create user'
			], RestController::HTTP_INTERNAL_ERROR); // 500 Internal Server Error
		} else {
			$this->response(
				[
					'status' => true,
					'message' => 'User created successfully'
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
	public function index_put()
	{
		$id = $this->put('id');

		// VALIDATION
		if (empty($id)) {
			$this->response([
				'status' => false,
				'message' => 'ID is required for updating user'
			], RestController::HTTP_BAD_REQUEST); // 400 Bad Request
			return;
		}

		$user_exist = $this->db->get_where('tb_user', ['id' => $id])->num_rows();

		if ($user_exist === 0) {
			$this->response([
				'status' => false,
				'message' => 'User with ID ' . $id . ' not found'
			], RestController::HTTP_NOT_FOUND); // 404 Not Found
			return;
		}

		// PROCESS
		$data = [
			'id' => $id,
			'name' => $this->put('name'),
			'role_id' => $this->put('role_id'),
			'phone' => $this->put('phone'),
			'is_active' => 1,
			'created_at' => date('Y-m-d H:i:s'),
			'created_by' => 1
		];
		$update = $this->db->update('tb_user', $data, ['id' => $this->put('id')]);
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
	public function index_delete()
	{
		$id = $this->delete('id');

		// VALIDATION
		if (empty($id)) {
			$this->response([
				'status' => false,
				'message' => 'ID is required for deleting user'
			], RestController::HTTP_BAD_REQUEST); // 400 Bad Request
			return;
		}

		$user_exist = $this->db->get_where('tb_user', ['id' => $id])->num_rows();

		if ($user_exist === 0) {
			$this->response([
				'status' => false,
				'message' => 'User with ID ' . $id . ' not found'
			], RestController::HTTP_NOT_FOUND); // 404 Not Found
			return;
		}

		// PROCESS
		$delete = $this->db->delete('tb_user', ['id' => $id]);
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
