<?php
  class User {
    protected $pdo;
    function __construct($pdo) {
      $this->pdo = $pdo;
    }
    public function checkInput($value) {
      $value = htmlspecialchars($value);
      $value = trim($value);
      $value = stripcslashes($value);
      return $value;
    }
    public function login($email, $password) {
      $password = md5($password);
      $query = $this->pdo->prepare('SELECT user_id
        FROM users WHERE email = :email AND  password = :password
      ');
      $query->bindParam(':email', $email, PDO::PARAM_STR);
      $query->bindParam(':password', $password, PDO::PARAM_STR);
      $query->execute();

      $user  = $query->fetch(PDO::FETCH_OBJ);
      $count = $query->rowCount();

      if ($count > 0) {
        $_SESSION['user_id'] = $user->user_id;
        header('Location: home.php');
      } else {
        return false;
      }
    }
    public function userData($user_id) {
      $query = $this->pdo->prepare('SELECT * FROM users
        WHERE user_id = :user_id
      ');
      $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
      $query->execute();
      return $query->fetch(PDO::FETCH_OBJ);
    }
  }
?>
