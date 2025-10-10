
$(document).ready(function () {
  


  
  $("#frmSubmittedWorks").submit(function (e) {
    e.preventDefault();

    $('#spinner').show();
    $('#btnCreateWork').prop('disabled', true);

    var formData = new FormData(this);
    formData.append('requestType', 'CreateClasswork');
    formData.append('room_id', room_id); 

    $.ajax({
        type: "POST",
        url: "../controller/end-points/controller.php",
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (response) {
            console.log(response);

            if (response.status === "success") {
                alertify.success('Created Successfully');
                setTimeout(function () {
                    // ✅ Reload page but keep tab
                    location.reload();
                }, 1000);
            } else {
                $('#spinner').hide();
                $('#btnCreateWork').prop('disabled', false);
                alertify.error(response.message);
            }
        }
    });
});



});
