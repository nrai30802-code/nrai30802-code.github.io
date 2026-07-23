<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

requireStudent();

$student_id = $_SESSION['student_id'];
mark_notifications_read($student_id);

setFlashMessage('success', 'All notifications marked as read.');
header("Location: ../dashboard.php");
exit;
?>
