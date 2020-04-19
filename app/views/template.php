<!doctype html>
<html lang="en">

<head>
  <?php
  include_once("shared/head.php");
  ?>
</head>

<body>
  <?php
  include_once("shared/nav.php");
  ?>

  <main role="main">


    <div class="album py-5 bg-light">
      <div class="container">
        <div class="row">
          <div class="col-lg-8">
          <?php
             echo $body;
            ?>
            
          </div>
          <div class="col-lg-4">
            <?php
            include_once("shared/sidebar.php");
            ?>
          </div>

        </div>
      </div>
    </div>

  </main>

  <?php
  include_once("shared/footer.php");
  ?>

<?php
  include_once("shared/scripts.php");
  ?>


</body>


</html>