<?php
    require("common.php");

    if(!empty($_POST))
    {
        if(empty($_POST['username']))
        {
            die("Please enter a username.");
        }

        if(empty($_POST['password']))
        {
            die("Please enter a password.");
        }

        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
        {
            die("Invalid E-Mail Address");
        }
        $query = "
            SELECT
                1
            FROM users
            WHERE
                username = :username
        ";
        $legit_username = preg_replace('/\s+/', '', $_POST['username']);

        $query_params = array(
            ':username' => $legit_username
        );

        try
        {
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }

        $row = $stmt->fetch();

        if($row)
        {
            die("This username is already in use");
        }

        $query = "
            SELECT
                1
            FROM users
            WHERE
                email = :email
        ";

        $query_params = array(
            ':email' => $_POST['email']
        );

        try
        {
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }

        $row = $stmt->fetch();

        if($row)
        {
            die("This email address is already registered");
        }

        $query = "
            INSERT INTO users (
                username,
                password,
                salt,
                email
            ) VALUES (
                :username,
                :password,
                :salt,
                :email
            )
        ";

        $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));

        $password = hash('sha256', $_POST['password'] . $salt);

        for($round = 0; $round < 65536; $round++)
        {
            $password = hash('sha256', $password . $salt);
        }

        $query_params = array(
            ':username' => $legit_username,
            ':password' => $password,
            ':salt' => $salt,
            ':email' => $_POST['email']
        );

        try
        {
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }

        header("Location: login_page.php?register");
        die("Redirecting to login_page.php?register");
    }

?>
