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
                   
                </div>
                `;
            });
            $('#signaturesContainer').html(html);
        });
    }

    loadSignatories(); // initial load

  




});
