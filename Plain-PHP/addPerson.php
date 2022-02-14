<?php
include 'connection.php';

if (!empty($_POST)) {
    $params = json_decode($_POST["value"]);

    try {
        if (!$params->overwritePerson) {
            $stmtPerson = $conn->prepare("select pe_id from person where lower(pe_vname) = lower(:vname) and lower(pe_nname) = lower(:nname)");
            $stmtPerson->bindParam(":vname", $params->firstname);
            $stmtPerson->bindParam(":nname", $params->lastname);
            $stmtPerson->execute();

            $result = $stmtPerson->fetchAll();

            if (count($result) > 0) {
                echo '-1';
                return;
            }
        }

        $stmtAddPerson = $conn->prepare("insert into person (pe_vname, pe_nname, job_id) values(:vname, :nname, :job)");
        $stmtAddPerson->bindParam(":vname", $params->firstname);
        $stmtAddPerson->bindParam(":nname", $params->lastname);
        $stmtAddPerson->bindParam(":job", $params->job);
        $stmtAddPerson->execute();

        $last_id = $conn->lastInsertId();

        $stmtAddPersonToLehrberuf = $conn->prepare("insert into person_lehrberuf (pe_id, bs_id, lb_id, pele_von, pele_bis) values(:person, :school, :lehrberuf, '2021-01-01', '2021-01-31')");
        $stmtAddPersonToLehrberuf->bindParam(":person", $last_id);
        $stmtAddPersonToLehrberuf->bindParam(":school", $params->school);
        $stmtAddPersonToLehrberuf->bindParam(":lehrberuf", $params->lehrberuf);
        $stmtAddPersonToLehrberuf->execute();
    }
    catch (PDOException $e){
        echo "Request failed: ".concat($e->getMessage());
    }
}

?>