<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ./../auth/login.php");
    exit;
}

$GLOBALS['username_gb'] = $_SESSION['username'];

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="cart_css.css">
    <style type="text/css">
        body {
    margin-top: 50px;
    /*font-family: 'Roboto Slab', serif;*/
    }
        .color{
    background-color: #aedbf2;
    height: 100vh;        
        }
    </style>

    <title>Issued Books</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
      <a class="navbar-brand" href="index.php">iLibrary</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" 
      aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item active">
            <a class="nav-link" href="index.php">Home
              <span class="sr-only">(current)</span>
            </a>
          </li>
          <!-- <li class="nav-item">
            <a class="nav-link" href="#">About</a>
          </li> -->
          <li class="nav-item">
            <a class="nav-link" href="issued_books.php">Issued Books</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="cart.php">Cart</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./../auth/reset-password.php">Reset Password</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./../auth/logout.php">Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

    <div class="jumbotron text-center">
        <div class="container">
            <h1 class="jumbotron-heading">Issued Books</h1>
        </div>
    </div>

    <div class="container mb-4 color">
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Book</th>
                                <th scope="col">Date of issue</th>
                                <th> </th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php
                          $prn_session_id = $GLOBALS['username_gb'];
                          $servername = "localhost";
                          $username = "root";
                          $password = "";
                          
                          $conn = mysqli_connect($servername, $username, $password, "ilibrarydb");
                          if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                          }
                                                  
                          $sql = "select title, book_id, date_of_issue, history_id 
                                    from users, books, book_type, user_history 
                                    where username='$prn_session_id'
                                    and books.book_type_fk = book_type.book_type_id 
                                    and is_returned = 0 
                                    and username_fk = '$prn_session_id'
                                    and book_id_fk = books.book_id
                                  ";
                          $result = mysqli_query($conn, $sql);

                          if(!($result))
                          {
                            $count = 0;  
                          }
                          else
                            $count = mysqli_num_rows($result);
                          
                          $GLOBALS['count_of_books'] = $count;

                          if ($count > 0) {
                            while($row = $result->fetch_assoc()) {
                              $row_string = 
                              "<tr>
                                <td>". $row["title"] ."</td>
                                <td>". $row["date_of_issue"] ."</td>
                                <td class='text-right'>
                                <a href='return.php?history_id=". $row["history_id"] ."' 
                                onclick='return confirm_box()'>  
                                <button  type='button' class='btn btn-danger'>
                                  Return
                                </button> </a>

                                </td>
                            </tr>";

                            echo $row_string;

                            }
                          } else {
                              echo "0 books issued";
                          }
                          
                        ?>
                        
                        <script>
                            function confirm_box() {
                                return confirm("Sure?");
                            }
                        </script>
                            <!-- <tr>
                                <td>Data Structures made easy</td>
                                <td>200</td>
                                <td class="text-right"><button type="button" class="btn btn-danger">Remove</button> </td>
                            </tr> -->
                          
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col mb-2">
                <div class="row">
                    <div class="col-sm-12 col-md-6" style="margin-bottom: 10px">
                    <?php
                    //   $link ="";
                    //   if ($GLOBALS['count_of_books'] < 2) {
                    //     $link = "index.php";
                    //     $addButton = "<a href= $link>
                    //     <button class='btn btn-lg btn-warning btn-block btn-outline-primary text-uppercase'>ADD MORE</button>
                    //     </a>";
                    //     echo $addButton;
                    //   }
                    ?> 
                    </div>
                    <!-- <div class="col-sm-12 col-md-6 text-right">
                      <a href="confirm.php">
                        <button type='button' class='btn btn-lg btn-block btn-success text-uppercase' 
                        data-toggle='modal' data-target='#exampleModal'>Confirm</button>
                      </a>
                    </div> -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>