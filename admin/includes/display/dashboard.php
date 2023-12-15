<?php require("widgets.php"); ?>



<div class="align-self-center" id="columnchart_material" style="width: auto; height: 500px;">

</div>

<!-- Create Google Chart -->
<script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Totals', 'Approved', 'Pending', 'Rejected'],

          ['Posts', <?php echo "" . countTotal("posts", "post_status_id = 4") . "," . countTotal("posts", "post_status_id = 2") . "," . countTotal("posts", "post_status_id = 3") ?>],

          ['Comments', <?php echo "" . countTotal("comments", "comment_status_id = 4") . "," . countTotal("comments", "comment_status_id = 2") . "," . countTotal("comments", "comment_status_id = 3") ?>],

          ['Users', <?php echo "" . countTotal("users", "user_status_id = 4") . "," . countTotal("users", "user_status_id = 2") . "," . countTotal("users", "user_status_id = 3") ?>],

          ['Categories', <?php echo "" . countTotal("categories"); ?>, 0, 0],
        ]);

        var options = {
          chart: {
            title: 'Website Perfomance',
            subtitle: 'Total posts, comments, users and categories used across the website.',
          }}

        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    </script>
