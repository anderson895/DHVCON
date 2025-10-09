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
  <!-- ✅ Google Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
 
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/alertify.css" integrity="sha512-MpdEaY2YQ3EokN6lCD6bnWMl5Gwk7RjBbpKLovlrH6X+DRokrPRAF3zQJl1hZUiLXfo2e9MrOt+udOnHCAmi5w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js" integrity="sha512-JnjG+Wt53GspUQXQhc+c4j8SBERsgJAoHeehagKHlxQN+MtCCmFDghX9/AcbkkNRZptyZU4zC8utK59M5L45Iw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>



  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

</head>

<body class="bg-[#1e1f22] text-white min-h-screen flex">

  <!-- Sidebar -->
  <aside class="w-60 bg-[#232428] flex flex-col p-3 border-r border-gray-800">
    <h2 class="text-lg font-semibold px-2 mb-4">DHVCON</h2>

    <nav class="flex flex-col gap-1">
      <a href="#" class="flex items-center gap-3 px-3 py-2 bg-[#3c3f44] rounded-md">
        <span class="material-icons-outlined text-xl text-gray-300">groups</span>
        <span>Create Room</span>
      </a>

      <hr class="my-3 border-gray-600">

      <h2 class="text-sm text-gray-400 px-3 mb-1 uppercase tracking-wide">Rooms</h2>

      <a href="room.php" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-[#3c3f44] transition">
        <span>Room 101</span>
      </a>

      <a href="room.php" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-[#3c3f44] transition">
        <span>Room 102</span>
      </a>
    </nav>

    <!-- User Section -->
    <div class="mt-auto pt-4 border-t border-gray-700 relative">
      <div class="flex items-center gap-3 px-3 py-2">
        <div class="w-8 h-8 rounded-full bg-green-600"></div>
        <div>
          <p class="text-sm font-medium">joshua padilla</p>
          <p class="text-xs text-gray-400">Online</p>
        </div>
        <span id="settings-btn" class="material-icons-outlined text-gray-400 text-xl cursor-pointer ml-auto hover:text-white">settings</span>
      </div>

      <!-- Dropdown Menu -->
     <div id="settings-menu" class="hidden absolute bottom-14 left-0 right-0 bg-[#2b2d31] border-t border-gray-700 shadow-lg">
        <a href="settings" class="block px-4 py-2 text-sm hover:bg-[#3c3f44] transition">Settings</a>
        <a href="logout.php" class="block px-4 py-2 text-sm hover:bg-[#3c3f44] transition text-red-400">Logout</a>
        </div>
    </div>
  </aside>

  <script>
    $(document).ready(function () {
      $("#settings-btn").on("click", function () {
        $("#settings-menu").toggleClass("hidden");
      });

      // Hide dropdown when clicking outside
      $(document).on("click", function (e) {
        if (!$(e.target).closest("#settings-btn, #settings-menu").length) {
          $("#settings-menu").addClass("hidden");
        }
      });
    });
  </script>
