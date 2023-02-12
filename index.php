<?php
  $servername="localhost";
  $username="root";
  $password="";
  $dbname="inotes";
  $checkSubmit=false;
  $checkUpdate=false;
  $checkDelete = false;
  // establishing connectivity
  $conn = mysqli_connect($servername,$username,$password,$dbname);
  if(!$conn){
    die("Error : ".mysqli_connect_error());
  }
  /*
  // creating table named notes
  $sql = "CREATE TABLE `notes`(
    `slno` INT NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(50),
    `description` TEXT,
    `tstamp` DATETIME,
    PRIMARY KEY(`slno`)
  );";
  $sqlResult = mysqli_query($conn,$sql);
  if(!$sqlResult){
    die("Error : ".mysqli_error($conn));
  }
  */
  if(isset($_GET['delete'])){
    $sno = $_GET['delete'];

    $sql = "DELETE FROM `notes` WHERE `slno`='$sno';";
    $sqlResult = mysqli_query($conn,$sql);
    if($sqlResult){
      $checkDelete = true;
    }
  }
  if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['slnoAreaEdit'])){
      $sno = $_POST['slnoAreaEdit'];
      $title = $_POST['titleAreaEdit'];
      $description = $_POST['descriptionAreaEdit'];

      $sql = "UPDATE `notes` SET `title`='$title',`description`='$description' WHERE `slno`=$sno ;";
      $sqlResult = mysqli_query($conn,$sql);
      if($sqlResult){
        $checkUpdate=true;
      }
    }else{
      $title = $_POST['titleArea'];
      $description = $_POST['descriptionArea'];

      $sql = "INSERT INTO `notes`(`title`,`description`,`tstamp`) VALUES(
        '$title','$description',current_timestamp()
    );";
      $sqlResult = mysqli_query($conn,$sql);
      if($sqlResult){
        $checkSubmit=true;
      }
    }
    
  }

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>iNotes | Place where you can create notes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

    <!-- datatables css -->
    <link href="//cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css" rel="stylesheet">
  </head>
  <body>
    
<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editModalLabel">Edit Record</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="/login-dir/inotes-new/index.php">
          <input type="hidden" id="slnoAreaEdit" name="slnoAreaEdit">
    <div class="mb-3">
  <label for="titleAreaEdit" class="form-label">Edit Title</label>
  <input type="text" class="form-control" id="titleAreaEdit" name="titleAreaEdit">
</div>
<div class="mb-3">
  <label for="descriptionAreaEdit" class="form-label">Note Description</label>
  <textarea class="form-control" id="descriptionAreaEdit" name="descriptionAreaEdit" rows="3"></textarea>
</div>
<button type="submit" class="btn btn-primary">Add Note</button>
  </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
    <!-- nav bar -->
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">iNotes</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Contact</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">About</a>
        </li>
      </ul>
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>
<!-- notification -->
<?php
if($checkSubmit){
  echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
  <strong>Success!</strong> The note is added successfully.
  <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
</div>";
}else if($checkUpdate){
  echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
  <strong>Success!</strong> The note is updated successfully.
  <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
</div>";
}
?>

<!-- note form -->
<div class="container my-5">
	<h1>Enter the note</h1>
	<form method="POST" action="/login-dir/inotes-new/index.php">
		<div class="mb-3">
  <label for="titleArea" class="form-label">Note Title</label>
  <input type="text" class="form-control" id="titleArea" name="titleArea">
</div>
<div class="mb-3">
  <label for="descriptionArea" class="form-label">Note Description</label>
  <textarea class="form-control" id="descriptionArea" name="descriptionArea" rows="3"></textarea>
</div>
<button type="submit" class="btn btn-primary">Add Note</button>
	</form>
</div>

<! -- table to show data -->
<div class="container">
    <table class="table" id="myTable">
  <thead>
    <tr>
      <th scope="col">Sl.No</th>
      <th scope="col">Title</th>
      <th scope="col">Description</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $sql = "SELECT * FROM `notes`;";
      $sqlResult=mysqli_query($conn,$sql);
      $row=0;
      while($rows = mysqli_fetch_assoc($sqlResult)){
        $row++;
        echo "<tr>
                <th scope='row'>".$row."</th>
                <td>".$rows['title']."</td>
                <td>".$rows['description']."</td>
                <td><button type='submit' class='edit btn btn-primary btn-sm' id=".$rows['slno'].">Edit</button> <button type='submit' class='delete btn btn-primary btn-sm' id=".$rows['slno'].">Delete</button></td>
              </tr>";
      }
    ?>
  </tbody>
</table>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <!-- jquery cdn -->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <!-- datatables jquery cdn -->
    <script src="//cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script>
      $(document).ready( function () {
    $('#myTable').DataTable();
} );
    </script>
    <script>
      edits = document.getElementsByClassName('edit');
      Array.from(edits).forEach((element)=>{
        element.addEventListener('click',(e)=>{
            tr=e.target.parentNode.parentNode;
            titleAreaEdit.value = tr.getElementsByTagName('td')[0].innerText;
            descriptionAreaEdit.value = tr.getElementsByTagName('td')[1].innerText;
            slnoAreaEdit.value=e.target.id;
            const myModal = new bootstrap.Modal('#editModal', {
              keyboard: false
            })
            myModal.toggle();

        })
      })
      deletes = document.getElementsByClassName('delete');
      Array.from(deletes).forEach((element)=>{
        element.addEventListener('click',(e)=>{
          sno = e.target.id;
          if(confirm('Are you sure?')){
            window.location=`/login-dir/inotes-new/index.php?delete=${sno}`;
          }
        })
      })
    </script>
  </body>
</html>