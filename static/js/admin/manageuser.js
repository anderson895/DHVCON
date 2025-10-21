$(document).ready(function () {
    const urlParams = new URLSearchParams(window.location.search);
    const page = urlParams.get("pages") || "all";

    loadUsers(page);

    function loadUsers(filter) {
        $.ajax({
            url: "../controller/end-points/controller.php",
            type: "POST",
            data: { requestType: "fetchUsers", filter: filter },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    let html = `
                        <div class="overflow-x-auto">
                          <table class="min-w-full bg-[#1a1a1a] text-white border border-gray-700 rounded-md">
                            <thead class="bg-[#FFD700] text-black">
                              <tr>
                                <th class="py-2 px-3 text-left">ID</th>
                                <th class="py-2 px-3 text-left">Full Name</th>
                                <th class="py-2 px-3 text-left">Email</th>
                                <th class="py-2 px-3 text-left">Type</th>
                                <th class="py-2 px-3 text-left">Status</th>
                                <th class="py-2 px-3 text-center">Actions</th>
                              </tr>
                            </thead>
                            <tbody>`;

                    if (response.data.length === 0) {
                        html += `<tr><td colspan="6" class="text-center py-4 text-gray-400">No users found.</td></tr>`;
                    } else {
                        response.data.forEach(u => {
                            const statusText = 
                                u.user_status == 0 ? "Pending" : 
                                u.user_status == 1 ? "Active" : 
                                "Disabled";

                            const statusClass = 
                                u.user_status == 0 ? "text-yellow-400" : 
                                u.user_status == 1 ? "text-green-400" : 
                                "text-red-400";

                            const safeReq = encodeURIComponent(u.user_requirements);

                            html += `
                                <tr class="border-t border-gray-700 hover:bg-[#222] transition">
                                  <td class="py-2 px-3">${u.user_id}</td>
                                  <td class="py-2 px-3 capitalize">${u.user_fullname}</td>
                                  <td class="py-2 px-3">${u.user_email}</td>
                                  <td class="py-2 px-3 capitalize">${u.user_type}</td>
                                  <td class="py-2 px-3 ${statusClass}">${statusText}</td>
                                  <td class="py-2 px-3 text-center space-x-1">
                                    <button class="cursor-pointer view-req-btn bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded cursor-pointer" 
                                      data-req="${safeReq}" data-name="${u.user_fullname}">
                                      View Requirements
                                    </button>
                                    ${u.user_status == 0 ? `
                                        <button class="cursor-pointer approve-btn bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded cursor-pointer" data-id="${u.user_id}">
                                          Approve
                                        </button>` 
                                    : ""}
                                    ${u.user_status == 1 ? `
                                        <button class="cursor-pointer disable-btn bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded cursor-pointer" data-id="${u.user_id}">
                                          Disable
                                        </button>` 
                                    : ""}
                                    ${u.user_status == 2 ? `
                                        <button class="cursor-pointer restore-btn bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded cursor-pointer" data-id="${u.user_id}">
                                          Restore
                                        </button>`
                                    : ""}
                                  </td>
                                </tr>`;
                        });
                    }

                    html += `</tbody></table></div>`;
                    $(".user-table").html(html);
                } else {
                    $(".user-table").html("<p class='text-gray-400'>Failed to load users.</p>");
                }
            },
            error: function () {
                $(".user-table").html("<p class='text-red-400'>Error connecting to the server.</p>");
            }
        });
    }




$(document).on("click", ".view-req-btn", function () {
  const rawReq = decodeURIComponent($(this).data("req"));
  const name = $(this).data("name");
  let files = [];

  try {
    files = JSON.parse(rawReq || "[]");
  } catch (e) {
    console.error("JSON parse error:", e);
    files = [];
  }

  let listHTML = `
    <div class="col-span-full mb-3">
      <h3 class="text-lg md:text-xl font-semibold text-yellow-400">${name}'s Requirements</h3>
    </div>`;

  if (files.length === 0) {
    listHTML += `<p class="text-gray-400 col-span-full">No uploaded requirements.</p>`;
  } else {
    files.forEach(file => {
      const fileExt = file.split('.').pop().toLowerCase();
      const filePath = `../static/upload/requirements/${file}`;

      if (["jpg", "jpeg", "png", "gif"].includes(fileExt)) {
        // 🖼 Image preview
        listHTML += `
          <div class="bg-[#2b2b2b] rounded-lg shadow-md p-2 flex flex-col items-center text-center">
            <img src="${filePath}" alt="${file}" class="w-full h-48 object-contain rounded-md mb-2">
            <a href="${filePath}" target="_blank" class="text-blue-400 hover:text-blue-300 underline text-sm">Open Image</a>
          </div>`;
      } else if (fileExt === "pdf") {
        // 📄 PDF preview
        listHTML += `
          <div class="bg-[#2b2b2b] rounded-lg shadow-md p-2 flex flex-col text-center">
            <iframe src="${filePath}" class="w-full h-48 rounded-md mb-2"></iframe>
            <a href="${filePath}" target="_blank" class="text-blue-400 hover:text-blue-300 underline text-sm">Open PDF</a>
          </div>`;
      } else {
        // 📁 Other file
        listHTML += `
          <div class="bg-[#2b2b2b] rounded-lg shadow-md p-3 flex justify-between items-center">
            <span class="truncate text-sm">${file}</span>
            <a href="${filePath}" target="_blank" class="text-blue-400 hover:text-blue-300 underline text-sm">View</a>
          </div>`;
      }
    });
  }

  $("#requirementsList").html(listHTML);
  $("#requirementsModal").removeClass("hidden");
});

// ✅ Close modal (header or footer or click outside)
$(document).on("click", "#closeModal, #closeModalFooter", function () {
  $("#requirementsModal").addClass("hidden");
});
$(document).on("click", "#requirementsModal", function (e) {
  if (e.target.id === "requirementsModal") {
    $("#requirementsModal").addClass("hidden");
  }
});


    // ✅ Approve / Disable / Restore (unchanged)
    $(document).on("click", ".approve-btn, .disable-btn, .restore-btn", function () {
        const id = $(this).data("id");
        let newStatus, actionText, actionColor;

        if ($(this).hasClass("approve-btn")) {
            newStatus = 1;
            actionText = "approve this user";
            actionColor = "#16A34A";
        } else if ($(this).hasClass("disable-btn")) {
            newStatus = 2;
            actionText = "disable this user";
            actionColor = "#DC2626";
        } else {
            newStatus = 1;
            actionText = "restore this user";
            actionColor = "#2563EB";
        }

        Swal.fire({
            title: "Are you sure?",
            text: `Do you want to ${actionText}?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: actionColor,
            cancelButtonColor: "#6B7280",
            confirmButtonText: "Yes, proceed",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "../controller/end-points/controller.php",
                    type: "POST",
                    data: { requestType: "updateStatus", id: id, status: newStatus },
                    dataType: "json",
                    success: function (res) {
                        if (res.success) {
                            Swal.fire({
                                icon: "success",
                                title: "Success!",
                                text: "User status updated successfully.",
                                timer: 1500,
                                showConfirmButton: false
                            });
                            loadUsers(page);
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Error!",
                                text: "Failed to update user status."
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: "error",
                            title: "Server Error",
                            text: "Could not connect to the server."
                        });
                    }
                });
            }
        });
    });
});
