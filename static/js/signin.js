$(document).ready(function () {

  $("#frmLogin").submit(function (e) {
    e.preventDefault();

    $('#spinner').show();
    $('#btnLogin').prop('disabled', true);

    var formData = $(this).serializeArray();
    formData.push({ name: 'requestType', value: 'Login' });
    var serializedData = $.param(formData);

    $.ajax({
      type: "POST",
      url: "controller/end-points/controller.php",
      data: serializedData,
      dataType: 'json',
      success: function (response) {
        $('#spinner').hide();
        $('#btnLogin').prop('disabled', false);

        if (response.status === "success") {
          Swal.fire({
            icon: "success",
            title: "Login Successful!",
            text: "Redirecting to your dashboard...",
            confirmButtonColor: "#28a745",
            timer: 2000,
            timerProgressBar: true,
            showConfirmButton: false
          }).then(() => {
            // ✅ Redirect based on user_type safely
            if (response.data && response.data.user_type) {
              const userType = response.data.user_type.toLowerCase(); // normalize
              if (userType === "admin") {
                window.location.href = "admin/dashboard";
              } else {
                window.location.href = "home/";
              }
            } else {
              // fallback if user_type not returned
              window.location.href = "home/";
            }
          });

        } else {
          let msg = response.message ? response.message : "Login failed. Please try again.";

          if (msg.toLowerCase().includes("awaiting")) {
            Swal.fire({
              icon: "info",
              title: "Account Pending Approval",
              text: msg,
              confirmButtonColor: "#3085d6"
            });
          } else if (msg.toLowerCase().includes("disabled")) {
            Swal.fire({
              icon: "error",
              title: "Account Disabled",
              text: msg,
              confirmButtonColor: "#d33"
            });
          } else {
            Swal.fire({
              icon: "error",
              title: "Login Failed",
              text: msg,
              confirmButtonColor: "#d33"
            });
          }
        }
      },
      error: function (xhr, status, error) {
        $('#spinner').hide();
        $('#btnLogin').prop('disabled', false);
        console.error("Error:", error);
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "An unexpected error occurred. Please try again.",
          confirmButtonColor: "#d33"
        });
      }
    });
  });

});
