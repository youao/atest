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

function encryptDecrypt($key, $data, $decrypt = 0)
{
    if ($decrypt) {
        return openssl_decrypt($data, 'DES-ECB', md5($key));
    } else {
        return openssl_encrypt($data, 'DES-ECB', md5($key));
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

function checkAuth($auth = null, $member = null)
{
    $auth = empty($auth) ? getAuthData() : $auth;
    if (empty($auth['id'])) {
        return false;
    }
    $member = empty($member) ? selectMemberById($auth['id']) : $member;
    foreach ($auth as $key => $value) {
        $v = $member[$key];
        if ($v != $value) {
            return false;
        }
    }
    return $member;
}

function getAuthData()
{
    global $API_KEY;
    $headers = getallheaders();
    $auth = $headers['Authenticate'];
    if (empty($auth)) {
        exitRequestJson('请先登录', 401);
    }

    $str = encryptDecrypt($API_KEY, $auth, 1);

    $dy = md5($API_KEY . '=');
    $and = md5($API_KEY . '&');

    $arr = explode($and, $str);
    $data = array();
    for ($i = 0; $i < count($arr); $i++) {
        $o = explode($dy, $arr[$i]);
        $data[$o[0]] = $o[1];
    }
    return $data;
}

function selectMemberById($id)
{
    $conn = dbconn();
    $sql = "SELECT * FROM member WHERE id=$id LIMIT 1";
    $result = $conn->query($sql);
    $conn->close();

    $data = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data[0];
}
