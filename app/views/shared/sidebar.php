<?php
$sidebar_output = "";
$sidebar_title_output = "";
$sidebar_output = "Sign in to post pictures";
$sidebar_title_output = "Sidebar";
if (isset($connected_user)) {
  $sidebar_output = "Welcome ";
  $sidebar_output .= ss($connected_user, "first_name");
  $sidebar_output .= " ";
  $sidebar_output .= ss($connected_user, "last_name");
}

if ($sidebar != "") {
  $sidebar_output = $sidebar;
}
if ($sidebar_title != "") {
  $sidebar_title_output = $sidebar_title;
}
?>

<div class="card">
  <div class="card-header text-center">
  <span style='font-family: LeckerliOne;font-size:20px;'><?php echo  $sidebar_title_output; ?></span>
  </div>
  <div class="card-body">
      
        <?php echo  $sidebar_output; ?>
    
  </div>
</div>