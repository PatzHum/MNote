<?php
    require_once "db.php";
    if (isset($_POST["rid"]) && isset($_POST["raw"])){
        update_instance($_POST["rid"],$_POST["raw"]);
        header("Location: ../index.php?r=" . $_POST["rid"]);   
    }else if (isset($_POST["raw"])){
        $rid = create_instance($_POST["raw"]);
        header("Location: ../index.php?r=" . $rid);   
    }
?>
