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
    public function logout() {
      $_SESSION = array();
      session_destroy();
      header('Location: ../index.php');
    }
    public function checkExistingEmail($email) {
      $query = $this->pdo->prepare('SELECT email
        FROM users WHERE email = :email
      ');
      $query->bindParam(':email', $email, PDO::PARAM_STR);
      $query->execute();
      $count = $query->rowCount();
      if ($count > 0) {
        return true;
      } else {
        return false;
      }
    }
    public function checkExistingUsername($username) {
      $query = $this->pdo->prepare('SELECT username
        FROM users WHERE username = :username
      ');
      $query->bindParam(':username', $username, PDO::PARAM_STR);
      $query->execute();
      $count = $query->rowCount();
      if ($count > 0) {
        return true;
      } else {
        return false;
      }
    }
    public function create($table, $fields = array()) {
      $columns = implode(', ', array_keys($fields));
      $values  = ':' . implode(', :', array_keys($fields));
      $sql     = 'INSERT INTO ' . $table . ' (' . $columns . ')
        VALUES (' . $values . ')
      ';
      if ($query = $this->pdo->prepare($sql)) {
        foreach ($fields as $key => $value) {
          $query->bindValue(':' . $key, $value);
        }
        $query->execute();
        return $this->pdo->lastInsertId();
      }
    }
		/**
		 * Prepare a query to select data from database
		 * @param array $params; e.g:
		 * 'table' required name of table
		 * 'wheres' Specify id or else for updating records
		 * 'columns' => data e.g name=>new name
		 *
		 * @return boolean
		 */	 
	public function update($params){
		if(is_array($params)){
				$db = $this->MalikDbConnection(true);
				$count_rows = count($params['columns']);
				$increment      = 1;
			foreach($params['columns'] as $keys => $value) {
				for($i=1;$i<=$count_rows;$i++){
						$data[$keys] = $value;
					}
			}
			foreach($data as $keys => $values) {
				if($increment == $count_rows) {
						$columns [] = "{$keys} = '{$values}'";
				} else {
						$columns [] = "{$keys} = '{$values}'";
				}
				$increment++;
			}
			$columns  = implode(' , ', $columns);
			if(isset($params['wheres'])) {		
				if(!empty($params['wheres'])) {
						$wheres = "WHERE " . implode(' and ', array_values($params['wheres']));
				}else{
					$wheres = '';
				}
			}else{
				$wheres = '';
			}			
				$query  = "UPDATE {$params['table']} SET {$columns} {$wheres}";
				var_dump($query);
					if(isset($params['debug']) and $this->Strings->MalikStringConversion(['type'=>'lowercase','text'=>$params['debug']]) === 'on' ){
					    	var_dump($query);
					}
					$prepare = $db->prepare($query);
					if($prepare->execute()) {
							
							$db = $this->MalikDbConnection(false);
							return true;
					}			
		}else{
			return false;
		}		
	}
    public function register($email, $screenName, $password) {
      $password = md5($password);
      $query = $this->pdo->prepare('INSERT INTO users (email, screenName, password, profileImage, profileCover)
        VALUES (:email, :screenName, :password, "assets/images/defaultProfileImage.png", "assets/images/defaultCoverImage.png")
      ');
      $query->bindParam(':email', $email, PDO::PARAM_STR);
      $query->bindParam(':screenName', $screenName, PDO::PARAM_STR);
      $query->bindParam(':password', $password, PDO::PARAM_STR);
      $query->execute();
      $user_id = $this->pdo->lastInsertId();
      $_SESSION['user_id'] = $user_id;
    }
    public function userIdByUsername($username) {
      $sql = 'SELECT user_id
        FROM users
        WHERE username = :username
      ';
      $query = $this->pdo->prepare($sql);
      $query->bindParam(':username', $username, PDO::PARAM_STR);
      $query->execute();
      $user = $query->fetch(PDO::FETCH_OBJ);
      return $user->user_id;
    }
    public function loggedIn() {
      return (isset($_SESSION['user_id'])) ? true : false;
    }
    public function uploadImage($file) {
      $filename = basename($_FILES['name']);
      $fileTmp  = $file['tmp_name'];
      $fileSize = $file['size'];
      $error    = $file['error'];

      $fileExtension = explode('.', $filename);
      $fileExtension = strtolower(end($fileExtension));
      $extensions    = array('jpg', 'png', 'jpeg');
      if (in_array($fileExtension, $extensions) === true) {
        if ($error === 0) {
          if ($fileSize <= 209272152) {
            $fileRoot = 'users/' . $filename;
            move_uploaded_file($fileTmp, $fileroot);
            return $fileRoot;
          } else {
            $GLOBALS['imageError'] = 'Image size must not be more than 2 Mega Bytes(2MB)';
          }
        }
      } else {
        $GLOBALS['imageError'] = 'The Extension is not valid please choost png, jpg, jpeg or contact us';
      }
    }
  }
?>
