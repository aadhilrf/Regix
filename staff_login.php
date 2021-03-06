<!DOCTYPE html>
<?php
if(isset($_POST['submit']))
{
include_once 'functions.php';
sec_session_start();
require("dbconnect.php");
$regix_id=$_POST['regix_id'];
$regix_staff_id=$_POST['regix_staff_id'];
$staff_password=$_POST['staff_password'];
$captcha_verify=$_POST['g-recaptcha-response'];
$url = "https://www.google.com/recaptcha/api/siteverify";
$data = array("secret"=>"6Lci2BUUAAAAAOq7-VF2z_7XdtcQr3VuhXC91BR-","response"=>$captcha_verify);
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
$res=json_decode($result,true);
if($res['success']==true)
{
    try{
$pdo = new PDO($dsn, $user, $pass, $opt);
$tbl=$regix_id."_staff_list";
$stmt=$pdo->prepare("select password from ".$tbl." where username=?");
$stmt->execute([$regix_staff_id]);
if($row=$stmt->fetch())
{
    if($staff_password==$row["password"])
    {
        $user_browser = $_SERVER['HTTP_USER_AGENT'];
        $_SESSION['login_string'] = hash('sha512',$row["password"].$user_browser);
        $_SESSION['regix_staff_id']=$_POST['regix_staff_id'];
        $_SESSION['regix_id']=$regix_id;
        header("Location: staff_panel.php");
    }
    else
    {
        $wrong=true;
    }
}
else
{
    $wrong=true;
}
}
catch(PDOException $p)
{
    echo "error";
}
finally
{
    $pdo=null;
}   
}
else
{
    $retry=true;
}
}
?>
<html lang="en" style="height:100%">
<head>
  <meta charset="utf-8">
  <meta name="description" content="Miminium Admin Template v.1">
  <meta name="author" content="Isna Nur Azis">
  <meta name="keyword" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Regix staff login</title>
  <link rel="stylesheet" type="text/css" href="asset/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="asset/css/plugins/font-awesome.min.css"/>
  <link rel="stylesheet" type="text/css" href="asset/css/plugins/simple-line-icons.css"/>
  <link rel="stylesheet" type="text/css" href="asset/css/plugins/animate.min.css"/>
  <link rel="stylesheet" type="text/css" href="asset/css/plugins/icheck/skins/flat/aero.css"/>
  <link href="asset/css/style.css" rel="stylesheet">
  <link rel="shortcut icon" href="images/logo.png">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
    <body>
      <div class="col-md-12">
        <form id="myform" class="form-signin" method="post">
          <div class="panel periodic-login">
              <div class="panel-body text-center">
                  <h1 class="atomic-symbol">Regix</h1>
                  <p class="atomic-mass">v0.1 beta</p>
                  <b><p class="element-name">by Digital_Brains</p></b>
                  <b><i class="icons icon-arrow-down"></i></b>
                  <div class="form-group form-animate-text" style="margin-top:40px !important;">
                    <input type="text" class="form-text" name="regix_id" required autofocus>
                    <span class="bar"></span>
                    <label>Regix_id</label>
                  </div>
								<div class="form-group form-animate-text" style="margin-top:40px !important;">
                    <input type="text" class="form-text" name="regix_staff_id" required autofocus>
                    <span class="bar"></span>
                    <label>Regix Staff_id</label>
                  </div>
                  <div class="form-group form-animate-text" style="margin-top:40px !important;">
                    <input type="password" class="form-text" name="staff_password" required>
                    <span class="bar"></span>
                    <label>Password</label>
                  </div>
                  <?php
                  if($wrong==true)
                  {
                      $wrong=false;
                  ?> 
                  <div class="alert alert-danger alert-dismissable">
                      <a href="#" class="close" data-dismiss="alert" aria-label="close">X</a>
                      <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> Wrong Credentials
                    </div>   
                  <?php
                  }
                  ?>
                  <?php
                  if($retry==true)
                  {
                      $retry=false;
                  ?> 
                  <div class="alert alert-danger alert-dismissable">
                      
                      <a href="#" class="close" data-dismiss="alert" aria-label="close">X</a>
                      <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>  Retry the captcha
                    </div>   
                  <?php
                  }
                  ?>
                  <div class="g-recaptcha" data-sitekey="6Lci2BUUAAAAAJqnreeCFEULyJzE6OpvZ2IjcEl9"></div>
                  <input type="submit" name="submit" class="btn col-md-12" value="SignIn"/>
              </div>
                <div class="text-center" style="padding:5px;">
                    <a href="forgotpass.html">Forgot Password </a>
                    <a href="reg.html">| Signup</a>
                </div>
          </div>
        </form>
        </div>
        <script src="asset/js/jquery.min.js"></script>
        <script src="asset/js/bootstrap.min.js"></script>
   </body>
</html>