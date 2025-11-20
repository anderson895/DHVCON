$(document).ready(function(){


    
    const requestUrl = "../controller/end-points/controller.php?requestType=signatories"; // adjust path

    // Load all signatories
    function loadSignatories() {
        $.get(requestUrl, { action: 'list' }, function(response){
            const data = JSON.parse(response).data;
            let html = '';
            data.forEach((sig, idx) => {
                html += `
                <div class="text-center signature-block" data-index="${idx}">
                    <div class="w-48 h-16 border-b border-gray-500 mx-auto mb-2"></div>
                    <p class="font-semibold text-white name">${sig.name}</p>
                    <p class="text-gray-400 text-sm position-department">${sig.position}, ${sig.department}</p>
                    <div class="mt-2 flex justify-center gap-2">
                        <button class="edit-btn cursor-pointer bg-yellow-500 text-black px-3 py-1 rounded text-sm">Edit</button>
                        <button class="delete-btn cursor-pointer bg-red-500 text-white px-3 py-1 rounded text-sm">Delete</button>
                    </div>
                </div>
                `;
            });
            $('#signaturesContainer').html(html);
        });
    }

    loadSignatories(); // initial load

    // Add new signatory
    // Open modal
$('#openAddSignatoryModal').click(function(){
    $('#addSignatoryModal').removeClass('hidden');
});

// Close modal
$('#closeModal').click(function(){
    $('#addSignatoryModal').addClass('hidden');
});

// Optional: click outside modal to close
$('#addSignatoryModal').click(function(e){
    if(e.target.id === 'addSignatoryModal'){
        $(this).addClass('hidden');
    }
});

// Add signatory via AJAX with SweetAlert
$('#addSignatoryBtn').click(function(){
    const name = $('#name').val().trim();
    const position = $('#position').val().trim();
    const department = $('#department').val().trim();

    if(name && position && department){
        $.get("../controller/end-points/controller?requestType=signatories", {
            action: 'add',
            name: name,
            position: position,
            department: department
        }, function(response){
            loadSignatories(); // reload signatures
            $('#addSignatoryModal').addClass('hidden'); // close modal
            $('#name').val('');
            $('#position').val('');
            $('#department').val('');

            // SweetAlert success
            Swal.fire({
                icon: 'success',
                title: 'Added!',
                text: 'Signatory added successfully.',
                timer: 2000,
                showConfirmButton: false
            });
        });
    } else {
        // SweetAlert warning for empty fields
        Swal.fire({
            icon: 'warning',
            title: 'All fields are required',
            text: 'Please fill in name, position, and department.'
        });
    }
});




// Open Edit Modal
$(document).on('click', '.edit-btn', function(){
    const block = $(this).closest('.signature-block');
    const index = block.data('index');
    const name = block.find('.name').text();
    const positionDepartment = block.find('.position-department').text();
    const [position, department] = positionDepartment.split(', ').map(s => s.trim());

    // Fill modal inputs
    $('#editIndex').val(index);
    $('#editName').val(name);
    $('#editPosition').val(position);
    $('#editDepartment').val(department);

    $('#editSignatoryModal').removeClass('hidden');
});

// Close Edit Modal
$('#closeEditModal').click(function(){
    $('#editSignatoryModal').addClass('hidden');
});

// Optional: click outside modal to close
$('#editSignatoryModal').click(function(e){
    if(e.target.id === 'editSignatoryModal'){
        $(this).addClass('hidden');
    }
});

// Update signatory via AJAX
$('#updateSignatoryBtn').click(function(){
    const index = $('#editIndex').val();
    const name = $('#editName').val();
    const position = $('#editPosition').val();
    const department = $('#editDepartment').val();

    if(name && position && department){
        $.get(requestUrl, {
            action: 'update',
            index: index,
            name: name,
            position: position,
            department: department
        }, function(response){
            loadSignatories(); // reload signatures
            $('#editSignatoryModal').addClass('hidden'); // close modal

            // Show success using SweetAlert
            Swal.fire({
                icon: 'success',
                title: 'Updated!',
                text: 'Signatory updated successfully.',
                timer: 2000,
                showConfirmButton: false
            });
        });
    } else {
        Swal.fire({
            icon: 'warning',
            title: 'All fields are required',
        });
    }
});


   // Delete signatory using SweetAlert
$(document).on('click', '.delete-btn', function(){
    const index = $(this).closest('.signature-block').data('index');

    Swal.fire({
        title: 'Are you sure?',
        text: "This will permanently delete the signatory.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f59e0b', // yellow
        cancelButtonColor: '#ef4444', // red
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.get(requestUrl, { action: 'delete', index: index }, function(response){
                loadSignatories(); // reload signatures
                Swal.fire({
                    icon: 'success',
                    title: 'Deleted!',
                    text: 'Signatory deleted successfully.',
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        }
    });
});




});
