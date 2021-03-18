<?php

$type = empty($_POST['type']) ? 'password' : $_POST['type'];
$mobile = $_POST['mobile'];
$password = $_POST['password'];

if ($type == 'password') {
    if (empty($mobile) || empty($password)) {
        exitRequestJson('缺少参数');
    }

    if (strlen($mobile) != 11) {
        exitRequestJson('手机号格式错误');
    }

    $result = selectMemberByMobile($mobile);
    if (count($result) == 0) {
        exitRequestJson('该手机号未注册');
    }

    $member = $result[0];
    $_time = date("Y-m-d H:i:s", time());

    $result = updateLastLogTime($member['id'], $_time);
    if (!$result) {
        exitRequestJson('更新时间出错');
    }

    $token = createToken($member, $_time);

    $data = array(
        'token' => $token,
        'member' => $member
    );

    exitRequestJson('登录成功', 1, $data);
}

exitRequestJson('参数出错');

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

function updateLastLogTime($id, $time)
{
    $conn = dbconn();
    $sql = "UPDATE member SET last_log_time='" . $time . "' WHERE id=" . $id;
    $result = $conn->query($sql);
    $conn->close();
    return $result;
}

function createToken($member, $time)
{
    global $API_KEY;
    $dy = md5($API_KEY . '=');
    $and = md5($API_KEY . '&');
    $str = 'id' . $dy . $member['id'] . $and . 'mobile' . $dy . $member['mobile'] . $and . 'pwd' . $dy . $member['pwd'] . $and . 'last_log_time' . $dy . $time;
    return encryptDecrypt($API_KEY, $str, 0);
}
