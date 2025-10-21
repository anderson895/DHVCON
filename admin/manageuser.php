<?php 
include "../src/components/admin/header.php";

if($_GET['pages'] === 'pending'){
    $pageTitle = "All Pending Users";
} else if($_GET['pages'] === 'teacher'){
    $pageTitle = "All Teachers";
} else if($_GET['pages'] === 'student'){
    $pageTitle = "All Students";
}else{
    $pageTitle = "Manage All Users";
}
?>

<div class="flex justify-between items-center bg-[#0D0D0D] p-4 mb-6 rounded-md shadow-lg">
  <h2 class="text-xl font-bold text-[#FFD700] capitalize tracking-wide"><?=$pageTitle?></h2>
</div>

<!-- AJAX user table -->
<div class="user-table"></div>

<!-- ✅ Responsive Modal for Viewing User Requirements -->
<div id="requirementsModal" class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-50 p-4">
  <div class="bg-[#1f1f1f] w-full max-w-6xl rounded-xl shadow-2xl text-white border border-gray-700 flex flex-col max-h-[90vh]">
    <!-- Header -->
    <div class="flex justify-between items-center border-b border-gray-700 px-5 py-4">
      <h2 class="text-xl md:text-2xl font-bold text-[#FFD700]">User Requirements</h2>
      <button id="closeModal" class="cursor-pointer text-gray-400 hover:text-white text-3xl leading-none">&times;</button>
    </div>

    <!-- Content -->
    <div id="requirementsList"
      class="p-4 sm:p-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 overflow-y-auto flex-1">
      <!-- JS dynamically injects content here -->
    </div>

    <!-- Footer -->
    <div class="border-t border-gray-700 px-5 py-3 flex justify-end">
      <button id="closeModalFooter"
        class="cursor-pointer bg-red-600 hover:bg-red-700 px-5 py-2 rounded-md text-white font-semibold transition-all">
        Close
      </button>
    </div>
  </div>
</div>


<?php include "../src/components/admin/footer.php"; ?>
<script src="../static/js/admin/manageuser.js"></script>
