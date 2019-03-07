<?php
include "dbcon.php";
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../");
}
$user = $_SESSION['user'];


$query = $connection->prepare("select uid from user where uname like ?");
$query->bind_param('s', $user);
$query->execute();
$query = $query->get_result();
$uid = $query->fetch_assoc();
$_SESSION['uid'] = $uid['uid'];
$class = $_POST['class'];
$title = $_POST['title'];
$isbn = $_POST['isbn'];
$key = $_POST['key'];
$isbn = $_POST['isbn'];
$isbn_old = $_POST['isbn_old'];


$s = "UPDATE record SET record_isbn='$isbn',record_name = '$title', class_number = '$class' WHERE c_key = '$key'";
$q = $connection->query($s);
if ($connection->affected_rows > 0) {
    $s = "UPDATE acession SET record_isbn='$isbn' WHERE c_key = '$key'";
    $q = $connection->query($s);
    if ($connection->affected_rows > 0) {
        $dir = "../barcodes/*";
        foreach (glob($dir) as $file) {
            $filename = explode("/", $file);
            $filename = $filename[2];
            $record = explode("@", $filename);
            $record_number = $record[1];
            $record_id = $record[0];
            $ac_cpy_num = explode(".", $record_number);
            $ac_cpy_num = $ac_cpy_num[0];
            $ac_cpy_num = explode("-", $ac_cpy_num);
            $ac_num = $ac_cpy_num[0];
            $cpy_num = $ac_cpy_num[1];
            $formatted = sprintf("%09d", $ac_num);
            if ($record_id == $isbn_old) {
                rename($file,"../barcodes/".$isbn."@".$ac_num."-".$cpy_num.".png");
                echo $file;
            }

        }

        header("Location: index?success");
    } else {
        header("Location: index?err");
    }
} else {
    header("Location: index?err");
}