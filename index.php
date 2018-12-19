<?php
    session_start();
    $blog = 4;
    $commentdate = date("Y-m-d");
    include "utility/utility.php";
    include "php/server-response.php";
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

<body class="body1">
<nav>
    <ul>   
        <p>Eccron</p>
        <li><a class = loginbtn>Logga in här</a>
         <ul class="login-dropdown">
            <form action="" method="post">
        <input name="username" placeholder="Username"> </br>
        <input name="password" placeholder="Password"> </br>
        <input type="submit" value="Logga In">
    </form>
    </ul> 
        </li>
        <li><a href="#">länkar</a>
           
        </li>
        <li><a href="#"></a></li>
        <li><a href="#"></a></li>
        <li><a href="#"></a></li>
        <li><a href="#"></a></li>
    </ul>
</nav>

        <form action="create-img.php" method="post" enctype="multipart/form-data">
            <input name="fileName" type="file" multiple>
            <input name="blog" type="hidden" value="<?php echo $blog ?>">
            <input type="submit" value="Submit" name="submit_file">
        </form>

    <div class="create">
        <form action="" method="post">
            <input name="title" placeholder="Title"> </br>
            <input name="datepost" type="date"> </br>
            <textarea name="content" placeholder="Text here..."></textarea> </br>
            <input class="create-submit" type="submit" value="Submit">   
        </form>
    <!--SELECT IMAGE-->
        <form action="select-img.php" method="post">
        <?php
            $dir = "img/4/fold";
            $ignore = Array(".", "..");
            $a = scandir($dir);
            foreach($a as $img){ 
                if(!in_array($img, $ignore)){   
                    echo "<input type='checkbox' value='$img' name='object[]'/>", $img, "</br>";
                }
            }
        ?>
        <input name="blog" type="hidden" value="<?php echo $blog ?>">
        <input name="post" type="hidden" value="30">
        <input type="submit" value="Submit">
        </form>
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

                    <?php
                        if($array["postID"] == 28){//scandir måste jämföra mappens namn med postID
                            $dir = "img/4/30";
                            $ignore = Array(".", "..");
                            $a = scandir($dir);
                            foreach($a as $img){ 
                                if(!in_array($img, $ignore)){   
                                    echo "<img src='$dir/$img' width='30%'>";
                                }

                            }
                        }
                    ?>
                    
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

    <script>
        $(".loginbtn").click(function(){
  $(".login-dropdown").toggleClass("show");
});
    </script>

</body>
</html>