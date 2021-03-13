<?php
$page = empty($_GET['page']) ? 1 : $_GET['page'];
$pageSize = empty($_GET['pageSize']) ? 10 : $_GET['pageSize'];

$from = ($page - 1) * $pageSize;
$to = $page * $pageSize;

$conn = dbconn();

$sql = "SELECT * FROM liuyanban LIMIT $from, $to";
$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

$result = array(
    'status' => 1,
    'data' => $data,
    'message' => ''
);

echo json_encode($result);
