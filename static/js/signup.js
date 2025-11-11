$(document).ready(function () {

    $("#frmSignup").submit(function (e) {
        e.preventDefault();

        // Check if Terms and Conditions is checked
        if (!$("#terms").is(":checked")) {
            Swal.fire({
                icon: 'error',
                title: 'Terms and Conditions',
                text: 'You must agree to the Terms and Conditions before registering.',
                confirmButtonColor: '#3085d6',
            });
            return; // stop form submission
        }

        const password = $("#password").val();
        const confirmPassword = $("#confirm_password").val();

        if (password !== confirmPassword) {
            Swal.fire({
                icon: 'error',
                title: 'Passwords do not match',
                confirmButtonColor: '#3085d6',
            });
            return;
        }

        // Show Swal loader
        Swal.fire({
            title: 'Processing...',
            text: 'Please wait while we register your account.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => Swal.showLoading()
        });

        // Create FormData to support file uploads
        const formData = new FormData(this);
        formData.append('requestType', 'Register'); // ensure requestType is sent

        $.ajax({
            type: "POST",
            url: "controller/end-points/mailer.php",
            data: formData,
            processData: false, // required for FormData
            contentType: false, // required for FormData
            dataType: 'json',
            success: function (response) {
                Swal.close(); // close the loader

                if (response.status === "success") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Registration Successful',
                        text: 'Verification code sent!',
                        confirmButtonColor: '#3085d6'
                    }).then(() => {
                        window.location.href = "verification";
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Registration Failed',
                        text: response.message,
                        confirmButtonColor: '#3085d6'
                    });
                }
            },
            error: function (xhr, status, error) {
                Swal.close();
                console.error("AJAX Error:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred. Please try again.',
                    confirmButtonColor: '#3085d6'
                });
            }
        });
    });

});
