<?php
    include 'connection.php';
try {
    $bs = $_GET['bs'];
    $stmtLehrberufe = $conn->prepare("select a.lb_id, a.lb_kurz from lehrberuf a join bs_lehrberuf b on a.lb_id = b.lb_id where b.bs_id = :bs");
    $stmtLehrberufe->bindParam(':bs', $bs);
    $stmtLehrberufe->execute();
    $result = '<option value="" disabled selected>Lehrberuf</option>';
    while ($row = $stmtLehrberufe->fetch()){
        $result .= "<option value=\"".$row["lb_id"]."\">".$row["lb_kurz"]."</option>";
    }
    echo $result;
}
catch (PDOException $e) {
    echo $e->getMessage();
}
?>