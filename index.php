<?php
session_start();

if (isset($_GET['logout'])) {
    $_SESSION = [];
    session_destroy();
    header("Location: index.php");
    exit();
}

$users = [
    [
        "id" => 1,
        "email" => "user@example.com",
        "password" => "password",
        "name" => "Test User",
        "role" => "Member",
        "bio" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras consectetur ipsum eu consequat ullamcorper."
    ],
    [
        "id" => 2,
        "email" => "admin@example.com",
        "password" => "admin123",
        "name" => "Admin User",
        "role" => "Administrator",
        "bio" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras consectetur ipsum eu consequat ullamcorper."
    ]
];

if (isset($_SESSION['logged_in'], $_GET['user']) && $_SESSION['logged_in'] === true) {
    $userId = $_GET['user'];

    if ($userId == $_SESSION['user_id']) {
        $userName  = $_SESSION['user_name'];
        $userEmail = $_SESSION['user_email'];
        $userRole  = $_SESSION['user_role'];
        $userBio   = $_SESSION['user_bio'];
        $lastLogin = $_SESSION['last_login'];

        
        $avatarUrl = "https://ui-avatars.com/api/?name=" . urlencode($userName) . "&background=0D8ABC&color=fff&size=150";
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>User Profile</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body class="bg-light">
            <div class="container mt-5">
                <div class="card shadow p-4 text-center">
                    <img src="<?php echo $avatarUrl; ?>" 
     alt="Profile Picture" 
     class="rounded-circle mb-3" 
     style="width:80px; height:80px; object-fit:cover;">
                    <h2 class="mb-3">Welcome, <?php echo htmlspecialchars($userName); ?> 👋</h2>
                    <ul class="list-group text-start">
                        <li class="list-group-item"><strong>User ID:</strong> <?php echo htmlspecialchars($userId); ?></li>
                        <li class="list-group-item"><strong>Email:</strong> <?php echo htmlspecialchars($userEmail); ?></li>
                        <li class="list-group-item"><strong>Role:</strong> <?php echo htmlspecialchars($userRole); ?></li>
                        <li class="list-group-item"><strong>About Me:</strong> <?php echo htmlspecialchars($userBio); ?></li>
                        <li class="list-group-item"><em>Last Login:</em> <?php echo htmlspecialchars($lastLogin); ?></li>
                    </ul>
                    <a class="btn btn-danger mt-3" href="?logout=true">🚪 Logout</a>
                </div>
            </div>
        </body>
        </html>
        <?php
        exit();
    }
}

$loginError = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['psw'] ?? '';

    
    foreach ($users as $user) {
        if ($email === $user['email'] && $password === $user['password']) {
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_bio']  = $user['bio'];

            date_default_timezone_set('Asia/Manila');
            $_SESSION['last_login'] = date("Y-m-d H:i:s");

            header("Location: index.php?user=" . $_SESSION['user_id']);
            exit();
        }
    }

    $loginError = "❌ Invalid email or password.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow p-4" style="max-width: 400px; margin: auto;">
            <h2 class="mb-3">Login</h2>
            <?php if ($loginError): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($loginError); ?></div>
            <?php endif; ?>
            <form action="index.php" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label"><b>Email</b></label>
                    <input type="email" class="form-control" placeholder="Enter Email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="psw" class="form-label"><b>Password</b></label>
                    <input type="password" class="form-control" placeholder="Enter Password" name="psw" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
