<?php
require_once('../../private/initialize.php');

// Until we learn about encryption, we will use an unencrypted
// master password as a stand-in. It should go without saying
// that this should *never* be done in real production code.
$master_password = 'secret';

// Set default values for all variables the page needs.
$errors = array();
$username = '';
$password = '';
$failed_login = 0;

if(is_post_request() && request_is_same_domain()) {
  ensure_csrf_token_valid();

  // Confirm that values are present before accessing them.
  if(isset($_POST['username'])) { $username = $_POST['username']; }
  if(isset($_POST['password'])) { $password = $_POST['password']; }

  // Throttleing time before any validation or authentication
  if($remaining_time = throttle_time($username)){
    $errors[] = "Too many failed logins. You need to wait " . ceil($remaining_time/60) . " minutes";
  }else{
    // Validations
    if (is_blank($username)) {
      $errors[] = "Username cannot be blank.";
    }
    if (is_blank($password)) {
      $errors[] = "Password cannot be blank.";
    }
  }

    // If there were no errors, submit data to database
    if (empty($errors)) {
      $users_result = find_users_by_username($username);
      // No loop, only one result
      $user = db_fetch_assoc($users_result);
      if($user) {
        // if($password === $master_password) {
        if(password_verify($password, $user['password_hash'])){
          // Username found, password matches
          log_in_user($user);
          reset_failed_login($username);
          // Redirect to the staff menu after login
          redirect_to('index.php');
        } else {
          // Username found, but password does not match.
          $errors[] = "Log in was unsuccessful.";
          $failed_login = record_failed_login($user['username']);
        }
      }else {
        // No username found
        $errors[] ="Log in was unsuccessful.";//Give the same error message to prevent user enumeration.
        $failed_login = record_failed_login($user['username']);
      }
    }
    if($failed_login == 5){
      $errors[] = "You have failed 5 times.";
    }
  }


?>
<?php $page_title = 'Log in'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>
<div id="menu">
  <ul>
    <li><a href="../index.php">Public Site</a></li>
  </ul>
</div>

<div id="main-content">
  <h1>Log in</h1>

  <?php echo display_errors($errors); ?>

  <form action="login.php" method="post">
    <?php echo csrf_token_tag(); ?>
    Username:<br />
    <input type="text" name="username" value="<?php echo h($username); ?>" /><br />
    Password:<br />
    <input type="password" name="password" value="" /><br />
    <input type="submit" name="submit" value="Submit"  />
  </form>

</div>

<?php include(SHARED_PATH . '/footer.php'); ?>
