<?php
global $session;
?>
<nav class="navbar navbar-expand-md navbar-dark bg-dark">
  <a class="navbar-brand" href="<?php echo PROJECT_URL; ?>home" style='font-family: LeckerliOne;'><i class="fas fa-camera-retro"></i> Camagru</a>
  <button class="navbar-toggler" type="button" id="navbarBtn" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation" onclick='check_nav()'>
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarElems">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item <?php echo is_active_link($view,"home",$args); ?>">
        <a class="nav-link" style='font-family: LeckerliOne;' href="<?php echo PROJECT_URL; ?>home">Home <span class="sr-only">(current)</span></a>
      </li>
      <?php if (!$session->is_logged_in()) { ?>
        <li class="nav-item <?php echo is_active_link($view,"signup",$args); ?>">
          <a class="nav-link" style='font-family: LeckerliOne;' href="<?php echo PROJECT_URL; ?>signup">Sign up</a>
        </li>
        <li class="nav-item <?php echo is_active_link($view,"signin",$args); ?>">
          <a class="nav-link" style='font-family: LeckerliOne;' href="<?php echo PROJECT_URL; ?>signin">Sign in</a>
        </li>
      <?php } ?>
      <?php if ($session->is_logged_in()) { ?>
        <li class="nav-item <?php echo is_active_link($view,"publish",$args); ?>">
          <a class="nav-link" style='font-family: LeckerliOne;' href="<?php echo PROJECT_URL; ?>publish">Publish</a>
        </li>
        <li class="nav-item <?php echo is_active_link($view,"profile",$args); ?>">
          <a class="nav-link" style='font-family: LeckerliOne;' href="<?php echo PROJECT_URL; ?>profile">Profile</a>
        </li>
        <li class="nav-item <?php echo is_active_link($view,"profile/edit",$args); ?>">
          <a class="nav-link" style='font-family: LeckerliOne;' href="<?php echo PROJECT_URL; ?>profile/edit">Edit profile</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" style='font-family: LeckerliOne;' href="<?php echo PROJECT_URL; ?>logout">Logout</a>
        </li>
      <?php } ?>
    </ul>
  </div>
</nav>