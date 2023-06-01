<?php
class OTP {
  /* PART 1 - DATABASE SUPPORT FUNCTIONS */
  protected $pdo = null;
  protected $stmt = null;
  public $error = "";
  public $lastID = null;
  function __construct() {
  // __construct() : connect to the database
  // PARAM : DB_HOST, DB_CHARSET, DB_NAME, DB_USER, DB_PASSWORD

    // ATTEMPT CONNECT
    try {
      $str = "mysql:host=" . DB_HOST . ";charset=" . DB_CHARSET;
      if (defined('DB_NAME')) { $str .= ";dbname=" . DB_NAME; }
      $this->pdo = new PDO(
        $str, DB_USER, DB_PASSWORD, [
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
          PDO::ATTR_EMULATE_PREPARES => false
        ]
      );
      return true;
    }

    // ERROR - DO SOMETHING HERE
    // THROW ERROR MESSAGE OR SOMETHING
    catch (Exception $ex) {
      print_r($ex);
      die();
    }
  }

  function __destruct() {
  // __destruct() : close connection when done

    if ($this->stmt !== null) { $this->stmt = null; }
    if ($this->pdo !== null) { $this->pdo = null; }
  }

  function exec($sql, $data=null) {
  // exec() : run insert, replace, update, delete query
  // PARAM $sql : SQL query
  //       $data : array of data

    try {
      $this->stmt = $this->pdo->prepare($sql);
      $this->stmt->execute($data);
      $this->lastID = $this->pdo->lastInsertId();
    } catch (Exception $ex) {
      $this->error = $ex;
      return false;
    }
    $this->stmt = null;
    return true;
  }

  function fetch ($sql, $cond=null, $sort=null) {
  // fetch() : perform select query (single row expected)
  //           returns an array of column => value
  // PARAM $sql : SQL query
  //       $cond : array of conditions
  //       $sort : custom sort function

    $result = [];
    try {
      $this->stmt = $this->pdo->prepare($sql);
      $this->stmt->execute($cond);
      if (is_callable($sort)) {
        while ($row = $this->stmt->fetch(PDO::FETCH_NAMED)) {
          $result = $sort($row);
        }
      } else {
        while ($row = $this->stmt->fetch(PDO::FETCH_NAMED)) {
          $result = $row;
        }
      }
    } catch (Exception $ex) {
      $this->error = $ex;
      return false;
    }
    // Return result
    $this->stmt = null;
    return count($result)==0 ? false : $result ;
  }

  /* PART 2 - GENERATE RANDOM OTP */
  function generate ($id) {
  // generate() : create a new OTP
  // PARAM $id : user ID, order ID, or just any unique transaction ID

    // Create random password
    //$alphabets = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    // Change this alphabets set as needed. For example, if you want numbers only
    $alphabets = "0123456789";
    $count = strlen($alphabets) - 1;
    $pass = "";
    for ($i = 0; $i < OTP_LEN; $i++) {
      $pass .= $alphabets[rand(0, $count)];
    }

    // Database entry
    $sql = "REPLACE INTO `otp` (`id`, `otp_pass`, `otp_timestamp`, `otp_tries`) VALUES (?, ?, ?, ?)";
    $data = [$id, $pass, date("Y-m-d H:i:s"), 0];
    $ok = $this->exec($sql, $data);

    // Result
    return [
      "status" => $ok ? 1 : 0,
      "pass" => $ok ? $pass : ""
    ];
  }

  /* PART 3 - CHALLENGE OTP */
  function challenge ($id, $pass) {
  // challenge() : challenge the OTP

    // Get the OTP entry
    $sql = "SELECT * FROM `otp` WHERE `id`=?";
    $data = [$id];
    $otp = $this->fetch($sql, $data);

    // OTP entry not found!?
    if ($otp === false) {
      $this->error = "OTP transaction not found.";
      return false;
    }

    // Too many tries
    if ($otp['otp_tries'] >= OTP_TRIES) {
      $this->error = "Too many tries for OTP.";
      return false;
    }

    // Expired
    $validTill = strtotime($otp['otp_timestamp']) + (OTP_VALID * 60);
    if (strtotime("now") > $validTill) {
      $this->error = "OTP has expired.";
      return false;
    }

    // Incorrect password
    if ($pass != $otp['otp_pass']) {
      // Add a strike to number of tries
      $strikes = $otp['otp_tries'] + 1;
      $sql = "UPDATE `otp` SET `otp_tries`=? WHERE `id`=?";
      $data = [$strikes, $id];
      $this->exec($sql, $data);

      // Security lock down - Too many tries
      if ($strikes >= OTP_TRIES) {
        $this->lockdown();
      }

      // Return result
      $this->error = "Incorrect OTP.";
      return false;
    }

    // OK - Correct OTP
    // You can delete the OTP at this point if you want
    /* 
     * $sql = "DELETE FROM `otp` WHERE `id`=?";
     * $data = [$id];
     * $this->exec($sql, $data);
     */
    return true;
  }

  function lockdown () {
  // lockdown() : failed challenge multiple times

    // DO YOUR OWN SECURITY LOCKDOWN!
    // Suspend the order?
    // Suspend the user account?
    // Maybe temporary lock for a few hours to prevent spam?
    // Send warning email + SMS?
    // All up to what you want to do.
  }
}
?>