<?php
session_start();
include "auth.php";

$db = new auth_class();
$cert = new certificate_class();

if (isset($_SESSION['user_id'], $_GET['meeting_id'], $_GET['meeting_pass'])) {
    $user_id = intval($_SESSION['user_id']);
    $meeting_id = intval($_GET['meeting_id']);
    $meeting_pass = $_GET['meeting_pass'];

    $On_Session = $db->check_account($user_id);

    if (!empty($On_Session)) {
        $certificate_data = $cert->meeting_certificate($meeting_id, $meeting_pass, $user_id);
        if (empty($certificate_data)) {
            header('Location: ../404');
            exit;
        }
    } else {
        header('Location: ../404');
        exit;
    }
} else {
    header('Location: ../404');
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Certificate</title>
  <link href="../src/output.css" rel="stylesheet" />

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <style>
  /* 🧾 Default: Landscape bond paper */
  @page {
    size: landscape;
    margin: 0; /* remove white printer margins */
  }

  @media print {
    html, body {
      margin: 0;
      padding: 0;
      width: 100%;
      height: 100%;
      background: none !important;
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
    }

    body * {
      visibility: hidden;
    }

    #certificateArea, #certificateArea * {
      visibility: visible;
    }

    /* 📄 Fit exactly to full bondpaper area (no white border) */
    #certificateArea {
      position: fixed;
      inset: 0;
      width: 100vw;
      height: 100vh;
      margin: 0;
      transform: none;
      border-radius: 0;
      overflow: hidden;
      box-sizing: border-box;
    }

    #printButton {
      display: none !important;
    }
  }

  @media screen {
    body {
      background-color: #1e1f22;
    }
  }
  </style>
</head>

<body class="bg-[#1e1f22] flex flex-col items-center justify-center min-h-screen text-white font-[Poppins] relative">


  <button id="printButton"
    class="cursor-pointer fixed top-6 right-6 bg-yellow-500 hover:bg-yellow-400 text-black font-semibold px-5 py-2 rounded-lg shadow-lg transition-all flex items-center gap-2 z-50">
    <span class="material-icons-round text-base">print</span> Print Certificate
  </button>

  <!-- 🎓 Certificate Layout -->
  <div id="certificateArea"
    class="bg-[#2b2d31] border-[12px] border-[#3a3d43] shadow-2xl p-12 text-center relative flex flex-col justify-between overflow-hidden w-full h-full">

    <!-- Top Logos -->
    <div class="flex justify-between items-center">
      <img src="../static/image/logo2.png" alt="DHVSU Logo" class="h-20">
      <img src="../static/image/logo1.png" alt="CSS Logo" class="h-20">
    </div>

    <!-- Main Content -->
    <div class="flex flex-col items-center justify-center flex-1">
      <h1 class="text-5xl font-[Playfair_Display] font-bold text-yellow-400 mb-3 tracking-wide">
        Certificate of Completion
      </h1>

      <p class="text-gray-300 text-lg mb-5 italic">
        This certificate is proudly presented to
      </p>

      <h2 class="text-3xl font-[Playfair_Display] text-white font-semibold underline decoration-yellow-500 mb-8">
        <?=$certificate_data[0]['user_fullname']?>
      </h2>

      <p class="text-gray-300 text-base mb-10 leading-relaxed max-w-3xl mx-auto">
        For the successful completion and active participation in the event
        <span class="text-yellow-400 font-medium">
          “<?=ucfirst($certificate_data[0]['meeting_title'])?>”
        </span>.
        Your dedication and enthusiasm have greatly contributed to the success of this program.
      </p>

      <!-- Dates -->
      <div class="flex justify-center gap-20 text-sm text-gray-400 mb-10">
        <div class="text-center">
          <p class="font-medium text-white"><?=date('F d, Y', strtotime($certificate_data[0]['meeting_start']))?></p>
          <p class="text-xs text-gray-400">Start Date</p>
        </div>
        <div class="text-center">
          <p class="font-medium text-white"><?=date('F d, Y', strtotime($certificate_data[0]['meeting_end']))?></p>
          <p class="text-xs text-gray-400">End Date</p>
        </div>
      </div>
    </div>

    <!-- Signatures -->
    <div class="flex justify-between items-center px-16 mb-4">
      <div class="text-center">
        <div class="w-48 h-16 border-b border-gray-500 mx-auto mb-2"></div>
        <p class="font-semibold text-white">Dr. Maria Santos</p>
        <p class="text-gray-400 text-sm">Dean, College of Computing Studies</p>
      </div>
      <div class="text-center">
        <div class="w-48 h-16 border-b border-gray-500 mx-auto mb-2"></div>
        <p class="font-semibold text-white">Mr. Juan Dela Cruz</p>
        <p class="text-gray-400 text-sm">Chairperson, Computer Studies Department</p>
      </div>
    </div>

    <!-- Decorative Frame -->
    <div class="absolute inset-0 border-4 border-yellow-400/40 pointer-events-none"></div>
  </div>

  <script>
    $("#printButton").on("click", function() {
      window.print();
    });
  </script>

</body>
</html>
