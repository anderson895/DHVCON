<?php 
session_start();
include "auth.php";

$db = new auth_class();

if (isset($_SESSION['user_id'])) {
    $id = intval($_SESSION['user_id']);
    $On_Session = $db->check_account($id);

    if (empty($On_Session)) {
        header('location: ../login');
        exit;
    }
} else {
    header('location: ../login');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>DHVCON</title>

  <link href="../src/output.css" rel="stylesheet" />
  <link href="../src/alertifyconfig.css" rel="stylesheet" />
  <link rel="icon" type="image/x-icon" href="../static/image/favicon.ico">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/alertify.css" crossorigin="anonymous" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <style>
    body { font-family: 'Poppins', sans-serif; }
  </style>
</head>

<?php include "../plugins/PageSpinner.php"; ?>

<body class="bg-[#1A1A1A] text-white overflow-x-hidden">

<!-- Layout Wrapper -->
<div class="min-h-screen flex flex-col lg:flex-row">

  <!-- Sidebar -->
  <aside id="sidebar" 
         class="bg-[#0D0D0D] shadow-lg w-64 lg:w-1/5 xl:w-1/6 p-5 flex flex-col justify-between 
                lg:static fixed inset-y-0 left-0 z-50 transform -translate-x-full 
                lg:translate-x-0 transition-transform duration-300 ease-in-out overflow-y-auto">
    
    <div>
      <!-- Sidebar Header -->
      <div class="flex flex-col items-center text-center bg-[#1A1A1A] rounded-lg p-4 mb-6 shadow-inner hover:shadow-xl transition">
        <img src="../static/image/logo1.png" alt="Logo" class="w-20 h-20 rounded-full border-2 border-gray-700 mb-3 hover:scale-105 transition-transform">
        <h1 class="text-lg font-bold text-[#FFD700]">Administrator</h1>
      </div>

      <!-- Navigation -->
      <nav class="space-y-3 text-[#CCCCCC] text-sm md:text-base">
        <a href="dashboard" class="nav-link flex items-center space-x-3 hover:text-[#FFD700] hover:bg-white/10 px-4 py-2 rounded-md transition">
          <span class="material-icons">dashboard</span>
          <span>Dashboard</span>
        </a>

        <!-- Deals Dropdown -->
        <button id="toggleUsers" class="cursor-pointer flex justify-between items-center w-full text-[#CCCCCC] hover:text-[#FFD700] hover:bg-white/10 px-4 py-2 rounded-md transition">
          <div class="flex items-center space-x-3">
            <span class="material-icons">sell</span><span>Manage Users</span>
          </div>
          <span id="user_dropdownIcon" class="material-icons transition-transform">expand_more</span>
        </button>
        <div id="dealsDropdown" class="ml-8 hidden space-y-2">
          <a href="manageuser?pages=pending&&user_type=all" class="block hover:text-[#FFD700] hover:bg-white/10 px-4 py-2 rounded-md">Pending</a>
          <a href="manageuser?pages=all&&user_type=all" class="block hover:text-[#FFD700] hover:bg-white/10 px-4 py-2 rounded-md">All User</a>
          <a href="manageuser?pages=teacher&&user_type=teacher" class="block hover:text-[#FFD700] hover:bg-white/10 px-4 py-2 rounded-md">Teacher</a>
          <a href="manageuser?pages=student&&user_type=student" class="block hover:text-[#FFD700] hover:bg-white/10 px-4 py-2 rounded-md">Student</a>
        </div>

        

       
      </nav>
    </div>

    <!-- Bottom: Profile -->
    <div class="mt-6 border-t border-gray-700 pt-4">
      <button id="toggleProfile" class="cursor-pointer w-full flex justify-between items-center text-[#CCCCCC] hover:text-[#FFD700] hover:bg-white/10 px-4 py-2 rounded-md transition">
        <div class="flex flex-col items-start">
          <span class="font-semibold truncate capitalize"><?=$On_Session[0]['user_fullname']?></span>
          <span class="text-sm text-gray-400 capitalize">
            <?= ($On_Session[0]['user_type'] === 'admin') ? 'Administrator' : ucfirst($On_Session[0]['user_type']) ?>
          </span>

        </div>
        <span id="profile_dropdownIcon" class=" material-icons transition-transform">expand_more</span>
      </button>

      <div id="profileDropdown" class="ml-8 mt-2 hidden space-y-2">
        <a href="profile" class="block hover:text-[#FFD700] hover:bg-white/10 px-4 py-2 rounded-md">My Profile</a>
        <!-- <a href="change_password" class="block hover:text-[#FFD700] hover:bg-white/10 px-4 py-2 rounded-md">Change Password</a> -->
        <a href="logout" class="block hover:text-red-500 hover:bg-white/10 px-4 py-2 rounded-md">Logout</a>
      </div>
    </div>
  </aside>

  <!-- Overlay (Mobile) -->
  <div id="overlay" class="fixed inset-0 bg-black opacity-50 hidden lg:hidden z-40"></div>

  <!-- Main Content -->
  <main class="flex-1 p-4 sm:p-6 md:p-8 lg:p-12">
    <button id="menuButton" class="lg:hidden text-[#FFD700] bg-white/10 hover:bg-white/20 p-2 rounded-md mb-4">
      <span class="material-icons">menu</span>
    </button>

  
<!-- JavaScript -->
<script>
  // Dropdown Toggles
  const toggleDropdown = (btnId, dropId, iconId) => {
    $(`#${btnId}`).click(function () {
      $(`#${dropId}`).slideToggle(300);
      const icon = $(`#${iconId}`);
      icon.text(icon.text() === "expand_more" ? "expand_less" : "expand_more");
    });
  };

  toggleDropdown("toggleProfile", "profileDropdown", "profile_dropdownIcon");
  toggleDropdown("toggleUsers", "dealsDropdown", "user_dropdownIcon");

  // Mobile Sidebar
  const menuButton = document.getElementById('menuButton');
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('overlay');

  menuButton.addEventListener('click', () => {
    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
  });

  overlay.addEventListener('click', () => {
    sidebar.classList.add('-translate-x-full');
    overlay.classList.add('hidden');
  });

  // Highlight Active Link
  const currentURL = window.location.href; // full URL including query
  const navLinks = document.querySelectorAll('nav a');

  navLinks.forEach(link => {
    const href = link.getAttribute('href');
    if (currentURL.includes(href)) {
      link.classList.add('text-[#FFD700]', 'bg-white/10', 'font-semibold');

      // If this link is inside a dropdown, open the dropdown automatically
      const dropdown = link.closest('#dealsDropdown');
      if (dropdown) {
        dropdown.style.display = 'block'; // make dropdown visible
        const icon = document.getElementById('user_dropdownIcon');
        if (icon) icon.textContent = 'expand_less';
      }
    }
  });
</script>


</body>
</html>
