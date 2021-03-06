<?php
    session_start();
    // checks if user is logged in (login === true) and if not redirects to login:
    if(!isset($_SESSION["login"])) {
        header("LOCATION:awsumforum_login.php"); die();
    }
    // logs out if the user hits logout-button, resets session and redirects
    if(isset($_POST["logout"])){
        $_SESSION["login"] === false;
        unset($_SESSION["username"]);
        session_destroy();
        header("LOCATION:awsumforum_login.php");
     }
?>
<html>
  <head>
    <title>awsum forum</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:300,400,700" rel="stylesheet">
  </head>
  <body>
    <h1>Login success!</h1>
    <div class="container flex-container flex-container--justify-content">
      <div class="flex-container flex-container--column">
        <section class="section-container--white">
          <p>congrats <?php echo $_SESSION['username'] ?> you are in</p>

          <?php
          $servername = "localhost:8889";
          $username = "root";
          $password = "root";
          $dbname = "awsumforum";

          $connection = new mysqli($servername, $username, $password, $dbname);

          if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
          }
          ?>
          <form action="" method="post">
            <label>Username:</label>
            <input type="text" name="myName" id="name" required="required" placeholder="Please enter name"/><br /><br />
            <label>Message:</label>
            <input type="text" name="myMessage" id="message" required="required" placeholder="Type a message"/><br/><br />
            <input type="submit" value=" Submit " name="insertSubmit"/><br />
          </form>
          <?php
          if(isset($_POST["insertSubmit"])) {
            $sql = "INSERT INTO messageboard (messageContent, messageAuthor)
            VALUES ('".$_POST['myMessage']."','".$_POST['myName']."')";
           if ($connection->query($sql) === TRUE) {
             echo "New record created successfully";
             $query = "select messageContent, messageAuthor, messageDate from messageboard";
             $queryResult = mysqli_query($connection, $query)
               or die("Virhe: " . mysqli_error($connection));
             while($tableRow=mysqli_fetch_array($queryResult)) {
               echo ($tableRow["messageDate"] . "</strong><br>\n" . $tableRow["messageAuthor"] . ": <br>\n" . $tableRow["messageContent"] . "<br>\n <br>\n");
             }
           } else {
             echo "Error: " . $sql . "<br>" . $connection->error;
           } $connection->close();
         }
          mysqli_free_result($queryResult);
          mysqli_close($connection);
          ?>

        </section>
        <form action="" method="post">
          <section class="button-section flex-container flex-container--space-around">
            <input type="submit" value="Sign out" name="logout">
          </section>
        </form>
      </div>
    </div>
  </body>
</html>
