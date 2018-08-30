<?php
    session_start();
    //error_reporting(E_ALL);
    //ini_set('display_errors', 1);
    define("TITLE", "Profile | The Nostalgia Vault");
    include('includes/header.php');
    include('includes/connection.php');
    include('includes/functions.php');

    /*This function checks to see if the user is already logged in and directs them back to the main page.
    This is actually not necessary since their login is shown at the top of the page and if clicked it will
    direct them to the upload page*/

    if (!logged_in()){
        
        header('location: http://www.thecampbellscorner.com/nostalgia/main.php');
        /*header('location: main.php');*/
        exit();
        
    }

    $user = get_user_id($_SESSION['subUserName'], $Connect);
    $info = getUserInfo($user['subID'], $Connect);
    $cbuteData = getContribution($user['subID'], $Connect);
    $update_success = "";
    $update_error_old = "";
    $update_error_new = "";
    $update_error_all = "";

    if (isset($_POST['pass_update'])) {
        
        $oldPass    = clean_input($_POST['oldpass']);
        $newPass    = clean_input($_POST['newpass']);
        $checkPass  = clean_input($_POST['newpass2']);
        
        if ($oldPass === "" || $newPass === "" || $checkPass === "") {
            
            $update_error_all = "All fields are required";
            
        } else {
        
        $passQuery          = mysqli_query($Connect, "SELECT subPassword FROM subscribe WHERE subID = $user[subID];");
        $passQueryResult    = mysqli_fetch_assoc($passQuery);
        
        if (!password_verify($oldPass, $passQueryResult['subPassword'])){       //User supplied password and database password are checked against one another
                
                $update_error_old = "Old Password does not match";                       //If the don't match then the login error variable is set to this message
                
            } else if ($newPass !== $checkPass) {
            
                $update_error_new = "New Passwords do not match";
            
            } else {
            
                $cryptPass = password_hash("$newPass", PASSWORD_DEFAULT);
                $updateQuery = mysqli_query($Connect, "UPDATE subscribe SET subPassword = '$cryptPass' WHERE subID = $user[subID];");
                $update_success = "Password Changed";
            
            }
            
        }
        
    }

?>

<div class="row log">
    <div class="col-xs-12">
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

<div class="row">
   
   <div class="col-sm-offset-2 col-sm-8" id="proheader">
       
       <h1><?php echo $info['subUserName']; ?>&#39;s Profile</h1>
       
   </div>
   
</div>
   
<div class="row">
    
    <div class="col-xs-12 col-sm-offset-2 col-sm-8 col-md-offset-2 col-md-8 col-lg-offset-2 col-lg-8" id="proMain">

      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
          <h2>Member Info</h2>
      </div>
      
    
      

       <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 memTbl table-responsive">
        
        <table class="table">

            <tbody>
                <tr>
                <th>First Name:</th>
                <td><?php echo $info['subFirstName']; ?></td>
                
                <th>Last Name:</th>
                <td><?php echo $info['subLastName']; ?></td>
                
                <th>Email:</th>
                <td><?php echo $info['subEmail']; ?></td>
                </tr>
                <tr>
                <th>User Name:</th>
                <td><?php echo $info['subUserName']; ?></td>
                
                <th>Date Joined:</th>
                <td><?php echo $info['subJoinDate']; ?></td>
                </tr>
                <tr>

                </tr>
            </tbody>

            <tfoot>
                <tr>
                    <td id="resetbtn"><button type="button" class="mytblbtn" onclick="showReset()">Change Password</button></td>
                    <td class="proData" colspan="2" id="resetmsg">
                    
                        <?php if ($update_error_all != "" && $update_error_old == "" && $update_error_new == "") { 

                                    echo $update_error_all;

                                } else if ($update_error_all == "" && $update_error_old != "" && $update_error_new == "") {

                                    echo $update_error_old;

                                } else if ($update_error_all == "" && $update_error_old == "" && $update_error_new != "") {

                                    echo $update_error_new;

                                } else if ($update_error_all == "" && $update_error_old == "" && $update_error_new == "" && $update_success != "") {

                                    echo $update_success;

                        } ?>
                                                            
                    </td>
                </tr>
            </tfoot>

        </table>

        </div>
       
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div id="reset">
                <h2>Reset Your Password</h2>
                <form action="profile.php" method="post">
                <div class="form-group col-sm-4">
                <label class="control-label" for="oldpass">Old Password:</label>
                <input class="form-control" type="password" id="oldpass" name="oldpass"><br>
                <label class="control-label" for="newpass">New Password:</label>
                <input class="form-control" type="password" id="newpass" name="newpass"><br>
                <label class="control-label" for="newpass2">Re-enter Password:</label>
                <input class="form-control" type="password" id="newpass2" name="newpass2"><br>
                <button type="submit" id="pass_update" name="pass_update">Submit</button>
                <button onclick="hideReset()">Cancel</button>
                </div>
                </form>
            </div>
        </div>
       
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            
            <h3>Member Contributions</h3>
            
        </div>        

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 table-responsive">
            <table class="table">
            <thead>
                <th>Track</th>
                <th>Album</th>
                <th>Composer</th>
                <th>Date Added</th>
            </thead>
            <tbody>

                <?php while ($row = mysqli_fetch_assoc($cbuteData)){

                    echo "<tr><td>$row[C_File_Name]</td>";
                    echo "<td>$row[C_Album_Name]</td>";
                    echo "<td>$row[C_Composer]</td>";
                    echo "<td>$row[C_Date]</td></tr>";

                } ?>

            </tbody>
            </table>
        </div>
        </div>
    </div>

<script src="includes/nostalgia_js.js"></script>

<?php
    include('includes/footer.php');
?>