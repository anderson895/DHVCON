let userStatusChart, meetingChart;

const initCharts = () => {
  // User Status (Donut)
  userStatusChart = new ApexCharts(document.querySelector("#userStatusChart"), {
    chart: { type: "donut", height: 300, foreColor: "#fff" },
    labels: ["Active", "For Approval", "Disabled"],
    series: [0, 0, 0],
    colors: ["#22c55e", "#facc15", "#ef4444"],
    legend: { position: "bottom", labels: { colors: "#fff" } },
    dataLabels: { style: { colors: ["#fff"] } }
  });
  userStatusChart.render();

  // Meetings Overview (Bar)
  meetingChart = new ApexCharts(document.querySelector("#meetingChart"), {
    chart: { type: "bar", height: 300, foreColor: "#fff" },
    series: [{ name: "Meetings", data: [0, 0] }],
    xaxis: { categories: ["Open", "Closed"] },
    colors: ["#3b82f6", "#6366f1"]
  });
  meetingChart.render();
};

const getDataAnalytics = () => {
  $.ajax({
    url: "../controller/end-points/controller.php",
    method: "GET",
    data: { requestType: "dashboard_analytics" },
    dataType: "json",
    success: function (response) {
      if (response.success) {
        const d = response.data;

        // Update Stats
        $("#total_users").text(d.total_users);
        $("#active_users").text(d.active_users);
        $("#for_approval_users").text(d.for_approval_users);
        $("#disabled_users").text(d.disabled_users);
        $("#total_rooms").text(d.total_rooms);
        $("#active_rooms").text(d.active_rooms);
        $("#total_meetings").text(d.total_meetings);
        $("#open_meetings").text(d.open_meetings);
        $("#closed_meetings").text(d.closed_meetings);
        $("#total_classworks").text(d.total_classworks);
        $("#active_classworks").text(d.active_classworks);
        $("#archived_classworks").text(d.archived_classworks);
        $("#total_submissions").text(d.total_submissions);
        $("#not_submitted").text(d.not_submitted);
        $("#total_claimed_certificates").text(d.total_claimed_certificates);
        $("#total_room_members").text(d.total_room_members);
        $("#total_meeting_logs").text(d.total_meeting_logs);

        // Update Charts
        userStatusChart.updateSeries([
          parseInt(d.active_users),
          parseInt(d.for_approval_users),
          parseInt(d.disabled_users)
        ]);

        meetingChart.updateSeries([
          { name: "Meetings", data: [parseInt(d.open_meetings), parseInt(d.closed_meetings)] }
        ]);
      } else {
        console.error("Failed to load analytics:", response.message);
      }
    },
    error: function (xhr, status, error) {
      console.error("AJAX Error:", status, error);
    }
  });
};

// Initialize
initCharts();
getDataAnalytics();
setInterval(getDataAnalytics, 5000);
