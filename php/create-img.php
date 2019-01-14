<?php 
    try{
        include "../utility/utility.php";
        
        $target_dir = "../img/";
        $target_folder = $_POST["blog"];
        
        $target_file = $target_dir . $target_folder . "/" . "fold" . "/" . basename($_FILES["fileName"]["name"]);
        $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        //CHECKS IF FILE IS AN IMAGE
        if($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" && $fileType != "gif" ) {
            throw new Exception("Felaktigt filformat");
        }

        //CHECKS IF FILE IS NOT FAKE 
        $check = getimagesize($_FILES["fileName"]["tmp_name"]);
        if($check === false){
            throw new Exception("Filen är fake");
        }

        //CHECKS IF FILE ALREADY EXISTS
        if(file_exists($target_file) === true){
            throw new Exception("Filen existerar redan");
        }

        //CHECKS FILE SIZE
        if($_FILES["fileName"]["size"] > 500000){
            throw new Exception("Filen är för stor"); 
        }

        //CHECKS IF FOLDER EXISTS
        if(is_dir($target_dir . $target_folder) === true){
            //UPLOADS THE FILE
            if(move_uploaded_file($_FILES["fileName"]["tmp_name"], $target_file) === false){
                throw new Exception("Kunde inte ladda upp filen");
            }
        }else{
            //CREATES A NEW FOLDER AND UPLOADS FILE
            mkdir($target_dir . $target_folder . "fold");
            if(move_uploaded_file($_FILES["fileName"]["tmp_name"], $target_file) === false){
                throw new Exception("Kunde inte ladda upp filen");
            }
        }

        $response = [
            "status"=>true,
            "message"=>"Bild tillagd"
        ];
    
    }catch(Exception $exc){
        $response = [
            "status"=>false,
            "message"=>$exc->getMessage()
        ];
    }
    echo json_encode($response);
    $image = $_FILES["fileName"]["name"];
    $img = $target_dir . $target_folder . "/fold/" . $image;
    echo $img;
    echo '<img src="'.$img.'">';
?>