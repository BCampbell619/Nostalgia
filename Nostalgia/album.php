<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    define("TITLE", "Albums | The Nostalgia Vault");
    include('includes/header.php');
    include('includes/connection.php');
    include('includes/functions.php');

    $problem    = "";                                                                   //variable to hold error message for page
    $arrayCount = count($albumTitle);                                                   //variable to hold the count of the albumTitle array
    $albumCheck = "";                                                                   //variable that is used as the album title place holder
    $handle     = "";                                                                   //variable that holds the file or directory handler
    $handleFile = "";                                                                   //variable that holds the file name read from the directory
    $archive    = "";                                                                   //variable to hold the archive track
    $songArray  = array();                                                              //variable to hold the array of file names from the directory
    $trackArray = array();                                                              //variable to hold the array of track titles that have the .mp3.zip stripped off the end
    $noArchive  = "";                                                                   //variable to hold no archive message
    $natTrackArr= array();

    if (isset($_GET['albumName'])){                                                     //This checks to see if album name is set in the $_GET array
        
        $album = strip_bad_chars($_GET['albumName']);                                   //The album name is assigned to $album variable and is stripped of any malicious characters
        
        for ($i = 1; $i <= $arrayCount; $i++) {                                         //A 'for' loop is initiated to go through the albumTitle array and find a match
            
            $albumCheck = "album"."$i";                                                 //The $albumCheck is assigned 'album' and is concatenated with $i the loop counter variable to represent
                                                                                        //the albumTitle key for each album title pair
            if ($album == strip_bad_chars($albumTitle[$albumCheck]['title'])) {         //If statement then checks the album number retrieved from the $_GET array against the albumTitle array
                    
                    $songArray  = scandir($album."/");
                    $trackArray = scandir($album."/");

                    $album  = $albumCheck;                                              //$album is then assigned the album number that represents the album selected on main.php
                    break;                                                              //Once the album is found the loop is broken
                
            } else if ($album == "") {

                $problem = "Album not found!";
                $noArchive = "No album to download! Album was not found";
                
            }//End of 1st inner IF statement
            
        }//End of for loop
        
        $arrayCount = count($trackArray);
        
        for ($i = 0; $i < $arrayCount; $i++) {
                
            $natTrackArr[] = dot_check($trackArray[$i]);

        }
        
        if (!count($natTrackArr) <= 1) {
            $i = 0;
            while ($i < 2) {
                    
                array_shift($songArray);
                array_shift($natTrackArr);
                $i = $i + 1;
                    
            }
        }

        
        /*if ($getCount = count($songArray) > 2) {                                      //the following code is for the finding and assigning of the archive file for each album.
                                                                                        //I need to find a way to uniquely name the archive so it can be found every time an album is created.
            foreach ($songArray as $song) {
            
                if (preg_match('/Archive/', $song, $matches)) {
            
                    $archive = $song;                                                   //This takes off the archive file of the array and assigns it to a variable
                    $natTrackArr = str_replace("$archive", "", $natTrackArr);
                    break;
            
                }  
            
            }
            
            foreach ($natTrackArr as $track) {
            
                if (preg_match('/Archive/', $track, $matches)) {
            
                    $natTrackArr = str_replace("$track", "", $natTrackArr);
                    break;
            
                }  
            
            }
            
        }
        
        if ($archive == "") {
            
            $noArchive = "Full album not available for download";                       //If there is no archive then the no archive message is assigned to the variable                                    
            
        }*/

    }//End of outermost IF statement

?>

<div class="row log">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
           
            <nav class="navleft">
    
                <ul>
                    <li><a href="main.php">The Nostalgic Vault</a></li>
                </ul>
    
            </nav><!-- end of nav -->
           
            <nav class="navright">
                <ul>
                    <li><?php if (!logged_in()){ ?>
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
            
            <nav >
    
                <ul class="navleft-sm">
                    <li><a href="main.php">The Nostalgic Vault</a></li>
                </ul>

                <ul class="navright-sm"><?php if (logged_in()){ ?>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="contribute.php">Contribute</a></li>
                    <li><a href="logout.php">Log Out</a></li>
                    <?php } else { ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="signup.php">Sign Up</a></li><?php } ?>
                    
                </ul>
            </nav>
            
    </div>
</div>

<div class="row backmeup">
     
     <div class="col-xs-offset-1 col-xs-10 col-sm-offset-3 col-sm-6 col-md-offset-3 col-md-6 col-lg-offset-3 col-lg-6" id="albumList">
      
        <div class="thumbnail">
          
          <?php 
            
            $albumImg = strip_bad_chars($albumTitle[$album]['title'])."."."png";
            $title    = $albumTitle[$album]['title'];
            
            if ($problem == "" && ($albumImg <> ".png" or $albumImg <> ".jpg")) {
            
                
                echo "<img src=\"images/$albumImg\" alt=\"$title\" class=\"image-responsive\" id=\"albumImg\">
                <br> <h1 class=\"albumMarquee\">$title</h1>";
            
            } else if ($problem == "" && ($albumImg == ".png" or $albumImg == ".jpg")) {
                
                echo "<img src=\"images/sitelogo_sm.png\" alt=\"$title\" class=\"image-responsive\" id=\"albumImg\">
                <br> <h2 class=\"albumMarquee\">$title</h2>";
                
            } else if ($problem != "") {
                
                echo $problem;
                
            }

            /*
                if ($problem == "" && $noArchive == "") {
                    
                    echo "<p>Download any song by clicking any track</p>";
                    
                } else if ($problem == "" && $noArchive != "") {
                    
                    echo "<p>$noArchive</p>";
                    
                } else if ($problem != "" && $noArchive != "") {
                    
                    echo "<p class=\"albumError\" class=\"text-danger\">Please select an album</p>";
                    
                }
            */

            ?>
            
        </div>


            <?php if ($noArchive == "") {?> 
            <p>Click or right click on any track below to download &darr;</p>
            <p>If you find an &#39;archive&#39; file&#44; then use that to download the whole album&#46;</p>
            <?php } else if ($noArchive != "") {?>
            <p>No Tracks for this album&#33;</p>
            <?php } ?>
            <br>
             
              <ul>
              
               <?php $songCount = count($songArray);
                  
                  sort($songArray, SORT_NATURAL);
                  sort($natTrackArr, SORT_NATURAL);
                  
                  $folder = strip_bad_chars($albumTitle[$album]['title']);
                  
                  for ($z = 0; $z < $songCount; $z++) {

                    echo "<li><a href=\"$folder/$songArray[$z]\" download=\"$songArray[$z]\">$natTrackArr[$z]</a></li>";

                  } 
                  
                ?>
                
              </ul>
              
    </div>
            
</div>       

<?php

    include('includes/footer.php');

?>