<?php
  include 'database/connection.php';
  include 'classes/user.php';
  include 'classes/tweet.php';
  include 'classes/follow.php';

  global $pdo;

  session_start();

  $getFromU = new User($pdo);
  $getFromT = new Tweet($pdo);
  $getFromF = new Follow($pdo);

  define('BASE_URL', 'http://localhost/twitter/');
?>
<?php
 /* function setTimer(lower = 0, upper = 10, interval) {
  var timer = function() {
    if (lower == upper + 1) {
      window.clearInterval(timer);
    } else {
      console.log(lower);
      lower++;
    }
  }
  var timer = setInterval(timer, interval * 1000);
}
setTimer(0, 5, 1);
*/
?>
