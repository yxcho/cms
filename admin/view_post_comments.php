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
                        Welcome to comments
                        <small>Author</small>
                    </h1>

                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Author</th>
                                <th>Comment</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>In response to</th>
                                <th>Date</th>
                                <th>Approve</th>
                                <th>Reject</th>
                                <th>Delete</th>
                            </tr>
                        </thead>

                        <tbody>


                            <?php

                            $query = "SELECT * FROM comments WHERE comment_post_id =" . mysqli_real_escape_string($connection, $_GET['id']) . " ";
                            $select_comments = mysqli_query($connection, $query);

                            while ($row = mysqli_fetch_assoc($select_comments)) {
                                $comment_id = $row['comment_id'];
                                $comment_post_id = $row['comment_post_id'];
                                $comment_author = $row['comment_author'];
                                $comment_content = $row['comment_content'];
                                $comment_email = $row['comment_email'];
                                $comment_status = $row['comment_status'];
                                $comment_date = $row['comment_date'];


                                echo "<tr>";
                                echo "<td>$comment_id</td>";
                                echo "<td>$comment_author</td>";
                                echo "<td>$comment_content</td>";

                                // $query = "SELECT * FROM categories WHERE cat_id = {$post_category_id}";
                                // $select_categories_id = mysqli_query($connection, $query);

                                // while ($row = mysqli_fetch_assoc($select_categories_id)) {
                                //     $cat_id = $row['cat_id'];
                                //     $cat_title = $row['cat_title'];
                                //     echo "<td>{$cat_title}</td>";
                                // }

                                echo "<td>$comment_email</td>";
                                echo "<td>$comment_status</td>";


                                $query = "SELECT * FROM posts WHERE post_id = $comment_post_id";
                                $select_post_id_query = mysqli_query($connection, $query);

                                while ($row = mysqli_fetch_assoc($select_post_id_query)) {
                                    $post_id = $row['post_id'];
                                    $post_title = $row['post_title'];
                                    echo "<td><a href='../post.php?p_id=$post_id'>$post_title</a></td>";
                                }









                                echo "<td>$comment_date</td>";

                                echo "<td><a href='comments.php?approve=$comment_id'>Approve</a></td>";
                                echo "<td><a href='comments.php?reject=$comment_id'>Reject</a></td>";
                                echo "<td><a onClick=\"javascript:return confirm(\'Are you sure you want to delete this comment?\')\" href='view_post_comments.php?delete=$comment_id&id=" . $_GET['id'] . "'>Delete</a></td>";
                                echo "</tr>";
                            }
                            ?>


                        </tbody>
                    </table>


                    <?php

                    if (isset($_GET['approve'])) {

                        $the_comment_id = $_GET['approve'];
                        $query = "UPDATE comments SET comment_status= 'approved' WHERE comment_id = $the_comment_id";
                        $approve_comment_query = mysqli_query($connection, $query);
                        header("Location: comments.php");
                    }
                    if (isset($_GET['reject'])) {

                        $the_comment_id = $_GET['reject'];
                        $query = "UPDATE comments SET comment_status= 'rejected' WHERE comment_id = $the_comment_id";
                        $reject_comment_query = mysqli_query($connection, $query);
                        header("Location: comments.php");
                    }




                    if (isset($_GET['delete'])) {

                        $the_comment_id = $_GET['delete'];
                        $query = "DELETE FROM comments WHERE comment_id= {$the_comment_id}";
                        $delete_query = mysqli_query($connection, $query);
                        header("Location: view_post_comments.php?id=" . $_GET['id'] . "");
                    }
                    ?>


                </div>
            </div>
            <!-- /.row -->

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->
    <?php include "includes/admin_footer.php"; ?>