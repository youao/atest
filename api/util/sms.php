<?php

$mobile = $_POST['mobile'];
$type = $_POST['type'];

if (empty($mobile) || empty($type)) {
    exitRequestJson('缺少参数');
}

if (strlen($mobile) != 11) {
    exitRequestJson('手机号格式错误');
}

if ($type == 'register') {
    $member = selectMemberByMobile($mobile);
    if (count($member) != 0) {
        exitRequestJson('该手机号已注册');
    }
    sendSms();
}

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

function sendSms()
{
    global $mobile, $type;

    $code = rand(100000, 999999);
    $_time = date("Y-m-d H:i:s", time());
    $sql = "INSERT INTO sms (code, mobile, send_type, create_time) VALUES ('" . $code . "', '" . $mobile . "', '" . $type . "', '" . $_time . "')";

    $conn = dbconn();
    $result = $conn->query($sql);

    if ($result === TRUE) {
        exitRequestJson('验证码发送成功', 1, $code);
    } else {
        $message = 'Error: ' . $sql . ' ' . $conn->error;
        exitRequestJson($message);
    }
    $conn->close();
}
