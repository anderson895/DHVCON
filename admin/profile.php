<?php 
include "../src/components/admin/header.php";

if (isset($On_Session) && !empty($On_Session)) {
  $User = $On_Session[0];
} else {
  echo "<p class='text-red-500'>Session not found.</p>";
  exit;
}

$statusText = $User['user_status'] == 1 ? "Active" : ($User['user_status'] == 0 ? "Pending" : "Disabled");
$statusColor = $User['user_status'] == 1 ? "text-green-400" : ($User['user_status'] == 0 ? "text-yellow-400" : "text-red-400");
?>

<div class="flex justify-between items-center bg-[#0D0D0D] p-4 mb-6 rounded-md shadow-lg">
  <h2 class="text-xl font-bold text-[#FFD700] capitalize tracking-wide">My Profile</h2>
</div>

<!-- Profile Container -->
<div class="bg-[#1a1a1a] p-6 rounded-lg shadow-md border border-gray-700 max-w-2xl mx-auto text-white">
  <div class="flex flex-col space-y-3">
    <div>
      <label class="text-gray-400 block text-sm mb-1">Full Name</label>
      <p class="text-lg font-semibold capitalize"><?= htmlspecialchars($User['user_fullname']) ?></p>
    </div>

    <div>
      <label class="text-gray-400 block text-sm mb-1">Email</label>
      <p class="text-lg font-semibold"><?= htmlspecialchars($User['user_email']) ?></p>
    </div>

    <div>
      <label class="text-gray-400 block text-sm mb-1">User Type</label>
      <p class="text-lg font-semibold capitalize"><?= htmlspecialchars($User['user_type']) ?></p>
    </div>

    <div>
      <label class="text-gray-400 block text-sm mb-1">Status</label>
      <p class="text-lg font-semibold <?= $statusColor ?>"><?= $statusText ?></p>
    </div>

    <div class="mt-6 flex space-x-3">
      <button id="editProfileBtn" class="bg-[#FFD700] text-black font-semibold px-5 py-2 rounded-lg hover:bg-yellow-500 cursor-pointer">
        <i class="fa fa-edit mr-1"></i> Edit Profile
      </button>
      <button id="changePassBtn" class="bg-gray-700 hover:bg-gray-600 text-white px-5 py-2 rounded-lg cursor-pointer">
        <i class="fa fa-lock mr-1"></i> Change Password
      </button>
    </div>
  </div>
</div>


<!-- ✨ Edit Profile Modal -->
<div id="editProfileModal" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm flex justify-center items-center z-50">
  <div class="bg-[#1a1a1a]/80 p-6 rounded-xl shadow-2xl w-full max-w-md border border-gray-700 backdrop-blur-md">
    <h3 class="text-xl font-bold text-[#FFD700] mb-4">Edit Profile</h3>
    <form id="editProfileForm" class="space-y-4">
      <div>
        <label class="block text-sm text-gray-300 mb-1">Full Name</label>
        <input type="text" name="fullname" id="fullname"
          value="<?= htmlspecialchars($User['user_fullname']) ?>"
          class="w-full px-3 py-2 bg-[#0D0D0D]/60 text-white rounded border border-gray-700 focus:outline-none focus:border-[#FFD700]">
      </div>
      <div>
        <label class="block text-sm text-gray-300 mb-1">Email</label>
        <input type="email" name="email" id="email"
          value="<?= htmlspecialchars($User['user_email']) ?>"
          class="w-full px-3 py-2 bg-[#0D0D0D]/60 text-white rounded border border-gray-700 focus:outline-none focus:border-[#FFD700]">
      </div>
      <div class="flex justify-end space-x-2">
        <button type="button" id="closeModal"
          class="bg-gray-700/70 hover:bg-gray-600 text-white px-4 py-2 rounded-lg cursor-pointer">Cancel</button>
        <button type="submit"
          class="bg-[#FFD700] text-black font-semibold px-4 py-2 rounded-lg hover:bg-yellow-500 cursor-pointer">Save
          Changes</button>
      </div>
    </form>
  </div>
</div>

<!-- ✨ Change Password Modal -->
<div id="changePassModal" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm flex justify-center items-center z-50">
  <div class="bg-[#1a1a1a]/80 p-6 rounded-xl shadow-2xl w-full max-w-md border border-gray-700 backdrop-blur-md">
    <h3 class="text-xl font-bold text-[#FFD700] mb-4">Change Password</h3>
    <form id="changePasswordForm" class="space-y-4">
      <div>
        <label class="block text-sm text-gray-300 mb-1">Current Password</label>
        <input type="password" name="old_password" id="old_password" required
          class="w-full px-3 py-2 bg-[#0D0D0D]/60 text-white rounded border border-gray-700 focus:outline-none focus:border-[#FFD700]">
      </div>
      <div>
        <label class="block text-sm text-gray-300 mb-1">New Password</label>
        <input type="password" name="new_password" id="new_password" required
          class="w-full px-3 py-2 bg-[#0D0D0D]/60 text-white rounded border border-gray-700 focus:outline-none focus:border-[#FFD700]">
      </div>
      <div>
        <label class="block text-sm text-gray-300 mb-1">Confirm Password</label>
        <input type="password" id="confirm_password" required
          class="w-full px-3 py-2 bg-[#0D0D0D]/60 text-white rounded border border-gray-700 focus:outline-none focus:border-[#FFD700]">
      </div>
      <div class="flex justify-end space-x-2">
        <button type="button" id="closePassModal"
          class="bg-gray-700/70 hover:bg-gray-600 text-white px-4 py-2 rounded-lg cursor-pointer">Cancel</button>
        <button type="submit"
          class="bg-[#FFD700] text-black font-semibold px-4 py-2 rounded-lg hover:bg-yellow-500 cursor-pointer">Update
          Password</button>
      </div>
    </form>
  </div>
</div>

<?php include "../src/components/admin/footer.php"; ?>



<script>
$(document).ready(function(){
  // === Edit Profile Modal ===
  // === Edit Profile Modal ===
  $("#editProfileBtn").click(function () {
    $("#editProfileModal").removeClass("hidden").hide().fadeIn(200);
  });

  $("#closeModal").click(function () {
    $("#editProfileModal").fadeOut(200, function () {
      $(this).addClass("hidden");
    });
  });

  // === Change Password Modal ===
  $("#changePassBtn").click(function () {
    $("#changePassModal").removeClass("hidden").hide().fadeIn(200);
  });

  $("#closePassModal").click(function () {
    $("#changePassModal").fadeOut(200, function () {
      $(this).addClass("hidden");
    });
  });

  // === Submit Edit Profile Form ===
  $("#editProfileForm").submit(function(e){
    e.preventDefault();
    const formData = $(this).serialize() + "&requestType=updateProfile";

    Swal.fire({
      title: "Save Changes?",
      text: "Do you want to update your profile information?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#FFD700",
      cancelButtonColor: "#6B7280",
      confirmButtonText: "Yes, save it!"
    }).then((result) => {
      if(result.isConfirmed){
        $.ajax({
          url: "../controller/end-points/controller.php",
          type: "POST",
          data: formData,
          dataType: "json",
          success: function(res){
            if(res.success){
              Swal.fire({
                icon: "success",
                title: "Profile Updated!",
                timer: 1500,
                showConfirmButton: false
              });
              setTimeout(() => location.reload(), 1600);
            } else {
              Swal.fire("Error!", "Failed to update profile.", "error");
            }
          },
          error: function(){
            Swal.fire("Error!", "Server connection failed.", "error");
          }
        });
      }
    });
  });

  // === Submit Change Password Form ===
  $("#changePasswordForm").submit(function(e){
    e.preventDefault();

    let newPass = $("#new_password").val();
    let confirmPass = $("#confirm_password").val();

    if (newPass !== confirmPass) {
      Swal.fire("Error!", "Passwords do not match.", "error");
      return;
    }

    const formData = $(this).serialize() + "&requestType=updatePassword";

    Swal.fire({
      title: "Update Password?",
      text: "Are you sure you want to change your password?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#FFD700",
      cancelButtonColor: "#6B7280",
      confirmButtonText: "Yes, update it!"
    }).then((result) => {
      if(result.isConfirmed){
        $.ajax({
          url: "../controller/end-points/controller.php",
          type: "POST",
          data: formData,
          dataType: "json",
          success: function(res){
            if(res.success){
              Swal.fire({
                icon: "success",
                title: "Password Updated!",
                timer: 1500,
                showConfirmButton: false
              });
              $("#changePasswordForm")[0].reset();
              $("#changePassModal").fadeOut(200, () => $(this).addClass("hidden"));
            } else {
              Swal.fire("Error!", "Current password is incorrect.", "error");
            }
          },
          error: function(){
            Swal.fire("Error!", "Server connection failed.", "error");
          }
        });
      }
    });
  });
});
</script>
