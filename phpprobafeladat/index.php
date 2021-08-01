<?php
require("header.php");
include "connect.php";


if (isset($_GET['page'])) {
    $limit = 10;
    $pageNumber = ($_GET['page'] - 1) * $limit;
    $query = "SELECT owners.name,owners.id AS ownerID,owners.email,projects.title,projects.description,projects.id,statuses.name AS statusName FROM owners
    INNER JOIN project_owner_pivot ON project_owner_pivot.owner_id = owners.id
    INNER JOIN projects ON projects.id = project_owner_pivot.project_id
    INNER JOIN project_status_pivot ON project_status_pivot.project_id = projects.id
    INNER JOIN statuses ON statuses.id = project_status_pivot.status_id LIMIT $pageNumber,$limit";
} else {
    $pageNumber = 0;

    $query = "SELECT owners.name,owners.id AS ownerID,owners.email,projects.title,projects.description,projects.id,statuses.name AS statusName FROM owners
    INNER JOIN project_owner_pivot ON project_owner_pivot.owner_id = owners.id
    INNER JOIN projects ON projects.id = project_owner_pivot.project_id
    INNER JOIN project_status_pivot ON project_status_pivot.project_id = projects.id
    INNER JOIN statuses ON statuses.id = project_status_pivot.status_id";
}

//Nem müködik ez lenne a filter de scopen kivül nem nagyon megy ki a query
if (isset($_POST['statusID']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $stID = intval($_POST['statusID']);

    $query = "SELECT projects.id,projects.title,projects.description,owners.name,owners.email,owners.id AS ownerID,statuses.key AS statusKey,statuses.name AS statusName
    FROM project_status_pivot
    INNER JOIN statuses ON statuses.id = project_status_pivot.status_id
    INNER JOIN projects ON projects.id = project_status_pivot.project_id
    INNER JOIN project_owner_pivot ON project_owner_pivot.project_id = projects.id
    INNER JOIN owners ON owners.id = project_owner_pivot.owner_id
    WHERE project_status_pivot.status_id = $stID";
}
?>

<div class="row d-flex justify-content-center mt-4">
    <div class="col-sm-6">
        <?php
        if ($stmt = $conn->prepare($query)) {
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
        ?>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title d-flex justify-content-between">
                            <h5 class="d-inline"><?php echo $row['title'] ?></h5>
                            <h5 class="float-end"><?php echo $row['statusName'] ?></h5>
                        </h5>
                        <p class="card-text"><?php echo $row['name'] . " " . "(" . $row['email'] . ")" ?></p>
                        <a href="update.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Szerkeztés</a>
                        <a class="btn btn-danger deleteButton" name="delete" data-id="<?php echo $row['id']; ?>" data-ownerid="<?php echo $row['ownerID']; ?>">Törlés</a>
                    </div>
                </div>
            <?php
            }
            ?>
    </div>
</div>
<?php
        } else {
            echo "Hiba történt!";
        }
?>

<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
        <?php

        $sql = "SELECT * FROM projects";
        $res = mysqli_query($conn, $sql);
        $db = mysqli_num_rows($res);
        $paginationCount = ceil($db / 10);

        if ($paginationCount != 1) {
            for ($i = 1; $i <= $paginationCount; $i++) {
        ?>
                <li class="page-item"><a class="page-link" href="index.php?page=<?php echo $i ?>"> <?php echo $i ?></a></li>
        <?php
            }
        }
        ?>
    </ul>
</nav>