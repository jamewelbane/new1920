<!-- Modal settings -->
<div class="modal fade" id="settingsModal" tabindex="-1" role="dialog" aria-labelledby="settingsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="settingsModalLabel">Settings</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Initial Button -->
                <button type="button" class="btn btn-primary" id="changePasswordBtn">Change Password</button>

                <!-- Change password form -->
                <form id="changePasswordForm" action="function/settings/change-password-process.php" method="post" style="display: none;">
                    <div class="form-group">
                        <label for="currentPassword">Current Password</label>
                        <input type="password" class="form-control" id="currentPassword" name="currentPassword" placeholder="Enter your current password" required>
                        <div id="currentPasswordError" style="color: red; font-style: italic; font-size: 12px;"></div>
                    </div>
                    <div class="form-group">
                        <label class="details">New Password</label>
                        <div style="display: grid; grid-template-columns: 1fr auto;">
                            <input type="password" id="passwordField" name="userpass" placeholder="Enter new password" class="form-control" required>
                            <button class="showPass" type="button" onclick="togglePasswordVisibility('passwordField', 'passwordToggleIcon')">
                                <span class="far fa-eye" id="passwordToggleIcon"></span>
                            </button>
                        </div>
                        <div id="passwordError" style="color: red; font-style: italic; font-size: 12px;"></div>
                    </div>
                    <div class="form-group">
                        <label class="details">Confirm Password</label>
                        <div style="display: grid; grid-template-columns: 1fr auto;">
                            <input type="password" id="confirmPasswordField" name="confirm_userpass" placeholder="Re-type your new password" class="form-control" required>
                            <button class="showPass" type="button" onclick="togglePasswordVisibility('confirmPasswordField', 'confirmPasswordToggleIcon')">
                                <span class="far fa-eye" id="confirmPasswordToggleIcon"></span>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                    <button type="button" class="btn btn-secondary" id="backToSettingsBtn">Back</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Swipe animation */
    @keyframes swipeIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes swipeOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }

        to {
            transform: translateX(-100%);
            opacity: 0;
        }
    }

    .swipe-in {
        animation: swipeIn 0.5s forwards;
    }

    .swipe-out {
        animation: swipeOut 0.5s forwards;
    }
</style>


<script>
    document.getElementById('changePasswordBtn').addEventListener('click', function() {
        var changePasswordForm = document.getElementById('changePasswordForm');

        // Add swipe-in animation class
        changePasswordForm.style.display = 'block';
        changePasswordForm.classList.remove('swipe-out');
        changePasswordForm.classList.add('swipe-in');

        // Hide the button with swipe-out animation
        this.classList.add('swipe-out');
        this.addEventListener('animationend', function() {
            this.style.display = 'none';
        }, {
            once: true
        });
    });

    document.getElementById('backToSettingsBtn').addEventListener('click', function() {
        var changePasswordForm = document.getElementById('changePasswordForm');
        var changePasswordBtn = document.getElementById('changePasswordBtn');

        // Add swipe-out animation class
        changePasswordForm.classList.remove('swipe-in');
        changePasswordForm.classList.add('swipe-out');

        // After animation ends, hide the form and show the button
        changePasswordForm.addEventListener('animationend', function() {
            changePasswordForm.style.display = 'none';
            changePasswordBtn.classList.remove('swipe-out');
            changePasswordBtn.style.display = 'block';
            changePasswordBtn.classList.add('swipe-in');
        }, {
            once: true
        });
    });

    document.getElementById('changePasswordForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the form from submitting normally

        var currentPassword = document.getElementById('currentPassword').value;
        var newPassword = document.getElementById('passwordField').value;
        var confirmPassword = document.getElementById('confirmPasswordField').value;
        var currentPasswordError = document.getElementById('currentPasswordError');

        if (currentPassword.length > 0) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'function/settings/change-password-process.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === 'error') {
                            currentPasswordError.textContent = 'Current password is incorrect.';
                        } else {
                            currentPasswordError.textContent = '';
                            alert(response.message); // Display success or error message
                            if (response.status === 'success') {
                                // Reset the form if password changed successfully
                                document.getElementById('changePasswordForm').reset();
                                // You can also hide the form and display the button again here if needed
                            }
                        }
                    } else {
                        // Handle other status codes if necessary
                    }
                }
            };
            xhr.send('currentPassword=' + encodeURIComponent(currentPassword) + '&userpass=' + encodeURIComponent(newPassword) + '&confirm_userpass=' + encodeURIComponent(confirmPassword));
        } else {
            currentPasswordError.textContent = '';
        }
    });


    // Function to toggle password visibility
    function togglePasswordVisibility(fieldId, iconId) {
        var passwordField = document.getElementById(fieldId);
        var passwordToggleIcon = document.getElementById(iconId);

        if (passwordField.type === "password") {
            passwordField.type = "text";
            passwordToggleIcon.classList.remove("fa-eye");
            passwordToggleIcon.classList.add("fa-eye-slash");
        } else {
            passwordField.type = "password";
            passwordToggleIcon.classList.remove("fa-eye-slash");
            passwordToggleIcon.classList.add("fa-eye");
        }
    }
</script>
<script src="../assets/js/sign-up.js"></script>