<?php

// prevents SQL injection
function escape($string)
{
    global $connection;
    return mysqli_real_escape_string($connection, trim(strip_tags($string)));
}


// find out how many users are online
function usersOnline()
{
    global $connection;
    if (isset($_GET['onlineusers'])) {

        // confusion
        if (!$connection) {
            session_start();
            include("../includes/db.php");




            // every time we start a session, session_id() will catch the id of the sesssion
            //  if you open with chrome, there will be a session id; open with safari, another session id
            $session = session_id();
            $time = time();
            $time_out_in_seconds = 300;
            $timeout = $time - $time_out_in_seconds;

            $query = "SELECT * FROM users_online WHERE session = '$session'";
            $send_query = mysqli_query($connection, $query);
            $count = mysqli_num_rows($send_query);

            // if nobody is online, num_rows returns 0
            if ($count == NULL) {
                $insert_query = "INSERT INTO users_online(session, time) VALUES ('$session','$time')";
                mysqli_query($connection, $insert_query);
            } else {
                $update_users_online_query = "UPDATE users_online SET time = '$time' WHERE session ='$session'";
                mysqli_query($connection, $update_users_online_query);
            }

            $users_online_query = mysqli_query($connection, "SELECT * FROM users_online WHERE time > '$timeout'");
            echo $count_online_users = mysqli_num_rows($users_online_query);
        }
    } // get request isset
}

// call the function
usersOnline();

function confirmQuery($result)
{
    global $connection;
    if (!$result) {
        die("QUERY FAILED " . mysqli_error($connection));
    }
}





function insert_category()
{

    global $connection;
    if (isset($_POST['submit'])) {
        $cat_title = $_POST['cat_title'];
        if ($cat_title == "" || empty($cat_title)) {
            echo "Category field should not be empty";
        } else {

            $statement =
                mysqli_prepare($connection, "INSERT INTO categories(cat_title) VALUES(?)");

            mysqli_stmt_bind_param($statement, "s", $cat_title);
            mysqli_stmt_execute($statement);


            if (!$statement) {
                die("ADD CATEGORY FAILED" . mysqli_error($connection));
            }
        }
        mysqli_stmt_close($statement);
    }
}


function findAllCategories()
{
    global $connection;

    $query = "SELECT * FROM categories ";
    $select_categories = mysqli_query($connection, $query);

    while ($row = mysqli_fetch_assoc($select_categories)) {
        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];
        echo "<tr>";
        echo "<td>{$cat_id}</td>";
        echo "<td>{$cat_title}</td>";
        echo "<td><a href='categories.php?delete={$cat_id}'>Delete</a></td>";
        echo "<td><a href='categories.php?edit={$cat_id}'>Edit</a></td>";
        echo "</tr>";
    }
}


function deleteCategory()
{
    global $connection;


    if (isset(($_GET['delete']))) {
        $cat_id_to_delete = $_GET['delete'];
        $query = "DELETE FROM categories WHERE cat_id={$cat_id_to_delete}";

        $delete_query = mysqli_query($connection, $query);
        header("Location:categories.php");
    }
}


// count the number of rows in a selected query
function recordCount($table)
{
    global $connection;
    $query = "SELECT * FROM " . $table;
    $select_all_posts = mysqli_query($connection, $query);
    $result = mysqli_num_rows($select_all_posts);
    confirmQuery($result);
    return $result;
}


function checkStatus($table, $column, $status)
{
    global $connection;

    $query = "SELECT * FROM $table WHERE $column = '$status' ";
    $result = mysqli_query($connection, $query);
    confirmQuery($result);
    return mysqli_num_rows($result);
}


function isAdmin($username)
{
    global $connection;
    $query = "SELECT user_role FROM users WHERE username = '$username'";
    $result = mysqli_query($connection, $query);
    confirmQuery($result);

    $row  = mysqli_fetch_array($result);
    if ($row['user_role'] == 'admin') {
        return true;
    } else {
        return false;
    }
}


function username_exists($username)
{
    global $connection;
    $query = "SELECT username FROM users WHERE username = '$username'";
    $result = mysqli_query($connection, $query);
    confirmQuery($result);

    if (mysqli_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}



function email_exists($email)
{
    global $connection;
    $query = "SELECT user_email FROM users WHERE user_email = '$email'";
    $result = mysqli_query($connection, $query);
    confirmQuery($result);

    if (mysqli_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

function redirect($location)
{
    return header("Location: " . $location);
}


function register_user($username, $email, $password)
{
    global $connection;

    $username = mysqli_real_escape_string($connection, $username);
    $email = mysqli_real_escape_string($connection, $email);
    $password = mysqli_real_escape_string($connection, $password);

    $password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));


    // encrypt password
    // $query = "SELECT randSalt FROM users";
    // $select_randsalt_query  = mysqli_query($connection, $query);

    // if (!$select_randsalt_query) {
    //     die("QUERY FAILED" . mysqli_error($connection));
    // }

    // $row = mysqli_fetch_array($select_randsalt_query);
    // $salt = $row['randSalt'];

    // $password = crypt($password, $salt);


    $query = "INSERT INTO users (username, user_email, user_password, user_role) ";
    $query .= "VALUES ('{$username}', '{$email}','{$password}', 'subscriber')";

    $register_user_query = mysqli_query($connection, $query);
    confirmQuery($register_user_query);
}

function login_user($username, $password)
{
    global $connection;
    $username = trim($username);
    $password = trim($password);

    $username = mysqli_real_escape_string($connection, $username);
    $password = mysqli_real_escape_string($connection, $password);

    $query = "SELECT * FROM users WHERE username='{$username}' ";
    $select_user_query = mysqli_query($connection, $query);

    if (!$select_user_query) {
        die("QUERY FAILED" . mysqli_error($connection));
    }


    while ($row = mysqli_fetch_array($select_user_query)) {
        $db_user_id = $row['user_id'];
        $db_username = $row['username'];
        $db_user_password = $row['user_password'];
        $db_user_firstname = $row['user_firstname'];
        $db_user_lastname = $row['user_lastname'];
        $db_user_role = $row['user_role'];
    }

    // decrypt password
    // $password is the password user types in and db_user_password is the encrypted password in db
    // $password = crypt($password, $db_user_password);

    if (password_verify($password, $db_user_password)) {

        $_SESSION['username'] = $db_username;
        $_SESSION['firstname'] = $db_user_firstname;
        $_SESSION['lastname'] = $db_user_lastname;
        $_SESSION['user_role'] = $db_user_role;
        redirect("/cms/admin");
    } else {
        redirect("/cms/index.php");
    }
}
