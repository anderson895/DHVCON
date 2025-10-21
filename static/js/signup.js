$(document).ready(function () {

  $("#frmSignup").submit(function (e) {
    e.preventDefault();

    // Get password values
    var password = $("#password").val();
    var confirmPassword = $("#confirm_password").val();

    // Confirm password validation
    if (password !== confirmPassword) {
      Swal.fire({
        icon: "error",
        title: "Password Mismatch",
        text: "Passwords do not match.",
        confirmButtonColor: "#d33"
      });
      return; // Stop form submission
    }

    $('#spinner').show();

    // Collect form data (including files)
    var formData = new FormData(this);
    formData.append("requestType", "SignUp");

    $.ajax({
      type: "POST",
      url: "controller/end-points/controller.php",
      data: formData,
      processData: false, // Required for file upload
      contentType: false, // Required for file upload
      dataType: "json",
      success: function (response) {
        $('#spinner').hide();

        if (response.status === "success") {
          Swal.fire({
            icon: "success",
            title: "Account Created!",
            text: "Please wait for the admin’s approval.",
            confirmButtonColor: "#28a745",
            timer: 2500,
            timerProgressBar: true,
            showConfirmButton: false
          }).then(() => {
            window.location.href = "signin";
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Registration Failed",
            text: response.message,
            confirmButtonColor: "#d33"
          });
        }
      },
      error: function (xhr, status, error) {
        $('#spinner').hide();
        console.error("Error:", error);
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "An error occurred. Please try again.",
          confirmButtonColor: "#d33"
        });
      }
    });
  });

});
