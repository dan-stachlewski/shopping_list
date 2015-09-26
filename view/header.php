<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Shopping List Application</title>
    </head>
    <body>
        <div id="page">
            <div id="header">
                <h1>Shopping List</h1>
            </div> <!-- END header -->
            <?php if (isset($current_user) && $current_user !== FALSE) : ?>
                <a href="./login/index.php?action=logout">Logout</a> |
                <a href="./login/index.php?action=show_update_form">Update Details</a>
            <?php endif; ?>
            <div id="errors">
                <!-- PART 1: The Errors -->
                <?php if (count($errors) > 0) : ?>
                <h2>Errors</h2>
                    <ul>
                        <?php foreach($errors as $error) : ?>
                            <li><?= $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div> <!-- END errors -->


