<?php
    session_start();
    $blog = 4;
    $commentdate = date("Y-m-d");
    include "utility/utility.php";
    include "php/server-response.php";
    include "php/links.php";

    $status = "X";
    if(isset($_POST["displayAll"])){
        $status = "5";
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <script src="js/jquery-3.3.1.min.js"></script>

    <script>
    <?php
        echo "var status = $status;\n";
    ?>
        function justera(){
            if(status == "5"){
                $(".displayAll").toggleClass("hide");
                $(".hideAll").toggleClass("hide");
            }
            
        }
    </script>
</head>
<body onload="justera();">
<nav>
    <ul>   
        <p>Eccron</p>

        <li>
        <a class="select-btn material-icons md-36">account_circle</a>
            <ul class="login-dropdown">
            <li>
             <form action="" method="post">
                <input name="username" placeholder="Username"> </br>
                <input name="password" placeholder="Password"> </br>
                <input name="loginBtn" class="submitBtn" type="submit" value="SIGN IN">
             </form> 
             </li>
            </ul>     
        </li>
    
    <?php if(isset($_SESSION["account"]) == 21){ ?>         
        <!--create post-->
        <li>
        <a class="select-btn material-icons md-36">note_add</a>
        <ul class="post-dropdown">
            <form action="" method="post">
                <input name="title" placeholder="Title"></br>
                <input name="datepost" type="date"></br>
                <textarea name="content" placeholder="Text here..."></textarea> </br>
                <input class="submitBtn" type="submit" value="CREATE">   
            </form>
        </ul>
        </li>

        <!--create image-->
        <li>
        <a class="select-btn material-icons md-36">add_photo_alternate</a>
            <ul class="img-dropdown">
                <form action="create-img.php" method="post" enctype="multipart/form-data">
                    <input name="fileName" type="file">
                    <input name="blog" type="hidden" value="<?php echo $blog ?>">
                    <input type="submit" value="ADD" name="submit_file" class="submitBtn">
                </form>
            </ul>
        </li>
            <!--SELECT IMAGE-->
            <li>
            <a class="select-btn material-icons md-36">photo_library</a>
                <ul class="select-dropdown">
            <form action="select-img.php" method="post">
            <?php
                $dir = "img/4/fold";
                $ignore = Array(".", "..");
                $a = scandir($dir);
                foreach($a as $img){ 
                    if(!in_array($img, $ignore)){   
                        echo "<input type='checkbox' value='$img' name='object[]'/> <span class='selectImgTxt'>", $img ,"</span></br>";
                    }
                }
            ?>
                <input name="targetFolder" type="hidden" value="img/4/fold">
                <input name="blog" type="hidden" value="<?php echo $blog ?>">
                <input name="post" type="hidden" value="30">
                <input type="submit" value="INSERT" class="submitBtn">
            </form>
            </div>
            </ul>
        
            </li>
    </ul>
    <?php } ?>
        
        <?php $response = myCurl::execute_curl("http://10.130.216.144/~theprovider/blog/php/get-title.php",["blogID"=>4]);
        $blogTitle = json_decode($response,true); ?>
        <h6><?php echo $blogTitle['title'][0]['title']; ?></h6>

    <?php if(isset($_SESSION["account"]) == 21){ ?>
        <button class="changeTitleBtn material-icons">edit</button>
        <button class="cancelBtn material-icons hide">clear</button>
        <form class="edit hide" action="" method="post">
            <input class="changeTitleTxt" maxlength="42" name="blogTitle" value="<?php echo $blogTitle['title'][0]['title']; ?>">
            <input class="changeTitleBtn hide material-icons" type="submit" value="done">
        </form>
    <?php } ?>
</nav>

    
   
    <div class="flow">
        <?php 
            $response = myCurl::execute_curl("http://10.130.216.144/~theprovider/blog/php/get-all-posts.php",["blogID"=>4]); 
            $post = json_decode($response,true);
            foreach($post["posts"] as $array){ ?>
                <div class="post">
                    <h1><?php echo $array["title"]; ?></h1>
                    <h2><?php echo $array["date"]; ?></h2>
                    <h3><?php echo $array["content"]; ?></h3>
                    <?php if(isset($_SESSION["account"]) == 21){ ?>
                    <form action="" method="post">
                        <input type="hidden" name="postID" value="<?php echo $array["postID"]; ?> ">
                        <input class="removePostBtn material-icons md-36" name="removePost" type="submit" value="delete">                        
                    </form>
                    <?php } ?>
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
                        <input type="text" maxlength="90" name="commentcontent" placeholder="Text">
                        <input type="hidden" name="commentdate" value="<?php echo $commentdate; ?>">
                        <input type="hidden" name="id" value="<?php echo $array["postID"]; ?>">
                        <input class="material-icons md-36 comment-submit" type="submit" value="send">
                    </form>
                    <!--Display 5 comments-->
                    <?php $response = myCurl::execute_curl("http://10.130.216.144/~theprovider/blog/php/get-all-comments.php",
                        ["blogID"=>$blog, "postID"=>$array["postID"]]);
                        $comment = json_decode($response,true);
                        $x = 1;
                        
                        if(isset($comment["posts"])){
                            foreach($comment["posts"] as $object){ 
                                if($x <= 5){ ?>
                                <div class="comment">
                                    <h4><?php echo $object["content"],"</br>\n"; ?></h4>
                                    <h5><?php echo $object["date"],"</br>\n"; ?></h5>
                                    <?php if(isset($_SESSION["account"]) == 21){ ?>
                                    <form action="" method="post">
                                        <input type="hidden" name="commentID" value="<?php echo $object["commentID"]; ?> ">
                                        <input class="removeCommentBtn material-icons md-24" name="removeComment" type="submit" value="delete">                        
                                    </form>
                                    <?php } ?>
                                </div>
                                <?php $x++;
                                }else{break;}
                            } ?>
                    
                        <!--Display all comments-->
                            <form action="" method="post">
                                <input type="hidden" name="id" value="<?php echo $array["postID"]; ?>">
                                <input type="submit" name="displayAll" class="load displayAll material-icons md-48" value="keyboard_arrow_down">
                            </form>
                            <?php
                                $ignoreObj = array_slice($comment["posts"], 0, 5);
                                if(isset($_POST["displayAll"]) && isset($_POST["id"])){
                                    foreach($comment["posts"] as $object){ 
                                        if(!in_array($object, $ignoreObj)){ ?> 
                                            <h4><?php echo $object["content"],"</br>\n"; ?></h4>
                                            <h5><?php echo $object["date"],"</br>\n"; ?></h5>
                                    <?php }
                                    } 
                                } ?>
                            
                        <!--Display less comments-->
                            <form action="" method="post">
                                <input type="hidden" name="id" value="<?php echo $array["postID"]; ?>">
                                <input type="submit" name="hideAll" class="load hideAll hide material-icons md-48" value="keyboard_arrow_up">
                            </form>
                            <?php
                            $hideObj = $comment["posts"];
                            if(isset($_POST["hideAll"]) && isset($_POST["id"])){
                                foreach($comment["posts"] as $object){ 
                                    if(!in_array($object, $hideObj)){ ?> 
                                        <h4><?php echo $object["content"],"</br>\n"; ?></h4>
                                        <h5><?php echo $object["date"],"</br>\n"; ?></h5>
                                <?php }
                                }
                            }
                        } ?>
                    </div>
                </div>
        <?php } ?> 
    </div>

    <script>


$(".select-btn").click(function(e){
  $(".post-dropdown").hide();
  $(".img-dropdown").hide();
  $(".select-dropdown").hide();
  $(".login-dropdown").hide();
  $(this).parent().find('ul').show();
});


$(document).click(function(e) {
    if ($(e.target).closest('nav').length === 0) {     
        $(".post-dropdown").hide();
        $(".img-dropdown").hide();
        $(".select-dropdown").hide();
        $(".login-dropdown").hide();
    }
});


 </script>

<script>
    $(".displayAll").click(function(){
        $(".displayAll").toggleClass("hide");
        $(".hideAll").toggleClass("hide");
    });
    $(".hideAll").click(function(){
        $(".displayAll").toggleClass("hide");
        $(".hideAll").toggleClass("hide");
    });
</script>
<script>
    $(".changeTitleBtn").click(function(){
        $(".edit").toggleClass("hide");
        $(".changeTitleBtn").toggleClass("hide");
        $(".cancelBtn").toggleClass("hide");
    });
    $(".cancelBtn").click(function(){
        $(".edit").toggleClass("hide");
        $(".changeTitleBtn").toggleClass("hide");
        $(".cancelBtn").toggleClass("hide");
    });
</script>

</body>
</html>