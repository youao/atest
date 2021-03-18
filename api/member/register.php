<?php

$mobile = $_POST['mobile'];
$code = $_POST['code'];
$password = $_POST['password'];

if (empty($mobile) || empty($code) || empty($password)) {
    exitRequestJson('缺少参数');
}

if (strlen($mobile) != 11) {
    exitRequestJson('手机号格式错误');
}

$conn = dbconn();

$member = selectMemberByMobile($mobile);
if (count($member) != 0) {
    $conn->close();
    exitRequestJson('该手机号已注册');
}

$_code = selectCodeByMobile($mobile);
if (empty($_code)) {
    $conn->close();
    exitRequestJson('请先发送验证码');
}
if ($_code != $code) {
    $conn->close();
    exitRequestJson('验证码错误');
}

$result = insertMember();
if ($result === TRUE) {
    exitRequestJson('注册成功', 1);
} else {
    $message = 'Error: ' . $sql . ' ' . $conn->error;
    $conn->close();
    exitRequestJson($message);
}

function selectMemberByMobile($mobile)
{
    global $conn;
    $sql = "SELECT * FROM member WHERE mobile='" . $mobile . "' LIMIT 1";
    $result = $conn->query($sql);

    $data = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}

function selectCodeByMobile($mobile)
{
    global $conn;
    $sql = "SELECT code FROM sms WHERE mobile='" . $mobile . "' and send_type='register' ORDER BY create_time DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        return null;
    }

    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data[0]['code'];
}

function createPassword($password)
{
    global $API_KEY;
    return encryptDecrypt($API_KEY, $password);
}

function insertMember()
{
    global $mobile, $password, $conn;
    $pwd = createPassword($password);
    $_time = date("Y-m-d H:i:s", time());

    $sql = "INSERT INTO member (mobile, pwd, create_time, update_time) VALUES ('" . $mobile . "', '" . $pwd . "', '" . $_time . "', '" . $_time . "')";
    return $conn->query($sql);
}
