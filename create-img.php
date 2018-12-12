<?php 
    try{
        include "utility/utility.php";
        /*Input::validate($input,[
            "accountID"=>null,
            "token"=>20,
            "title"=>50
        ]);
        if(!Token::verify($input["accountID"], $input["token"]))
        {
            throw new Exception("Felaktig token");
        }
        $connection = new DBConnection();
        */
        $target_dir = "img/";
        $target_file = $target_dir . basename($_FILES["fileName"]["name"]);
        $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        //CHECKS IF FILE IS AN IMAGE
        if($fileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
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

        //CHECK FILE SIZE
        if($_FILES["fileName"]["size"] > 500000){
            throw new Exception("Filen är för stor"); 
        }

        //UPLOADS THE FILE
        if(move_uploaded_file($_FILES["fileName"]["tmp_name"], $target_file) === false){
            throw new Exception("Kunde inte ladda upp filen");
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
?>