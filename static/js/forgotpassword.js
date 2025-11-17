$(document).ready(function () {

    const swalDark = (icon, title, text) => {
        Swal.fire({
            icon: icon,
            title: title,
            text: text,
            background: '#1A1A1A', // dark background
            color: '#FFFFFF',        // white text
            iconColor: icon === 'success' ? '#00FF00' : '#FF5555', // green for success, red for error
            confirmButtonColor: '#FFD700', // gold button
        });
    };

    // ------------------------------
    // Step 1: Request Reset Token
    // ------------------------------
    $('#frmForgotPassword').submit(function (e) {
        e.preventDefault();

        const email = $('#email').val().trim();
        if (!email) {
            swalDark('error', 'Error', 'Please enter your email');
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
                swalDark(res.status === 'success' ? 'success' : 'error',
                         res.status === 'success' ? 'Success' : 'Error',
                         res.message).then(() => {
                    if (res.status === 'success') {
                        $('#frmForgotPassword').hide();
                        $('#frmResetToken').show();
                    }
                });
            },
            error: function () {
                $('#spinner').hide();
                swalDark('error', 'Error', 'Something went wrong. Try again.');
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
            swalDark('error', 'Error', 'All fields are required');
            return;
        }

        if (new_pass !== confirm_pass) {
            swalDark('error', 'Error', 'Passwords do not match');
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
                swalDark(res.status === 'success' ? 'success' : 'error',
                         res.status === 'success' ? 'Success' : 'Error',
                         res.message).then(() => {
                    if (res.status === 'success') {
                        window.location.href = 'signin.php';
                    }
                });
            },
            error: function () {
                $('#spinner').hide();
                swalDark('error', 'Error', 'Something went wrong. Try again.');
            }
        });
    });

});
