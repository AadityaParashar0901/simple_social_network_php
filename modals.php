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