<?php
include_once("header.php");
require("connect.php");

if (isset($_POST['title'])) {

    $title = $_POST['title'];
    $description = $_POST['description'];
    $name = $_POST['contactName'];
    $email = $_POST['contactEmail'];
    $statusID;

    $result = $conn->query('SELECT MAX( id ) FROM projects;');
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $count = intval(implode($row)) + 1; //típus konverzió miatt átalakítva int-é
        }
    }

    if ($_POST['status'] == "in_progress") {
        $statusID = 2;
    } elseif ($_POST['status'] == "todo") {
        $statusID = 1;
    } elseif ($_POST['status'] == "done") {
        $statusID = 3;
    }

    //add Owner
    $insertOwners = "INSERT INTO `owners` (`name`,`email`) VALUES (?, ?)";
    if ($stmt = $conn->prepare($insertOwners)) {
        $stmt->bind_param("ss", $name, $email);
        $stmt->execute();
    }

    //project insert new project data
    $insertProjects = "INSERT INTO `projects` (`id`, `title`,`description`) VALUES (?, ?, ?)";
    if ($stmt = $conn->prepare($insertProjects)) {
        $stmt->bind_param("iss", $count, $title, $description);
        $stmt->execute();
        echo $conn->error;
    }

    //check lastID
    $result = $conn->query('SELECT MAX( id ) FROM owners;');
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $lastID = implode($row);
        }
    }

    //project owner pivot insert new project data
    $insertProjectOwnerQuery = "INSERT INTO `project_owner_pivot` (`project_id`, `owner_id`) VALUES (?, ?)";
    if ($stmt = $conn->prepare($insertProjectOwnerQuery)) {
        $stmt->bind_param("ii", $count, $lastID);
        $stmt->execute();
        echo $conn->error;
    }

    //project status pivot insert new project data
    $insertProjectStatusQuery = "INSERT INTO `project_status_pivot` (`project_id`, `status_id`) VALUES (?, ?)";
    if ($stmt = $conn->prepare($insertProjectStatusQuery)) {
        $stmt->bind_param("ii", $count, $statusID);
        $stmt->execute();
        echo $conn->error;
    }
    echo true;
    header("Location:index.php");
} else {
    echo false;
}

?>
<div class="row d-flex justify-content-center mt-4">
    <div class="col-sm-6">
        <form id="createForm" action="create.php" method="POST">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Cím</label>
                <input type="text" class="form-control" id="title" aria-describedby="emailHelp" name="title" required>
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Leírás</label>
                <input type="text" class="form-control" id="description" name="description" required>
            </div>

            <div class="mb-2 mt-2" id="statusSelect">
                <select class="form-select" id="stat" aria-label="Default select example">
                    <option selected value="default">Státusz kiválasztása</option>
                    <?php
                    $sql = mysqli_query($conn, "SELECT * FROM `statuses`");
                    while ($row = $sql->fetch_assoc()) {
                        echo "<option id='statuses' value=" . $row["key"] . ">" . $row["name"] . "</option>";
                    }
                    ?>
                </select>
            </div>


            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Kapcsolattartó neve</label>
                <input type="text" class="form-control" id="contactName" aria-describedby="emailHelp" name="contactName" required>
            </div>

            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Kapcsolattartó email címe</label>
                <input type="email" class="form-control" id="contactEmail" aria-describedby="emailHelp" name="contactEmail" required>
            </div>

            <button id="createButton" type="submit" class="btn btn-primary">Mentés</button>
        </form>
    </div>
</div>