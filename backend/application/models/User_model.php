<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class User_model
 * @extends CI_Model
 * Handles all database operations for the 'users' table, including basic CRUD 
 * and complex queries involving the 'roles' table.
 */
class User_model extends CI_Model
{

    /**
     * @var string The main table name (users)
     */
    private $table = 'tb_user';

    /**
     * @var string The name of the table to join (roles)
     */
    private $role_table = 'tb_role';

    /**
     * @var string The name of the table to join (shifts)
     */
    private $shift_table = 'tb_shift';

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // --------------------------------------------------------------------
    // BASIC CRUD OPERATIONS
    // --------------------------------------------------------------------

    /**
     * Retrieves all user records.
     *
     * @return array|bool Returns an array of user objects on success, or FALSE on failure.
     */
    public function get_all_users()
    {
        $query = $this->db->get($this->table);
        return $query->result();
    }

    /**
     * Retrieves a single user record by their ID.
     *
     * @param int $id The user's primary ID.
     * @return object|bool Returns a single user object, or FALSE if not found.
     */
    public function get_user_by_id($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table);
        return $query->row();
    }

    /**
     * Inserts a new user record into the database.
     *
     * @param array $data An associative array containing user data.
     * @return int|bool The ID of the inserted record on success, or FALSE on failure.
     */
    public function insert_user($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Updates an existing user record.
     *
     * @param int $id The ID of the user to update.
     * @param array $data An associative array with the fields to be updated.
     * @return bool TRUE on success, FALSE on failure.
     */
    public function update_user($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Deletes a user record by ID.
     *
     * @param int $id The ID of the user to delete.
     * @return bool TRUE on success, FALSE on failure.
     */
    public function delete_user($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }


    // --------------------------------------------------------------------
    // JOIN QUERIES
    // --------------------------------------------------------------------

    /**
     * Retrieves all users along with their associated role name.
     *
     * @return array|bool Returns an array of joined objects, or FALSE on failure.
     */
    public function get_users_with_role()
    {
        $this->db->select(
            $this->table . '.*, ' .
                $this->role_table . '.role_name, ' .
                $this->shift_table . '.shift_name'
        );

        $this->db->from($this->table);

        $this->db->join(
            $this->role_table,
            $this->role_table . '.id = ' . $this->table . '.role_id',
            'inner'
        );
        $this->db->join(
            $this->shift_table,
            $this->shift_table . '.id = ' . $this->table . '.shift_id',
            'left',
        );

        $query = $this->db->get();

        return $query->result();
    }

    /**
     * Retrieves a single user record by ID along with their associated role name (JOIN).
     *
     * @param int $id The user's primary ID.
     * @return object|bool Returns a single joined object, or FALSE if not found.
     */
    public function get_user_with_join_by_id($id)
    {
        $this->db->select(
            $this->table . '.*, ' .
                $this->role_table . '.role_name' .
                $this->shift_table . '.shift_name'

        );

        $this->db->from($this->table);

        $this->db->join(
            $this->role_table,
            $this->role_table . '.id = ' . $this->table . '.role_id',
            'inner'
        );
        $this->db->join(
            $this->shift_table,
            $this->shift_table . '.id = ' . $this->table . '.shift_id',
            'left',
        );


        $this->db->where($this->table . '.id', $id);

        $query = $this->db->get();

        return $query->row();
    }
}
