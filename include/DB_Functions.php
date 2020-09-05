<?php

function storeUser($link, $name, $email, $phone, $password, $city) {
    //$uuid = uniqid('', true);

    $stmt = mysqli_prepare($link, "INSERT INTO user(name, email, phone, password, city, created_at) VALUES(?, ?, ?, ?, ?, NOW())");
    mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $phone, $password, $city);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
     }

    // check for successful store
    if ($result) {
        $stmt = mysqli_prepare($link, "SELECT * FROM user WHERE phone = ?");
        mysqli_stmt_bind_param($stmt, "s", $phone);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
         echo "aaaaaaaaaaaaaaaaaaaa";

        return $user;
    } else {
        echo "ddddddddddddddddd";
        //echo mysqli_stmt_error($stmt);
        return false;
    }
}

/**
 * Get user by phone and password
 */
function getUserByPhoneAndPassword($phone, $password) {
    global $link;
    $stmt = mysqli_prepare($link, "SELECT * FROM user WHERE phone = ?");
    mysqli_stmt_bind_param($stmt, "s", $phone);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        if ($user['password'] == $password) {
            // user authentication details are correct
            return $user;
        }
    } else {
        return NULL;
    }
}

/**
 * Get user by email
 */
function getUserByEmail($email) {

    $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");

    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $user;
    }
}

/**
 * Check user is existed or not
 */
function isUserExisted($link, $phone) {
    $stmt = mysqli_prepare($link, "SELECT phone from user WHERE phone = ?");
    mysqli_stmt_bind_param($stmt, "s", $phone);
    if (mysqli_stmt_execute($stmt)) {
      mysqli_stmt_store_result($stmt);
    }

    if (mysqli_stmt_num_rows($stmt) > 0) {
        // user existed 
        //echo "user exits";
        mysqli_stmt_close($stmt);
        return true;
    } else {      
        // user not existed
        //echo "user not existed";
        $stmt->close();
        return false;
    }
    
}

/**
 * Encrypting password
 * @param password
 * returns salt and encrypted password
 */
function hashSSHA($password) {

    $salt = sha1(rand());
    $salt = substr($salt, 0, 10);
    $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
    $hash = array("salt" => $salt, "encrypted" => $encrypted);
    return $hash;
}

/**
 * Decrypting password
 * @param salt, password
 * returns hash string
 */
function checkhashSSHA($salt, $password) {

    $hash = base64_encode(sha1($password . $salt, true) . $salt);

    return $hash;
}

function getUserBalance($link, $phone){
    $rs = mysqli_query($link, "SELECT * FROM user WHERE phone= '$phone'");
    $user = mysqli_fetch_assoc($rs);
    $balance = $user['balance'];
    return $balance;    
}

?>