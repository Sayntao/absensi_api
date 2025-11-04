<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class User_model
 * @extends CI_Model
 * Handles all database operations for the 'users' table, including basic CRUD 
 * Where role_id = 3
 */
class Employee_model extends CI_Model
{

    /**
     * @var string The main table name (users)
     */
    private $table = 'tb_user';

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
     * Retrieves all employee records.
     *
     * @return array|bool Returns an array of employee objects on success, or FALSE on failure.
     */
    public function get_all_employees()
    {
        $this->db->where('role_id', 3);
        $query = $this->db->get($this->table);
        return $query->result();
    }

    /**
     * Retrieves a single employee record by their ID.
     *
     * @param int $id The employee's primary ID.
     * @return object|bool Returns a single employee object, or FALSE if not found.
     */
    public function get_employee_by_id($id)
    {
        $this->db->where('role_id', 3);
        $this->db->where('id', $id);
        $query = $this->db->get($this->table);
        return $query->row();
    }

    /**
     * Inserts a new employee record into the database.
     *
     * @param array $data An associative array containing employee data.
     * @return int|bool The ID of the inserted record on success, or FALSE on failure.
     */
    public function insert_employee($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Updates an existing employee record.
     *
     * @param int $id The ID of the employee to update.
     * @param array $data An associative array with the fields to be updated.
     * @return bool TRUE on success, FALSE on failure.
     */
    public function update_employee($id, $data)
    {
        $this->db->where('role_id', 3);
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Deletes a employee record by ID.
     *
     * @param int $id The ID of the employee to delete.
     * @return bool TRUE on success, FALSE on failure.
     */
    public function delete_employee($id)
    {
        $this->db->where('role_id', 3);
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }


    // --------------------------------------------------------------------
    // JOIN QUERIES
    // --------------------------------------------------------------------

    /**
     * Retrieves all employees along with their associated shift name.
     *
     * @return array|bool Returns an array of joined objects, or FALSE on failure.
     */
    public function get_employees_with_shift()
    {
        $this->db->select(
            $this->table . '.*, ' .
                $this->shift_table . '.shift_name'
        );

        $this->db->from($this->table);

        $this->db->join(
            $this->shift_table,
            $this->shift_table . '.id = ' . $this->table . '.shift_id',
            'left',
        );

        $this->db->where('role_id', 3);
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * Retrieves a single employee record by ID along with their associated shift name (JOIN).
     *
     * @param int $id The employee's primary ID.
     * @return object|bool Returns a single joined object, or FALSE if not found.
     */
    public function get_employee_with_join_by_id($id)
    {
        $this->db->select(
            $this->table . '.*, ' .
                $this->shift_table . '.shift_name'
        );

        $this->db->from($this->table);

        $this->db->join(
            $this->shift_table,
            $this->shift_table . '.id = ' . $this->table . '.shift_id',
            'left',
        );

        $this->db->where('role_id', 3);
        $this->db->where($this->table . '.id', $id);

        $query = $this->db->get();

        return $query->row();
    }
}
