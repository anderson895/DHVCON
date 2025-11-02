<?php 
include "../src/components/home/header.php";
$roomcode = $_GET['code']; 

$meeting = $db->check_meeting($roomcode);
?>

    <main class="flex-1 bg-[#1e1f22] ml-0 md:ml-60 p-4 transition-all duration-300 min-h-screen flex flex-col">









 





        </div>


    </main>





<?php 
include "../src/components/home/footer.php";
?>