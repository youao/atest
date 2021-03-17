<?php
$SERVER_NAME = "localhost";
$USER_NAME = "youao";
$PASSWORD = "123654";
$DB_NAME = "atest";

$API_KEY = 'ATEST';

$MOD = $_GET['mod'];
$ACT = $_GET['act'];

include $MOD . '/' . $ACT . '.php';


function dbconn()
{
    global $SERVER_NAME, $USER_NAME, $PASSWORD, $DB_NAME;

    $conn = new mysqli($SERVER_NAME, $USER_NAME, $PASSWORD, $DB_NAME);

    if ($conn->connect_error) {
        die("连接失败: " . $conn->connect_error);
    }

    return $conn;
}

// function encryptDecrypt($key, $string, $decrypt)
// {

//     if ($decrypt) {

//         $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($string), MCRYPT_MODE_CBC, md5(md5($key))), "12");

//         return $decrypted;
//     } else {

//         $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key))));

//         return $encrypted;
//     }
// }

function encryptDecrypt($key, $string, $decrypt)
{

    if ($decrypt) { } else {

        return openssl_encrypt($string, 'DES-ECB', $key, 0);
    }
}

function exitRequestJson($message, $status = 0, $data = null)
{
    $result = array(
        'status' => $status,
        'message' => $message
    );
    if (!empty($data)) {
        $result['data'] = $data;
    }
    exit(json_encode($result));
}