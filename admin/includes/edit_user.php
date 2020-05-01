<?php


if (isset($_GET['edit_user'])) {
    $the_user_id = $_GET['edit_user'];

    $query = "SELECT * FROM users WHERE user_id= $the_user_id";
    $select_users_query = mysqli_query($connection, $query);

    while ($row = mysqli_fetch_assoc($select_users_query)) {
        $user_id = $row['user_id'];
        $username = $row['username'];
        $user_password = $row['user_password'];
        $user_firstname = $row['user_firstname'];
        $user_lastname = $row['user_lastname'];
        $user_email = $row['user_email'];
        $user_image = $row['user_image'];
        $user_role = $row['user_role'];
    }


    if (isset($_POST['edit_user'])) {

        $user_firstname  = ($_POST['user_firstname']);
        $user_lastname       = ($_POST['user_lastname']);
        $user_role       = ($_POST['user_role']);
        $username         = ($_POST['username']);
        $user_email         = ($_POST['user_email']);
        $user_password      = ($_POST['user_password']);
        $post_date         = (date('d-m-y'));

        // $post_image        = ($_FILES['image']['name']);
        // $post_image_temp   = ($_FILES['image']['tmp_name']);
        // $post_comment_count = 4;

        // move_uploaded_file($post_image_temp, "../images/$post_image");

        // get randSalt to encrypt the password entered below
        // $query = "SELECT randSalt FROM users";
        // $select_randsalt_query = mysqli_query($connection, $query);
        // if (!$select_randsalt_query) {
        //     die("QUERY FAILED" . mysqli_error($connection));
        // }

        // $row = mysqli_fetch_array($select_randsalt_query);
        // $salt = $row['randSalt'];
        // $hashed_password = crypt($user_password, $salt);


        if (!empty($user_password)) {
            $query_old_password = "SELECT user_password FROM users WHERE user_id = $the_user_id";
            $get_user_query = mysqli_query($connection, $query_old_password);
            confirmQuery($get_user_query);

            $row = mysqli_fetch_array($get_user_query);
            $db_user_old_password = $row['user_password'];

            if ($db_user_old_password != $user_password) {
                $hashed_password = password_hash($user_password, PASSWORD_BCRYPT, array('cost' => 12));
            }



            $query = "UPDATE users SET ";
            $query .= "user_firstname = '{$user_firstname}', ";
            $query .= "user_lastname = '{$user_lastname}', ";
            $query .= "user_role = '{$user_role}', ";
            $query .= "username = '{$username}', ";
            $query .= "user_email = '{$user_email}', ";
            // send in hashed_password
            $query .= "user_password = '{$hashed_password}' ";
            $query .= "WHERE user_id = {$the_user_id} ";


            $edit_user_query = mysqli_query($connection, $query);


            confirmQuery($edit_user_query);

            //   $the_post_id = mysqli_insert_id($connection);


            echo "<p class='bg-success'>User updated. <a href='users.php'>View users </a></p>";
        }
    }
} else {
    // if no user_id is given, redirect back 
    header("Location:index.php");
}


?>

<form action="" method="post" enctype="multipart/form-data">


    <div class="form-group">
        <label for="title">First name</label>
        <input value="<?php echo $user_firstname; ?>" type="text" class="form-control" name="user_firstname">
    </div>
    <div class="form-group">
        <label for="title">Last name</label>
        <input value="<?php echo $user_lastname; ?>" type="text" class="form-control" name="user_lastname">
    </div>

    <div class="form-group">
        <select name="user_role" id="">
            <option value="<?php echo $user_role; ?>"><?php echo $user_role; ?></option>

            <?php
            if ($user_role == 'admin') {
                echo "<option value='subsriber'>Subscriber</option>";
            } else {
                echo "<option value='admin'>Admin</option>";
            }
            ?>
        </select>
    </div>







    <!-- <div class="form-group">
        <label for="post_image">Post Image</label>
        <input type="file" name="image">
    </div> -->

    <div class="form-group">
        <label for="post_tags">Username</label>
        <input value="<?php echo $username; ?>" type="text" class="form-control" name="username">
    </div>

    <div class="form-group">
        <label for="post_tags">Email</label>
        <input value="<?php echo $user_email; ?>" type="email" class="form-control" name="user_email">
    </div>

    <div class="form-group">
        <label for="post_tags">Password</label>
        <input autocomplete="off" type="password" class="form-control" name="user_password">
    </div>


    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="edit_user" value="Edit user">
    </div>


</form>