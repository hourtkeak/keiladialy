<?php

require_once 'dbconfig.php';

class USER
{	

	private $conn;
	
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }
	
	public function runQuery($sql)
	{
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}
	
	public function lasdID()
	{
		$stmt = $this->conn->lastInsertId();
		return $stmt;
	}

	function sec_session_start() {
	    $session_name = 'sec_session_id';   // Set a custom session name 
	    $secure = SECURE;

	    // This stops JavaScript being able to access the session id.
	    $httponly = true;

	    // Forces sessions to only use cookies.
	    if (ini_set('session.use_only_cookies', 1) === FALSE) {
	        header("Location: ../index.php?error=Could not initiate a safe session (ini_set)");
	        exit();
	    }

	    // Gets current cookies params.
	    $cookieParams = session_get_cookie_params();
	    session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);

	    // Sets the session name to the one set above.
	    session_name($session_name);

	    session_start();            // Start the PHP session 
	    session_regenerate_id();    // regenerated the session, delete the old one. 
	}
	
	public function register($email, $uname, $upass,$code,$fname, $lname, $dname, $position, $ulevel, $uphoto)
	{
		try
		{							
		
		// Create a random salt
        $random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));

        // Create salted password 
        $password = hash('sha512', $upass. $random_salt);


		$stmt = $this->conn->prepare("INSERT INTO tbl_users(userName, userEmail, userPass, saltPass, tokenCode, firstName, LastName, displayName, position, level, userPhoto) 
			                                             VALUES(:user_name, :user_email, :user_pass, :salt_pass, :actave_code, :first_name, :last_name, :display_Name, :user_position, :user_level, :user_photo)");
		$stmt->bindparam(":user_name",$uname);
		$stmt->bindparam(":user_email",$email);
		$stmt->bindparam(":user_pass",$password);
		$stmt->bindparam(":salt_pass",$random_salt);
		$stmt->bindparam(":actave_code",$code);
		$stmt->bindparam(":first_name",$fname);
		$stmt->bindparam(":last_name",$lname);
		$stmt->bindparam(":display_Name",$dname);
		$stmt->bindparam(":user_position",$position);
		$stmt->bindparam(":user_level",$ulevel);
		$stmt->bindparam(":user_photo",$uphoto);
		$stmt->execute();	
		return $stmt;
		
		}
		catch(PDOException $ex)
		{
			echo $ex->getMessage();
		}
	}
	

public function checkbrute($user_id) {

    // Get timestamp of current time 
    $now = time();

    // All login attempts are counted from the past 2 hours. 
    $valid_attempts = $now - (2 * 60 * 60);

    if ($stmt =  $this->conn->prepare("SELECT time 
                                  FROM login_attempts 
                                  WHERE user_id = :uid AND time > :valid_attempts")) {
        $stmt->execute(array(':uid' => $user_id, ':valid_attempts'=> $valid_attempts));

       $count_row = $stmt->rowCount();

        // If there have been more than 5 failed logins 
        if ($count_row  > 5) {
            return true;
        } else {
            return false;
        }
    } else {
        // Could not create a prepared statement
       	header("Location: login.php?error");
        exit();
    }
}

public function login($email,$upass)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT * FROM tbl_users WHERE userEmail=:email_id");
			$stmt->execute(array(':email_id'=>$email));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);

			// hash the password with the unique salt.
       		 $password = hash('sha512', $upass.$userRow['saltPass']);
			
			if($stmt->rowCount() == 1)
			{

				if($userRow['userStatus']=="Y")
				{
				
					if ($this->checkbrute($userRow['userID']) == true) {

						// Account is locked 
                		// Send an email to user saying their account is locked 
                		//return false;
                		header("Location: index.php?error=Account is Locked! Try agian after 2 hours");
						exit;

                	}else{

                	;

						if($userRow['userPass']==$password)
						{
							// Password is correct!
		                    // Get the user-agent string of the user.
		                    $user_browser = $_SERVER['HTTP_USER_AGENT'];

		                    // XSS protection as we might print this value
		                    $user_id = preg_replace("/[^0-9]+/", "", $user_id);
		                    $_SESSION['userSession'] = $userRow['userID'];

		                    // XSS protection as we might print this value
		                    $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);

		                    $_SESSION['username'] = $userRow['userName'];
		                    $_SESSION['login_string'] = hash('sha512', $password . $user_browser);

		                    // Login successful. 
		                    return true;
						}
						else
						{
							$user_id = $userRow['userID'];
							$now = time();
	                    	if (!$this->conn->query("INSERT INTO login_attempts(user_id, time) 
	                                    VALUES ('$user_id', '$now')")) {
	                        	header("Location: index.php?error");
	                        	exit();
	                    	}
	                    	//Testign Compare  Password 
	                    	//echo $userRow['userPass'].'<br/>';
                			//echo $password.'<br/>';
							header("Location: index.php?error= Password is wrong! Try agian!");
							exit;
						}
					}
				}
				else
				{
					header("Location: index.php?inactive");
					exit;
				}	
			}
			else
			{
				header("Location: index.php?error= No user registered");
				exit;
			}		
		}
		catch(PDOException $ex)
		{
			echo $ex->getMessage();
		}
	}

public function is_logged_in1()
	{
		if(isset($_SESSION['userSession']))
		{
			return true;
		}
	}

public function is_logged_in(){
	
	// Check if all session variables are set 
    if (isset($_SESSION['userSession'], $_SESSION['username'], $_SESSION['login_string'])) {
        $user_id = $_SESSION['userSession'];
        $login_string = $_SESSION['login_string'];
        $username = $_SESSION['username'];

        // Get the user-agent string of the user.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];

        if ($stmt = $this->conn->prepare("SELECT userPass
				      FROM tbl_users 
				      WHERE userID = :user_id LIMIT 1")) {
         
         
            $stmt->execute(array(':user_id' => $user_id));  
        	$rs_user = $stmt->fetch(PDO::FETCH_ASSOC);
          

            if ($stmt->rowCount() == 1) {
                // If the user exists get variables from result.
                $password = $rs_user['userPass'];
                $login_check = hash('sha512', $password . $user_browser);

                if ($login_check == $login_string) {
                    // Logged In!!!! 
                    return true;
                } else {
                    // Not logged in 
                    return false;
                }
            } else {
                // Not logged in 
                return false;
            }
        } else {
            // Could not prepare statement
            header("index.php?error=Database error: cannot prepare statement");
            exit();
        }
    } else {
        // Not logged in 
        return false;
    }
}

public function redirect($url)
	{
		header("Location: $url");
	}
	
	public function logout()
	{
		session_destroy();
		$_SESSION['userSession'] = false;
	}
	
	function send_mail($email,$message,$subject)
	{						
		require_once('mailer/class.phpmailer.php');
		$mail = new PHPMailer();
		$mail->IsSMTP(); 
		$mail->SMTPDebug  = 0;                     
		$mail->SMTPAuth   = true;                  
		$mail->SMTPSecure = "ssl";                 
		$mail->Host       = "sg2plcpnl0208.prod.sin2.secureserver.net";      
		$mail->Port       = 465;             
		$mail->AddAddress($email);
		$mail->Username="it@tramkak.com";  
		$mail->Password="Khmer0505";            
		$mail->SetFrom('it@tramkak.com','Hourt Keak');
		$mail->AddReplyTo("it@tramkak.com","hourtkeak");
		$mail->Subject    = $subject;
		$mail->MsgHTML($message);
		$mail->Send();
	}	
}