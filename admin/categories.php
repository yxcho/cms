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
                        <small>Author</small>
                    </h1>
                    <div class="col-xs-6">



                        <?php
                        insert_category();

                        ?>
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="cat-title" class="">Add category</label>
                                <input type="text" class="form-control" name="cat_title">
                            </div>
                            <div class="form-group">
                                <input class="btn btn-primary" type="submit" name="submit" value="Add category">
                            </div>
                        </form>



                        <!-- edit category -->
                        <?php if (isset($_GET['edit'])) {
                            $cat_id = $_GET['edit'];
                            include "includes/update_categories.php";
                        }
                        ?>


                    </div>
                    <div class="col-xs-6">

                        <?php
                        $query = "SELECT * FROM categories ";
                        $select_categories = mysqli_query($connection, $query);

                        ?>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Category Title</th>
                                </tr>
                            </thead>
                            <tbody>



                                <?php
                                // find all categories
                                findAllCategories();
                                ?>

                                <?php
                                // delete query
                                deleteCategory();
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.row -->

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->
    <?php include "includes/admin_footer.php"; ?>