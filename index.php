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
<body>
    <form action="" method="post">
        <input name="username" placeholder="Username"> </br>
        <input name="password" placeholder="Password"> </br>
        <input type="submit" value="Submit">
    </form>

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

        <table>
            <tr>
                <td>Bilder</td>
            </tr>
            <tr>
                <td><?php
                  $dirname = "img/4/";
                  $images = scandir($dirname);
                  $ignore = Array(".", "..");
                  foreach($images as $curimg){
                  if(!in_array($curimg, $ignore)) {
                  echo "<img src='img/4/$curimg' width='30%' />";
                  };
                  }
                    ?></td>
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