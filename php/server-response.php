<?php 
    /*Sends form as json to server, generate-token*/
        if(isset($_POST["username"]) && isset($_POST["password"])){
            $username = $_POST["username"];
            $password = $_POST["password"];
            $response = myCurl::execute_curl("http://10.130.216.144/~theprovider/generate-token.php",
            [
                "username"=>$username,
                "password"=>$password,
            ]);
                var_dump($response);
                $token = json_decode($response,true);
                $_SESSION["token"] = $token["token"];
                $account = json_decode($response,true);
                $_SESSION["account"] = $account["accountID"];
        }
    /*Sends form as json to server, create-post*/
        if(isset($_POST["title"]) && isset($_POST["datepost"]) && isset($_POST["content"])){
            $title = $_POST["title"];
            $datepost = $_POST["datepost"];
            $content = $_POST["content"];
           
            $response = myCurl::execute_curl("http://10.130.216.144/~theprovider/blog/php/create-post.php",
            [
                "token"=>$_SESSION["token"],
                "accountID"=>$_SESSION["account"],
                "blogID"=>$blog,
                "title"=>$title,
                "date"=>$datepost,
                "content"=>$content
            ]);
        }
    /*Sends form as json to server, create-comment*/ 
        if(isset($_POST["commentcontent"]) && isset($_POST["commentdate"]) && isset($_POST["id"])){
            $commentcontent = $_POST["commentcontent"];
            $commentdate = $_POST["commentdate"];
            $id = $_POST["id"];
            
            $response = myCurl::execute_curl("http://10.130.216.144/~theprovider/blog/php/create-comment.php",
            [
                "token"=>$_SESSION["token"],
                "accountID"=>$_SESSION["account"],
                "postID"=>$id,
                "date"=>$commentdate,
                "content"=>$commentcontent,
                "blogID"=>$blog
            ]);
        }
    /*Sends form as json to server, change-title*/
        if(isset($_POST["blogTitle"])){
            $blogTitle = $_POST["blogTitle"];

            $response = myCurl::execute_curl("http://10.130.216.144/~theprovider/blog/php/change-title.php",
            [
                "token"=>$_SESSION["token"],
                "accountID"=>$_SESSION["account"],
                "title"=>$blogTitle,
                "blogID"=>$blog
            ]);
        }
?>