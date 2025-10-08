<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/x-icon" href="static/image/favicon.ico">
  <link href="./src/output.css" rel="stylesheet" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/alertify.css" integrity="sha512-MpdEaY2YQ3EokN6lCD6bnWMl5Gwk7RjBbpKLovlrH6X+DRokrPRAF3zQJl1hZUiLXfo2e9MrOt+udOnHCAmi5w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js" integrity="sha512-JnjG+Wt53GspUQXQhc+c4j8SBERsgJAoHeehagKHlxQN+MtCCmFDghX9/AcbkkNRZptyZU4zC8utK59M5L45Iw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>

  <title>DHVCON</title>
</head>
<body class="bg-gradient-to-br from-yellow-50 via-white to-red-50 min-h-screen flex flex-col">

  <!-- Header -->
  <header class="flex justify-between items-center px-8 py-4 bg-gradient-to-r from-yellow-400 via-white to-red-500 shadow-md">
    <!-- Left Side -->
    <div class="flex items-center space-x-2">
      <!-- <span class="material-icons text-red-700 text-3xl">groups</span> -->
         <!-- Logo -->
      <img src="static/image/logo.jpg" alt="Logo" class="h-10 w-auto rounded-full border-2 border-yellow-300 shadow-md" />
      <h1 class="text-xl font-semibold text-gray-900">DHVCON</h1>
    </div>

    <!-- Right Side (Logo + Sign In) -->
    <div class="flex items-center space-x-4">
        <a href="signin">
            <button class="cursor-pointer text-red-700 hover:text-yellow-600 font-medium flex items-center space-x-1">
                <span class="material-icons text-lg">person</span>
                <span>Sign in</span>
            </button>
        </a> 
    </div>
  </header>
