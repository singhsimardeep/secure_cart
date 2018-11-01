<?php
class main{
  private $_db;
  // Constructor definition
  public function __construct(){
    $this->_db = new PDO('mysql:host=localhost;dbname=mycart', 'root', '');
    session_start();
    // Generate CSRF using md5 and storage in session
    $csrf = md5(openssl_random_pseudo_bytes(32));
    if (!isset($_SESSION['csrf'])){
        $_SESSION['csrf'] = $csrf;
    }
    // Session timeout handling. Session will be destroyed after 15 secs of inactivity
    $this->setCookie();
    if(isset($_SESSION['LAST_ACTIVE']) && $_SESSION['LAST_ACTIVE']+60 <= time() ){
      session_destroy();
    }
    $_SESSION['LAST_ACTIVE'] = time();
  }

  // Function to register user into tbl_users table
  public function register($POST)
  {
    if (isset($POST['password'],$POST['firstname'],$POST['lastname'],$POST['address'],$POST['email'])){
        $password = $POST["password"];
        $firstname = strip_tags($POST["firstname"]);
        $lastname = strip_tags($POST["lastname"]);
        $address = strip_tags($POST["address"]);
        $email = strip_tags($POST["email"]);
        // Secure password handling using salting implemented by password_hash
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO tbl_users (firstname, lastname, address ,email, password) VALUES (?,?,?,?,?)";
        try{
          $result = $this->_db->prepare($sql);
          $result->execute(array($firstname,$lastname,$address,$email,$hash));
          if ($result->rowCount() == 1){
            return true;
          }
          else {
            return false;
          }
        }
        catch(PDOException $e)
        {
          echo $e->getMessage();
      }
    }
    else{
      return false;
    }
  }

  // User authentication
  public function authenticate($POST)
  {
    $user = $POST["loginemail"];
    $password = $POST["loginpassword"];
    $sql = "SELECT * FROM tbl_users  WHERE email = ?";
    try {
      $result = $this->_db->prepare($sql);
      $result->execute(array($user));
      $fetchData = $result->fetchAll();
      $fetchedPass= $fetchData[0]['password'];
        # Verification of password using password_verify
        if (password_verify($password,$fetchedPass) && $result->rowCount() == 1){
          $_SESSION['user'] = $user;
          $_SESSION['userid'] = $fetchData[0]['id'];
          $_SESSION['userdetails']= $fetchData[0];
          return true;
        }
        else {
          return false;
        }
    }
    catch (PDOException $e) {
      echo $e->getMessage();
    }

  }

  // function to setup cookie for user
  public function setCookie(){
    $time = time() + (86400 * 180 );
    if (!isset($_COOKIE['cookie_user'])){
        $cookie_user = md5(openssl_random_pseudo_bytes(32));
        setcookie("cookie_user", $cookie_user, $time);
    }
    if(isset($_SESSION['userid'])){
      $user_id=$_SESSION['userid'];
      $logged_in=1;
    }
    if(!isset($_SESSION['userid'])){
      $user_id=0;
      $logged_in=0;
    }
    $date=date('Y-m-d H:i:s', $time);
      $this->addCookies($_COOKIE['cookie_user'],$date,$user_id,$logged_in);
  }

  // Updation and addition of cookie in tbl_cookies table
  public function addCookies($cookie_user,$time,$user_id,$logged_in){
    $sql = "INSERT INTO tbl_cookies (cookie_user,id_user,logged_in,login_expire) VALUES(?,?,?,?) ON DUPLICATE KEY UPDATE id_user = ? , logged_in = ?";
    try{
      $result = $this->_db->prepare($sql);
      $result->execute(array($cookie_user, $user_id, $logged_in, $time,$user_id,$logged_in));

    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }
  // Insertion of basket into tbl_basket database after validating the item
  public function addCart($id,$amount){
    $cookie_user = $_COOKIE['cookie_user'];
    $id=(int)$id;
    if((int)$amount>0){
      $sql = "SELECT * from tbl_items WHERE id = ?";
      try {
        $result = $this->_db->prepare($sql);
        $result->execute(array($id));
        if ($result->rowCount() == 1){
          $sql = "INSERT INTO tbl_basket (cookie_user, id_items, amount) VALUES (?,?,?)";
            $result = $this->_db->prepare($sql);
            $result->execute(array($cookie_user,$id,$amount));
            }
        else {
          echo "Product not found";
          }

      }
      catch(PDOException $e){
        echo $e->getMessage();
      }
    }

  }
  // Fetch basket from tbl_basket from table
  public function getBasket(){
    $sql = "SELECT tb.id_items, sum(tb.amount) as qty,ti.name,ti.price as ttl from tbl_basket as tb inner join tbl_items as ti on tb.id_items = ti.id
WHERE cookie_user = ? group by tb.id_items, ti.name ";
    try {
      $result = $this->_db->prepare($sql);
      $result->execute(array($_COOKIE['cookie_user']));
      $fetchData = $result->fetchAll();
      return $fetchData;
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }

  // Fetch Item details from tbl_items table
  public function getItem($id){
    $sql = "SELECT name, price, stock from tbl_items WHERE id = ?";
    try {
      $result = $this->_db->prepare($sql);
      $result->execute(array($id));
      $fetchData = $result->fetchAll();
      return $fetchData;
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }

  public function getItems()
  {
    return $this->_db->query('SELECT * from tbl_items');
  }



  // Function to add order into tbl_orders table
  public function addOrder($amount)
  {

    $sql = "INSERT INTO tbl_orders (id_user, amountprice) VALUES (?,?)";

    try{
      $result = $this->_db->prepare($sql);
      $result->execute(array($_SESSION['userid'],$amount));
      if ($result->rowCount() == 1){
        $sql = "DELETE FROM tbl_basket WHERE cookie_user = ?";
        $result = $this->_db->prepare($sql);
        $result->execute(array($_COOKIE['cookie_user']));
      }
    }
    catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  // Fetch orders from tbl_orders table
  public function getOrders()
  {
    $sql = "SELECT id,amountprice,orderdate FROM tbl_orders WHERE id_user = ? ";
    try{
      $result = $this->_db->prepare($sql);
      $result->execute(array($_SESSION['userid']));
      $fetchData = $result->fetchAll();
      return $fetchData;
    }
    catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  // Update tbl_basket table based on user basket

  public function updateBasket($id,$amount){
    try {
    $sql = "DELETE FROM tbl_basket WHERE cookie_user = ? and id_items= ?";
    $result = $this->_db->prepare($sql);
    $result->execute(array($_COOKIE['cookie_user'],$id));
    $result->debugDumpParams();
    if ($result->rowCount() >= 1 && $amount!=0){
      $sql = "INSERT INTO tbl_basket (cookie_user,id_items, amount) VALUES (?,?,?)";
      $result = $this->_db->prepare($sql);
      $result->execute(array($_COOKIE['cookie_user'],$id,$amount));
      $result->debugDumpParams();
      }
    }
    catch (PDOException $e) {
      echo $e->getMessage();
    }
  }


  //fetch items from tbl_items
  public function getProducts($id)
   {
     $sql = "SELECT * FROM tbl_items WHERE id = ? ";
     try{
       $result = $this->_db->prepare($sql);
       $result->execute(array($id));
       $fetchData = $result->fetchAll();
       return $fetchData;
     }
     catch (PDOException $e) {
       echo $e->getMessage();
     }
   }



}

 ?>
