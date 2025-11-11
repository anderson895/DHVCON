<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/x-icon" href="static/image/favicon.ico">
  <link href="./src/output.css" rel="stylesheet" />
  <link href="src/alertifyconfig.css" rel="stylesheet" />
  <!-- Alertify -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/alertify.css" integrity="sha512-MpdEaY2YQ3EokN6lCD6bnWMl5Gwk7RjBbpKLovlrH6X+DRokrPRAF3zQJl1hZUiLXfo2e9MrOt+udOnHCAmi5w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js" integrity="sha512-JnjG+Wt53GspUQXQhc+c4j8SBERsgJAoHeehagKHlxQN+MtCCmFDghX9/AcbkkNRZptyZU4zC8utK59M5L45Iw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <!-- Google Fonts & Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <title>DHVCON</title>
</head>
<body class="bg-[#121215] text-white min-h-screen flex flex-col font-inter">

  <!-- Header -->
  <header class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 border-b border-gray-700 shadow-lg">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
    
    <!-- Logo sa kaliwa -->
    <a href="admin/../" class="flex items-center space-x-3">
      <img src="static/image/logo1.png" alt="Logo" class="h-10 w-10 rounded-full border-2 border-gray-700 shadow-lg">
      <span class="text-2xl font-bold tracking-wide">DHVCON</span>
    </a>

    <!-- Menu sa kanan -->
    <div class="hidden md:flex items-center space-x-4">
      <a href="signin" class="flex items-center space-x-1 px-4 py-2 text-gray-300 hover:text-white transition">
        <span class="material-icons-outlined text-lg">person</span>
        <span class="font-medium">Sign In</span>
      </a>
      <a href="signup" class="flex items-center space-x-1 px-4 py-2 text-gray-300 hover:text-white transition">
        <span class="material-icons-outlined text-lg">person_add</span>
        <span class="font-medium">Sign Up</span>
      </a>
    </div>

    <!-- Mobile Menu Button (right) -->
    <div class="md:hidden flex items-center">
      <button id="mobile-menu-button" class="text-gray-300 hover:text-white focus:outline-none">
        <span class="material-icons-outlined text-3xl">menu</span>
      </button>
    </div>
  </div>

  <!-- Mobile Menu -->
  <div id="mobile-menu" class="md:hidden hidden px-4 pb-4 space-y-2">
    <a href="signin" class="block px-4 py-2 text-gray-300 hover:text-white rounded transition">Sign In</a>
    <a href="signup" class="block px-4 py-2 text-gray-300 hover:text-white rounded transition">Sign Up</a>
  </div>
</header>


  <script>
    const menuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    menuButton.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
    });
  </script>
