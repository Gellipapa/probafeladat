<?php
require("header.php");
include "connect.php";
error_reporting(E_ALL);
ini_set('display_errors', 'On');


if (isset($_GET['id'])) {
    $updateID = $_GET['id'];

    if ($updateID > 0) {

        $listStatus = "SELECT name FROM statuses";
        if ($stmt = $conn->prepare($listStatus)) {
            $stmt->execute();
            $result = $stmt->get_result();
            $list = $result;
        }

        $queryUpdateTest = "SELECT projects.id,projects.title,projects.description,owners.name,owners.email,owners.id AS ownerID,statuses.key AS statusKey,statuses.name AS statusName
    FROM projects
    INNER JOIN project_owner_pivot ON project_owner_pivot.project_id = projects.id 
    INNER JOIN project_status_pivot ON project_status_pivot.project_id = project_owner_pivot.project_id
    INNER JOIN owners ON owners.id = project_owner_pivot.owner_id
    INNER JOIN statuses ON statuses.id = project_status_pivot.status_id WHERE projects.id = ?";


        if ($stmt = $conn->prepare($queryUpdateTest)) {
            $stmt->bind_param("i", $updateID);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
?>
                <div class="row d-flex justify-content-center mt-4">
                    <div class="col-sm-6">
                        <form method="POST" action="#" id="updateForm">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Cím</label>
                                <input type="text" class="form-control" id="updateTitle" name="updateTitle" aria-describedby="emailHelp" value="<?php echo $row["title"] ?>">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Leírás</label>
                                <input type="text" class="form-control" id="updateDescription" name="updateDescription" value="<?php echo $row["description"] ?>">
                            </div>

                            <div class="mb-2 mt-2" id="statusSelect">
                                <select class="form-select" aria-label="Default select example">
                                    <option selected value="<?php echo $row["statusKey"] ?>"><?php echo $row["statusName"] ?></option>
                                    <?php
                                    printf($row["statusKey"]);
                                    $sql = mysqli_query($conn, "SELECT * FROM `statuses`");
                                    while ($data = $sql->fetch_assoc()) {
                                        if ($row["statusName"] != $data["name"]) {
                                            echo "<option value=" . $data["key"] . ">" . $data["name"] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Kapcsolattartó neve</label>
                                <input type="text" class="form-control" id="updateContactName" name="updateContactName" aria-describedby="emailHelp" value="<?php echo $row["name"] ?>">
                            </div>

                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Kapcsolattartó email címe</label>
                                <input type="email" class="form-control" id="updateContactEmail" name="updateContactEmail" aria-describedby="emailHelp" value="<?php echo $row["email"] ?>">
                            </div>
                            <input type="hidden" id="ownerID" data-updateID="<?php echo $updateID ?>" value="<?php echo $row["ownerID"] ?>">
                            <input type="hidden" id="updateID" value="<?php echo $updateID ?>">
                            <button id="updateButton" type="submit" class="btn btn-primary">Mentés</button>
                        </form>
                    </div>
                </div>
<?php }
        } else {
            echo "Hiba történt!";
        }
    }
}

if (isset($_POST['update']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $name = $_POST['contactName'];
    $email = $_POST['contactEmail'];
    $owID = $_POST['ownerID'];
    $upID = $_POST['updateID'];

    $statusID;
    if ($_POST['status'] == "in_progress") {
        $statusID = 2;
    } elseif ($_POST['status'] == "todo") {
        $statusID = 1;
    } elseif ($_POST['status'] == "done") {
        $statusID = 3;
    }

    $updateOwners = "UPDATE `owners` SET `name` = ?, `email`= ? WHERE id = ?";
    if ($stmt = $conn->prepare($updateOwners)) {
        $stmt->bind_param("ssi", $name, $email, $owID);
        $stmt->execute();
    }

    $updateProjects = "UPDATE `projects` SET `title` = ?, `description`= ? WHERE id = ?";
    if ($stmt = $conn->prepare($updateProjects)) {
        $stmt->bind_param("ssi", $title, $description, $upID);
        $stmt->execute();
    }

    $updateProjectsStatus = "UPDATE `project_status_pivot` SET `status_id`= ?  WHERE `project_id` = ?";
    if ($stmt = $conn->prepare($updateProjectsStatus)) {
        $stmt->bind_param("ii", $statusID, $upID);
        $stmt->execute();
    }

    echo "ok";
}
