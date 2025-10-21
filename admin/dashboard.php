<?php 
include "../src/components/admin/header.php";
?>

<!-- Top Bar -->
<div class="flex justify-between items-center bg-[#0D0D0D] p-4 mb-6 rounded-md shadow-lg">
  <h2 class="text-xl font-bold text-[#FFD700] uppercase tracking-wide">Dashboard</h2>

</div>

<div class="p-6 bg-[#0D0D0D] min-h-screen">
  <h1 class="text-2xl font-bold mb-6 text-[#FFD700] flex items-center space-x-2">
    <span class="material-icons text-[#FFD700]">analytics</span>
    <span>Admin Dashboard</span>
  </h1>

  <!-- Stats Grid -->
 <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

  <!-- Users -->
  <div class="bg-[#1A1A1A] shadow rounded-xl p-4">
    <h3 class="text-lg font-semibold text-[#FFD700] mb-3 flex items-center space-x-2">
      <span class="material-icons">groups</span>
      <span>Users</span>
    </h3>
    <p class="text-gray-400">Total Users</p>
    <h2 class="text-3xl font-bold text-white mb-2" id="total_users">0</h2>
    <div class="text-sm text-gray-400 space-y-1">
      <p>Active: <span class="text-green-400 font-bold" id="active_users">0</span></p>
      <p>For Approval: <span class="text-yellow-400 font-bold" id="for_approval_users">0</span></p>
      <p>Disabled: <span class="text-red-400 font-bold" id="disabled_users">0</span></p>
    </div>
  </div>

  <!-- Rooms -->
  <div class="bg-[#1A1A1A] shadow rounded-xl p-4">
    <h3 class="text-lg font-semibold text-[#FFD700] mb-3 flex items-center space-x-2">
      <span class="material-icons">meeting_room</span>
      <span>Rooms</span>
    </h3>
    <p class="text-gray-400">Total Rooms</p>
    <h2 class="text-3xl font-bold text-white mb-2" id="total_rooms">0</h2>
    <div class="text-sm text-gray-400 space-y-1">
      <p>Active: <span class="text-green-400 font-bold" id="active_rooms">0</span></p>
    </div>
  </div>

  <!-- Classworks -->
  <div class="bg-[#1A1A1A] shadow rounded-xl p-4">
    <h3 class="text-lg font-semibold text-[#FFD700] mb-3 flex items-center space-x-2">
      <span class="material-icons">work_outline</span>
      <span>Classworks</span>
    </h3>
    <p class="text-gray-400">Total Classworks</p>
    <h2 class="text-3xl font-bold text-white mb-2" id="total_classworks">0</h2>
    <div class="text-sm text-gray-400 space-y-1">
      <p>Active: <span class="text-green-400 font-bold" id="active_classworks">0</span></p>
      <p>Archived: <span class="text-gray-400 font-bold" id="archived_classworks">0</span></p>
    </div>
  </div>

  <!-- Meetings -->
  <div class="bg-[#1A1A1A] shadow rounded-xl p-4">
    <h3 class="text-lg font-semibold text-[#FFD700] mb-3 flex items-center space-x-2">
      <span class="material-icons">calendar_month</span>
      <span>Meetings</span>
    </h3>
    <p class="text-gray-400">Total Meetings</p>
    <h2 class="text-3xl font-bold text-white mb-2" id="total_meetings">0</h2>
    <div class="text-sm text-gray-400 space-y-1">
      <p>Open: <span class="text-yellow-400 font-bold" id="open_meetings">0</span></p>
      <p>Closed: <span class="text-blue-400 font-bold" id="closed_meetings">0</span></p>
    </div>
  </div>

  <!-- Submissions -->
  <div class="bg-[#1A1A1A] shadow rounded-xl p-4">
    <h3 class="text-lg font-semibold text-[#FFD700] mb-3 flex items-center space-x-2">
      <span class="material-icons">assignment_turned_in</span>
      <span>Submissions</span>
    </h3>
    <p class="text-gray-400">Total Submitted</p>
    <h2 class="text-3xl font-bold text-white mb-2" id="total_submissions">0</h2>
    <p class="text-sm text-gray-400">Not Submitted: <span class="text-red-400 font-bold" id="not_submitted">0</span></p>
  </div>

  <!-- Certificates -->
  <div class="bg-[#1A1A1A] shadow rounded-xl p-4">
    <h3 class="text-lg font-semibold text-[#FFD700] mb-3 flex items-center space-x-2">
      <span class="material-icons">verified</span>
      <span>Certificates</span>
    </h3>
    <p class="text-gray-400">Claimed Certificates</p>
    <h2 class="text-3xl font-bold text-green-400 mb-2" id="total_claimed_certificates">0</h2>
  </div>

  <!-- Members -->
  <div class="bg-[#1A1A1A] shadow rounded-xl p-4">
    <h3 class="text-lg font-semibold text-[#FFD700] mb-3 flex items-center space-x-2">
      <span class="material-icons">group_add</span>
      <span>Room Members</span>
    </h3>
    <h2 class="text-3xl font-bold text-white mb-2" id="total_room_members">0</h2>
  </div>

  <!-- Meeting Logs -->
  <div class="bg-[#1A1A1A] shadow rounded-xl p-4">
    <h3 class="text-lg font-semibold text-[#FFD700] mb-3 flex items-center space-x-2">
      <span class="material-icons">list_alt</span>
      <span>Meeting Logs</span>
    </h3>
    <h2 class="text-3xl font-bold text-white mb-2" id="total_meeting_logs">0</h2>
  </div>

</div>


  <!-- Charts -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-[#1A1A1A] shadow rounded-xl p-4">
      <h2 class="text-lg font-semibold mb-4 flex items-center space-x-2 text-white">
        <span class="material-icons text-[#FFD700]">pie_chart</span>
        <span>Users by Status</span>
      </h2>
      <div id="userStatusChart"></div>
    </div>
    <div class="bg-[#1A1A1A] shadow rounded-xl p-4">
      <h2 class="text-lg font-semibold mb-4 flex items-center space-x-2 text-white">
        <span class="material-icons text-[#FFD700]">bar_chart</span>
        <span>Meetings Overview</span>
      </h2>
      <div id="meetingChart"></div>
    </div>
  </div>
</div>

<?php 
include "../src/components/admin/footer.php";
?>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="../static/js/admin/dashboard.js"></script>
