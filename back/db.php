<?php
    require_once "auth.php";
    //Generate database object for MySQL
    function GenDBH($dbname, $username ="root", $password = "user", $hostname = "localhost"){
        $dbh = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
        //Throw exception on error
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbh;
    }
    //Wrapper for PDO for binding string to request
    function BindString(&$stmt, $identifier, $str){
        $stmt->bindParam($identifier, $str, PDO::PARAM_STR);
    }
    function create_instance($raw){
        try{
            $dbh = GenDBH("note");
            $stmt = $dbh->prepare("INSERT INTO instances(raw) VALUES(:raw)");
            BindString($stmt, ":raw", $raw);
            $stmt->execute();
           
            $stmt = $dbh->prepare("SELECT @LAST_UUID");
            $stmt->execute();
            return $stmt->fetchColumn();
        }catch(Exception $e){
        }
    }
    function get_instance_raw($rid){
        try{
            $dbh = GenDBH("note");
            $stmt = $dbh->prepare("SELECT raw FROM instances WHERE rid=:rid");
            BindString($stmt, ":rid", $rid);

            $stmt->execute();
            return $stmt->fetchColumn();
        }catch(Exception $e){
        }
    }
    function update_instance($rid, $raw){
        try{
            $dbh = GenDBH("note");
            $stmt = $dbh->prepare("UPDATE instances SET raw=:raw WHERE rid=:rid");
            BindString($stmt, ":rid", $rid);
            BindString($stmt, ":raw", $raw);

            $stmt->execute();
            return $stmt->fetchColumn();

        }catch(Exception $e){
        }
    }
?>
