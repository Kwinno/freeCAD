<?php

// MySQL Injection Prevention
function escapestring($value)
{
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_errno) {
        die('Could not connect: ' . $conn->connect_error);
    }
    return strip_tags(mysqli_real_escape_string($conn, $value));
}

// Insert into Database
function dbquery($sql, $returnresult = true)
{
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_errno) {
        error_log('MySQL could not connect: ' . $conn->connect_error);
        return $conn->connect_error;
    }
    $return = array();
    $result = mysqli_query($conn, $sql);
    if ($returnresult) {
        if (mysqli_num_rows($result) != 0) {
            while ($r = $result->fetch_assoc()) {
                array_push($return, $r);
            }
        } else {
            $return = array();
        }
    } else {
        $return = array();
    }
    return $return;
}

// Log Function
function logAction($action, $user) {
    global $time;
    global $us_date;
    dbquery('INSERT INTO logs (action, username, timestamp) VALUES ("' . escapestring($action) . '", "' . escapestring($user) . '", "' . $time . ' ' . $us_date . '")', false);
}

// Login Function
function userLogin($username, $passwordAttempt) {
    global $pdo;
    global $url;

    $sql             = "SELECT user_id, username, password FROM users WHERE username = :username";
    $stmt            = $pdo->prepare($sql);
    $stmt->bindValue(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user === false) {
        header('Location: ' . $url['login'] . '?user=notfound');
        exit();
    } else {
        $validPassword = password_verify($passwordAttempt, $user['password']);
        if ($validPassword) {
            $_SESSION['user_id']   = $user['user_id'];
            $_SESSION['logged_in'] = time();
            //Do some actions if it is the users first login
            $settingsRow = dbquery('SELECT * FROM settings')[0];
                if ($settingsRow['validation_enabled'] == "yes" || "Yes") {
                    if ($user['first_login'] === 0) {
                        dbquery('UPDATE users SET usergroup="Unverified" WHERE user_id='.$user['user_id'])[0];
                    }
                    dbquery('UPDATE users SET first_login="1" WHERE user_id='.$user['user_id'])[0];
                    header('Location: ' . $url['index'] . '?logged=in');
                    exit();
                } else {
                header('Location: ' . $url['index'] . '?logged=in');
                exit();
            }
        } else {
            header('Location: ' . $url['login'] . '?password=invalid');
            exit();
        }
    }
}


// Register Function
function userRegister($username, $email, $pass, $discord) {
    global $pdo;
    global $us_date;
    global $ip;
    global $url;

    //Add any checks (length, etc here....)
    if (strlen($pass) < 6) {
        header('Location: ' . $url['register'] . '?password=short');
        exit();
    } elseif (strlen($pass) > 120) {
        header('Location: ' . $url['register'] . '?password=long');
        exit();
    } elseif (strlen($username) > 36) {
        header('Location: ' . $url['register'] . '?username=long');
        exit();
    }
    //Continue the execution, check if email is taken.
    $sql  = "SELECT COUNT(email) AS num FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row['num'] > 0) {
        header('Location: ' . $url['register'] . '?email=taken');
        exit();
    }
    //Continue the execution, check if username is taken.
    $sql  = "SELECT COUNT(username) AS num FROM users WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':username', $username);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row['num'] > 0) {
        header('Location: ' . $url['register'] . '?username=taken');
        exit();
    }
    if (discordModule_isInstalled) {
        $passwordHash = password_hash($pass, PASSWORD_BCRYPT, array("cost" => 12));
        $sql          = "INSERT INTO users (username, email, password, join_date, join_ip, discord) VALUES (:username, :email, :password, :join_date, :join_ip, :discord)";
        $stmt         = $pdo->prepare($sql);
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password', $passwordHash);
        $stmt->bindValue(':join_date', $us_date);
        $stmt->bindValue(':join_ip', $ip);
        $stmt->bindValue(':discord', $discord);
        $result = $stmt->execute();
        if ($result) {
            //redirect
            header('Location: ' . $url['welcome'] . '');
            exit();
        }
    } else {
      //if everything passes, than continue
      $passwordHash = password_hash($pass, PASSWORD_BCRYPT, array("cost" => 12));
      $sql          = "INSERT INTO users (username, email, password, join_date, join_ip) VALUES (:username, :email, :password, :join_date, :join_ip)";
      $stmt         = $pdo->prepare($sql);
      $stmt->bindValue(':username', $username);
      $stmt->bindValue(':email', $email);
      $stmt->bindValue(':password', $passwordHash);
      $stmt->bindValue(':join_date', $us_date);
      $stmt->bindValue(':join_ip', $ip);
      $result = $stmt->execute();
      if ($result) {
          //redirect
          header('Location: ' . $url['welcome'] . '');
          exit();
      }
    }
}