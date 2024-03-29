<?php

include "delete_modal.php";

if (isset($_POST['checkBoxArray'])) {
    foreach ($_POST['checkBoxArray'] as $checkBoxPostIdValue) {
        $bulk_options = $_POST['bulk_options'];
        switch ($bulk_options) {
            case "published":
                $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id = {$checkBoxPostIdValue} ";

                $update_status_to_published = mysqli_query($connection, $query);
                confirmQuery($update_status_to_published);
                break;

            case "draft":
                $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id = {$checkBoxPostIdValue} ";

                $update_status_to_draft = mysqli_query($connection, $query);
                confirmQuery($update_status_to_draft);
                break;

            case "clone":
                $query = "SELECT * FROM posts WHERE post_id = '{$checkBoxPostIdValue}' ";
                $copy_post_query = mysqli_query($connection, $query);

                while ($row = mysqli_fetch_array(($copy_post_query))) {
                    $post_category_id = $row['post_category_id'];
                    $post_title = $row['post_title'];
                    $post_author = $row['post_author'];
                    $post_user = $row['post_user'];
                    $post_date = $row['post_date'];
                    $post_status = $row['post_status'];
                    $post_image = $row['post_image'];
                    $post_tags = $row['post_tags'];
                    $post_content = $row['post_content'];
                }

                $query = "INSERT INTO posts(post_category_id, post_title, post_author, post_user, post_date, post_status, post_image, post_tags, post_content) ";
                $query .= "VALUES({$post_category_id}, '{$post_title}', '{$post_author}', '{$post_user}', '{$post_date}', '{$post_status}', '{$post_image}', '{$post_tags}', '{$post_content}') ";

                $clone_query = mysqli_query($connection, $query);

                if (!$clone_query) {
                    die("QUERY FAILED" . mysqli_error($connection));
                }

                break;
            case "delete":
                $query = "DELETE FROM posts WHERE post_id = {$checkBoxPostIdValue} ";

                $delete_post = mysqli_query($connection, $query);
                confirmQuery($delete_post);
                break;
        }
    }
}
?>

<form action="" method="post">
    <table class="table table-bordered table-hover">

        <div id="bulkOptionContainer" class="col-xs-4">
            <select class="form-control" name="bulk_options" id="">
                <option value="">Select options</option>
                <option value="published">Publish</option>
                <option value="draft">Draft</option>
                <option value="clone">Clone</option>
                <option value="delete">Delete</option>
            </select>
        </div>
        <div class="col-xs-4">
            <input type="submit" name="submit" value="Apply" class="btn btn-success">
            <a href="posts.php?source=add_post" class="btn btn-primary">Add new</a>
        </div>




        <thead>
            <tr>
                <th><input type="checkbox" id="selectAllBoxes"></th>
                <th>Id</th>
                <th>User</th>
                <th>Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Image</th>
                <th>Tags</th>
                <th>Comments</th>
                <th>Dates</th>
                <th>View post</th>
                <th>Views</th>
                <th>Edit</th>
                <th>Delete</th>

            </tr>
        </thead>

        <tbody>


            <?php


            // Join tables
            $query = "SELECT posts.post_id, posts.post_author,posts.post_user, posts.post_title,posts.post_category_id, posts.post_status, posts.post_image, ";
            $query .= "posts.post_tags, posts.post_comment_count, posts.post_date, posts.post_view_count, ";
            $query .= "categories.cat_id, categories.cat_title FROM posts ";
            $query .= "LEFT JOIN categories ON posts.post_category_id = categories.cat_id ";
            $query .= "ORDER BY posts.post_id DESC";


            $select_posts = mysqli_query($connection, $query);

            if (!$select_posts) {
                die("QUERY FAILED" . mysqli_error($connection));
            }

            while ($row = mysqli_fetch_assoc($select_posts)) {
                $post_id            = $row['post_id'];
                $post_user          = $row['post_user'];
                $post_author        = $row['post_author'];
                $post_title         = $row['post_title'];
                $post_category_id   = $row['post_category_id'];
                $post_status        = $row['post_status'];
                $post_image         = $row['post_image'];
                $post_tags          = $row['post_tags'];
                $post_comment_count = $row['post_comment_count'];
                $post_date          = $row['post_date'];
                $post_view_count    = $row['post_view_count'];
                $category_id        = $row['cat_id'];
                $category_title     = $row['cat_title'];


                echo "<tr>";
            ?>

                <td><input type='checkbox' class='checkBoxes' name='checkBoxArray[]' value="<?php echo $post_id; ?>"></td>

                <?php
                echo "<td>$post_id</td>";

                if (!empty($post_author)) {
                    echo "<td>$post_author</td>";
                } elseif (!empty($post_user)) {
                    echo "<td>$post_user</td>";
                }


                echo "<td>$post_title</td>";



                // $query = "SELECT * FROM categories WHERE cat_id = {$post_category_id}";
                // $select_categories_id = mysqli_query($connection, $query);

                // while ($row = mysqli_fetch_assoc($select_categories_id)) {
                //     $cat_id = $row['cat_id'];
                // $cat_title = $row['cat_title'];
                echo "<td>{$category_title}</td>";
                // }



                echo "<td>$post_status</td>";
                echo "<td><img width='100' src='../images/$post_image' alt='image'> </td>";
                echo "<td>$post_tags</td>";


                // count number of comments addressed to a post
                $query = "SELECT * FROM comments WHERE comment_post_id = $post_id";
                $send_comment_query = mysqli_query($connection, $query);
                $comment_counts = mysqli_num_rows($send_comment_query);

                // to get all the comments related to a post
                $row = mysqli_fetch_array($send_comment_query);
                // $comment_id = $row['comment_id'];

                echo "<td><a href='view_post_comments.php?id=$post_id'>$comment_counts</a></td>";

                echo "<td>$post_date</td>";
                echo "<td><a href='posts.php?resetViews={$post_id}'>$post_view_count</a></td>";
                echo "<td><a class='btn btn-primary' href='../post.php?p_id={$post_id}'>View post</a></td>";
                echo "<td><a class='btn btn-info' href='posts.php?source=edit_post&p_id={$post_id}'>Edit</a></td>";
                ?>
                <!-- change delete post into using a post request button-->
                <form method="post">
                    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                    <?php
                    echo '<td><input class="btn btn-danger" type="submit" name="delete" value = "Delete"></td>'; ?>
                </form>
            <?php
                // echo "<td><a rel='$post_id' href='javascript:void(0)' class='delete_link'>Delete</a></td>";

                // echo "<td><a onClick=\"javascript:return confirm('Are you sure you want to delete?')\" href='posts.php?delete={$post_id}'>Delete</a></td>";
                echo "</tr>";
            }
            ?>


        </tbody>
    </table>
</form>

<?php
if (isset($_POST['delete'])) {

    $the_post_id = $_POST['post_id'];
    $query = "DELETE FROM posts WHERE post_id= {$the_post_id}";
    $delete_query = mysqli_query($connection, $query);
    header("Location:posts.php");
}


if (isset($_GET['resetViews'])) {
    $the_post_id = $_GET['resetViews'];
    $query = "UPDATE posts SET post_view_count = 0 WHERE post_id =" . mysqli_real_escape_string($connection, $_GET['resetViews']) . " ";
    $reset_view_query = mysqli_query($connection, $query);
    header("Location:posts.php");
}
?>

<script>
    $(document).ready(function() {

        // create event
        $(".delete_link").on('click', function() {
            var id = $(this).attr("rel");
            var delete_url = "posts.php?delete=" + id + " ";


            $(".modal_delete_link").attr("href", delete_url);
            $("#myModal").modal("show");
        })
    })
</script>