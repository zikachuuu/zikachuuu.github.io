<?php
session_start () ;
require_once "pdo.php" ;
require_once "utility.php" ;
$stmt = $pdo -> query ("SELECT profile_id , user_id , first_name , last_name , headline FROM _js.profile") ;
$rows =  $stmt -> fetchall (PDO :: FETCH_ASSOC) ;
$stmt2 = $pdo -> prepare ("SELECT name FROM _js.users WHERE user_id = :uid") ;
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="utf-8">
<title> Zikachu </title>
</head>

<body>

<h1> Welcome to Resume Registry </h1>

<?php
flashmsg() ;

if ( $login ) {
  echo ("<p> Logged in as " . htmlentities($username) . "</p>\n") ;
} else {
  echo ("<p> Please log in to make changes </p>") ;
} ;

if ( empty ($rows) ) {
  echo ("<p> No Rows Found </p>") ;

} else {
?>

  <table border="1">
    <tr>
      <th> Name </th>
      <th> Headline </th>
      <th> Recorded by </th>
      <th> Action </th>
    </tr>
    <?php foreach ($rows as $row) { ?>
      <tr>
        <td> <?= htmlentities ($row['first_name'] . ' ' . $row['last_name']) ?> </td>
        <td> <?= htmlentities ($row['headline']) ?> </td>
        <td>
          <?php
          $stmt2 -> execute ( array ( ':uid' => $row['user_id'] ) ) ;
          $row2 = $stmt2 -> fetch (PDO :: FETCH_ASSOC) ;
          echo ( htmlentities ($row2['name']) ) ; ?>
        </td>
        <td>
          <a href="view.php?profile_id=<?= $row['profile_id'] ?>">View</a>
          <?php if ( $row['user_id'] === $user) { ?>
            / <a href="edit.php?profile_id=<?= $row['profile_id'] ?>">Edit</a>
            / <a href="delete.php?profile_id=<?= $row['profile_id'] ?>">Delete</a>
          <?php } ?>
        </td>
      </tr>
    <?php } ?>
  </table>

<?php } ?>

<?php if ( $login ) { ?>
  <p> <a href="add.php">Add New Entry</a> </p>
  <p> <a href="logout.php">Logout</a> </p>
<?php } else { ?>
  <p> <a href="login.php">Please log in</a> </p>
<?php } ?>

</body>
</html>
