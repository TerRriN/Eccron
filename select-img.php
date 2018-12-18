<?php
    try{
        include "utility/utility.php";

        $target_folder = $_POST["targetFolder"];
        $target_dir = $_POST["post"];
        $object = $_POST["object"];
        
        $oldTarget = $target_folder . "/";//behöver ett värde för att inte ta bort mappen
        $newTarget = $target_folder . "/" . $target_dir . "/";
        mkdir($newTarget);

        foreach($object as $img){ 
            rename($oldTarget . $img, $newTarget . $img);
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