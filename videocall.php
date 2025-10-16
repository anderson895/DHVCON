<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>DHVCON | Video Call</title>

  <!-- Tailwind CSS -->
  <link href="../src/output.css" rel="stylesheet" />
  <link href="../src/scrollbar.css" rel="stylesheet" />
  <link href="../src/alertifyconfig.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="../static/image/favicon.ico">

  <!-- ✅ Google Material Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" rel="stylesheet" />

  <!-- Alertify -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/alertify.css" crossorigin="anonymous" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js" crossorigin="anonymous"></script>

  <!-- Jitsi -->
  <script src="https://meet.jit.si/external_api.js"></script>

  <style>
    .material-symbols-rounded {
      font-variation-settings:
        'FILL' 1,
        'wght' 400,
        'GRAD' 0,
        'opsz' 40;
    }
  </style>
</head>

<body class="bg-[#1e1f22] text-white min-h-screen flex">

  <!-- Mobile menu button -->
  <button id="mobile-menu-btn" class="md:hidden absolute top-4 right-4 z-50 p-2 bg-gray-700 rounded-lg h-12 w-12 flex items-center justify-center hover:bg-gray-600 transition">
    <span class="material-symbols-rounded text-white text-3xl">menu</span>
  </button>

  <!-- Sidebar -->
  <aside id="sidebar" class="bg-[#232428] w-64 p-4 border-r border-gray-800 fixed inset-y-0 left-0 transform -translate-x-full md:translate-x-0 transition-transform duration-300 flex flex-col z-40">
    <h2 class="text-xl font-bold px-2 mb-6 tracking-wide flex items-center gap-2">
      <span class="material-symbols-rounded text-indigo-400 text-3xl">videocam</span> DHVCON
    </h2>

    <nav id="roomNav" class="flex flex-col gap-2">
      <!-- All Rooms -->
      <a href="../home/" class="flex items-center gap-3 px-3 py-2 rounded-md text-gray-300 hover:bg-gray-700 hover:text-white transition">
        <span class="material-symbols-rounded text-2xl">groups</span>
        <span>All Rooms</span>
      </a>

      <hr class="my-4 border-gray-700">

      <!-- Created Rooms -->
      <h2 class="text-sm text-gray-400 px-3 mb-1 uppercase tracking-widest">Created Rooms</h2>
      <div id="createdRooms" class="flex flex-col gap-1 text-sm"></div>

      <hr class="my-4 border-gray-700">

      <!-- Joined Rooms -->
      <h2 class="text-sm text-gray-400 px-3 mb-1 uppercase tracking-widest">Joined Rooms</h2>
      <div id="joinedRooms" class="flex flex-col gap-1 text-sm"></div>
    </nav>

    <!-- User Section -->
    <div class="mt-auto pt-4 border-t border-gray-700 relative">
      <div class="flex items-center justify-between px-3 py-2">
        <span class="text-gray-400 text-sm">Settings</span>
        <span id="settings-btn" class="material-symbols-rounded text-gray-400 text-2xl cursor-pointer hover:text-white">settings</span>
      </div>

      <!-- Settings Dropdown -->
      <div id="settings-menu" class="hidden absolute bottom-14 left-0 right-0 bg-[#2b2d31] border-t border-gray-700 shadow-lg rounded-b-md overflow-hidden">
        <a href="logout.php" class="flex items-center gap-2 px-4 py-2 text-sm text-red-400 hover:bg-[#3c3f44] transition">
          <span class="material-symbols-rounded text-base">logout</span>
          Logout
        </a>
      </div>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="flex-1 ml-0 md:ml-64 flex flex-col items-center justify-center p-6 transition-all duration-300 w-full">
    <!-- Header -->
    <header class="mb-6 text-center">
      <h1 class="text-3xl font-bold text-indigo-400 mb-2 flex items-center justify-center gap-2">
        <span class="material-symbols-rounded text-4xl text-indigo-400">videocam</span>
        Video Call Room
      </h1>
      <p class="text-gray-300">Welcome, <span class="font-semibold text-indigo-300">Joshua</span> — you're now connected!</p>
    </header>

    <!-- Video Container -->
    <div class="w-full max-w-5xl bg-[#2b2d31] rounded-2xl shadow-xl border border-gray-700 overflow-hidden">
      <div id="meet" class="w-full h-[600px]"></div>
    </div>

    <!-- Footer -->
    <footer class="mt-6 text-gray-500 text-sm">
      Powered by <span class="font-semibold text-indigo-400">Jitsi Meet</span>
    </footer>
  </main>

  <script>
    // Sidebar toggle for mobile
    const menuBtn = document.getElementById("mobile-menu-btn");
    const sidebar = document.getElementById("sidebar");
    menuBtn.addEventListener("click", () => {
      sidebar.classList.toggle("-translate-x-full");
    });

    // Settings dropdown toggle
    const settingsBtn = document.getElementById("settings-btn");
    const settingsMenu = document.getElementById("settings-menu");
    settingsBtn.addEventListener("click", () => {
      settingsMenu.classList.toggle("hidden");
    });

    // Initialize Jitsi Meet
    const domain = "meet.jit.si";
    const options = {
      roomName: "JoshuaDemoRoom123",
      width: "100%",
      height: 600,
      parentNode: document.querySelector("#meet"),
      userInfo: { displayName: "Joshua" }
    };
    const api = new JitsiMeetExternalAPI(domain, options);
  </script>
</body>
</html>
