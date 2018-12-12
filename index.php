<?php
    session_start();
    include "utility/utility.php";
    $blog = 4;
    $commentdate = date("Y-m-d");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/index-sass.css">
    <script src="js/jquery-3.3.1.min.js"></script>
</head>
<body>
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
    ?>
    <form action="" method="post">
        <input name="username" placeholder="Username"> </br>
        <input name="password" placeholder="Password"> </br>
        <input type="submit" value="Submit">
    </form>

    <div class="create">
        <form action="create-img.php" method="post" enctype="multipart/form-data">
            <input name="fileName" type="file" multiple>
            <input name="blog" type="hidden" value="<?php echo $blog ?>">
            <input type="submit" value="Submit">
        </form>

        <form action="" method="post">
            <input name="title" placeholder="Title"> </br>
            <input name="datepost" type="date"> </br>
            <textarea name="content" placeholder="Text here..."></textarea> </br>
            <input class="create-submit" type="submit" value="Submit">   
        </form>

        <table>
            <tr>
                <td>Bilder</td>
            </tr>
            <tr>
                <td><?php  ?></td>
            </tr>
        </table>
    </div>
   
    <div class="flow">
        <?php 
            $response = myCurl::execute_curl("http://10.130.216.144/~theprovider/blog/php/get-all-posts.php",["blogID"=>4]); 
            $post = json_decode($response,true);
            foreach($post["posts"] as $array){ ?>
                <p class="objects">
                    <span>
                        <h1><?php echo $array["title"]; ?></h1>
                        <h2><?php echo $array["date"]; ?></h2>
                        <h3><?php echo $array["content"]; ?></h3>
                    </span>
                    
                    <div class="comment-field">
                        <form action="" method="post">
                            <input type="text" name="commentcontent" placeholder="Text">
                            <input type="hidden" name="commentdate" value="<?php echo $commentdate; ?>">
                            <input type="hidden" name="id" value="<?php echo $array["postID"]; ?>">
                            <input class="material-icons comment-submit" type="submit" value="done_outline">
                        </form>

                    <?php $response = myCurl::execute_curl("http://10.130.216.144/~theprovider/blog/php/get-all-comments.php",
                        ["blogID"=>$blog, "postID"=>$array["postID"]]);
                        $comment = json_decode($response,true);
                        foreach($comment["posts"] as $object){ ?>
                            <p class="comments">
                                <span>
                                    <h5><?php echo $object["date"],"</br>\n"; ?></h5>
                                    <h4><?php echo $object["content"],"</br>\n"; ?></h4>
                                </span> 
                            </p>
                        <?php } ?>
                    </div>
                </p>
        <?php } ?> 
    </div>
</body>
</html>