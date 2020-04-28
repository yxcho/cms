<?php


if (isset($_POST['create_user'])) {

    $user_firstname  = ($_POST['user_firstname']);
    $user_lastname       = ($_POST['user_lastname']);
    $user_role       = ($_POST['user_role']);
    $username         = ($_POST['username']);
    $user_email         = ($_POST['user_email']);
    $user_password      = ($_POST['user_password']);

    // $post_image        = ($_FILES['image']['name']);
    // $post_image_temp   = ($_FILES['image']['tmp_name']);
    // $post_date         = (date('d-m-y'));
    // $post_comment_count = 4;

    // move_uploaded_file($post_image_temp, "../images/$post_image");


    $query = "INSERT INTO users(user_firstname, user_lastname, user_role, username,user_email,user_password) ";

    $query .= "VALUES('{$user_firstname}','{$user_lastname}','{$user_role}','{$username}','{$user_email}','{$user_password}') ";

    $create_user_query = mysqli_query($connection, $query);


    confirmQuery($create_user_query);


    echo "User created: " . "<a href = 'users.php' >View users</a>";
    //   $the_post_id = mysqli_insert_id($connection);


    //   echo "<p class='bg-success'>Post Created. <a href='../post.php?p_id={$the_post_id}'>View Post </a> or <a href='posts.php'>Edit More Posts</a></p>";



}




?>

<form action="" method="post" enctype="multipart/form-data">


    <div class="form-group">
        <label for="title">First name</label>
        <input type="text" class="form-control" name="user_firstname">
    </div>
    <div class="form-group">
        <label for="title">Last name</label>
        <input type="text" class="form-control" name="user_lastname">
    </div>

    <div class="form-group">
        <select name="user_role" id="">
            <option value="subscriber">Select options</option>
            <option value="admin">Admin</option>
            <option value="subsriber">Subscriber</option>
        </select>
    </div>







    <!-- <div class="form-group">
        <label for="post_image">Post Image</label>
        <input type="file" name="image">
    </div> -->

    <div class="form-group">
        <label for="post_tags">Username</label>
        <input type="text" class="form-control" name="username">
    </div>

    <div class="form-group">
        <label for="post_tags">Email</label>
        <input type="email" class="form-control" name="user_email">
    </div>

    <div class="form-group">
        <label for="post_tags">Password</label>
        <input type="password" class="form-control" name="user_password">
    </div>


    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="create_user" value="Add user">
    </div>


</form>