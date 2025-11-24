 <?php
    defined('BASEPATH') or exit('No direct script access allowed');

    /**
     * Class Attendance_model
     * @extends CI_Model
     * Handles all database operations for the 'attendances' table, including basic CRUD 
     */
    class Attendance_model extends CI_Model
    {

        /**
         * @var string The main table name (attendances)
         */
        private $table = 'tb_attendance';

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
         * Retrieves all attendance records.
         *
         * @return array|bool Returns an array of attendance objects on success, or FALSE on failure.
         */
        public function get_all_attendances()
        {
            $query = $this->db->get($this->table);
            return $query->result();
        }

        /**
         * Retrieves a single attendance record by their ID.
         *
         * @param int $id The attendance's primary ID.
         * @return object|bool Returns a single attendance object, or FALSE if not found.
         */
        public function get_attendance_by_id($id)
        {
            $this->db->where('id', $id);
            $query = $this->db->get($this->table);
            return $query->row();
        }

        /**
         * Inserts a new attendance record into the database.
         *
         * @param array $data An associative array containing attendance data.
         * @return int|bool The ID of the inserted record on success, or FALSE on failure.
         */
        public function insert_attendance($data)
        {
            $this->db->insert($this->table, $data);
            return $this->db->insert_id();
        }

        /**
         * Updates an existing attendance record.
         *
         * @param int $id The ID of the attendance to update.
         * @param array $data An associative array with the fields to be updated.
         * @return bool TRUE on success, FALSE on failure.
         */
        public function update_attendance($id, $data)
        {
            $this->db->where('id', $id);
            return $this->db->update($this->table, $data);
        }

        /**
         * Deletes a attendance record by ID.
         *
         * @param int $id The ID of the attendance to delete.
         * @return bool TRUE on success, FALSE on failure.
         */
        public function delete_attendance($id)
        {
            $this->db->where('id', $id);
            return $this->db->delete($this->table);
        }

        // --------------------------------------------------------------------
        // JOIN QUERIES                                                        
        // --------------------------------------------------------------------

        public function get_attendance_with_details($id)
        {
            $this->db->select('a.*, e.name as username, s.shift_name');
            $this->db->from('tb_attendance a');
            $this->db->join('tb_user e', 'a.user_id = e.id', 'left');
            $this->db->join('tb_shifts s', 'a.shift_id = s.id', 'left');
            $this->db->where('a.id', $id);
            $query = $this->db->get();
            return $query->row();
        }
    }
