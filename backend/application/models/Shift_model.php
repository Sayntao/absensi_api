 <?php
    defined('BASEPATH') or exit('No direct script access allowed');

    /**
     * Class Shift_model
     * @extends CI_Model
     * Handles all database operations for the 'shifts' table, including basic CRUD 
     * and complex queries involving the 'roles' table.
     */
    class Shift_model extends CI_Model
    {

        /**
         * @var string The main table name (shifts)
         */
        private $table = 'tb_shift';

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
         * Retrieves all shift records.
         *
         * @return array|bool Returns an array of shift objects on success, or FALSE on failure.
         */
        public function get_all_shifts()
        {
            $query = $this->db->get($this->table);
            return $query->result();
        }

        /**
         * Retrieves a single shift record by their ID.
         *
         * @param int $id The shift's primary ID.
         * @return object|bool Returns a single shift object, or FALSE if not found.
         */
        public function get_shift_by_id($id)
        {
            $this->db->where('id', $id);
            $query = $this->db->get($this->table);
            return $query->row();
        }

        /**
         * Inserts a new shift record into the database.
         *
         * @param array $data An associative array containing shift data.
         * @return int|bool The ID of the inserted record on success, or FALSE on failure.
         */
        public function insert_shift($data)
        {
            $this->db->insert($this->table, $data);
            return $this->db->insert_id();
        }

        /**
         * Updates an existing shift record.
         *
         * @param int $id The ID of the shift to update.
         * @param array $data An associative array with the fields to be updated.
         * @return bool TRUE on success, FALSE on failure.
         */
        public function update_shift($id, $data)
        {
            $this->db->where('id', $id);
            return $this->db->update($this->table, $data);
        }

        /**
         * Deletes a shift record by ID.
         *
         * @param int $id The ID of the shift to delete.
         * @return bool TRUE on success, FALSE on failure.
         */
        public function delete_shift($id)
        {
            $this->db->where('id', $id);
            return $this->db->delete($this->table);
        }
    }
