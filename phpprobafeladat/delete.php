<?php

include "connect.php";

if (isset($_POST['removeID']) && $_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = $_POST['removeID'];
    $ownerID = $_POST['ownerID'];

    $deleteOwnerPivot_query = "DELETE FROM project_owner_pivot WHERE project_id = ?";
    if ($stmt = $conn->prepare($deleteOwnerPivot_query)) {
        $stmt->bind_param("s", $id);
        $stmt->execute();
    } else {
        echo "Hiba történt!";
    }

    $deleteStatusPivot_query = "DELETE FROM project_status_pivot WHERE project_id = ?";
    if ($stmt = $conn->prepare($deleteStatusPivot_query)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
    } else {
        echo "Hiba történt!";
    }

    $deleteProjects_query = "DELETE FROM projects WHERE id = ?";
    if ($stmt = $conn->prepare($deleteProjects_query)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
    } else {
        echo "Hiba történt!";
    }

    $deleteOwners_query = "DELETE FROM owners WHERE id = ?";
    if ($stmt = $conn->prepare($deleteOwners_query)) {
        $stmt->bind_param("i", $ownerID);
        $stmt->execute();
        header("Location:index.php");
    } else {
        echo "Hiba történt!";
    }
}
