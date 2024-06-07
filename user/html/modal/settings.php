<?php


$queryInfo = "SELECT user_id, email, phone_number, address FROM user_info WHERE user_id = ?";


$stmtInfo = $link->prepare($queryInfo);


$stmtInfo->bind_param("i", $verifiedUID);


$stmtInfo->execute();


$resultInfo = $stmtInfo->get_result();


if ($resultInfo->num_rows > 0) {

    $rowInfo = $resultInfo->fetch_assoc();

    // Extract the values
    $user_id = $rowInfo['user_id'];
    $email = $rowInfo['email'];
    $phone = $rowInfo['phone_number'];
    $address = $rowInfo['address'];
} else {

    echo "No user found with the provided ID.";
}


$stmtInfo->close();

?>
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
                <!-- main menu button -->
                <div>
                    <button type="button" class="btn btn-primary" id="changePasswordBtn">Change Password</button>
                </div>
                <div style="margin-top: 10px">
                    <button type="button" class="btn btn-primary" id="changeInfoBtn">Update Information</button>
                </div>



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

                <!-- Update info form -->
                <form id="changeInfoForm" action="function/settings/change-info-process.php" method="post" style="display: none;">
                    <input type="text" name="user_id" value="<?= $user_id ?>" hidden>
                    <div class="form-group">
                        <label class="details">Email</label>
                        <input type="text" name="email" class="form-control" value="<?= $email ?>">
                    </div>
                    <div class="form-group">
                        <label class="details">Phone number</label>
                        <input type="text" name="phone" class="form-control" value="<?= $phone ?>">
                    </div>
                    <div class="form-group">
                        <label class="details">Address</label>
                        <textarea name="address" class="form-control" rows="4"><?= $address ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Information</button>
                    <button type="button" class="btn btn-secondary" id="backToSettingsBtn2">Back</button>
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
   // Event listener for Change Password button
document.getElementById('changePasswordBtn').addEventListener('click', function() {
    var changePasswordForm = document.getElementById('changePasswordForm');
    var changeInfoBtn = document.getElementById('changeInfoBtn');

    // Add swipe-in animation class
    changePasswordForm.style.display = 'block';
    changePasswordForm.classList.remove('swipe-out');
    changePasswordForm.classList.add('swipe-in');

    // Hide the Change Password button
    this.style.display = 'none';

    // Hide the Update Info button
    changeInfoBtn.style.display = 'none';
});

// Back button for Change Password form
document.getElementById('backToSettingsBtn').addEventListener('click', function() {
    var changePasswordForm = document.getElementById('changePasswordForm');
    var changeInfoBtn = document.getElementById('changeInfoBtn');

    // Add swipe-out animation class
    changePasswordForm.classList.remove('swipe-in');
    changePasswordForm.classList.add('swipe-out');

    // After animation ends, hide the form and show the buttons
    changePasswordForm.addEventListener('animationend', function() {
        changePasswordForm.style.display = 'none';
        document.getElementById('changePasswordBtn').style.display = 'block';
        changeInfoBtn.style.display = 'block';
    }, {
        once: true
    });
});

// Event listener for Update Info button
document.getElementById('changeInfoBtn').addEventListener('click', function() {
    var changeInfoForm = document.getElementById('changeInfoForm');
    var changePasswordBtn = document.getElementById('changePasswordBtn');

    // Add swipe-in animation class
    changeInfoForm.style.display = 'block';
    changeInfoForm.classList.remove('swipe-out');
    changeInfoForm.classList.add('swipe-in');

    // Hide the Update Info button
    this.style.display = 'none';

    // Hide the Change Password button
    changePasswordBtn.style.display = 'none';
});

// Back button for Update Info form
document.getElementById('backToSettingsBtn2').addEventListener('click', function() {
    var changeInfoForm = document.getElementById('changeInfoForm');
    var changePasswordBtn = document.getElementById('changePasswordBtn');

    // Add swipe-out animation class
    changeInfoForm.classList.remove('swipe-in');
    changeInfoForm.classList.add('swipe-out');

    // After animation ends, hide the form and show the buttons
    changeInfoForm.addEventListener('animationend', function() {
        changeInfoForm.style.display = 'none';
        document.getElementById('changeInfoBtn').style.display = 'block';
        changePasswordBtn.style.display = 'block';
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
                          
                            }
                        }
                    } else {
                  
                    }
                }
            };
            xhr.send('currentPassword=' + encodeURIComponent(currentPassword) + '&userpass=' + encodeURIComponent(newPassword) + '&confirm_userpass=' + encodeURIComponent(confirmPassword));
        } else {
            currentPasswordError.textContent = '';
        }
    });


    // changeinfo ajax
    document.getElementById('changeInfoForm').addEventListener('submit', function(event) {
    event.preventDefault(); 

    // Get form data
    var formData = new FormData(this);

    // Send form data via AJAX
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'function/settings/change-info-process.php', true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                // Handle successful response here
                var response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    alert(response.message); 
                 
                } else {
                 
                    alert(response.message); 
             
                }
            } 
        }
    };
    xhr.send(formData);
});



    // Function to toggle password visibility
   
</script>
<script src="../assets/js/sign-up.js"></script>