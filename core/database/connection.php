<?php
  try {
    $pdo = new PDO('mysql:host=localhost;dbname=tweety', 'root', '');
  } catch (PDOException $e) {
    echo 'Unable to connecct with databse ' . $e->getMessage();
  }

?>
