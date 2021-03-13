<?php
$content = $_POST['content'];

if (empty($content)) {
    $result = array(
        'status' => 0,
        'message' => 'Error: 请先输入内容！'
    );
    exit(json_encode($result));
}

$_time = date("Y-m-d H:i:s", time());

$sql = "INSERT INTO liuyanban (content, create_time, update_time) VALUES ('" . $content . "', '" . $_time . "', '" . $_time . "')";

$conn = dbconn();

if ($conn->query($sql) === TRUE) {

    $result = array(
        'status' => 1,
        'message' => '添加成功'
    );

    echo json_encode($result);
} else {

    $result = array(
        'status' => 0,
        'message' => 'Error: ' . $sql . ' ' . $conn->error
    );

    echo json_encode($result);
}

$conn->close();
