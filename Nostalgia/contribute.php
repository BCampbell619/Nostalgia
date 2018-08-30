<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    define("TITLE", "Contribute | Nastalgia Vault");
    include('includes/connection.php');
    include('includes/header.php');
    include('includes/functions.php');

    //file and submission handling variables

    $mFileName      =   "";                                                                     //variable to hold the music file name
    $mFileTmpName   =   "";                                                                     //variable to hold the music temp file name
    $mFileExt       =   "";                                                                     //variable to hold the music file extension
    $mFileExtName   =   "";                                                                     //variable to hold the entire music file name for database
    $mFileExtension =   "";                                                                     //variable to hold just the music file extension
    $ext            =   "mp3.zip";                                                              //variable to hold my desired music extension for file uploads
    $iFileName      =   "";                                                                     //variable to hold the image file name
    $iFileTmpName   =   "";                                                                     //variable to hold the image temp file name
    $iFileExt       =   "";                                                                     //variable to hold the image file extension
    $iFileExtName   =   "";                                                                     //variable to hold the entire image file name for database
    $iFileExtension =   "";
    $albumName      =   "";                                                                     //variable for the album name given by user
    $albumComposer  =   "";                                                                     //variable for the album composer given by user
    $albumNameChange=   "";                                                                     //variable to hold the album name change for image rename and upload
    $UserName       =   $_SESSION['subUserName'];                                               //variable for user name
    $target         =   "";                                                                     //variable for upload folder target
    $imageTarget    =   "images/";                                                              //variable for image file target folder
    $uploadDate     =   date('Ymd');                                                            //variable for upload date for database
    $userID         =   "";                                                                     //variable to hold array return of database query
    $idUser         =   "";                                                                     //variable to hold the actual data returned by the query array
    $uploadQuery    =   "";                                                                     //variable to hold the insert query
    $albumCount     =   count($albumTitle);                                                     //variable to hold the number of albums listed in the album title array
    $albumInsert    =   "";                                                                     //variable to hold the album number to be inserted into the arrays.php file
    $albumCheck     =   "FALSE";                                                                //variable to check whether an album already exists in the album title array

    //Error & success handling variables

    $uploadError    =   "";                                                                     //variable to hold the error messages
    $uploadSuccess  =   "";                                                                     //variable to hold the success message
    $imageError     =   "";                                                                     //variable to hold the image upload error message
    $imageSuccess   =   "";                                                                     //variable to hold teh image upload success message
    $error          =   "";                                                                     //variable to hold errors that occur with the database
    $mysqlReport    =   "";                                                                     //variable to hold the actual mysql error report
    $report         =   "";                                                                     //variable to hold any other error reports
    $file           =   "";                                                                     //variable to call and hold the file to which the errors will be written to
    $notice         =   "";                                                                     //variable to hold the error notice call to user

    if (isset($_POST['uploadSubmit'])){                                                         //Checks whether the user has clicked the submit button
        
        $mFileName      = $_FILES['myfile']['name'][0];                                           //assigns music file name to variable
        $mFileTmpName   = $_FILES['myfile']['tmp_name'][0];                                       //assigns music temp file name to variable
        $iFileName      = $_FILES['myfile']['name'][1];                                           //assigns image file name to variable
        $iFileTmpName   = $_FILES['myfile']['tmp_name'][1];                                       //assigns image temp file name to variable
        $albumName      = clean_input($_POST['albumTitle']);                                    //assigns user provided album title to variable
        $albumComposer  = clean_input($_POST['albumComposer']);                                 //assigns user provided album composer to variable
        $userID         = get_user_id($UserName, $Connect);                                     //calls function to pull user id from subscribe table and assigns array to variable
        $idUser         = $userID['subID'];                                                     //assigns user id to variable
        
        if ($albumName == "" && $mFileName != "") {                                             //Checks if an album name has been provided when a file has been uploaded
            
            $uploadError = "Please provide an album name for the track";
            
        } else if ($albumComposer == "") {                                                      //Checks to see if an album composer has been provided
            
            $albumCompser = "Anonymous";
            
        } // end of album & composer name IF ELSE IF statements 
        
        if ($albumName == "" && $mFileName == "" && $mFileTmpName == "") {                      //Checks to see if the file has been provided
        
            $uploadError = "Please provide an album name and a file";
            
        }   else if ($albumName != "" && $mFileName != "" && $mFileTmpName != "") {             //Checks to see if the album name and file has been provided
        
            $mFileExt        = explode(".", $mFileName);                                        //breaks file name and extension up
            $mFileExtension  = $mFileExt[1];                                                    //assigns file extension to variable
            
            /*This section checks to see if the correct files have been submitted. If anything other than an audio file has been submitted an error will be thrown.
            If a correct file has been submitted then the complete file name will be assigned to a variable for insertion into the database and the INSERT query
            will be assigned to a variable. If the connection to the database and query execute then the file will be uploaded and a success message will be
            assigned to the $uploadError variable. If the connection and query do not go successfully then the error report will be generated and an error message
            will be given to the user.*/
            
            if ($mFileExtension != "mp3" xor $mFileExtension != "m4a" xor $mFileExtension != "wav" xor $mFileExtension != "aiff" xor $mFileExtension != "wma" xor $mFileExtension != "m4p" xor $mFileExtension != "aac"){
            
                $uploadError = "File is in the wrong format.";                                  //Error if the user uploads the wrong kind of file
            
            } else {

                $target         = strip_bad_chars($albumName)."/";                            //variable takes the album name to be used in the mkdir statement to create the album folder for mp3 uploads
                //$mFileExtName   = preg_replace('/\.[^.]+$/', '.', $mFileName).$ext;         //adding desired extension for file 'mp3.zip'
                /*$fileExtName  = $fileExt[0].".".$fileExt[1]."."."zip";                      //File name assigned to variable*/
                $checkQuery     = "SELECT C_File_Name FROM contribute WHERE C_File_Name = \"$mFileName\";";         //check query assigned
                $uploadQuery    = "INSERT INTO contribute(C_File_Name, C_Album_Name, C_Composer, C_Date, subID) VALUES(\"$mFileName\", \"$albumName\", \"$albumComposer\", $uploadDate, $idUser);";   //Query is assigned
                $checkResult    = file_check($checkQuery, $Connect);
                
                for ($i = 1; $i <= $albumCount; $i++) {
                    
                    $albumInsert = "album"."$i";
                    
                    if ($albumName == $albumTitle[$albumInsert]['title']) {
                        
                        $albumCheck = "TRUE";
                        break;
                        
                    }
                    
                }
                
                if ($albumCheck != "TRUE") {
                
                $albumCount = $albumCount + 1;
                $fileHandle = fopen("includes/arrays.php", "r+b");
                $albumInsert = "album"."$albumCount";
                $insert = "\t\t\t\t\"$albumInsert\"\t=>\tarray(\"title\"\t=>\t\"$albumName\"),\r\n\r\n );\r\n\r\n ?>";
                fseek($fileHandle, -10, SEEK_END);
                fwrite($fileHandle, $insert);
                fclose($fileHandle);
                    
                }
                
            }
                    
            if (!is_dir($target)) {                                                             //This checks to see if the directory for the album name is already created. If not it is created
                        
                mkdir($target, 0755);
                        
            } else if ($checkResult) {                                                          //If the query is true then the file already exists and an error msg is returned
                        
                $uploadError = "File already uploaded!";
                
            } else if (mysqli_query($Connect, $uploadQuery)) {                                  //If the query is true then the statement ran against the database and the album info is created
                
                move_uploaded_file($mFileTmpName, $target."$mFileName");                        //File is uploaded to target folder
                $uploadSuccess = "File successfully uploaded!";                                 //The success message is loaded
                    
            } else {
                        
                $error = "File was not uploaded. Please try again.";                            //If an error occured when connected to database
                $mysqlReport = mysqli_error($Connect);                                          //The MySQL error is put to this variable
                
                $file = fopen("errors.txt", "a");                                               //The error file is opened in "a" - append mode
                
                fwrite($file, $mysqlReport." ".date('m-d-Y')." \r\n\r\n");                      //The error is written to the file with the date appended to it
                
                $notice = "mysql Error has been recorded.";                                     //notice variable is populated
                
                fclose($file);                                                                  //File is then closed
                        
            } // end of the IF, ELSE IF statements of the create directory & mySQL query checks
        
        } else {
            
            $uploadError = "File or Album Name were not provided";                             //error message set to the upload error variable
            $report = "File and Album Name were not provided";
            $file = fopen("errors.txt", "a");                                                   //The error file is opened in "a" - append mode
                    
            fwrite($file, $report." ".$UserName." ".date('m-d-Y')."\r\n\r\n");                  //The error is written to the file with the date appended to it
                    
            $notice = "file upload error has been recorded.";                                   //notice variable is populated
                    
            fclose($file);            
            
        } // end of IF ELSE IF statements checking for file content in submission
        
        if ($iFileName != "" && $iFileTmpName != "") {
            
            $iFileExt        = explode(".", $iFileName);                                        //breaks image file name and extension up
            $iFileExtension  = $iFileExt[1];                                                    //assigns image file extension to variable
            
            if ($iFileExtension == "jpg" xor $iFileExtension == "png") {                       //If statement to check if the file extension is equal to either 'jpeg' or 'png'

                $albumNameChange = strip_bad_chars($albumName);                                 //Name change variable is given the album name after going through the strip function
                $iFileName  =   str_replace($iFileName, $albumNameChange, $iFileName).".".$iFileExtension;          //The file name is replaced with the album name
                move_uploaded_file($iFileTmpName, $imageTarget."$iFileName");                   //The image is then uploaded to the 'images' folder
                $imageSuccess = "Image uploaded!";                                              //Success message is generated
                
            } else if ($iFileExtension != "jpeg" xor $iFileExtension != "png") {                //If statement checks to see if the image file extension is not equal to 'jpeg'
                
                $imageError = "Image not in the correct format!";                               //An error is thrown if not
                
            } else {
                
                $imageError = "Image was not uploaded!";                                        //Else if that all fails then the error message is generated
                
            }//End of inner IF statement for image file upload
            
        }//End of outer IF statement for image file upload
        
        if ($uploadError == "" and $error == "" and $uploadSuccess != "") {
        
            $to = "broccampbell@gmail.com";
                       
            $subject = "$UserName has uploaded a file to the Nostalgia Vault";
            
            $message = "Track Name: $mFileExtName \r\n";
            $message .= "Album Name: $albumName\r\n";
            $message .= "Composer Name: $albumComposer\r\n";
    
            
            $message = wordwrap($message, 70);
            
            $headers = "MIME-Version 1.0\r\n";
            $headers .="Content-type: text/plain; charset=iso-8859-1\r\n";
            $headers .="From: " . $UserName . " <" . "thecampbellscorner.com" . ">\r\n";
            $headers .="X-Priority: 1\r\n";
            $headers .="X-MSMail-Priority: High\r\n\r\n";
            
            mail($to, $subject, $message, $headers);
        
        }   else if ($uploadError != "" or $error != "") {
            
            $to = "broccampbell@gmail.com";
                       
            $subject = "File did not upload - Nostalgia Vault";
            
            $message  = "$uploadError - album name, file name, and tmp file name logic checked to be false. One of them was empty.\r\n";
            $message .= "$report\r\n\r\n";
            $message .= "$error - An error occurred when the data was being uploaded to the database:\r\n";
            $message .= "$mysqlReport\r\n\r\n";
            $message .= "$notice\r\n";
    
            
            $message = wordwrap($message, 70);
            
            $headers  = "MIME-Version 1.0\r\n";
            $headers .="Content-type: text/plain; charset=iso-8859-1\r\n";
            $headers .="From: " . $UserName . " <" . "thecampbellscorner.com" . ">\r\n";
            $headers .="X-Priority: 1\r\n";
            $headers .="X-MSMail-Priority: High\r\n\r\n";
            
        }

    }

?>

<div class="row log">
<div class="col-sm-12">
      
        <nav class="navleft">
    
            <ul>
                <li><a href="main.php">The Nostalgic Vault</a></li>
            </ul>
    
        </nav><!-- end of nav -->      
       
        <nav class="navright">
            <ul><?php if (!logged_in()) { ?>
                <li><a href="login.php">Login</a></li>
                <li> | </li>
                <li><a href="signup.php">Sign Up</a></li>
                <?php } else { ?>
                    <li class="dropdown"><span class="dropbtn"><?php echo $_SESSION['subUserName']; ?></span>
                    <div class="drop-content">
                        <a href="profile.php">My Profile</a>
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

<div class="row">
   <br>
    <div class="col-xs-12 col-sm-12 col-md-offset-2 col-md-8 col-lg-offset-2 col-lg-8" id="contributeBody">
    
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="audioheader">

            <?php if ($uploadError != "") { 

                echo "<h2>$uploadError</h2>"; 

            }   else  if ($uploadError == "" && $uploadSuccess != "")  {

                echo "<h2>$uploadSuccess</h2>";

            }   else  if ($error != "")  {

                echo "<h2>$error<br>An error occurred while updating the database</h2>";

            }   else {
    
                echo "<h2>Upload your music files</h2>";
    
            } ?>

        </div><!-- end of .mp3 header div -->

            <div class="col-xs-12 col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-3 col-lg-6">

                <form action="contribute.php" method="post" enctype="multipart/form-data">

                    <div class="form-group">
                        
                        <p><span class="reqfld">&#42; Required field</span></p>
                        <label for="albumTitle"><span class="reqfld">&#42;</span>Album Title:</label><br>
                        <input type="text" class="form-control" name="albumTitle" id="albumTitle" placeholder="Super Mario Bros."><br><br>
                        <label for="albumComposer">Album Composer:</label><br>
                        <input type="text" class="form-control" name="albumComposer" id="albumComposer" placeholder="Koji Kondo"><br><br>
                        <label for="file"><span class="reqfld">&#42;</span>Upload the track &#40;mp3&#44; m4a&#44; WAV&#44; wma&#44; AIFF&#44; m4p&#44; or AAC&#41;&#58;</label><br><br>
                        <input type="file" name="myfile[]" id="myfile[]"><br><br>
                        <label for="file">Upload album image &#40;jpeg&#44; png&#41;&#58;</label>
                        <p>&#42;This needs to be submitted only once. <br>&#42;For best viewing, it is best to have the same width and height for your image <br>&#40;i.e. 300px X 300px&#41;.</p>
                        <br>
                        <input type="file" name="myfile[]" id="myfile[]"><br>
                        <?php 
            
                            if ($imageError != "") {
            
                                echo "<h4>$imageError</h4>";
            
                            } else if ($imageSuccess != "") {
                    
                                echo "<h4>$imageSuccess</h4>";
                
                            } else {
                    
                                echo ""; 
                
                            } 
            
                        ?>
                        <br>
                        <button type="submit" name="uploadSubmit" id="uploadSubmit" class="myfrmbtn" onclick="upload_form_error()">Upload</button><br>

                    </div>

                </form>
                
            </div><!-- end of .mp3 form div -->

<?php
  
	$subURL = "";								                                            //variable to hold the submitted url by user
	$uploadURLQuery = "";						                                            //variable to hold the upload url query
    $errorURL   = "";                                                                       //variable to hold the url error message
    $successURL = "";                                                                       //variable to hold the url success message
	
	if (isset($_POST['uploadUrl'])) {
		
		$subURL = clean_input($_POST['vidUrl']);
		$userID         = get_user_id($UserName, $Connect);
		$idUser         = $userID['subID'];
		$uploadURLQuery = "INSERT INTO contributeURL(U_URL, U_Date, subID) VALUES('$subURL', '$uploadDate', $idUser)";
		
		if (!mysqli_query($Connect, $uploadURLQuery)) {
			
			$errorURL = "URL was not submitted. Please try again.";                          //If an error occured when connected to database
			$mysqlReport = mysqli_error($Connect);                                           //The MySQL error is put to this variable
                    
			$file = fopen("errors.txt", "a");                                                //The error file is opened in "a" - append mode
                    
			fwrite($file, $mysqlReport." ".date('m-d-Y')." \r\n\r\n");                       //The error is written to the file with the date appended to it
                    
			$notice = "mysql error has been recorded.";                                      //notice variable is populated
                    
			fclose($file);                                                                   //File is then closed
			
		} else {
			
			$successURL = "URL submitted successfully";
			$file = fopen("url.txt", "a");
			fwrite($file, $subURL." ".$userName." ".$uploadDate."\r\n");
			fclose($file);
			
		}
        
        if ($errorURL == "" && $uploadSuccess != "") {
        
            $to = "broccampbell@gmail.com";
                       
            $subject = "$UserName has uploaded a URL to the Nostalgia Vault";
            
            $message = "URL link: $subURL \r\n";
            
            $message = wordwrap($message, 70);
            
            $headers = "MIME-Version 1.0\r\n";
            $headers .="Content-type: text/plain; charset=iso-8859-1\r\n";
            $headers .="From: " . $UserName . " <" . "thecampbellscorner.com" . ">\r\n";
            $headers .="X-Priority: 1\r\n";
            $headers .="X-MSMail-Priority: High\r\n\r\n";
            
            mail($to, $subject, $message, $headers);
        
        }   else {
            
            $to = "broccampbell@gmail.com";
                       
            $subject = "URL did not upload - Nostalgia Vault";
            
            $message = "$errorURL\r\n";
            $message .= "$report\r\n";
            $message .= "$mysqlReport\r\n\r\n";
            $message .= "$notice\r\n";
    
            
            $message = wordwrap($message, 70);
            
            $headers = "MIME-Version 1.0\r\n";
            $headers .="Content-type: text/plain; charset=iso-8859-1\r\n";
            $headers .="From: " . $UserName . " <" . "thecampbellscorner.com" . ">\r\n";
            $headers .="X-Priority: 1\r\n";
            $headers .="X-MSMail-Priority: High\r\n\r\n";
            
        }
		
	}
        
?>
               
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="urlheader">

                    <?php if ($errorURL != "") {
    
                        echo "<h2>$errorURL</h2><br><p>An error occurred when updating the database</p>";
    
                    } else if ($successURL != "") {
    
                        echo "<h2>$successURL</h2>";
    
                    } else {
    
                        echo "<h2>Upload your video url</h2>";
    
                    }?>

                </div><!-- end of url header div -->

                <div class="col-xs-12 col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-3 col-lg-6">

                    <form action="" method="post">

                        <div class="form-group">

                            <label for="url"><span class="reqfld">&#42;</span>Video url</label><div id="errMsg"></div><br>
                            <input type="text" class="form-control" name="vidUrl" id="vidUrl" placeholder="https://..."><br><br>
                            <button class="myfrmbtn" name="uploadUrl" id="uploadUrl" onclick="form()">Submit</button>
                            <button class="myfrmbtn" onclick="form_reset()">reset</button><div id="resetMsg"></div>

                        </div>

                    </form>

                </div><!-- end of url form div -->

</div><!-- end of wrapping div -->

</div> <!-- end of row -->



<?php
    include('includes/footer.php')
?>