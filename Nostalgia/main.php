<?php
    session_start();
    //error_reporting(E_ALL);
    //ini_set('display_errors', 1);
    define("TITLE", "Main Page | Nastalgia Vault");
    include('includes/header.php');
    include('includes/functions.php');

    $albumCount = count($albumTitle);
    $albumPH    = "album";
    $albumImg   = "";
    $title      = "";

?>

<div class="row log">
    <div class="col-xs-12 col-sm-12 col-md-offset-8 col-md-4 col-lg-offset-8 col-lg-4">

           <nav class="navright-sm">
                <ul><?php if (logged_in()){ ?>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="contribute.php">Contribute</a></li>
                    <li><a href="logout.php">Log Out</a></li>
                    <?php } else { ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="signup.php">Sign Up</a></li><?php } ?>
                    
                </ul>
            </nav>

            <nav class="navright">
                <ul><?php if (!logged_in()){ ?>
                    <li><a href="login.php">Login</a></li>
                    <li> | </li>
                    <li><a href="signup.php">Sign Up</a></li>
                    <?php } else { ?>
                    <li class="dropdown"><span class="dropbtn"><?php echo $_SESSION['subUserName']; ?></span>
                    <div class="drop-content">
                        <a href="profile.php">Profile</a>
                        <a href="contribute.php">Contribute</a>
                    </div></li>
                    <li> | </li>
                    <li><a href="logout.php">Log Out</a></li><?php } ?>
                </ul>
            </nav>
            


    </div>
</div>


<div class="row">
    <div class="hero col-xs-12 col-sm-12 col-md-12 col-lg-12">
    
           <h1>Nostalgic Vault</h1>
           <p><span id="subheading">Video Game Music of the Ages</span></p>
        
    </div><!-- end of content -->
</div>


<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
   <?php 
    
    for ($i = 1; $i <= $albumCount; $i++) {
    
        $albumPH  = "album"."$i";
        $title    = $albumTitle[$albumPH]['title']; 
        $albumImg = strip_bad_chars($albumTitle[$albumPH]['title'])."."."png";
        
        if (is_file('images/'.$albumImg)) {
            
            echo 
            "
            <div class=\"sm-large col-xs-6 col-sm-6 col-md-3 col-lg-3 clearfix\">
                <div class=\"albumBack\">
                <div class=\"albumImg\">
                    <a href=\"album.php?albumName=$title\"><img src=\"images/$albumImg\" alt=\"$title\" class=\"image-responsive\" title=\"$title\"></a>
                </div>
                </div>
            </div>
            
            <div class=\"sm-main\">
                <div class=\"albumBack\">
                    <a href=\"album.php?albumName=$title\"><p>$title</p></a>
                </div>
            </div>
            
            ";
            
        } else if (!is_file('images/'.$albumImg)) {
            
            echo 
            "
            <div class=\"col-xs-6 col-sm-6 col-md-3 col-lg-3 clearfix\">
                <div class=\"albumBack\">
                <div class=\"albumImg\">
                    <a href=\"album.php?albumName=$title\"><img src=\"images/sitebackground.png\" width=\"300px\" height=\"300px\" alt=\"$title\" class=\"image-responsive\" title=\"$title\"></a>
                </div>
                </div>
            </div>
            
            ";
            
        }
        
    } ?>
</div>
</div>

<?php
    
    include('includes/footer.php');
    
?>