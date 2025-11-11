<?php 
include "../src/components/home/header.php";
$roomcode = $_GET['code']; 

$meeting = $db->check_meeting($roomcode);
?>

<main class="flex-1 bg-[#1e1f22] ml-0 md:ml-60 p-4 transition-all duration-300 min-h-screen flex flex-col items-center justify-center relative">

    <!-- Background Glow -->
    <div class="absolute inset-0 -z-10 flex items-center justify-center">
        <div class="w-[500px] h-[500px] bg-red-600/30 rounded-full blur-3xl animate-pulse"></div>
    </div>

    <!-- Main Content -->
    <div class="text-center px-6">
        <h2 class="text-2xl sm:text-3xl font-semibold mb-2 text-white">You have been removed from the meeting</h2>
        <p class="text-gray-400 mb-6 max-w-md mx-auto">
            You cannot access this meeting. If you think this is a mistake, contact the host.
        </p>

        <a href="pending_room?code=<?=$roomcode?>" class="inline-block px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors">
            Back to Meeting
        </a>
    </div>

</main>

<?php 
include "../src/components/home/footer.php";
?>
