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
        console.log(response.status);

        if (response.status === "success") {
            alertify.success('Login Successful');
            
            setTimeout(function () {
                window.location.href = "home/"; 
            }, 1000);

        } else {
            $('#spinner').hide();
            $('#btnLogin').prop('disabled', false);
            console.log(response); 
            alertify.error(response.message);
        }
        }
      });
    });


});