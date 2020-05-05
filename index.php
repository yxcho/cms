<?php
include "includes/header.php";
include "includes/db.php";
?>
<!-- Navigation -->
<?php include "includes/navigation.php"; ?>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Blog Entries Column -->
        <div class="col-md-8">
            <?php

            $post_per_page = 5;

            if (isset($_GET['page'])) {
                $page = $_GET['page'];
            } else {
                $page = '';
            }

            if ($page == "" || $page == 1) {
                $page_1 = 0;
            } else {
                // lets say its at third page (3*5)-5 = 10
                $page_1 = ($page * $post_per_page) - $post_per_page;
            }


            // let admin see every post, including the draft ones 
            if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == "admin") {
                $post_count_query = "SELECT * FROM posts";
            } else {
                $post_count_query = "SELECT * FROM posts WHERE post_status = 'published'";

            }


            // count total number of posts, to do pagination
            $post_count = mysqli_query($connection, $post_count_query);
            $total_post_count = mysqli_num_rows($post_count);

            // if there are no 'published' posts
            if ($total_post_count < 1) {
                echo "<h1 class='text-center'>There is no post available</h1>";
            } else {
                $page_needed = ceil($total_post_count / $post_per_page);

                // With two arguments, the first argument specifies the offset of the first row to return, and the second specifies the maximum number of rows to return. The offset of the initial row is 0 (not 1)
                $query = "SELECT * FROM posts LIMIT $page_1, $post_per_page";
                $select_all_posts_query = mysqli_query($connection, $query);

                while ($row = mysqli_fetch_assoc($select_all_posts_query)) {
                    $post_id = $row['post_id'];
                    $post_title = $row['post_title'];
                    $post_author = $row['post_user'];
                    $post_date = $row['post_date'];
                    $post_image = $row['post_image'];
                    $post_content = substr($row['post_content'], 0, 100);
                    $post_status = $row['post_status'];


            ?>
                    <h1 class="page-header">
                        Page Heading
                        <small>Secondary Text</small>
                    </h1>

                    <!-- First Blog Post -->
                    <h2>
                        <a href="post.php?p_id=<?php echo $post_id; ?>"><?php echo $post_title; ?></a>
                    </h2>
                    <p class="lead">
                        by <a href="author_post.php?author=<?php echo $post_author; ?>&p_id=<?php echo $post_id; ?>"><?php echo $post_author; ?></a>
                    </p>
                    <p><span class="glyphicon glyphicon-time"></span><?php echo $post_date; ?></p>
                    <hr>
                    <a href="post.php?p_id=<?php echo $post_id; ?>">
                        <img class="img-responsive" src="images/<?php echo $post_image; ?>" alt=""></a>
                    <hr>
                    <p><?php echo $post_content; ?></p>
                    <a class="btn btn-primary" href="post.php?p_id=<?php echo $post_id; ?>">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>

                    <hr>

            <?php
                }
            }

            ?>



        </div>

        <!-- Blog Sidebar Widgets Column -->
        <?php include "includes/sidebar.php"; ?>

    </div>
    <!-- /.row -->

    <hr>

    <ul class="pager">
        <?php
        for ($i = 1; $i <= $page_needed; $i++) {
            // to style the page number for the current page
            if ($i == $page) {
                echo "<li><a class='active_link' href='index.php?page={$i}'>{$i}</a></li>";
            } else {
                echo "<li><a href='index.php?page={$i}'>{$i}</a></li>";
            }
        }

        ?>


    </ul>



    <?php include "includes/footer.php"; ?>