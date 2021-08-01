<?php if (empty($_POST)) {
    include "connect.php";
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="scripts.js"></script>
    </head>

    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">We Love Test</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="index.php">Projectlistázás</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="create.php">Szerkeztés/Létrehozás</a>
                        </li>
                        <li>
                            <form method="POST" class="d-flex">
                                <select class="form-select" id="fSelect" aria-label="Default select example" name="select1">
                                    <option selected>Szűrés(Nem müködik)</option>
                                    <?php
                                    $sql = mysqli_query($conn, "SELECT * FROM `statuses`");
                                    while ($data = $sql->fetch_assoc()) {
                                        echo "<option id='optionSelect' data-stid =" . $data["id"] . " value=" . $data["key"] . ">" . $data["name"] . "</option>";
                                    }
                                    ?>
                                </select>
                                <input type="submit" id="filterButton" value="OK">
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div id="alertContainer"></div>
    </body>

    </html>

<?php } ?>