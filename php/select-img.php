<?php
    try{
        include "../utility/utility.php";

        $blog = $_POST["blog"];
        $target_dir = $_POST["post"];
        $object = $_POST["object"];
        
        $oldTarget = "../img/" . $blog . "/fold/";
        $newTarget = "../img/" . $blog . "/" . $target_dir;
        
        if(is_dir($newTarget) === false){
            mkdir($newTarget);
        }
        
        foreach($object as $img){ 
            rename($oldTarget . $img, $newTarget . "/" . $img);
        }
        
        $bla = "true";
        $response = [
            "status"=>true,
            "message"=>"Bild tillagd"
        ];
    
    }catch(Exception $exc){
        $bla = "false";
        $response = [
            "status"=>false,
            "message"=>$exc->getMessage()
        ];
    }
    header("Location: ../index.php?insertImg=$bla");
?>