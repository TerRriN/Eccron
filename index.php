<?php
    session_start();
    include "utility/utility.php";
    $blog = 4;
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
    /*Sends form as json to server, create-comment*/ 
        if(isset($_POST["comment_content"]) && isset($_POST["comment_date"]) && isset($_POST["id"])){
            $comment_content = $_POST["comment_content"];
            $comment_date = $_POST["comment_date"];
            $id = $_POST["id"];
            
            $response = myCurl::execute_curl("http://10.130.216.144/~theprovider/blog/php/create-comment.php",
            [
                "token"=>$_SESSION["token"],
                "accountID"=>$_SESSION["account"],
                "postID"=>$id,
                "date"=>$comment_date,
                "content"=>$content,
                "blogID"=>$blog
            ]);
                var_dump($response);
        }
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
            var_dump($response);
        }
    ?>
    <form action="" method="post">
        <input name="username" placeholder="Username"> </br>
        <input name="password" placeholder="Password"> </br>
        <input class="create-submit" type="submit" value="Submit">
    </form>

    <div class="create">
        <form action="" method="post">
            <input name="title" placeholder="Title"> </br>
            <input name="datepost" type="date"> </br>
            <textarea rows="10" cols="100" name="content" placeholder="Text here..."></textarea> </br>
            <input class="create-submit" type="submit" value="Submit">   
        </form>
    </div>
    
    <div class="flow">
        <?php 
            $response = myCurl::execute_curl("http://10.130.216.144/~theprovider/blog/php/get-all-posts.php",["blogID"=>4]); 
            $post = json_decode($response,true);
            foreach($post["posts"] as $array){ ?>
                <p class="objects">
                    <span>
                        <h1><?php echo $array["title"],"</br>\n"; ?></h1>
                        <h2><?php echo $array["date"],"</br>\n"; ?></h2>
                        <h3><?php echo $array["content"],"</br>\n"; ?></h3>
                    </span>
                    
                    <div class="comment-field">
                        <form action="" method="post">
                            <input type="text" name="comment-content" placeholder="Text">
                            <input type="date" name="comment_date">
                            <input type="hidden" name="id" value="<?php echo $array["postID"]; ?>">
                            <input class="material-icons comment-submit" type="submit" value="done_outline">
                        </form>

                    <?php $response = myCurl::execute_curl("http://10.130.216.144/~theprovider/blog/php/get-all-comments.php",
                        ["blogID"=>$blog, "postID"=>$array["postID"]]);
                        $comment = json_decode($response,true);
                        foreach($comment["posts"] as $object){?>
                            <p class="comments">
                                <span>
                                    <h2><?php echo $object["date"],"</br>\n"; ?></h2>
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