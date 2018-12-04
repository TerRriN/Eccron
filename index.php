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
        <input type="text" name="username" placeholder="Username"> </br>
        <input type="text" name="password" placeholder="Password"> </br>
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
                $object = json_decode($response,true);
                echo $object["status"];?>

            <?php foreach($object["posts"] as $array){?>
                <p class="objects">
                    <span>
                        <h1><?php echo $array["title"],"</br>\n"; ?></h1>
                        <h2><?php echo $array["date"],"</br>\n"; ?></h2>
                        <h3><?php echo $array["content"],"</br>\n"; ?></h3>
                    </span>
                    
                    <?php
                        $post = json_decode($response,true);
                        $_SESSION["postID"] = $token["postID"];
                    ?>

                    <div class="comment-field">
                        <form action="" method="post">
                            <input type="text" name="content" placeholder="Text">
                            <input class="material-icons comment-submit" type="submit" value="done_outline">
                        </form>
                       

                        <?php $response = myCurl::execute_curl("http://10.130.216.144/~theprovider/blog/php/get-all-comments.php",
                        ["postID"=>$_SESSION["postID"]]); /*Måste få varje individuellt post id*/ ?>
                    </div>
                </p>
            <?php } ?> 
    </div>

</body>
</html>