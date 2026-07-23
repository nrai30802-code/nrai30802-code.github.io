<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'internship_tracker');

$db = null;
$is_mock = false;

try {
    // Attempt PDO Connection
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $db = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
} catch (PDOException $e) {
    // Fall back to Mock Session Database if connection fails
    $is_mock = true;
    init_mock_database();
}

/**
 * Initializes mock database in session if connection to MySQL is not available.
 */
function init_mock_database() {
    // Default Student: student@tracker.com (Password: student123)
    if (!isset($_SESSION['mock_users'])) {
        $_SESSION['mock_users'] = [
            1 => [
                'id' => 1,
                'email' => 'student@tracker.com',
                'password' => '$2y$10$fV3.vO9Gz3L2WpZ071L3H.8W9V5u.n9.o68ZgUj8b5M0mC1mC/z2G', // student123
                'full_name' => 'Neha Rai',
                'roll_no' => 'MCA-2024-089',
                'department' => 'Computer Applications',
                'phone' => '+91 9876543210',
                'profile_pic' => 'assets/images/default-avatar.png',
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];
    }

    // Default Admin: admin@tracker.com (Password: admin123)
    if (!isset($_SESSION['mock_admins'])) {
        $_SESSION['mock_admins'] = [
            1 => [
                'id' => 1,
                'email' => 'admin@tracker.com',
                'password' => '$2y$10$n7/q4504c/8Hh52a32P7UexF2KqOa4r9K41C34U67eM.WpWw9G/7a', // admin123
                'full_name' => 'System Admin',
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];
    }

    // Default Internships
    if (!isset($_SESSION['mock_internships'])) {
        $_SESSION['mock_internships'] = [
            1 => [
                'id' => 1,
                'student_id' => 1,
                'company_name' => 'Google',
                'role' => 'Software Engineering Intern',
                'duration' => '3 Months',
                'start_date' => '2026-01-01',
                'end_date' => '2026-03-31',
                'status' => 'Completed',
                'mentor_name' => 'Mr. Amit Sharma',
                'stipend' => '$50,000 / Month',
                'location' => 'Bangalore (Remote)',
                'description' => 'Assisted in building frontend components for cloud consoles and tracking application performance.',
                'certificate_path' => 'uploads/google_cert.pdf',
                'certificate_status' => 'Approved',
                'certificate_feedback' => 'Excellent performance. Keep it up!',
                'created_at' => date('Y-m-d H:i:s')
            ],
            2 => [
                'id' => 2,
                'student_id' => 1,
                'company_name' => 'Microsoft',
                'role' => 'Data Analyst Intern',
                'duration' => '6 Months',
                'start_date' => '2026-05-01',
                'end_date' => '2026-10-31',
                'status' => 'Ongoing',
                'mentor_name' => 'Dr. Rachel Green',
                'stipend' => '$60,000 / Month',
                'location' => 'Hyderabad (Hybrid)',
                'description' => 'Developing dashboard pipelines in Power BI and writing SQL data extraction jobs.',
                'certificate_path' => null,
                'certificate_status' => 'Pending',
                'certificate_feedback' => null,
                'created_at' => date('Y-m-d H:i:s')
            ],
            3 => [
                'id' => 3,
                'student_id' => 1,
                'company_name' => 'Amazon',
                'role' => 'Cloud Solutions Architecture Intern',
                'duration' => '2 Months',
                'start_date' => '2026-08-01',
                'end_date' => '2026-09-30',
                'status' => 'Interview',
                'mentor_name' => 'Mr. David Miller',
                'stipend' => 'Unpaid',
                'location' => 'Delhi NCR',
                'description' => 'Working on setting up EC2 instances and configuring VPC subnets.',
                'certificate_path' => null,
                'certificate_status' => 'Pending',
                'certificate_feedback' => null,
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];
    }

    // Default Progress Logs
    if (!isset($_SESSION['mock_progress_logs'])) {
        $_SESSION['mock_progress_logs'] = [
            1 => [
                ['status' => 'Applied', 'notes' => 'Applied via referral code.', 'updated_at' => '2026-01-01 10:00:00'],
                ['status' => 'Shortlisted', 'notes' => 'Resume shortlisted. Scheduled coding test.', 'updated_at' => '2026-01-08 14:30:00'],
                ['status' => 'Interview', 'notes' => 'Passed DSA interview and technical round.', 'updated_at' => '2026-01-15 11:00:00'],
                ['status' => 'Selected', 'notes' => 'Received offer letter.', 'updated_at' => '2026-01-20 16:00:00'],
                ['status' => 'Ongoing', 'notes' => 'Began onboarding and team alignment.', 'updated_at' => '2026-01-22 09:00:00'],
                ['status' => 'Completed', 'notes' => 'Successfully finished project and submitted report.', 'updated_at' => '2026-03-31 17:00:00']
            ],
            2 => [
                ['status' => 'Applied', 'notes' => 'Applied on career portal.', 'updated_at' => '2026-04-20 10:00:00'],
                ['status' => 'Shortlisted', 'notes' => 'Shortlisted based on SQL assessment.', 'updated_at' => '2026-04-25 15:00:00'],
                ['status' => 'Interview', 'notes' => 'Completed panel interview.', 'updated_at' => '2026-04-28 12:00:00'],
                ['status' => 'Selected', 'notes' => 'Offer received and accepted.', 'updated_at' => '2026-05-01 10:00:00'],
                ['status' => 'Ongoing', 'notes' => 'Working on power BI dashboards.', 'updated_at' => '2026-05-02 09:00:00']
            ],
            3 => [
                ['status' => 'Applied', 'notes' => 'Applied on LinkedIn.', 'updated_at' => '2026-07-10 09:30:00'],
                ['status' => 'Interview', 'notes' => 'Interview scheduled for 25th July.', 'updated_at' => '2026-07-15 14:00:00']
            ]
        ];
    }

    // Default Notifications
    if (!isset($_SESSION['mock_notifications'])) {
        $_SESSION['mock_notifications'] = [
            1 => [
                'id' => 1,
                'student_id' => 1,
                'message' => 'Welcome to the Digital Internship Tracking System! Start by adding your active internships.',
                'is_read' => 1,
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
            ],
            2 => [
                'id' => 2,
                'student_id' => 1,
                'message' => 'Your certificate for Google Software Engineering Intern has been APPROVED by the admin.',
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];
    }
}

// --------------------------------------------------------
// DATA REPOSITORY FUNCTIONS
// --------------------------------------------------------

/**
 * Student Login check
 */
function student_login($email, $password) {
    global $db, $is_mock;
    if ($is_mock) {
        foreach ($_SESSION['mock_users'] as $user) {
            if ($user['email'] === $email && password_verify($password, $user['password'])) {
                return $user;
            }
        }
        return false;
    } else {
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}

/**
 * Student Register
 */
function student_register($email, $password, $full_name, $roll_no, $department, $phone) {
    global $db, $is_mock;
    $hashed_pass = password_hash($password, PASSWORD_BCRYPT);
    if ($is_mock) {
        // Check duplicate email
        foreach ($_SESSION['mock_users'] as $user) {
            if ($user['email'] === $email || $user['roll_no'] === $roll_no) {
                return false;
            }
        }
        $new_id = count($_SESSION['mock_users']) + 1;
        $user = [
            'id' => $new_id,
            'email' => $email,
            'password' => $hashed_pass,
            'full_name' => $full_name,
            'roll_no' => $roll_no,
            'department' => $department,
            'phone' => $phone,
            'profile_pic' => 'assets/images/default-avatar.png',
            'created_at' => date('Y-m-d H:i:s')
        ];
        $_SESSION['mock_users'][$new_id] = $user;
        
        // Add welcome notification
        add_notification($new_id, "Welcome to the Digital Internship Tracking System! Set up your profile and register your first internship.");
        return $user;
    } else {
        try {
            $stmt = $db->prepare("INSERT INTO users (email, password, full_name, roll_no, department, phone) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$email, $hashed_pass, $full_name, $roll_no, $department, $phone]);
            $user_id = $db->lastInsertId();
            
            // Add notification
            add_notification($user_id, "Welcome to the Digital Internship Tracking System! Set up your profile and register your first internship.");
            
            $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }
}

/**
 * Admin Login check
 */
function admin_login($email, $password) {
    global $db, $is_mock;
    if ($is_mock) {
        foreach ($_SESSION['mock_admins'] as $admin) {
            if ($admin['email'] === $email && password_verify($password, $admin['password'])) {
                return $admin;
            }
        }
        return false;
    } else {
        $stmt = $db->prepare("SELECT * FROM admins WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch();
        if ($admin && password_verify($password, $admin['password'])) {
            return $admin;
        }
        return false;
    }
}

/**
 * Fetch Student Details
 */
function get_student_details($student_id) {
    global $db, $is_mock;
    if ($is_mock) {
        return isset($_SESSION['mock_users'][$student_id]) ? $_SESSION['mock_users'][$student_id] : null;
    } else {
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$student_id]);
        return $stmt->fetch();
    }
}

/**
 * Update Student Details
 */
function update_student_profile($student_id, $full_name, $phone, $department, $profile_pic = null) {
    global $db, $is_mock;
    if ($is_mock) {
        if (isset($_SESSION['mock_users'][$student_id])) {
            $_SESSION['mock_users'][$student_id]['full_name'] = $full_name;
            $_SESSION['mock_users'][$student_id]['phone'] = $phone;
            $_SESSION['mock_users'][$student_id]['department'] = $department;
            if ($profile_pic) {
                $_SESSION['mock_users'][$student_id]['profile_pic'] = $profile_pic;
            }
            return true;
        }
        return false;
    } else {
        if ($profile_pic) {
            $stmt = $db->prepare("UPDATE users SET full_name = ?, phone = ?, department = ?, profile_pic = ? WHERE id = ?");
            return $stmt->execute([$full_name, $phone, $department, $profile_pic, $student_id]);
        } else {
            $stmt = $db->prepare("UPDATE users SET full_name = ?, phone = ?, department = ? WHERE id = ?");
            return $stmt->execute([$full_name, $phone, $department, $student_id]);
        }
    }
}

/**
 * Get Student Internships
 */
function get_student_internships($student_id) {
    global $db, $is_mock;
    if ($is_mock) {
        $list = [];
        foreach ($_SESSION['mock_internships'] as $internship) {
            if ((int)$internship['student_id'] === (int)$student_id) {
                $list[] = $internship;
            }
        }
        return $list;
    } else {
        $stmt = $db->prepare("SELECT * FROM internships WHERE student_id = ? ORDER BY start_date DESC");
        $stmt->execute([$student_id]);
        return $stmt->fetchAll();
    }
}

/**
 * Get a specific Internship details
 */
function get_internship($internship_id) {
    global $db, $is_mock;
    if ($is_mock) {
        return isset($_SESSION['mock_internships'][$internship_id]) ? $_SESSION['mock_internships'][$internship_id] : null;
    } else {
        $stmt = $db->prepare("SELECT i.*, u.full_name as student_name, u.roll_no, u.department FROM internships i JOIN users u ON i.student_id = u.id WHERE i.id = ?");
        $stmt->execute([$internship_id]);
        return $stmt->fetch();
    }
}

/**
 * Add Internship
 */
function add_internship($student_id, $company, $role, $duration, $start_date, $end_date, $status, $mentor, $stipend, $location, $description) {
    global $db, $is_mock;
    if ($is_mock) {
        $new_id = count($_SESSION['mock_internships']) + 1;
        $internship = [
            'id' => $new_id,
            'student_id' => $student_id,
            'company_name' => $company,
            'role' => $role,
            'duration' => $duration,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'status' => $status,
            'mentor_name' => $mentor,
            'stipend' => $stipend,
            'location' => $location,
            'description' => $description,
            'certificate_path' => null,
            'certificate_status' => 'Pending',
            'certificate_feedback' => null,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $_SESSION['mock_internships'][$new_id] = $internship;

        // Log Initial Progress status
        $_SESSION['mock_progress_logs'][$new_id] = [
            ['status' => 'Applied', 'notes' => 'Internship added in system with status ' . $status, 'updated_at' => date('Y-m-d H:i:s')]
        ];
        if ($status !== 'Applied') {
            $_SESSION['mock_progress_logs'][$new_id][] = [
                'status' => $status,
                'notes' => 'Status set to ' . $status,
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }

        add_notification($student_id, "New internship added: " . $role . " at " . $company);
        return $new_id;
    } else {
        $stmt = $db->prepare("INSERT INTO internships (student_id, company_name, role, duration, start_date, end_date, status, mentor_name, stipend, location, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$student_id, $company, $role, $duration, $start_date, $end_date, $status, $mentor, $stipend, $location, $description]);
        $internship_id = $db->lastInsertId();

        // Log progress status
        $stmt_log = $db->prepare("INSERT INTO progress_logs (internship_id, status, notes) VALUES (?, ?, ?)");
        $stmt_log->execute([$internship_id, 'Applied', 'Internship added in system.']);
        if ($status !== 'Applied') {
            $stmt_log->execute([$internship_id, $status, 'Status initialized to ' . $status]);
        }

        add_notification($student_id, "New internship added: " . $role . " at " . $company);
        return $internship_id;
    }
}

/**
 * Update Internship Status
 */
function update_internship_status($internship_id, $status, $notes = '') {
    global $db, $is_mock;
    if ($is_mock) {
        if (isset($_SESSION['mock_internships'][$internship_id])) {
            $old_status = $_SESSION['mock_internships'][$internship_id]['status'];
            if ($old_status !== $status) {
                $_SESSION['mock_internships'][$internship_id]['status'] = $status;
                $_SESSION['mock_progress_logs'][$internship_id][] = [
                    'status' => $status,
                    'notes' => $notes ?: 'Status updated to ' . $status,
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                $student_id = $_SESSION['mock_internships'][$internship_id]['student_id'];
                add_notification($student_id, "Your internship status at " . $_SESSION['mock_internships'][$internship_id]['company_name'] . " has been updated to " . $status);
                return true;
            }
        }
        return false;
    } else {
        // Fetch old status
        $stmt = $db->prepare("SELECT status, student_id, company_name FROM internships WHERE id = ?");
        $stmt->execute([$internship_id]);
        $intern = $stmt->fetch();
        if ($intern && $intern['status'] !== $status) {
            $stmt_up = $db->prepare("UPDATE internships SET status = ? WHERE id = ?");
            $stmt_up->execute([$status, $internship_id]);
            
            $stmt_log = $db->prepare("INSERT INTO progress_logs (internship_id, status, notes) VALUES (?, ?, ?)");
            $stmt_log->execute([$internship_id, $status, $notes ?: 'Status updated to ' . $status]);

            add_notification($intern['student_id'], "Your internship status at " . $intern['company_name'] . " has been updated to " . $status);
            return true;
        }
        return false;
    }
}

/**
 * Get Internship Progress Logs
 */
function get_progress_logs($internship_id) {
    global $db, $is_mock;
    if ($is_mock) {
        return isset($_SESSION['mock_progress_logs'][$internship_id]) ? $_SESSION['mock_progress_logs'][$internship_id] : [];
    } else {
        $stmt = $db->prepare("SELECT * FROM progress_logs WHERE internship_id = ? ORDER BY updated_at ASC");
        $stmt->execute([$internship_id]);
        return $stmt->fetchAll();
    }
}

/**
 * Upload Certificate
 */
function upload_internship_certificate($internship_id, $file_path) {
    global $db, $is_mock;
    if ($is_mock) {
        if (isset($_SESSION['mock_internships'][$internship_id])) {
            $_SESSION['mock_internships'][$internship_id]['certificate_path'] = $file_path;
            $_SESSION['mock_internships'][$internship_id]['certificate_status'] = 'Pending';
            $_SESSION['mock_internships'][$internship_id]['certificate_feedback'] = null;
            return true;
        }
        return false;
    } else {
        $stmt = $db->prepare("UPDATE internships SET certificate_path = ?, certificate_status = 'Pending', certificate_feedback = NULL WHERE id = ?");
        return $stmt->execute([$file_path, $internship_id]);
    }
}

/**
 * Get Student Notifications
 */
function get_student_notifications($student_id) {
    global $db, $is_mock;
    if ($is_mock) {
        $list = [];
        foreach ($_SESSION['mock_notifications'] as $notify) {
            if ((int)$notify['student_id'] === (int)$student_id) {
                $list[] = $notify;
            }
        }
        // sort by date desc
        usort($list, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        return $list;
    } else {
        $stmt = $db->prepare("SELECT * FROM notifications WHERE student_id = ? ORDER BY created_at DESC");
        $stmt->execute([$student_id]);
        return $stmt->fetchAll();
    }
}

/**
 * Mark notifications as read
 */
function mark_notifications_read($student_id) {
    global $db, $is_mock;
    if ($is_mock) {
        foreach ($_SESSION['mock_notifications'] as &$notify) {
            if ((int)$notify['student_id'] === (int)$student_id) {
                $notify['is_read'] = 1;
            }
        }
        return true;
    } else {
        $stmt = $db->prepare("UPDATE notifications SET is_read = 1 WHERE student_id = ?");
        return $stmt->execute([$student_id]);
    }
}

/**
 * Add Notification
 */
function add_notification($student_id, $message) {
    global $db, $is_mock;
    if ($is_mock) {
        $new_id = count($_SESSION['mock_notifications']) + 1;
        $_SESSION['mock_notifications'][] = [
            'id' => $new_id,
            'student_id' => $student_id,
            'message' => $message,
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];
        return true;
    } else {
        $stmt = $db->prepare("INSERT INTO notifications (student_id, message) VALUES (?, ?)");
        return $stmt->execute([$student_id, $message]);
    }
}

/**
 * Admin Get All Internships & Students Data (For Dashboard)
 */
function admin_get_all_internships() {
    global $db, $is_mock;
    if ($is_mock) {
        $list = [];
        foreach ($_SESSION['mock_internships'] as $intern) {
            $student = isset($_SESSION['mock_users'][$intern['student_id']]) ? $_SESSION['mock_users'][$intern['student_id']] : ['full_name' => 'Unknown', 'roll_no' => 'N/A', 'department' => 'N/A'];
            $list[] = array_merge($intern, [
                'student_name' => $student['full_name'],
                'roll_no' => $student['roll_no'],
                'department' => $student['department']
            ]);
        }
        // sort by date desc
        usort($list, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        return $list;
    } else {
        $stmt = $db->query("SELECT i.*, u.full_name as student_name, u.roll_no, u.department FROM internships i JOIN users u ON i.student_id = u.id ORDER BY i.created_at DESC");
        return $stmt->fetchAll();
    }
}

/**
 * Admin Get All Students
 */
function admin_get_all_students() {
    global $db, $is_mock;
    if ($is_mock) {
        return array_values($_SESSION['mock_users']);
    } else {
        $stmt = $db->query("SELECT id, email, full_name, roll_no, department, phone, profile_pic, created_at FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }
}

/**
 * Admin Approve/Reject Certificate
 */
function admin_review_certificate($internship_id, $status, $feedback = '') {
    global $db, $is_mock;
    if ($is_mock) {
        if (isset($_SESSION['mock_internships'][$internship_id])) {
            $_SESSION['mock_internships'][$internship_id]['certificate_status'] = $status;
            $_SESSION['mock_internships'][$internship_id]['certificate_feedback'] = $feedback;
            
            $student_id = $_SESSION['mock_internships'][$internship_id]['student_id'];
            $company = $_SESSION['mock_internships'][$internship_id]['company_name'];
            $role = $_SESSION['mock_internships'][$internship_id]['role'];
            
            add_notification($student_id, "Your certificate for $role at $company has been " . strtoupper($status) . ". Feedback: " . ($feedback ?: 'None'));
            return true;
        }
        return false;
    } else {
        $stmt = $db->prepare("UPDATE internships SET certificate_status = ?, certificate_feedback = ? WHERE id = ?");
        $stmt->execute([$status, $feedback, $internship_id]);

        $stmt_intern = $db->prepare("SELECT student_id, company_name, role FROM internships WHERE id = ?");
        $stmt_intern->execute([$internship_id]);
        $intern = $stmt_intern->fetch();
        if ($intern) {
            add_notification($intern['student_id'], "Your certificate for " . $intern['role'] . " at " . $intern['company_name'] . " has been " . strtoupper($status) . ". Feedback: " . ($feedback ?: 'None'));
        }
        return true;
    }
}

/**
 * Helper to get Stats breakdown
 */
function get_student_stats($student_id) {
    $internships = get_student_internships($student_id);
    $stats = [
        'total' => count($internships),
        'ongoing' => 0,
        'completed' => 0,
        'pending_cert' => 0,
        'applied' => 0,
        'shortlisted' => 0,
        'interview' => 0,
        'selected' => 0
    ];

    foreach ($internships as $i) {
        switch ($i['status']) {
            case 'Ongoing':
                $stats['ongoing']++;
                break;
            case 'Completed':
                $stats['completed']++;
                break;
            case 'Applied':
                $stats['applied']++;
                break;
            case 'Shortlisted':
                $stats['shortlisted']++;
                break;
            case 'Interview':
                $stats['interview']++;
                break;
            case 'Selected':
                $stats['selected']++;
                break;
        }
        if ($i['certificate_path'] !== null && $i['certificate_status'] === 'Pending') {
            $stats['pending_cert']++;
        }
    }
    return $stats;
}
?>
