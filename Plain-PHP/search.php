<?php
include 'connection.php';

try {
    $stmtBerufe = $conn->prepare("select job_id, job_name from job");
    $stmtBerufe->execute();
} catch (PDOException $e) {
    echo $e->getMessage();
}

if (!empty($_POST)) {
    try {
        if (isset($_POST['job'])) {
            $stmtSearch = $conn->prepare("select pe_vname, pe_nname, job_name from person a join job b on a.job_id = b.job_id and b.job_id = :job where lower(pe_nname) like CONCAT('%',:name,'%')");
            $stmtSearch->bindParam(":job", $_POST["job"]);
        }
        else {
            $stmtSearch = $conn->prepare("select pe_vname, pe_nname, job_name from person a join job b on a.job_id = b.job_id where lower(pe_nname) like CONCAT('%',:name,'%')");
        }
        $stmtSearch->bindParam(":name", $_POST["name"]);
        $stmtSearch->execute();
    } catch (PDOException $e) {
        echo "Request failed: " . ($e->getMessage());
    }
}


include 'header.php';

?>
<body>
<div class="container-scroller">
    <!-- partial:../../partials/_navbar.html -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper justify-content-center">
        <!-- partial -->
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row justify-content-center">
                    <div class="col-lg-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Personensuche</h4>
                                <p class="card-description">
                                <form class="pt-3" action="search.php" method="POST">
                                    <div style="padding-bottom: 10px;">
                                        <input placeholder="Name" class="form-control form-control-lg" name="name"/>
                                    </div>
                                    <div>
                                        <select class="form-control form-control-lg" name="job">
                                            <option class="form-control form-control-lg" value="" disabled selected>
                                                Beruf
                                            </option>
                                            <?php
                                            while ($row = $stmtBerufe->fetch()) {
                                                echo "<option class='form-control form-control-lg option-black' value=\"" . $row["job_id"] . "\">" . $row["job_name"] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mt-3">
                                        <button type="submit"
                                                class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn"
                                                href="../../index.html">Suchen
                                        </button>
                                    </div>
                                </form>
                                </p>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>Vorname</th>
                                            <th>Nachname</th>
                                            <th>Job</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        while ($row = $stmtSearch->fetch()) {
                                            echo "<tr><td>" . $row['pe_vname'] . "</td><td>" . $row['pe_nname'] . "</td><td>".$row['job_name']."</td></tr>";
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
            <!-- partial:../../partials/_footer.html -->
            <footer class="footer">
                <div class="card">
                    <div class="card-body">
                        <div class="d-sm-flex justify-content-center justify-content-sm-between">
                            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2020 <a
                                        href="https://www.bootstrapdash.com/" class="text-muted" target="_blank">Bootstrapdash</a>. All rights reserved.</span>
                            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center text-muted">Free <a
                                        href="https://www.bootstrapdash.com/" class="text-muted" target="_blank">Bootstrap dashboard</a> templates from Bootstrapdash.com</span>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- partial -->
        </div>
        <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
</div>
<!-- container-scroller -->
<!-- base:js -->
<script src="../../vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page-->
<!-- End plugin js for this page-->
<!-- inject:js -->
<script src="../../js/off-canvas.js"></script>
<script src="../../js/hoverable-collapse.js"></script>
<script src="../../js/template.js"></script>
<script src="../../js/settings.js"></script>
<script src="../../js/todolist.js"></script>
<!-- endinject -->
<!-- Custom js for this page-->
<!-- End custom js for this page-->
</body>
</html>

