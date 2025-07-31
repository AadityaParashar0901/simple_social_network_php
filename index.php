<html>
<head>
        <title>Simple Social Network</title>
        <link rel = "stylesheet" href = "bootstrap/bootstrap.min.css">
        <script src = "bootstrap/bootstrap.bundle.min.js"></script>
        <link rel = "stylesheet" href = "https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=account_box,dark_mode,help,home,light_mode,login,logout,menu,person_add,post,post_add">
        <style>
                :root {
                        --color_1: #78B9B5;
                        --color_2: #0F828C;
                        --color_3: #065084;
                        --color_4: #320A6B;
                }
                body {
                        background-image: linear-gradient(to right bottom, var(--color_2), var(--color_3), var(--color_4));
                        transition: background-color 0.5s;
                }
                .btn-custom, .btn-custom-toggler {
                        color: #fff;
                        font-size: 20px;
                        transition: background-color 0.2s, transform 0.2s, border 0.2s;
                        background-color: var(--color_3);
                        padding: 0.5rem;
                        box-shadow: 4px 4px black;
                        border: 4px solid #00000000;
                }
                @media screen and (max-width: 576px) {
                        .btn {
                                width: 64px;
                        }
                        .modal-dialog * .btn-custom {
                                width: auto;
                        }
                        .navbar {
                                backdrop-filter: blur(5px);
                                background-color: #0000007f;
                        }
                        .navbar-collapse, .navbar-nav {
                                display: flex;
                                flex-wrap: wrap;
                                flex-direction: row;
                        }
                }
                @media screen and (min-width: 576px) {
                        .btn-custom-toggler {
                                display: none;
                        }
                }
                .btn-custom:hover, .btn-custom-toggler {
                        color: #fff;
                        border: 4px solid #003042;
                }
                .navbar {
                        z-index: 10;
                }
                .card {
                        background-color: #202020;
                }
                .card-header {
                        background-color: var(--color_1);
                        color: #000;
                        font-size: 20px;
                }
                .card-body {
                        background-color: var(--color_2);
                        color: #fff;
                        font-size: 16px;
                }
                .card-footer {
                        background-color: var(--color_3);
                        color: #fff;
                        font-size: 18px;
                }
                /* google material images */
                .material-symbols-rounded {
                        font-variation-settings:
                        'FILL' 0,
                        'wght' 400,
                        'GRAD' 0,
                        'opsz' 48
                }
        </style>
</head>
<body data-bs-theme = "light">
        <?php
                // php initialize
                session_start();
                if (!isset($_SESSION['login_status'])) $_SESSION['login_status'] = "false";
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

                // show last message
                if (isset($_SESSION['toast_message'])) if ($_SESSION['toast_message'] != "") { showToastMessage($_SESSION['toast_message']); $_SESSION['toast_message'] = ""; }
                function console_log($__output) { ?><script>console.log("<?php echo $__output; ?>");</script><?php } // for debug
                console_log("login_status: ".$_SESSION['login_status']);
                console_log("login_userID: ".$_SESSION['login_userID']);
                
                // login, register, logout, utilities
                function get_login_info() { return $_SESSION['login_status']; }
                function login($__username, $__password) {
                        global $connection;
                        $result = $connection->query("select * from users where Username = \"".$__username."\";");
                        if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                if ($row["Username"] == $__username && $row["Password"] == $__password) {
                                        $_SESSION['login_status'] = "true";
                                        $_SESSION['login_userID'] = $row["ID"];
                                        $_SESSION['toast_message'] = "Logged In";
                                } else {
                                        $_SESSION['toast_message'] = "Incorrect Password";
                                };
                        } else {
                                $_SESSION['toast_message'] = "User not found";
                        }
                        header("Location: index.php");
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
                                $_SESSION['toast_message'] = "User Registered";
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
                <div class = "toast show position-fixed mt-5 mx-3 fade" style = "z-index: 20;">
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
                        <button class = "btn btn-custom-toggler" type = "button" data-bs-toggle = "collapse" data-bs-target = "#collapsibleNavbar">
                                <span class = "material-symbols-rounded">menu</span>
                        </button>
                        <div class = "collapse navbar-collapse rounded-4" id = "collapsibleNavbar">
                                <ul class = "navbar-nav me-auto">
                                        <li class = "nav-item p-1">
                                                <a class = "rounded-3 btn btn-custom" href = "index.php"><span class = "material-symbols-rounded">home</span></a>
                                        </li>
                                        <li class = "nav-item p-1">
                                                <button type = "button" class = "rounded-3 btn btn-custom <?php if (get_login_info() == "false") echo "disabled"; ?>" data-bs-toggle = "modal" data-bs-target = "#postsModal"><span class = "material-symbols-rounded">post</span></button>
                                        </li>
                                        <li class = "nav-item p-1">
                                                <button type = "button" class = "rounded-3 btn btn-custom <?php if (get_login_info() == "false") echo "disabled"; ?>" data-bs-toggle = "modal" data-bs-target = "#accountSettingsModal"><span class = "material-symbols-rounded">account_box</span></button>
                                        </li>
                                        <li class = "nav-item p-1">
                                                <button type = "button" class = "rounded-3 btn btn-custom <?php if (get_login_info() == "false") echo "disabled"; ?>" data-bs-toggle = "modal" data-bs-target = "#addPostModal"><span class = "material-symbols-rounded">post_add</span></button>
                                        </li>
                                </ul>
                                <ul class = "navbar-nav me-2">
                                        <?php if (get_login_info() == "true") { ?>
                                                <li class = "nav-item p-1">
                                                        <button type = "button" class = "rounded-3 btn btn-custom" data-bs-toggle = "modal" data-bs-target = "#logoutModal"><span class = "material-symbols-rounded">logout</span></button>
                                                </li>
                                        <?php } else { ?>
                                                <li class = "nav-item p-1">
                                                        <button type = "button" class = "rounded-3 btn btn-custom" data-bs-toggle = "modal" data-bs-target = "#loginModal"><span class = "material-symbols-rounded">login</span></button>
                                                </li>
                                                <li class = "nav-item p-1">
                                                        <button type = "button" class = "rounded-3 btn btn-custom" data-bs-toggle = "modal" data-bs-target = "#registerModal"><span class = "material-symbols-rounded">person_add</span></button>
                                                </li>
                                        <?php } ?>
                                        <li class = "nav-item p-1">
                                                <button type = "button" class = "rounded-3 btn btn-custom" data-bs-toggle = "modal" data-bs-target = "#helpModal"><span class = "material-symbols-rounded">help</span></button>
                                        </li>
                                        <li class = "nav-item p-1">
                                                <button type = "button" class = "rounded-3 btn btn-custom" onClick = "toggleTheme();"><span class = "material-symbols-rounded" id = 'themeChangeButton'>light_mode</span></button>
                                        </li>
                                </ul>
                        </div>
                </div>
        </nav>
        <?php include "modals.php"; ?>
        <div class = "container p-5"></div>
        <!-- posts area -->
        <div class = "container d-flex justify-content-center align-items-center flex-column" id = "posts_area">
        </div>

        <!-- draw posts -->
        <?php
                function drawPost($__username, $__title, $__description, $__likescount, $__imageURL, $__liked) { ?>
                        <script>
                                document.getElementById("posts_area").innerHTML += "<div class = \"card rounded-4 w-75 mt-3 mb-3\"><img class = \"card-img-top w-100 h-100\" src = \"<?php echo $__imageURL ?>\" alt = \"Card Image\"><div class = \"card-header\"><?php echo $__title; ?></div><div class = \"card-body\"><?php echo $__description; ?></div><div class = \"card-footer d-flex justify-content-between\"><p><?php echo $__likescount; ?> likes</p><p><?php echo $__username; ?></p></div></div>";
                        </script>
                <?php }
                $result = $connection->query("select * from posts order by ID desc limit 25;");
                $currentPostsLoaded = 0;
                while ($row = $result->fetch_assoc()) {
                        drawPost($usernamesList[$row["UserID"]], $row["PostTitle"], $row["PostDescription"], $row["LikesCount"], $row['FileURL'], false);
                        $currentPostsLoaded++;
                }
                if ($currentPostsLoaded == 0) { ?>
                        <script>
                                document.getElementById("posts_area").innerHTML = "<i>No posts yet</i>"
                        </script>
                <?php }
        ?>
        <script>
                function toggleTheme() {
                        if (localStorage.getItem('theme') != "dark") {
                                localStorage.setItem('theme', "dark");
                                document.getElementsByTagName('body')[0].setAttribute("data-bs-theme", "dark");
                                document.getElementById('themeChangeButton').innerHTML = "dark_mode";
                        } else {
                                localStorage.setItem('theme', "light");
                                document.getElementsByTagName('body')[0].setAttribute("data-bs-theme", "light");
                                document.getElementById('themeChangeButton').innerHTML = "light_mode";
                        }
                }
                function setTheme() {
                        if (localStorage.getItem('theme') != "dark") {
                                document.getElementsByTagName('body')[0].setAttribute('data-bs-theme', "dark");
                                document.getElementById('themeChangeButton').innerHTML = "dark_mode";
                        } else {
                                document.getElementsByTagName('body')[0].setAttribute('data-bs-theme', "light");
                                document.getElementById('themeChangeButton').innerHTML = "light_mode";
                        }
                }
                setTheme();
        </script>
</body>
</html>