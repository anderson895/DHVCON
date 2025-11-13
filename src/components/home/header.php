<?php 
session_start();

include "auth.php";

$db = new auth_class();

if (isset($_SESSION['user_id'])) {
    $id = intval($_SESSION['user_id']);
    $On_Session = $db->check_account($id);
    if (!empty($On_Session)) {
    } else {
       header('location: ../signin');
    }
} else {
   header('location: ../signin');
}

// print_r($On_Session);

?>




<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>DHVCON</title>
  <link href="../src/output.css" rel="stylesheet" />
  <link href="../src/scrollbar.css" rel="stylesheet" />
  <link href="../src/alertifyconfig.css" rel="stylesheet" />

  <link rel="icon" type="image/x-icon" href="../static/image/favicon.ico">
  
  
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


  <!-- Google Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/alertify.css" integrity="sha512-MpdEaY2YQ3EokN6lCD6bnWMl5Gwk7RjBbpKLovlrH6X+DRokrPRAF3zQJl1hZUiLXfo2e9MrOt+udOnHCAmi5w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js" integrity="sha512-JnjG+Wt53GspUQXQhc+c4j8SBERsgJAoHeehagKHlxQN+MtCCmFDghX9/AcbkkNRZptyZU4zC8utK59M5L45Iw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<?php 
include "../plugins/PageSpinner.php";
?>

<body class="bg-[#1e1f22] text-white min-h-screen flex">

  <!-- Mobile menu button -->
<button id="mobile-menu-btn" 
        class="md:hidden fixed top-4 right-4 z-50 p-2 bg-gray-700 rounded h-12 w-12 flex items-center justify-center">
  <span class="material-icons-outlined text-white text-2xl">menu</span>
</button>



  <!-- Sidebar -->
<aside id="sidebar" class="bg-[#232428] w-60 p-4 border-r border-gray-800 fixed inset-y-0 left-0 transform -translate-x-full md:translate-x-0 transition-transform duration-300 flex flex-col z-40">

  <!-- Logo / Title -->
  <div class="flex flex-col items-center justify-start mb-6">
    
     <div class="mb-4"></div>
     <img src="../static/image/logo1.png" alt="DHVCON Logo" class="w-16 h-16 object-contain mb-2">  
    <h2 class="text-xl font-bold text-white tracking-wider ">DHVCON</h2> 
  </div>

  <!-- Navigation -->
  <nav id="roomNav" class="flex-1 flex flex-col gap-3 overflow-y-auto scrollbar-dark">

    <!-- All Rooms Link -->
    <a href="../home/" class="flex items-center gap-3 px-4 py-2 rounded-lg text-gray-300 hover:bg-[#2A2C31] hover:text-white transition-colors duration-200">
      <span class="material-icons-outlined text-xl">groups</span>
      <span class="font-medium">All Rooms</span>
    </a>

    <hr class="border-gray-700 my-2">

    <!-- Created Rooms Section -->
    <button id="toggleCreated" class="flex justify-between items-center w-full px-4 py-2 text-gray-400 uppercase text-sm font-medium hover:text-white cursor-pointer transition-colors duration-200">
      Created Rooms
      <span class="material-icons-outlined transition-transform rotate-180">expand_more</span>
    </button>
    <div id="createdRooms" class="flex flex-col gap-1 max-h-64 overflow-y-auto p-1 scrollbar-dark">
      <!-- Rooms will be appended here -->
    </div>

    <hr class="border-gray-700 my-2">

    <!-- Joined Rooms Section -->
    <button id="toggleJoined" class="flex justify-between items-center w-full px-4 py-2 text-gray-400 uppercase text-sm font-medium hover:text-white cursor-pointer transition-colors duration-200">
      Joined Rooms
      <span class="material-icons-outlined transition-transform rotate-180">expand_more</span>
    </button>
    <div id="joinedRooms" class="flex flex-col gap-1 max-h-64 overflow-y-auto p-1 scrollbar-dark">
      <!-- Rooms will be appended here -->
    </div>

  </nav>

  <!-- User Section -->
  <div class="mt-auto pt-4 border-t border-gray-700">
    <div class="flex items-center gap-2 px-4 py-2 bg-[#2A2C31] rounded-lg hover:bg-[#33353A] transition-colors duration-200 cursor-pointer relative">
      
      <!-- Profile Picture -->
      <div class="w-10 h-10 rounded-full overflow-hidden border border-gray-600 flex items-center justify-center bg-gray-700 text-white font-semibold text-lg">
        <?php if (!empty($On_Session[0]['user_profile_pict'])): ?>
          <img src="<?= '../static/upload/profile/' . htmlspecialchars($On_Session[0]['user_profile_pict']); ?>" alt="Profile Picture" class="w-full h-full object-cover">
        <?php else: ?>
          <span><?= strtoupper(substr($On_Session[0]['user_fullname'], 0, 1)); ?></span>
        <?php endif; ?>
      </div>

      <!-- User Info -->
      <div class="flex flex-col flex-1">
        <p class="text-sm font-semibold text-white"><?= ucfirst($On_Session[0]['user_fullname']); ?></p>
        <p class="text-xs text-gray-400 capitalize"><?= $On_Session[0]['user_type'] ?></p>
      </div>

      <!-- Settings Button -->
      <span id="settings-btn" class="material-icons-outlined text-gray-400 text-xl hover:text-white transition-colors duration-200">settings</span>

      <!-- Dropdown Menu -->
      <div id="settings-menu" class="hidden absolute bottom-14 left-0 right-0 bg-[#2b2d31] border-t border-gray-700 shadow-lg rounded-lg overflow-hidden">
        <a href="profile" class="block px-4 py-2 text-sm hover:bg-[#3c3f44] transition">Profile</a>
        <a href="logout.php" class="block px-4 py-2 text-sm hover:bg-[#3c3f44] transition text-red-400">Logout</a>
      </div>
    </div>
  </div>

</aside>



  <script>
$(document).ready(function () {
  // Settings dropdown toggle
  $("#settings-btn").on("click", function () {
    $("#settings-menu").toggleClass("hidden");
  });

  $(document).on("click", function (e) {
    if (!$(e.target).closest("#settings-btn, #settings-menu").length) {
      $("#settings-menu").addClass("hidden");
    }
  });

  // Mobile sidebar toggle with icon change
  $("#mobile-menu-btn").on("click", function () {
    $("#sidebar").toggleClass("-translate-x-full");

    // Change icon
    const icon = $(this).find("span.material-icons-outlined");
    if ($("#sidebar").hasClass("-translate-x-full")) {
      icon.text("menu"); // sidebar is hidden → show menu icon
    } else {
      icon.text("close"); // sidebar is visible → show close icon
    }
  });

  // Close sidebar if clicked outside (mobile only)
  $(document).on("click", function (e) {
    if (!$(e.target).closest("#sidebar, #mobile-menu-btn").length) {
      if (!$("#sidebar").hasClass("-translate-x-full")) {
        $("#sidebar").addClass("-translate-x-full");
        // Reset icon to menu when sidebar closes
        $("#mobile-menu-btn").find("span.material-icons-outlined").text("menu");
      }
    }
  });
});

  </script>