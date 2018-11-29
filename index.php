<?php
    session_start();
    include "utility/utility.php";
?>
<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <script src="js/jquery-3.3.1.min.js"></script>
</head>
<body>
    <form action="" method="post">
        <input name="username" placeholder="Username">
        <input name="password" placeholder="Password">
        <input type="submit" value="Submit">
    </form>
    <?php if(isset($_POST["username"]) && isset($_POST["password"])){
            $username = $_POST["username"];
            $password = $_POST["password"];
            $response = myCurl::execute("http://10.130.216.144/~theprovider/generate-token.php",
            [
                "username"=>$username,
                "password"=>$password,
            ]);
            
            $token = json_decode($response,true);
            $_SESSION["token"] = $token["token"];
            $account = json_decode($response,true);
            $_SESSION["account"] = $account["accountID"];
    } ?>

    <div class="create">
        <form action="" method="post">
            <input name="blogID" placeholder="BlogID">
            <input name="title" placeholder="Title">
            <input name="date" type="date">
            <input name="content" placeholder="Text here...">
            <input type="submit" value="Submit">   
        </form>
        <?php if(isset($_POST["blogID"]) && isset($_POST["title"]) && isset($_POST["date"]) && isset($_POST["content"])){
            $blog = $_POST["blogID"];
            $title = $_POST["title"];
            $date = $_POST["date"];
            $content = $_POST["content"];
            
            $response = myCurl::execute("http://10.130.216.144/~theprovider/blog/php/create-post.php",
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
        <?php $response = myCurl::execute("http://10.130.216.144/~theprovider/blog/php/get-all-posts.php",["blogID"=>4]);
            echo $response; 
        ?>
    </div>
</body>
</html>