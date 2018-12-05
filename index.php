<?php
    session_start();
    include "utility/utility.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <script src="js/jquery-3.3.1.min.js"></script>
</head>
<body>
    <form action="" method="post">
        <input name="username" placeholder="Username"> </br>
        <input name="password" placeholder="Password"> </br>
        <input class="create-submit" type="submit" value="Submit">
    </form>
    <?php if(isset($_POST["username"]) && isset($_POST["password"])){
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
    } ?>

    <div class="create">
        <form action="" method="post">
            <input name="blogID" placeholder="BlogID"> </br>
            <input name="title" placeholder="Title"> </br>
            <input name="date" type="date"> </br>
            <input type="text" name="content" placeholder="Text here..."> </br>
            <input class="create-submit" type="submit" value="Submit">   
        </form>
        <?php if(isset($_POST["blogID"]) && isset($_POST["title"]) && isset($_POST["date"]) && isset($_POST["content"])){
            $blog = $_POST["blogID"];
            $title = $_POST["title"];
            $date = $_POST["date"];
            $content = $_POST["content"];
            
            $response = myCurl::execute_curl("http://10.130.216.144/~theprovider/blog/php/create-post.php",
            [
                "token"=>$_SESSION["token"],
                "accountID"=>$_SESSION["account"],
                "blogID"=>$blog,
                "title"=>$title,
                "date"=>$date,
                "content"=>$content
            ]);
            var_dump($response);
        } ?>
    </div>
    
    <div class="flow">
        <?php $response = myCurl::execute_curl("http://10.130.216.144/~theprovider/blog/php/get-all-posts.php",["blogID"=>4]); 
                $post = json_decode($response,true);

                foreach($post["posts"] as $array){?>
                    <p class="objects">
                        <span>
                            <h1><?php echo $array["title"],"</br>\n"; ?></h1>
                            <h2><?php echo $array["date"],"</br>\n"; ?></h2>
                            <h3><?php echo $array["content"],"</br>\n"; ?></h3>
                        </span>
                       
                        <div class="comment-field">
                            <form action="" method="post">
                                <input type="text" name="content" placeholder="Text">
                                <input name="date" type="date">
                                <input class="material-icons comment-submit" type="submit" value="done_outline">
                            </form>

                            <?php if(isset($_POST["content"]) && isset($_POST["date"])){
                                $content = $_POST["content"];
                                $date = $_POST["date"];

                                $response = myCurl::execute_curl("http://10.130.216.144/~theprovider/blog/php/create-comment.php",
                                [
                                    "token"=>$_SESSION["token"],
                                    "accountID"=>$_SESSION["account"],
                                    "postID"=>$array["postID"],
                                    "date"=>$date,
                                    "content"=>$content,
                                    "blogID"=>4
                                ]);
                                var_dump($response);
                            }

                            $response = myCurl::execute_curl("http://10.130.216.144/~theprovider/blog/php/get-all-comments.php",
                            ["blogID"=>4, "postID"=>$array["postID"]]); /*Måste få varje individuellt post id*/ 
                            $comment = json_decode($response,true);

                            foreach($comment["posts"] as $object){?>
                                <p class="objects">
                                    <span>
                                        <h4><?php echo $object["content"],"</br>\n"; ?></h4>
                                        <h2><?php echo $object["date"],"</br>\n"; ?></h2>
                                    </span> 
                           <?php } ?>
                        </div>
                    </p>
                <?php } ?> 
    </div>

</body>
</html>