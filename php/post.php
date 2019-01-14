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