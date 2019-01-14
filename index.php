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
                <input name="createBtn" class="submitBtn" type="submit" value="CREATE">   
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
                <input name="insertFile" type="submit" value="INSERT" class="submitBtn">
            </form>
            </div>
            </ul>
        
            </li>
    </ul>
    <?php } 
        $response = myCurl::execute_curl("http://10.130.216.144/~theprovider/blog/php/get-title.php",["blogID"=>4]);
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
        
    <?php
        if(isset($_POST["loginBtn"])){
            if($newResponse["status"] == true){
                echo "<div class='message'>Signed in</div>";
            }else{
                echo "<div class='message'>Wrong username/password</div>";
            }
        }
        if(isset($_POST["createBtn"])){
            if($newResponse["status"] == true){
                echo "<div class='message'>Post created</div>";
            }else{
                echo "<div class='message'>Could not create post</div>";
            }
        }
        if(isset($_POST["submit-file"])){
            if($newResponse["status"] == true){
                echo "<div class='message'>Image added</div>";
            }else{
                echo "<div class='message'>Could not add image</div>";
            }
        }
        if(isset($_POST["insertFile"])){
            if($newResponse["status"] == true){
                echo "<div class='message'>Image inserted</div>";
            }else{
                echo "<div class='message'>Could not insert image</div>";
            }
        }
    ?>

    <div class="flow">
        <?php require("php/post.php"); ?> 
    </div>

    <script src="js/myJquery.js"></script>
</body>
</html>