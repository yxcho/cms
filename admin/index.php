<?php include "includes/admin_header.php"; ?>

<div id="wrapper">




    <!-- Navigation -->
    <?php include "includes/admin_navigation.php"; ?>
    <div id="page-wrapper">

        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        Welcome to admin

                        <small><?php echo $_SESSION['username']; ?></small>
                    </h1>

                </div>
            </div>
            <!-- /.row -->
            <!-- for the four visuals on admin dashboard -->
            <?php include "admin_widgets.php"; ?>

            <?php

            $query = "SELECT * FROM posts WHERE post_status = 'published'";
            $select_all_published_posts = mysqli_query($connection, $query);
            $publish_post_count = mysqli_num_rows($select_all_published_posts);

            $query = "SELECT * FROM posts WHERE post_status = 'draft'";
            $select_all_draft_posts = mysqli_query($connection, $query);
            $draft_post_count = mysqli_num_rows($select_all_draft_posts);


            $query = "SELECT * FROM comments WHERE comment_status = 'rejected'";
            $select_all_rejected_comments = mysqli_query($connection, $query);
            $rejected_comments_count = mysqli_num_rows($select_all_rejected_comments);


            $query = "SELECT * FROM users WHERE user_role = 'subscriber'";
            $select_all_subscribers = mysqli_query($connection, $query);
            $subscriber_count = mysqli_num_rows($select_all_subscribers);

            ?>



            <div class="row">
                <!-- bar charts -->
                <script type="text/javascript">
                    google.charts.load('current', {
                        'packages': ['bar']
                    });
                    google.charts.setOnLoadCallback(drawChart);

                    function drawChart() {
                        var data = google.visualization.arrayToDataTable([
                            ['Data', 'Count'],


                            <?php
                            $element_text = ["All posts", "Active Posts", "Draft Posts", "Comments", "Pending comments", "Users", "Subscribers", "Categories"];
                            $element_count = [$post_count, $publish_post_count, $draft_post_count, $comments_count, $rejected_comments_count, $users_count, $subscriber_count, $categories_count];

                            // the data will look like ['Posts', 1000]
                            for ($i = 0; $i < 8; $i++) {
                                echo "['{$element_text[$i]}'" . ', ' . "{$element_count[$i]}], ";
                            }
                            ?>
                        ]);

                        var options = {
                            chart: {
                                title: '',
                                subtitle: '',
                            }
                        };

                        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

                        chart.draw(data, google.charts.Bar.convertOptions(options));
                    }
                </script>

                <div id="columnchart_material" style="width: auto; height: 500px;"></div>
            </div>
        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->
    <?php include "includes/admin_footer.php"; ?>