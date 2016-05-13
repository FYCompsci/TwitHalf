<?php
    // quick register function, similar to previous ideas.
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
        $legit_username = preg_replace('/[^a-zA-Z0-9]+/', '', $_POST['username']); // regex to ensure that only alphanumeric is in the username

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

        $query = "
            INSERT INTO info (
                username,
                admin,
                following,
                followers,
                bio
            ) VALUES (
                :username,
                :admin,
                :following,
                :followers,
                :bio
            )
        ";

        $query_params = array(
            ':username' => $legit_username,
            ':admin' => 0,
            ':following' => $legit_username,
            ':followers' => 0,
            ':bio' => "Hey, it's ".$legit_username."!"
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
