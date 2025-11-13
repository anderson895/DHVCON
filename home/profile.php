<?php 
include "../src/components/home/header.php";

if (isset($On_Session) && !empty($On_Session)) {
  $User = $On_Session[0];
} else {
  echo "<p class='text-red-500'>Session not found.</p>";
  exit;
}

$statusText = $User['user_status'] == 1 ? "Active" : ($User['user_status'] == 0 ? "Pending" : "Disabled");
$statusColor = $User['user_status'] == 1 ? "text-green-400" : ($User['user_status'] == 0 ? "text-yellow-400" : "text-red-400");
?>

<main class="flex-1 p-4 sm:p-6 md:p-8 lg:p-12">

<!-- Profile Container -->
<div class="bg-[#1a1a1a] p-6 rounded-lg shadow-md border border-gray-700 max-w-2xl mx-auto text-white">
  <div class="flex flex-col items-center space-y-4">
    
    <!-- Profile Picture -->
   <div class="w-32 h-32 relative flex items-center justify-center bg-gray-700 rounded-full border-2 border-gray-500 text-white text-3xl font-bold">
      <?php if (!empty($User['user_profile_pict']) && file_exists("../static/upload/profile/" . $User['user_profile_pict'])): ?>
          <img 
              src="../static/upload/profile/<?= htmlspecialchars($User['user_profile_pict']) ?>" 
              alt="Profile Picture" 
              class="w-full h-full object-cover rounded-full"
          >
      <?php else: ?>
          <!-- Display first letter if no image -->
          <?= strtoupper(substr($User['user_fullname'], 0, 1)) ?>
      <?php endif; ?>
  </div>


    <div class="flex flex-col space-y-3 w-full">
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
        <button id="editProfileBtn" class="bg-[#5865f2] text-black font-semibold px-5 py-2 rounded-lg hover:bg-yellow-500 cursor-pointer">
          <i class="fa fa-edit mr-1"></i> Edit Profile
        </button>
        <button id="changePassBtn" class="bg-gray-700 hover:bg-gray-600 text-white px-5 py-2 rounded-lg cursor-pointer">
          <i class="fa fa-lock mr-1"></i> Change Password
        </button>
      </div>
    </div>
  </div>
</div>


</main>



<!-- ✨ Edit Profile Modal -->
<div id="editProfileModal" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm flex justify-center items-center z-50">
  <div class="bg-[#1a1a1a]/80 p-6 rounded-xl shadow-2xl w-full max-w-md border border-gray-700 backdrop-blur-md">
    <h3 class="text-xl font-bold text-[#FFD700] mb-4">Edit Profile</h3>
    <form id="editProfileForm" class="space-y-4" enctype="multipart/form-data">
      
      <!-- Profile Picture Upload -->
      <div class="flex flex-col items-center space-y-3">
        <div class="w-24 h-24 rounded-full border-2 border-[#FFD700] bg-gray-700 flex items-center justify-center overflow-hidden text-white text-4xl font-bold relative">
            <img id="profilePreview" 
                src="<?= !empty($User['user_profile_pict']) ? '../static/upload/profile/' . htmlspecialchars($User['user_profile_pict']) : '' ?>" 
                alt="Profile Preview" 
                class="w-full h-full object-cover rounded-full <?= empty($User['user_profile_pict']) ? 'hidden' : '' ?>">

            <?php if (empty($User['user_profile'])): ?>
                <!-- First letter of fullname -->
                <span id="profileInitial"><?= strtoupper(substr($User['user_fullname'], 0, 1)) ?></span>
            <?php endif; ?>
        </div>


        <label for="profilePic" class="cursor-pointer bg-gray-700/70 hover:bg-gray-600 text-white px-3 py-1 rounded-md text-sm">
          Choose Photo
        </label>
        <input type="file" id="profilePic" name="profilePic" accept="image/*" class="hidden">
      </div>

      <!-- Full Name -->
      <div>
        <label class="block text-sm text-gray-300 mb-1">Full Name</label>
        <input type="text" name="fullname" id="fullname"
          value="<?= htmlspecialchars($User['user_fullname']) ?>"
          class="w-full px-3 py-2 bg-[#0D0D0D]/60 text-white rounded border border-gray-700 focus:outline-none focus:border-[#FFD700]">
      </div>

      <!-- Email -->
      <div>
        <label class="block text-sm text-gray-300 mb-1">Email</label>
        <input type="email" name="email" id="email"
          value="<?= htmlspecialchars($User['user_email']) ?>"
          class="w-full px-3 py-2 bg-[#0D0D0D]/60 text-white rounded border border-gray-700 focus:outline-none focus:border-[#FFD700]">
      </div>

      <!-- Buttons -->
      <div class="flex justify-end space-x-2 pt-2">
        <button type="button" id="closeModal"
          class="bg-gray-700/70 hover:bg-gray-600 text-white px-4 py-2 rounded-lg cursor-pointer">
          Cancel
        </button>
        <button type="submit"
          class="bg-[#5865f2] text-black font-semibold px-4 py-2 rounded-lg hover:bg-yellow-500 cursor-pointer">
          Save Changes
        </button>
      </div>
    </form>
  </div>
</div>

<!-- 🧠 JS Preview Script -->
<script>
  const profileInput = document.getElementById('profilePic');
const profilePreview = document.getElementById('profilePreview');
const profileInitial = document.getElementById('profileInitial');

profileInput.addEventListener('change', function() {
  const file = this.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function(e) {
      profilePreview.src = e.target.result;
      profilePreview.classList.remove('hidden'); // ipakita ang image
      if(profileInitial) profileInitial.style.display = 'none'; // itago ang initial
    };
    reader.readAsDataURL(file);
  }
});

</script>



<!-- ✨ Change Password Modal -->
<div id="changePassModal" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm flex justify-center items-center z-50">
  <div class="bg-[#1a1a1a]/80 p-6 rounded-xl shadow-2xl w-full max-w-md border border-gray-700 backdrop-blur-md">
    <h3 class="text-xl font-bold text-[#FFD700] mb-4">Change Password</h3>
    <form id="changePasswordForm" class="space-y-4">

          <!-- Current Password -->
    <div class="relative">
      <label class="block text-sm text-gray-300 mb-1">Current Password</label>
      <div class="relative">
        <input type="password" name="old_password" id="old_password" required
          class="w-full pr-10 px-3 py-2 bg-[#0D0D0D]/60 text-white rounded border border-gray-700 focus:outline-none focus:border-[#FFD700]">
        <span class="material-icons text-gray-400 cursor-pointer absolute right-3 top-1/2 -translate-y-1/2 toggle-password" data-target="old_password">
          visibility_off
        </span>
      </div>
    </div>

    <!-- New Password -->
    <div class="relative">
      <label class="block text-sm text-gray-300 mb-1">New Password</label>
      <div class="relative">
        <input type="password" name="new_password" id="new_password" required
          class="w-full pr-10 px-3 py-2 bg-[#0D0D0D]/60 text-white rounded border border-gray-700 focus:outline-none focus:border-[#FFD700]">
        <span class="material-icons text-gray-400 cursor-pointer absolute right-3 top-1/2 -translate-y-1/2 toggle-password" data-target="new_password">
          visibility_off
        </span>
      </div>
    </div>

    <!-- Confirm Password -->
    <div class="relative">
      <label class="block text-sm text-gray-300 mb-1">Confirm Password</label>
      <div class="relative">
        <input type="password" id="confirm_password_modal" required
          class="w-full pr-10 px-3 py-2 bg-[#0D0D0D]/60 text-white rounded border border-gray-700 focus:outline-none focus:border-[#FFD700]">
        <span class="material-icons text-gray-400 cursor-pointer absolute right-3 top-1/2 -translate-y-1/2 toggle-password" data-target="confirm_password_modal">
          visibility_off
        </span>
      </div>
    </div>


      <div class="flex justify-end space-x-2">
        <button type="button" id="closePassModal"
          class="bg-gray-700/70 hover:bg-gray-600 text-white px-4 py-2 rounded-lg cursor-pointer">Cancel</button>
        <button type="submit"
          class="bg-[#FFD700] text-black font-semibold px-4 py-2 rounded-lg hover:bg-yellow-500 cursor-pointer">Update Password</button>
      </div>
    </form>
  </div>
</div>

<script>
  // Toggle password visibility for all fields with .toggle-password
  document.querySelectorAll('.toggle-password').forEach(icon => {
    icon.addEventListener('click', () => {
      const targetId = icon.getAttribute('data-target');
      const input = document.getElementById(targetId);
      if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = 'visibility'; // eye open
      } else {
        input.type = 'password';
        icon.textContent = 'visibility_off'; // eye closed
      }
    });
  });
</script>


<?php include "../src/components/home/footer.php"; ?>



<script>
$(document).ready(function(){
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

    const formData = new FormData(this); // automatically includes file input
    formData.append("requestType", "updateProfile"); // add extra field

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
          processData: false, // important for file upload
          contentType: false, // important for file upload
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
              Swal.fire("Error!", res.message || "Failed to update profile.", "error");
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
