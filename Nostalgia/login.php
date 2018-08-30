<?php
    session_start();//Make sure that the session_start is at the TOP of all pages
    //error_reporting(E_ALL);
    //ini_set('display_errors', 1);
    include('includes/connection.php');
    include('includes/functions.php');

    /*This function checks to see if the user is already logged in and directs them back to the main page.
    This is actually not necessary since their login is shown at the top of the page and if clicked it will
    direct them to the upload page*/

    if (logged_in()){
        
        header('location: http://www.thecampbellscorner.com/nostalgia/main.php');
        /*header('location: main.php');*/
        exit();
        
    }

    /*Setting the variables to empty for the login submission*/

    $Login_UserName     = "";   //variable to hold the login name or user name
    $db_UserName        = "";   //variable to hold the database stored user name
    $Login_Password     = "";   //variable to hold the login password
    $db_Password        = "";   //variable to hold the database stored password
	$login_error		= "";   //variable to hold the login error statements

    /*This is the set of code that runs when the login submission has been initiated.
    The credentials are stored then a query against the database for the matching credentials
    are run and stored. The passwords are then verified - if the user does exist - and if so
    the user is logged in to the site*/

    if (isset($_POST['login_submit'])){                             //when the submit button has been clicked
        
        $Login_UserName = clean_input($_POST['UserName']);          //User supplied login user name is stored
        $Login_Password = clean_input($_POST['Password']);          //User supplied password is stored
        
        if (user_db_check($Login_UserName, $Connect)){              //database is connected and queried
            
            $passwordQ      = mysqli_query($Connect, "SELECT subPassword FROM subscribe WHERE subUserName = '$Login_UserName'");    //User password record is pulled from database
            $db_Password    = mysqli_fetch_assoc($passwordQ);       //database password is stored
            
            if (!password_verify($Login_Password, $db_Password['subPassword'])){        //User supplied password and database password are checked against one another
                
                $login_error = "Password does not match";           //If the don't match then the login error variable is set to this message
                
                }   else    {
                
                $_SESSION['subUserName'] = $Login_UserName;         //If they do match then the user is logged in and directed to the main.php page
                /*header('Location: http://www.thecampbellscorner.com/nostalgia/main.php');*/
                header('Location: main.php');
                exit();
                }
        
        }   else    {
            
         $login_error = "User Name does not match";                 //If the user does not exist in the database then the login error variable holds this message
            
        }
        
        }                                                           //End of login code

    define("TITLE", "User Login | Nostalgic Vault");                //Set the title of the page
    include('includes/header.php');                                 //Including the header.php file

?>

<div class="row log">
    <div class="col-xs-12">
        <nav class="navleft">
    
            <ul>
                <li><a href="main.php"><img src="images/sitelogo_xs.png" alt="The Vault" class="img-responsive"></a></li>
            </ul>
    
        </nav><!-- end of nav -->
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="signupheader">
         
        <?php if ($login_error == ""){      //As long as the login error variable is blank the header remains?>
         
            <h2>Log into the vault</h2>
         
        <?php } else {
    
            echo "<h2>$login_error</h2>";   //Else the login error variable is displayed to show what error occurred  

        } ?>
         
    </div>
</div>

<div class="row" id="loginForm">
    <div class="col-xs-12 col-sm-12 col-md-offset-3 col-md-6 col-lg-offset-4 col-lg-4">
            <form method="post" action="login.php">
               <div class="form-group">
                <label for="username">User Name</label>
                <input type="text" class="form-control" id="username" name="UserName"><br>
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="Password"><br>
                <!-- <input type="checkbox" id="staylogged" name="ExtendedStay">
                <label for="staylogged">Keep me logged in</label><br><br> -->
                <button type="submit" class="myfrmbtn" name="login_submit">Log in</button>
                <a href="user_get.php">forgot user name</a>
                <a href="passwrd_reset.php">forgot password</a>
                </div>
            </form>
    </div>
</div>

<?php

    include('includes/footer.php');

?>