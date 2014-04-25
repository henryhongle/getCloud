<?php 
    $page_title = 'getCloud';
    require('../includes/header.inc.php');

    if (isset($_SESSION['userName'])) {
        $userName = $_SESSION['userName'];
    }
    else {
        header("Location:register.php");
    }
    require('../includes/sideregion.inc.php');
?>

<?php

    function isValidFile(){
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['file']['tmp_name']);
        switch ($mime) {
            case 'image/jpeg':
                return true;
            case 'image/jpg':
                return true;
            case 'application/pdf':
                return true;
        }
        return false;
    }

    function upload($file){
        require('../includes/mysql_connect.inc.php');
        $escapedfile = mysqli_real_escape_string($link, $file);
        $name = $_POST['name'];
        $caption = $_POST['caption'];
        $user = $_SESSION['userName'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $type = finfo_file($finfo, $_FILES['file']['tmp_name']);
        $status = 0;

        $query = "INSERT INTO FILE(Filename, Caption, Data, type, Status, Owner) 
                        VALUES('$name', '$caption', '$escapedfile', '$type', '$status', '$user')"; 
        $result = mysqli_query( $link, $query);
        mysqli_close($link);
        return $result;
    }
?>

<?php //MAIN

    if ($_FILES["file"]["error"] != 0){
        $_SESSION['uploadStatus'] = "<b style='color:red;float:left;'>Error:".$_FILES["file"]["error"]."<b>";
        header('Location:'.$_SESSION['prevURL']);
    }
    elseif (isValidFile()){
        $file = file_get_contents($_FILES['file']['tmp_name']);
        if (upload($file)){ 
            $_SESSION['uploadStatus'] = "<b style='color:green;float:left;'>File uploaded!</b>";
            header('Location:'.$_SESSION['prevURL']);
        }else{
            $_SESSION['uploadStatus'] = "<b style='color:red;float:left;'>Something wrong. File not uploaded.</b>";
            header('Location:'.$_SESSION['prevURL']);
        } 
    }
    else {
        $_SESSION['uploadStatus'] = "<b style='color:red;float:left;' align='center'>
        Sorry, we currently only support pdf, jpg, and jpeg files.<b>";
        header('Location:'.$_SESSION['prevURL']);
    }
?>

<?php
require('../includes/footer.inc.php');
?>