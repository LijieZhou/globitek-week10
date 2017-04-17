<?php
require_once('../../../private/initialize.php');
require_login();
?>

<?php
if(!isset($_GET['id'])) {
  redirect_to('index.php');
}
$id = $_GET['id'];
$country_result = find_country_by_id($id);
$country = db_fetch_assoc($country_result);


// When there is a loop
// $len = sizeof($country_result); 
// echo $len;

// No loop, only one result
// echo $country_result->num_rows;
// if ($country_result->num_rows == 1){ 
//   $country = db_fetch_assoc($country_result);
//   echo "I am here";
// }
// else{
//   //if multiple records
//   foreach($country_result as $country){
//     $country = db_fetch_assoc($country_result);
//     echo $country['name']; 
//   }

// }

?>

<?php $page_title = 'Staff: Country of ' . $country['name']; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="main-content">
  <a href="index.php">Back to Countries List</a><br />


  
  <h1>Country: <?php $country['name']; ?></h1>


  <?php
    echo "<table id=\"country\">";
    echo "<tr>";
    echo "<td>Name: </td>";
    echo "<td>" . h($country['name']) . "</td>";
    // echo "<td>" . h($secret['name']) . "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>Code: </td>";
    echo "<td>" . h($country['code']) . "</td>";
    echo "</tr>";
    echo "</table>";


?>
    <br />
    <a href="edit.php?id=<?php echo $country['id']; ?>">Edit</a><br />
    <hr />

    <h2>States</h2>
    <br />
    <a href="../states/new.php?id=<?php echo $country['id']; ?>">Add a State</a><br />

<?php
    $state_result = find_states_for_country_id($country['id']);

    echo "<ul id=\"states\">";
    while($state = db_fetch_assoc($state_result)) {
      echo "<li>";
      echo "<a href=\"../states/show.php?id=" . $state['id'] . "\">";
      echo $state['name'];
      echo "</a>";
      echo "</li>";
    } // end while $state
    db_free_result($state_result);
    echo "</ul>"; // #states

    db_free_result($country_result);
  ?>

</div>

<?php include(SHARED_PATH . '/footer.php'); ?>
