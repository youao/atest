<?php
$page = empty($_GET['page']) ? 1 : $_GET['page'];
$pageSize = empty($_GET['pageSize']) ? 10 : $_GET['pageSize'];

$from = ($page - 1) * $pageSize;
$to = $page * $pageSize;

$conn = dbconn();

$sql = "SELECT * FROM liuyanban LIMIT $from, $to";
$result = $conn->query($sql);

$conn->close();

$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

exitRequestJson('', 1, $data);
