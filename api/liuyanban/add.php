<?php

$member = checkAuth();
if (!$member) {
    exitRequestJson('登录验证失败', 402);
}

$content = $_POST['content'];

if (empty($content)) {
    exitRequestJson('Error: 请先输入内容！');
}

$_time = date("Y-m-d H:i:s", time());
$author_id = $member['id'];
$sql = "INSERT INTO liuyanban (author_id, content, create_time, update_time) VALUES ($author_id, '" . $content . "', '" . $_time . "', '" . $_time . "')";

$conn = dbconn();
$result = $conn->query($sql);

if ($result === TRUE) {
    exitRequestJson('添加成功');
} else {
    $message = 'Error: ' . $sql . ' ' . $conn->error;
    exitRequestJson($message);
}

$conn->close();
