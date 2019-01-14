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