$(document).ready(function () {

    // ------------------------------
    // Step 1: Request Reset Token
    // ------------------------------
    $('#frmForgotPassword').submit(function (e) {
        e.preventDefault();

        const email = $('#email').val().trim();
        if (!email) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please enter your email'
            });
            return;
        }

        $.ajax({
            url: "controller/end-points/mailer.php",
            type: 'POST',
            data: {
                requestType: 'ForgotPassword',
                email: email
            },
            dataType: 'json',
            beforeSend: function() {
                $('#spinner').show();
            },
            success: function (res) {
                $('#spinner').hide();
                Swal.fire({
                    icon: res.status === 'success' ? 'success' : 'error',
                    title: res.status === 'success' ? 'Success' : 'Error',
                    text: res.message
                }).then(() => {
                    if (res.status === 'success') {
                        $('#frmForgotPassword').hide();
                        $('#frmResetToken').show();
                    }
                });
            },
            error: function () {
                $('#spinner').hide();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong. Try again.'
                });
            }
        });
    });

    // ------------------------------
    // Step 2: Verify Token & Update Password
    // ------------------------------
    $('#frmResetToken').submit(function (e) {
        e.preventDefault();

        const token = $('#token').val().trim();
        const new_pass = $('#new_pass').val().trim();
        const confirm_pass = $('#confirm_pass').val().trim();

        if (!token || !new_pass || !confirm_pass) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'All fields are required'
            });
            return;
        }

        if (new_pass !== confirm_pass) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Passwords do not match'
            });
            return;
        }

        $.ajax({
            url: "controller/end-points/mailer.php",
            type: 'POST',
            data: {
                requestType: 'ResetPassword',
                token: token,
                newPassword: new_pass
            },
            dataType: 'json',
            beforeSend: function() {
                $('#spinner').show();
            },
            success: function (res) {
                $('#spinner').hide();
                Swal.fire({
                    icon: res.status === 'success' ? 'success' : 'error',
                    title: res.status === 'success' ? 'Success' : 'Error',
                    text: res.message
                }).then(() => {
                    if (res.status === 'success') {
                        window.location.href = 'signin.php';
                    }
                });
            },
            error: function () {
                $('#spinner').hide();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong. Try again.'
                });
            }
        });
    });

});
