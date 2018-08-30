<?php
    session_start();
    //error_reporting(E_ALL);
    //ini_set('display_errors', 1);
    define("TITLE", "Sign Up | The Nostalgia Vault");
    include('includes/header.php');
    include('includes/connection.php');
    include('includes/functions.php');

    /*This function checks to see if the user is already logged in and directs them back to the main page.
    This is actually not necessary since their login is shown at the top of the page and if clicked it will
    direct them to the upload page*/

    if (logged_in()){
        
        header('location: http://www.thecampbellscorner.com/nostalgia/main.php');
        exit();
        
    }

    /*Setting the variables to empty for the sign up submission*/

    $FirstName  = "";                   //variable to hold the first name of user
    $LastName   = "";                   //variable to hold the last name of user
    $Email      = "";                   //variable to hold the email of user
    $UserName   = "";                   //variable to hold the user name of the user
    $Password   = "";                   //variable to hold the password of the user
    $RePassword = "";                   //variable to hold the match password of the user
    $date       = getdate();            //variable to hold the join date of the user
    $year       = $date['year'];        //variable to hold the year of the current date
    $month      = $date['mon'];         //variable to hold the month of the current date
    $day        = $date['mday'];        //variable to hold the day of the current date
    $realDate   = $year.$month.$day;    //variable to hold the date in a format acceptable for the database
	$error		= "";                   //variable to hold any errors that occur
    $report     = "";                   //variable to hold the report of the errors that occur
    $success    = "";                   //variable to hold the success message

    /*This code is run when the user clicks the sign up button. Each supplied bit of data is stored into the variables and then validated
    for accuracy. They each run through a function that cleans then up for security. The password is encrypted and then another
    series of checks are executed. First the user is checked to see if he or she already exist in the database. If they are then
    an error is returned, but if not they a query is put together and then ran against the database. If any errors occur when
    interacting with the database they are kept in the report variable and uploaded/written to a file. When the user is successfully
    entered into the database a success message is given to the user*/

    if (isset($_POST['signup_submit'])){
        
        $FirstName  = clean_input($_POST['FirstName']);		
        $LastName   = clean_input($_POST['LastName']);
        $Email      = clean_input($_POST['Email']);
        $UserName   = clean_input($_POST['UserName']);
        $Password   = clean_input($_POST['Password']);
        $RePassword = clean_input($_POST['PasswordCheck']);
        
    
			if (strlen($FirstName) > 50){                                //If first name is greater than 50 characters it throws an error
				
				$error = "First Name is too long";
				
			} else if (strlen($LastName) > 50){                          //If last name is greater than 50 characters it throws an error
				
				$error = "Last Name is too long";
				
			} else if (!filter_var($Email, FILTER_VALIDATE_EMAIL)){      //If email address is not a valid email address an error is thrown
				
				$error = "Please provide a valid email address.";
				
			} else if (strlen($Email) > 100){                            //If email is greater than 100 characters an error is thrown
				
				$error = "Email address is too long. Please provide a shorter valid email address.";
				
			} else if (strlen($UserName) > 20){                          //If user name is greater than 20 characters an error is thrown
				
				$error = "User name is too long. User name can only be 20 characters.";
				
			} else if ($Password !== $RePassword){                       //If the passwords do not match an error is thrown
				
				$error = "Passwords do not match";
				
			} else {
				
				$error = "Welcome ".$UserName;                          //error variable in this case is being used as a success message. If everything passes a welcome message is given
				
			}
        
        $safePass = password_hash("$Password", PASSWORD_DEFAULT);       //Password is encrytped
        
        if (user_db_check($UserName, $Connect)){                        //function checks if user name already exists. If it does an error is thrown
            
            $error = "User already exists";
                
            
        } else if (email_db_check($Email, $Connect)) {                                                        //If the email does already exist then another error is thorwn
            
            $error = " user email already exists";
                
        } else {                                                                                             //If the user does not already exist then the query below is put to the query variable
        
            $insertQuery = "INSERT INTO subscribe(subFirstName, subLastName, subEmail, subUserName, subPassword, subJoinDate) VALUES('$FirstName', '$LastName', '$Email', '$UserName', '$safePass', $realDate)";

                if (mysqli_query($Connect, $insertQuery)){              //If the query is true then the statement ran against the database and the user is created
                    
                    $success = "Welcome ".$UserName."! You have become a member of the Nostalgia Vault!";   //The user is welcomed and congradulated
                    header('location: http://www.thecampbellscorner.com/nostalgia/main.php');               //The user is then sent to the main page
                    
                }   else    {
                    
                    $error = "Membership not created. Please try again.";                                   //If an error occured when connected to database
                    $report = mysqli_error($Connect);                                                       //The MySQL error is put to this variable
                    
                    $file = fopen("errors.txt", "a");                                                       //The error file is opened in "a" - append mode
                    
                    fwrite($file, $report." ".date('m-d-Y'));                                               //The error is written to the file with the date appended to it
                    
                    $notice = "Error has been recorded.";                                                   //notice variable is populated
                    
                    fclose($file);                                                                          //File is then closed
                    
                } 
		
        } 
    
    }                                                                                                       //Sign up code end
	
?>

<div class="row log">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      
        <nav class="navleft">
    
            <ul>
                <li><a href="main.php"><img src="images/sitelogo_xs.png" alt="Home" class="img-responsive"></a></li>
            </ul>
    
        </nav><!-- end of nav -->      
       
        <nav class="navright">
            <ul><?php if (!logged_in()) {                           //If the user is not logged in then the regular 'Login' & 'Sign Up' links are shown ?>
                <li><a href="login.php">Login</a></li>
                <li> | </li>
                <li><a href="signup.php">Sign Up</a></li>
                <?php } else {                                      //If the user is logged in then his or her user name is displayed & the 'Log Out' link ?>
                <li><a href="contribute.php">Contribute</a></li>
                <li> | </li>
                <li><a href="logout.php">Log Out</a></li> <?php } ?>
            </ul>
        </nav>
</div>
</div>   

<div class="row">
   <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="signupheader">
     
       <?php if ($error == ""){                 //If the sign up error variable is empty the usual header is displayed ?> 
    
            <h2>Sign up and become a member of the vault&#33;</h2>

       <?php } else if (!$success == ""){       //If success is not empty then the success message is shown else the error message is displayed
    
            echo "<h3 class=\"text-success\">$success</h3>";

        } else { 
    
            echo "<h3 class=\"text-danger\">$error</h3>"; } ?>
   </div>
</div>
<div class="row" id="signupForm">
    <div class="col-xs-12 col-sm-offset-3 col-sm-6 col-md-offset-4 col-md-4 col-lg-offset-4 col-lg-4">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
               <div class="form-group">
                <label for="fname">First Name</label>
                <input type="text" class="form-control" id="fname" name="FirstName">
                <label for="lname">Last Name</label>
                <input type="text" class="form-control" id="lname" name="LastName">
                <label for="email">Email</label>
                <input type="text" class="form-control" id="email" name="Email">
                <label for="userName">User Name <span class = "note">&#40;20 Characters Max&#41;</span></label>
                <input type="text" class="form-control" id="userName" name="UserName">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="Password">
                <label for="passwordcheck">Re-enter Password</label>
                <input type="password" class="form-control" id="passwordcheck" name="PasswordCheck"><br>
                <!-- <textarea name="ContactMessage" class="form-control" id="message"></textarea><br> -->
                <button type="submit" class="myfrmbtn" name="signup_submit">Sign Up</button><br>
                </div>
            </form>
    </div>
</div>

<?php
    include('includes/footer.php');
?>