<?php

$type = empty($_POST['type']) ? 'password' : $_POST['type'];
$mobile = $_POST['mobile'];
$password = $_POST['password'];

if ($type == 'password') {
    if (empty($mobile) || empty($password)) {
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
    if (count($member) == 0) {
        $result = array(
            'status' => 401,
            'message' => '该手机号未注册'
        );
        exit(json_encode($result));
    }

    $result = array(
        'status' => 0,
        'data' => $member,
        'message' => '参数出错'
    );
    exit(json_encode($result));
}

$result = array(
    'status' => 0,
    'message' => '参数出错'
);
exit(json_encode($result));

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
