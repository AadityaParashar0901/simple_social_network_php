<html>
<head>
        <title>Simple Social Network</title>
        <link rel = "stylesheet" href = "bootstrap.min.css">
        <script src = "bootstrap.bundle.min.js"></script>
        <style>
                .modal {
                        backdrop-filter: blur(12px);
                }
                .navbar {

                }
        </style>
</head>
<body data-bs-theme = "dark">
        <?php
                // php initialize
                session_start();
                if (!isset($_SESSION['login_status'])) $_SESSION['login_status'] = "FALSE";
                if (!isset($_SESSION['login_userID'])) $_SESSION['login_userID'] = "_";
                $servername = "localhost";
                $username = "ssn_user";
                $password = "ssn_password";
                $database = "ssn_db";
                $connection = mysqli_connect($servername, $username, $password, $database);
                if (!$connection) {
                        die("Connection failed: ".mysqli_connect_error());
                }

                // initialize users list
                $usernamesObject = $connection->query("select ID, Username from users;");
                $usernamesList = [];
                while ($username = $usernamesObject->fetch_assoc()) { $usernamesList[$username["ID"]] = $username["Username"]; }
                $result = $connection->query("select ID from posts order by ID desc limit 1;");
                if ($result === true) $lastPostID = $result->fetch_assoc()["ID"];
                else $lastPostID = 0;

                // show last message
                if (isset($_SESSION['toast_message'])) if ($_SESSION['toast_message'] != "") { showToastMessage($_SESSION['toast_message']); $_SESSION['toast_message'] = ""; }
                function console_log($__output) { ?><script>console.log("<?php echo $__output; ?>");</script><?php } // for debug
                console_log("login_status: ".$_SESSION['login_status']);
                console_log("login_userID: ".$_SESSION['login_userID']);
                
                // login, register utilities
                function get_login_info() { return $_SESSION['login_status']; }
                function login($__username, $__password) {
                        global $connection;
                        $result = $connection->query("select * from users where Username = \"".$__username."\" and Password = \"".$__password."\";");
                        if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                if ($row["Username"] == $__username && $row["Password"] == $__password) {
                                        $_SESSION['login_status'] = "true";
                                        $_SESSION['login_userID'] = $row["ID"];
                                        $_SESSION['toast_message'] = "Logged In";
                                        header("Location: index.php");
                                        return;
                                } else {
                                        showToastMessage("User not found");
                                };
                        } else {
                                showToastMessage("User not found");
                        }
                }
                function register($__username, $__password) {
                        global $connection;
                        global $usernamesList;
                        if (in_array($__username, $usernamesList)) {
                                showToastMessage("Username already exists");
                                return;
                        }
                        $result = $connection->query("insert into users (Username, Password) values (\"".$__username."\", \"".$__password."\");");
                        if ($result === TRUE) {
                                $_SESSION['toast_message'] = "User Registered and Logged In";
                                header("Location: index.php");
                        }
                }
                function logout() {
                        $_SESSION['login_status'] = "false";
                        $_SESSION['login_userID'] = "_";
                        $_SESSION['toast_message'] = "Logged Out";
                        header("Location: index.php");
                }
                
                // posts management
                function addPost($__title, $__description, $__file) {
                        global $connection;
                        $target_file = "posts/".basename($_FILES["add_post_file"]["name"]);
                        if (getimagesize($_FILES["add_post_file"]["tmp_name"]) !== false) {
                                if (move_uploaded_file($_FILES["add_post_file"]["tmp_name"], $target_file)) {
                                        $result = $connection->query("insert into posts (UserID, PostTitle, PostDescription, LikesCount, FileURL) values (".$_SESSION['login_userID'].", '".$__title."', '".$__description."', 0, 'posts/".$__file."')");
                                        if ($result === TRUE) {
                                                $_SESSION['toast_message'] = "Added Post Successfully";
                                        }
                                } else {
                                        $_SESSION['toast_message'] = "Sorry, there was an error uploading your file.";
                                }
                        } else {
                                $_SESSION['toast_message'] = "File is not an image.";
                        }
                        header("Location: index.php");
                }
                if (isset($_REQUEST['login_button']) && $_REQUEST['login_button'] == "true") {
                        login($_REQUEST['login_username'], $_REQUEST['login_password']);
                }
                if (isset($_REQUEST['register_button']) && $_REQUEST['register_button'] == "true") {
                        register($_REQUEST['register_username'], $_REQUEST['register_password']);
                }
                if (isset($_REQUEST['logout_button']) && $_REQUEST['logout_button'] == "true") {
                        logout();
                }
                if (isset($_REQUEST['add_post_button']) && $_REQUEST['add_post_button'] == "true") {
                        addPost($_REQUEST['add_post_title'], $_REQUEST['add_post_description'], $_FILES['add_post_file']['name']);
                }
        ?>
        <?php function showToastMessage($__toast_message) { ?>
                <div class = "toast show position-fixed mt-3 mx-3 fade" style = "z-index: 10;">
                        <div class = "toast-header">
                                <button type = "button" class = "btn btn-close" data-bs-dismiss = "toast"></button>
                        </div>
                        <div class = "toast-body">
                                <?php echo $__toast_message; ?>
                        </div>
                </div>
        <?php } ?>
        
        <!-- navigation bar -->
        <nav class = "navbar navbar-expand-sm w-100 position-fixed">
                <div class = "container-fluid">
                        <button class = "navbar-toggler" type = "button" data-bs-toggle = "collapse" data-bs-target = "#collapsibleNavbar">
                                <span class = "navbar-toggler-icon"></span>
                        </button>
                        <div class = "collapse navbar-collapse rounded-4" id = "collapsibleNavbar">
                                <ul class = "navbar-nav me-auto">
                                        <li class = "nav-item p-1">
                                                <a class = "rounded-4 btn btn-outline-warning" href = "index.php">Home</a>
                                        </li>
                                        <li class = "nav-item p-1">
                                                <button type = "button" class = "rounded-4 btn btn-outline-warning <?php if (get_login_info() == "false") echo "disabled"; ?>" data-bs-toggle = "modal" data-bs-target = "#postsModal">Posts</button>
                                        </li>
                                        <li class = "nav-item p-1">
                                                <button type = "button" class = "rounded-4 btn btn-outline-warning <?php if (get_login_info() == "false") echo "disabled"; ?>" data-bs-toggle = "modal" data-bs-target = "#accountSettingsModal">Account</button>
                                        </li>
                                        <li class = "nav-item p-1">
                                                <button type = "button" class = "rounded-4 btn btn-outline-success <?php if (get_login_info() == "false") echo "disabled"; ?>" data-bs-toggle = "modal" data-bs-target = "#addPostModal">Add Post</button>
                                        </li>
                                </ul>
                                
                                <!-- modals -->
                                <div class = "modal fade" id = "postsModal">
                                        <div class = "modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                                <div class = "modal-content">
                                                        <div class = "modal-header">
                                                                <h4 class = "modal-title">Posts</h4>
                                                                <button type = "button" class = "btn-close" data-bs-dismiss = "modal"></button>
                                                        </div>
                                                        <div class = "modal-body">
                                                                Under development
                                                        </div>
                                                        <div class = "modal-footer">
                                                                <button type = "button" class = "rounded-4 btn btn-danger" data-bs-dismiss = "modal">Close</button>
                                                        </div>
                                                </div>
                                        </div>
                                </div>
                                <div class = "modal fade" id = "accountSettingsModal">
                                        <div class = "modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                                <div class = "modal-content">
                                                        <div class = "modal-header">
                                                                <h4 class = "modal-title">Posts</h4>
                                                                <button type = "button" class = "btn-close" data-bs-dismiss = "modal"></button>
                                                        </div>
                                                        <div class = "modal-body">
                                                                Under development
                                                        </div>
                                                        <div class = "modal-footer">
                                                                <button type = "button" class = "rounded-4 btn btn-danger" data-bs-dismiss = "modal">Close</button>
                                                        </div>
                                                </div>
                                        </div>
                                </div>
                                <div class = "modal fade" id = "addPostModal">
                                        <div class = "modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                                <div class = "modal-content">
                                                        <div class = "modal-header">
                                                                <h4 class = "modal-title">Posts</h4>
                                                                <button type = "button" class = "btn-close" data-bs-dismiss = "modal"></button>
                                                        </div>
                                                        <div class = "modal-body">
                                                                <form action = "index.php" method = "post" enctype="multipart/form-data">
                                                                        <div class = "mb-3 mt-3 form-floating">
                                                                                <input type = "text" class = "form-control" id = "add_post_title" name = "add_post_title" required>
                                                                                <label for = "add_post_title">Title</label>
                                                                        </div>
                                                                        <div class = "mb-3 mt-3 form-floating">
                                                                                <textarea class = "form-control" id = "add_post_description" name = "add_post_description" required>
                                                                                </textarea>
                                                                                <label for = "add_post_description">Description</label>
                                                                        </div>
                                                                        <div class = "mb-3 mt-3">
                                                                                <label for = "add_post_file">Image</label>
                                                                                <input type="file" id="add_post_file" name="add_post_file">
                                                                        </div>
                                                                        <button type = "submit" class = "rounded-4 btn btn-success" name = "add_post_button" value = "true">Add Post</button>
                                                                </form>
                                                        </div>
                                                        <div class = "modal-footer">
                                                                <button type = "button" class = "rounded-4 btn btn-danger" data-bs-dismiss = "modal">Close</button>
                                                        </div>
                                                </div>
                                        </div>
                                </div>
                                
                                <?php if (get_login_info() == "true") { ?>
                                        <ul class = "navbar-nav me-2">
                                                <li class = "nav-item p-1">
                                                        <button type = "button" class = "rounded-4 btn btn-outline-warning" data-bs-toggle = "modal" data-bs-target = "#logoutModal">Logout</button>
                                                </li>
                                        </ul>
                                <?php } else { ?>
                                        <ul class = "navbar-nav me-2">
                                                <li class = "nav-item p-1">
                                                        <button type = "button" class = "rounded-4 btn btn-outline-warning" data-bs-toggle = "modal" data-bs-target = "#loginModal">Login</button>
                                                </li>
                                                <li class = "nav-item p-1">
                                                        <button type = "button" class = "rounded-4 btn btn-outline-success" data-bs-toggle = "modal" data-bs-target = "#registerModal">Register</button>
                                                </li>
                                                <li class = "nav-item p-1">
                                                        <button type = "button" class = "rounded-4 btn btn-outline-info" data-bs-toggle = "modal" data-bs-target = "#helpModal">Help</button>
                                                </li>
                                        </ul>
                                <?php } ?>
                        </div>
                        
                        <!-- more modals -->
                        <?php if (get_login_info() == "true") { ?>
                                <div class = "modal fade" id = "logoutModal">
                                        <div class = "modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                                <div class = "modal-content">
                                                        <div class = "modal-header">
                                                                <h4 class = "modal-title">Logout</h4>
                                                                <button type = "button" class = "btn-close" data-bs-dismiss = "modal"></button>
                                                        </div>
                                                        <div class = "modal-body">
                                                                Logout?
                                                                <form action = "index.php" method = "post">
                                                                        <button type = "submit" class = "mt-3 rounded-4 btn btn-danger w-100" name = "logout_button" value = "true">Yes</button>
                                                                </form>
                                                        </div>
                                                        <div class = "modal-footer">
                                                                <button type = "button" class = "rounded-4 btn btn-success w-100" data-bs-dismiss = "modal">Close</button>
                                                        </div>
                                                </div>
                                        </div>
                                </div>
                        <?php } else { ?>
                                <div class = "modal fade" id = "helpModal">
                                        <div class = "modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                                <div class = "modal-content">
                                                        <div class = "modal-header">
                                                                <h4 class = "modal-title">Help</h4>
                                                                <button type = "button" class = "btn-close" data-bs-dismiss = "modal"></button>
                                                        </div>
                                                        <div class = "modal-body">
                                                                This is a simple social network.
                                                        </div>
                                                        <div class = "modal-footer">
                                                                <button type = "button" class = "rounded-4 btn btn-danger" data-bs-dismiss = "modal">Close</button>
                                                        </div>
                                                </div>
                                        </div>
                                </div>
                                <div class = "modal fade" id = "loginModal">
                                        <div class = "modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                                <div class = "modal-content">
                                                        <div class = "modal-header">
                                                                <h4 class = "modal-title">Login</h4>
                                                                <button type = "button" class = "btn-close" data-bs-dismiss = "modal"></button>
                                                        </div>
                                                        <div class = "modal-body">
                                                                <form class = "was-validated" action = "index.php" method = "post">
                                                                        <div class = "mb-3 mt-3 form-floating">
                                                                                <input type = "text" class = "form-control" id = "login_username" name = "login_username" required>
                                                                                <label for = "login_username">Username</label>
                                                                                <div class = "invalid-feedback">Please fill out this field.</div>
                                                                        </div>
                                                                        <div class = "mb-3 mt-3 form-floating">
                                                                                <input type = "password" class = "form-control" id = "login_password" name = "login_password" required>
                                                                                <label for = "login_password">Password</label>
                                                                                <div class = "invalid-feedback">Please fill out this field.</div>
                                                                        </div>
                                                                        <button type = "submit" class = "rounded-4 btn btn-success" name = "login_button" value = "true">Login</button>
                                                                </form>
                                                        </div>
                                                        <div class = "modal-footer">
                                                                <button type = "button" class = "rounded-4 btn btn-danger" data-bs-dismiss = "modal">Close</button>
                                                        </div>
                                                </div>
                                        </div>
                                </div>
                                <div class = "modal fade" id = "registerModal">
                                        <div class = "modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                                <div class = "modal-content">
                                                        <div class = "modal-header">
                                                                <h4 class = "modal-title">Register</h4>
                                                                <button type = "button" class = "btn-close" data-bs-dismiss = "modal"></button>
                                                        </div>
                                                        <div class = "modal-body">
                                                                <form class = "was-validated" action = "index.php" method = "get">
                                                                        <div class = "mb-3 mt-3 form-floating">
                                                                                <input type = "text" class = "form-control" id = "register_username" name = "register_username" required>
                                                                                <label for = "register_username">Username</label>
                                                                                <div class = "invalid-feedback">Please fill out this field.</div>
                                                                        </div>
                                                                        <div class = "mb-3 mt-3 form-floating">
                                                                                <input type = "password" class = "form-control" id = "register_password" name = "register_password" required>
                                                                                <label for = "register_password">Password</label>
                                                                                <div class = "invalid-feedback">Please fill out this field.</div>
                                                                        </div>
                                                                        <button type = "submit" class = "rounded-4 btn btn-success" name = "register_button" value = "true">Register</button>
                                                                </form>
                                                        </div>
                                                        <div class = "modal-footer">
                                                                <button type = "button" class = "rounded-4 btn btn-danger" data-bs-dismiss = "modal">Close</button>
                                                        </div>
                                                </div>
                                        </div>
                                </div>
                        <?php } ?>
                </div>
        </nav>
        <div class = "container p-5"></div>
        <!-- posts area -->
        <div class = "container-fluid d-flex justify-content-center align-items-center flex-column" id = "posts_area">
        </div>

        <!-- draw posts -->
        <?php
                function drawPost($__username, $__title, $__description, $__likescount, $__imageURL) { ?>
                        <script>
                                document.getElementById("posts_area").innerHTML += "<div class = \"card w-25 rounded-4 w-50 mt-3 mb-3\"><img class = \"card-img-top w-100 h-100\" src = \"<?php echo $__imageURL ?>\" alt = \"Card Image\"><div class = \"card-header\"><?php echo $__title; ?></div><div class = \"card-body\"><?php echo $__description; ?></div><div class = \"card-footer d-flex justify-content-between\"><p><?php echo $__likescount; ?> likes</p><p><?php echo $__username; ?></p></div></div>";
                        </script>
                <?php }
                $result = $connection->query("select * from posts order by ID desc limit 25;");
                $currentPostsLoaded = 0;
                while ($row = $result->fetch_assoc()) {
                        drawPost($usernamesList[$row["UserID"]], $row["PostTitle"], $row["PostDescription"], $row["LikesCount"], $row['FileURL']);
                        $currentPostsLoaded++;
                }
                if ($currentPostsLoaded == 0) { ?>
                        <script>
                                document.getElementById("posts_area").innerHTML = "<i>No posts yet</i>"
                        </script>
                <?php }
        ?>
</body>
</html>