<?php
    session_start();
    $blog = 4;
    $commentdate = date("Y-m-d");
    include "utility/utility.php";
    include "php/server-response.php";
    include "php/links.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <script src="js/jquery-3.3.1.min.js"></script>
</head>

<body>
<nav>
    <ul>   
        <p>Eccron</p>
        <li><a class="loginbtn material-icons md-36">account_circle</a></li>
            <ul class="login-dropdown">
             <form action="" method="post">
                <input name="username" placeholder="Username"> </br>
                <input name="password" placeholder="Password"> </br>
                <input type="submit" value="Submit">
             </form> 
            </ul>     
        </li>
        
        <!--create image-->
        <li><a class="image-btn">Skapa bilder</a>
            <ul class="img-dropdown">

                <form action="create-img.php" method="post" enctype="multipart/form-data">
                    <input name="fileName" type="file" multiple>
                    <input name="blog" type="hidden" value="<?php echo $blog ?>">
                    <input type="submit" value="Submit" name="submit_file">
                </form>
            </ul>
        </li>
         
        <!--create post-->
        <li><a class="post-btn">Skapa post</a>
            <ul class="post-dropdown">
                <form action="" method="post">
                    <input name="title" placeholder="titel"></br>
                    <input name="datepost" type="date"></br>
                    <textarea name="content" placeholder="Text here..."></textarea> </br>
                    <input class="create-submit" type="submit" value="Submit">   
                </form>
            </ul>
        </li>       
            <!--SELECT IMAGE-->
            <li><a class="select-btn">visa bilder</a>
                <ul class="select-dropdown">
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
                <input name="targetFolder" type="hidden" value="img/4/fold">
                <input name="blog" type="hidden" value="<?php echo $blog ?>">
                <input name="post" type="hidden" value="30">
                <input type="submit" value="Submit">
            </form>
            </div>
            </ul>
        
            </li>
    </ul>
</nav>

    
   
    <div class="flow">
        <?php 
            $response = myCurl::execute_curl("http://10.130.216.144/~theprovider/blog/php/get-all-posts.php",["blogID"=>4]); 
            $post = json_decode($response,true);
            foreach($post["posts"] as $array){ ?>
                <p class="objects">
                    <div class="post">
                        <h1><?php echo $array["title"]; ?></h1>
                        <h2><?php echo $array["date"]; ?></h2>
                        <h3><?php echo $array["content"]; ?></h3>
                    </div>

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
                            <input name="commentcontent" placeholder="Text">
                            <input type="hidden" name="commentdate" value="<?php echo $commentdate; ?>">
                            <input type="hidden" name="id" value="<?php echo $array["postID"]; ?>">
                            <input class="material-icons md-36 comment-submit" type="submit" value="send">
                        </form>
                        <?php $response = myCurl::execute_curl("http://10.130.216.144/~theprovider/blog/php/get-all-comments.php",
                            ["blogID"=>$blog, "postID"=>$array["postID"]]);
                            $comment = json_decode($response,true);
                            foreach($comment["posts"] as $object){ ?>
                                <h4><?php echo $object["content"],"</br>\n"; ?></h4>
                                <h5><?php echo $object["date"],"</br>\n"; ?></h5>
                        <?php } ?>
                    </div>
                </p>
        <?php } ?> 
    </div>

    <script>
$(".login-btn").click(function(){
  $(".login-dropdown").toggleClass("show");
});

$(".image-btn").click(function(){
  $(".img-dropdown").toggleClass("show");
});

$(".post-btn").click(function(){
  $(".post-dropdown").toggleClass("show");
});

$(".select-btn").click(function(){
  $(".select-dropdown").toggleClass("show");
});
    </script>

</body>
</html>