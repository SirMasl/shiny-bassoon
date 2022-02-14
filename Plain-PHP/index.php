<?php
include "connection.php";

try {
    $stmt = $conn->prepare("select vname, nname from person");
    $stmt->execute();
} catch (PDOException $e) {
//    echo $e->getMessage();
}

if (!empty($_POST)) {
    try {
        if (isset($_POST['vname'])) {
            $stmtSearch = $conn->prepare("select vname, nname from person where lower(vname) like CONCAT('%',:vname,'%')");
            $stmtSearch->bindParam(":vname", $_POST["vname"]);
            $stmtSearch->execute();
        }
    } catch (PDOException $e) {
        echo "Request failed: " . ($e->getMessage());
    }
}

include "header.php";
?>

<body>

<nav class="navbar navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Navbar w/ text</a>
    <ul class="nav nav-pills">
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Active</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Link</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Link</a>
        </li>
        <li class="nav-item">
            <a class="nav-link disabled">Disabled</a>
        </li>
    </ul>
</nav>
<div class="container-fluid">
    <div class=" my-form-container">
        <h4 class="margin-top-10">Anmeldung</h4>
        <form action="#" onsubmit="addPerson();return false" id="form">
            <input class="form-control margin-top-10" type="text" id="firstname"
                   name="firstname" placeholder="Vorname">
            <input class="form-control margin-top-10" type="text" id="lastname"
                   name="lastname" placeholder="Nachname">
            <div class="radio margin-top-10">
                <label class="form-check-label text-muted">
                    <input class="form-check-input" type="radio" name="job" value="3">
                    Mama
                </label>
                <label class="radio-middle form-check-label text-muted">
                    <input class="form-check-input" type="radio" name="job" value="2">
                    Papa
                </label>
                <label class="form-check-label text-muted">
                    <input class="form-check-input" type="radio" name="job" value="1">
                    Auto
                </label>
            </div>

            <div class="row margin-top-10">
                <div class="col-6">
                    <select class="form-select" id="plz" name="plz"
                            onchange="const aaa = () => {onChangePLZ()}; aaa()">
                        <option value="" disabled selected>
                            PLZ
                        </option>
                        <!--                --><?php
                        //                while ($row = $stmt->fetch()) {
                        //                    echo "<option value=\"" . $row["id"] . "\">" . $row["zusatz"] . " " . $row["ort"] . "</option>";
                        //                }
                        //                ?>
                    </select>
                </div>
                <div class="col-6">
                    <select class="form-select" id="ort" name="ort">
                        <option value="0">Ort</option>
                    </select>
                </div>
            </div>
            <div class="text-center margin-top-20">
                <button class="btn btn-outline-primary form-button" type="submit">Speichern</button>
            </div>
        </form>

        <div class="margin-top-50">
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th>Column 1</th>
                    <th>Column 2</th>
                    <th>Column 3</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Row 1/1</td>
                    <td>Row 1/2</td>
                    <td>Row 1/3</td>
                </tr>
                <tr>
                    <td>Row 2/1</td>
                    <td>Row 2/2</td>
                    <td>Row 2/3</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<footer>
</footer>
</div>

<script type="text/javascript">
    const onChangePLZ = () => {
        let getLehrberufAjax = new XMLHttpRequest();
        getLehrberufAjax.open('GET', '/lehrberufe.php?bs='.concat(document.getElementById('schools').value), true);
        getLehrberufAjax.onreadystatechange = () => {
            if (getLehrberufAjax.status !== 200) {
                console.log('error ', getLehrberufAjax.statusText);
                return;
            }
            document.getElementById('lehrberufe').innerHTML = getLehrberufAjax.responseText;
        }
        getLehrberufAjax.send(null);
    }

    const addPerson = (overwritePerson = false) => {
        let addPersonAjax = new XMLHttpRequest();
        addPersonAjax.open("POST", "/addPerson.php", true);
        addPersonAjax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        addPersonAjax.onreadystatechange = () => {
            if (addPersonAjax.status !== 200) {
                console.log('error ', addPersonAjax.statusText);
                return null;
            }

            if (addPersonAjax.responseText === "-1" && !overwritePerson) {
                if (confirm('Person already exists. Save anyways?')) {
                    console.log('Thing was saved to the database.');
                    addPerson(true);
                } else {
                    console.log('Thing was not saved to the database.');
                    return;
                }
            }
        }
        let firstname = document.getElementById('firstname').value;
        let lastname = document.getElementById('lastname').value;
        let job = document.querySelector('input[name="job"]:checked').value;
        let school = document.getElementById('schools').value;
        let lehrberuf = document.getElementById('lehrberufe').value;
        // console.log('what', "value=".concat(JSON.stringify({firstname, lastname, job, school, lehrberuf, overwritePerson})));
        addPersonAjax.send("value=".concat(JSON.stringify({
            firstname,
            lastname,
            job,
            school,
            lehrberuf,
            overwritePerson
        })));
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>

</body>

</html>