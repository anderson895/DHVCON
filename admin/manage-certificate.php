<?php 
include "../src/components/admin/header.php";


$pageTitle = "Manage Certificates";

?>
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    


<div class="flex justify-between items-center bg-[#0D0D0D] p-4 mb-6 rounded-md shadow-lg">
  <h2 class="text-xl font-bold text-[#FFD700] capitalize tracking-wide"><?=$pageTitle?></h2>
</div>
</head>

<body class="bg-[#1e1f22] items-center justify-center min-h-screen text-white font-[Poppins]">

<div class="p-6 bg-[#0D0D0D] min-h-screen">
        <div class="flex justify-left mt-6 mb-6">
          <button id="openAddSignatoryModal" 
                  class="cursor-pointer bg-yellow-400 hover:bg-yellow-500 transition-colors duration-200 text-black font-semibold rounded-lg px-6 py-3">
            Add New Signatory
          </button>
        </div>



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

            <h2 class="text-3xl font-[Playfair_Display] text-white font-semibold underline decoration-yellow-500 mb-8 capitalize">
            Student Fullname
            </h2>

            <p class="text-gray-300 text-base mb-10 leading-relaxed max-w-3xl mx-auto">
                For the successful completion and active participation in the event
                <span class="text-yellow-400 font-medium">
                “'Meeting title'”
                </span>.
                Your dedication and enthusiasm have greatly contributed to the success of this program.
            </p>

            <!-- Dates -->
            <div class="flex justify-center gap-20 text-sm text-gray-400 mb-10">
                <div class="text-center">
                    <p class="font-medium text-white">
                    Meeting Start Date & Time
                    </p>
                    <p class="text-xs text-gray-400">Start Date</p>
                </div>
                <div class="text-center">
                    <p class="font-medium text-white">
                    Meeting End Date & Time
                    </p>
                    <p class="text-xs text-gray-400">End Date</p>
                </div>
                </div>

            </div>

        <!-- Signatures -->
        <div id="signaturesContainer" class="flex justify-between items-center px-16 mb-4">
            <!-- Signatories will be dynamically loaded via AJAX -->
        </div>


            <!-- Decorative Frame -->
            <div class="absolute inset-0 border-4 border-yellow-400/40 pointer-events-none"></div>



       




        </div>

        

</body>
</html>








<!-- Add Signatory Modal -->
<div id="addSignatoryModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
  <div class="bg-gray-800 rounded-2xl shadow-xl w-full max-w-md p-6 relative">
    <!-- Close button -->
    <button id="closeModal" class="absolute top-3 right-3 text-gray-400 hover:text-white text-xl cursor-pointer">&times;</button>

    <h3 class="text-xl font-semibold text-yellow-400 mb-4 text-center">Add New Signatory</h3>

    <div class="flex flex-col gap-3">
      <div>
        <label for="name" class="block text-gray-300 mb-1 text-sm">Name</label>
        <input type="text" id="name" placeholder="Enter full name" 
               class="w-full p-3 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-yellow-400" />
      </div>

      <div>
        <label for="position" class="block text-gray-300 mb-1 text-sm">Position</label>
        <input type="text" id="position" placeholder="Enter position" 
               class="w-full p-3 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-yellow-400" />
      </div>

      <div>
        <label for="department" class="block text-gray-300 mb-1 text-sm">Department</label>
        <input type="text" id="department" placeholder="Enter department" 
               class="w-full p-3 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-yellow-400" />
      </div>

      <button id="addSignatoryBtn" 
              class="bg-yellow-400 cursor-pointer hover:bg-yellow-500 transition-colors duration-200 text-black font-semibold rounded-lg py-3 mt-3 w-full">
        Add Signatory
      </button>
    </div>
  </div>
</div>










<!-- Edit Signatory Modal -->
<div id="editSignatoryModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
  <div class="bg-gray-800 rounded-2xl shadow-xl w-full max-w-md p-6 relative">
    <!-- Close button -->
    <button id="closeEditModal" class="absolute top-3 right-3 text-gray-400 hover:text-white text-xl cursor-pointer">&times;</button>

    <h3 class="text-xl font-semibold text-yellow-400 mb-4 text-center">Edit Signatory</h3>

    <div class="flex flex-col gap-3">
      <input type="hidden" id="editIndex">
      <div>
        <label for="editName" class="block text-gray-300 mb-1 text-sm">Name</label>
        <input type="text" id="editName" class="w-full p-3 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-yellow-400" />
      </div>

      <div>
        <label for="editPosition" class="block text-gray-300 mb-1 text-sm">Position</label>
        <input type="text" id="editPosition" class="w-full p-3 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-yellow-400" />
      </div>

      <div>
        <label for="editDepartment" class="block text-gray-300 mb-1 text-sm">Department</label>
        <input type="text" id="editDepartment" class="w-full p-3 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-yellow-400" />
      </div>

      <button id="updateSignatoryBtn" 
              class="cursor-pointer bg-yellow-400 hover:bg-yellow-500 transition-colors duration-200 text-black font-semibold rounded-lg py-3 mt-3 w-full">
        Update Signatory
      </button>
    </div>
  </div>
</div>
</div>









<?php include "../src/components/admin/footer.php"; ?>
<script src="../static/js/admin/manage-certificate.js"></script>
