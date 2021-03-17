<?php

$mobile = $_POST['mobile'];
$code = $_POST['code'];
$password = $_POST['password'];

if (empty($mobile) || empty($code) || empty($password)) {
    $result = array(
        'status' => 0,
        'message' => '缺少参数'
    );
    exit(json_encode($result));
}

if (strlen($mobile) != 11) {
    $result = array(
        'status' => 0,
        'message' => '手机号格式错误'
    );
    exit(json_encode($result));
}

$member = selectMemberByMobile($mobile);
if (count($member) != 0) {
    $result = array(
        'status' => 0,
        'message' => '该手机号已注册'
    );
    exit(json_encode($result));
}

verifyRegisterCode($mobile);

function selectMemberByMobile($mobile)
{
    $conn = dbconn();
    $sql = "SELECT * FROM member WHERE mobile='" . $mobile . "' LIMIT 1";
    $result = $conn->query($sql);
    $conn->close();

    $data = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}

function verifyRegisterCode($mobile) {
    $conn = dbconn();
    $sql = "SELECT code FROM sms WHERE mobile='" . $mobile . "' ORDER BY create_time
    DESC LIMIT 1";
    $result = $conn->query($sql);
    $conn->close();
   
    if ($result->num_rows == 0) {
        return false;
    }

    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    $result = array(
        'status' => 0,
        'data' => $data,
        'message' => '该手机号已注册'
    );
    exit(json_encode($result));
}
