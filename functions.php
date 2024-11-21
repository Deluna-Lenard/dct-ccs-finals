<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Retrieve data from POST.
 */
function postData($key) {
    return $_POST[$key] ?? null;
}

/**
 * Redirect to dashboard if the user is logged in.
 */ 
function guardLogin() {
    if (isset($_SESSION['email'])) {
        header("Location: admin/dashboard.php");
        exit();
    }
}

/**
 * Redirect to login page if the user is not authenticated.
 */
function guardDashboard() {
    if (!isset($_SESSION['email'])) {
        header("Location: ../index.php");
        exit();
    }
}

/**
 * Establish and return a database connection.
 */
function getConnection() {
    $config = [
        'host' => 'localhost',
        'dbName' => 'dct-ccs-finals',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4'
    ];

    try {
        $dsn = "mysql:host={$config['host']};dbname={$config['dbName']};charset={$config['charset']}";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        return new PDO($dsn, $config['username'], $config['password'], $options);
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

/**
 * Fetch all subjects from the database.
 */
function fetchSubjects() {
    $conn = getConnection();
    $stmt = $conn->query("SELECT * FROM subjects");
    return $stmt->fetchAll();
}

/**
 * Handle user login with email and password.
 */
function login($email, $password) {
    $errors = validateLoginCredentials($email, $password);

    if (!empty($errors)) {
        echo displayErrors($errors);
        return;
    }

    $conn = getConnection();
    $hashedPassword = md5($password);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
    $stmt->execute(['email' => $email, 'password' => $hashedPassword]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['email'] = $user['email'];
        header("Location: admin/dashboard.php");
        exit();
    } else {
        echo displayErrors(["Invalid email or password."]);
    }
}

/**
 * Validate login credentials for email and password.
 */
function validateLoginCredentials($email, $password) {
    $errors = [];

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    return $errors;
}

/**
 * Display errors in a formatted Bootstrap alert.
 */
function displayErrors($errors) {
    if (empty($errors)) return "";

    $html = '<div class="col-3 mx-auto mt-2">';
    $html .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
    $html .= '<strong>System Alerts:</strong><ul>';

    foreach ($errors as $error) {
        $html .= '<li>' . htmlspecialchars($error) . '</li>';
    }

    $html .= '</ul>';
    $html .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    $html .= '</div></div>';

    return $html;
}
?>