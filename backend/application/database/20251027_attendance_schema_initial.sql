/*
 * Database Schema for User Login and Employee Attendance System.
 *
 * This structure provides the necessary tables to manage user roles,
 * work shifts, user authentication, and daily attendance records.
 */

-- Table: tb_role
/*
 * Stores the list of user roles/permissions within the system (e.g., Admin, Employee).
 */
CREATE TABLE tb_role (
    id BIGINT NOT NULL AUTO_INCREMENT,
    role_name VARCHAR(50) NOT NULL UNIQUE,
    role_description TEXT,
    PRIMARY KEY (id)
);


-- Table: tb_user
/*
 * Stores user credentials and profile details.
 * Linked via Foreign Key (FK) to tb_role.
 * Includes self-referencing audit columns (created_by, updated_by).
 */
CREATE TABLE tb_user (
    id BIGINT NOT NULL AUTO_INCREMENT,
    role_id BIGINT NOT NULL,
    name VARCHAR(225) NOT NULL,
    phone VARCHAR(20) UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    last_login DATETIME,
    created_at DATETIME,
    created_by BIGINT,
    updated_at DATETIME,
    updated_by BIGINT,
    PRIMARY KEY (id),
    FOREIGN KEY (role_id) REFERENCES tb_role(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (created_by) REFERENCES tb_user(id) ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (updated_by) REFERENCES tb_user(id) ON DELETE SET NULL ON UPDATE CASCADE
);


-- Table: tb_shift
/*
 * Stores the defined standard work schedules/shifts.
 * Includes audit columns linked to tb_user.
 */
CREATE TABLE tb_shift (
    id BIGINT NOT NULL AUTO_INCREMENT,
    shift_name VARCHAR(255) NOT NULL ,
    check_in_time TIME NOT NULL,
    check_out_time TIME NOT NULL,
    created_at DATETIME,
    created_by BIGINT,
    updated_at DATETIME,
    updated_by BIGINT,
    PRIMARY KEY (id),
    FOREIGN KEY (created_by) REFERENCES tb_user(id) ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (updated_by) REFERENCES tb_user(id) ON DELETE SET NULL ON UPDATE CASCADE
);


-- Table: tb_attidance
/*
 * Records daily attendance events (check-in/check-out) for employees.
 * Linked via Foreign Keys (FK) to tb_user and tb_shift.
 */
CREATE TABLE tb_attidance (
    id BIGINT NOT NULL AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    shift_id BIGINT NOT NULL,
    date DATE NOT NULL,
    check_in_time TIME,
    check_out_time TIME,
    check_in_status ENUM('On Time', 'Late'),
    check_out_status ENUM('On Time', 'Early Leave', 'Overtime'),
    attedance_status ENUM('Present', 'Sick', 'Leave', 'Absent') NOT NULL,
    check_in_image VARCHAR(255),
    check_out_image VARCHAR(255),
    PRIMARY KEY (id),
    UNIQUE KEY unique_user_date (user_id, date),
    FOREIGN KEY (user_id) REFERENCES tb_user(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (shift_id) REFERENCES tb_shift(id) ON DELETE RESTRICT ON UPDATE CASCADE
);