<?php 
include "../src/components/admin/header.php";
if($_GET['pages'] === 'pending'){
    $pageTitle = "All pending Users";
} else if($_GET['pages'] === 'teacher'){
    $pageTitle = "All Teachers";
} else if($_GET['pages'] === 'student'){
    $pageTitle = "All Students";
}else{
    $pageTitle = "Manage All Users";
}
?>

<!-- Top Bar -->
<div class="flex justify-between items-center bg-[#0D0D0D] p-4 mb-6 rounded-md shadow-lg">
  <h2 class="text-xl font-bold text-[#FFD700] capitalize tracking-wide"><?=$pageTitle?></h2>

</div>


<?php 
include "../src/components/admin/footer.php";
?>
<script src="../static/js/admin/manageuser.js"></script>
