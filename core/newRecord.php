<?php
include "../main/dbcon.php";
session_start();
if (isset($_POST['add'])) {
    $recordName = $_POST['title'];
    $recordName = (string)$recordName;
    $recordName = htmlspecialchars($recordName, ENT_QUOTES);
    $recordCount = $_POST['quantity'];
    $recordIsbn = $_POST['isbn'];
    $recordClass = $_POST['class'];
    $recordClass = htmlspecialchars($recordClass, ENT_QUOTES);

    $user_id = $_SESSION['uid'];
    $key = time();
    $key = hash('sha1', $key);
    //prepared statement
    try {
        $a = "SELECT SUM((acession.accession_stop - acession.accession_start)+1) FROM acession INNER JOIN record on record.record_isbn like acession.record_isbn and record.c_key like acession.c_key WHERE record.record_isbn LIKE '$recordIsbn'";
        $q = $connection->query($a);
        $r = $q->fetch_array();
        $r = $r[0];
        if ($r > 0) {
            $i = $r + 1;
            header("Location: ../main/index?sup=error");
        } else {
           $i = 1;
//        }

            $stmet = "INSERT INTO record (record_isbn,record_name, class_number, quantity, pid,c_key) VALUES ('$recordIsbn','$recordName','$recordClass','$recordCount','$user_id','$key')";
            $result = $connection->query($stmet);
            $query = $connection->query("select accession_last_value from accession_control order by timestamp desc limit 0,1");
            $result = $query->fetch_assoc();
            $last_accession = $result['accession_last_value'];
            $ac_start = $last_accession + 1;
            $ac_stop = $ac_start + $recordCount - 1;
            $stmet = "INSERT INTO acession (record_isbn, accession_start, accession_stop,c_key) VALUES ('$recordIsbn','$ac_start','$ac_stop','$key')";
            $result = $connection->query($stmet);
            header("Location: ../main/gen.php?rid=$recordIsbn&count=$recordCount&la=$ac_stop&last_copy_num=$i");
        }
    } catch (Exception $e) {
        echo $e->getMessage();
        header("Location:../main?err=1");
    }

}