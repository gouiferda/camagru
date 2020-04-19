<?php


function get_theme_widget()
{
    $sidebar = "";
    // $sidebar .= "<div class='text-center'>";
    // $sidebar .= "<div class='btn-group' role='group'>";
    // $sidebar .= "<a class='btn btn-dark btn-sm' href='" . PROJECT_URL . 'theme/11' . "'>";
    // $sidebar .= "Dark mode";
    // $sidebar .= "</a>";
    // $sidebar .= "<a class='btn btn-primary btn-sm' href='" . PROJECT_URL . 'theme/2' . "'>";
    // $sidebar .= "Light mode";
    // $sidebar .= "</a>";
    // $sidebar .= "</div>";
    // $sidebar .= "</div>";
    $sidebar .= "<div class='text-center'>";
/*
    "1"=>"bootstrap",
    "2"=>"themes/minty",
    "3"=>"themes/litera",
    "4"=>"themes/cosmo",
    "5"=>"themes/darkly",
    "6"=>"themes/flaty",
    "7"=>"themes/journal",
    "8"=>"themes/lumen",
    "9"=>"themes/yeti",
    "10"=>"themes/materia",
    "11"=>"themes/cyborg",
    "12"=>"themes/superhero",
    "13"=>"themes/sketchy"
*/

    $sidebar .= "<table class='table table-sm table-bordered'>";
    $sidebar .= "<tbody>";
    $sidebar .= "<tr>";
    $sidebar .= "<td class='btn-light'><a href='" . PROJECT_URL . "theme/2" . "'><p>Default</p></a></td>";
    $sidebar .= "<td class='btn-primary'><a href='" . PROJECT_URL . "theme/8" . "'><p class='text-light'>Lumen</p></a></td>";
    $sidebar .= "<td class='btn-warning'><a href='" . PROJECT_URL . "theme/13" . "'><p class='text-light'>Sketchy</p></a></td>";
    $sidebar .= "</tr>";
    $sidebar .= "<tr>";
    $sidebar .= "<td class='btn-info'><a href='" . PROJECT_URL . "theme/9" . "'><p class='text-light'>Yeti</p></a></td>";
    $sidebar .= "<td class='btn-success'><a href='" . PROJECT_URL . "theme/4" . "'><p class='text-light'>Cosmo</p></a></td>";
    $sidebar .= "<td class='btn-danger'><a href='" . PROJECT_URL . "theme/7" . "'><p class='text-light'>Journal</p></a></td>";
    $sidebar .= "</tr>";
    $sidebar .= "<tr>";
    $sidebar .= "<td class='btn-dark'><a href='" . PROJECT_URL . "theme/11" . "'><p class='text-light'>Dark</p></a></td>";
    $sidebar .= "<td class='btn-dark'><a href='" . PROJECT_URL . "theme/5" . "'><p class='text-light'>Dark 2</p></a></td>";
    $sidebar .= "<td class='btn-dark'><a href='" . PROJECT_URL . "theme/12" . "'><p class='text-light'>Dark 3</p></a></td>";
    $sidebar .= "</tr>";
    $sidebar .= "</tbody>";
    $sidebar .= "</table>";

    // $sidebar .= "<div class='btn-group-vertical' data-toggle='buttons'>";
    // $sidebar .= "<a href='" . PROJECT_URL . "theme/8" . "' class='btn btn-primary'>Default theme</a>";
    // $sidebar .= "<a href='" . PROJECT_URL . "theme/2" . "' class='btn btn-primary'>Green Theme</a>";
    // $sidebar .= "<a href='" . PROJECT_URL . "theme/9" . "' class='btn btn-primary'>Blue Theme</a>";
    // $sidebar .= "<a href='" . PROJECT_URL . "theme/4" . "' class='btn btn-primary'>Blue2 Theme</a>";
    // $sidebar .= "<a href='" . PROJECT_URL . "theme/1" . "' class='btn btn-primary'>Bootstrap Theme</a>";
    // $sidebar .= "<a href='" . PROJECT_URL . "theme/11" . "' class='btn btn-primary'>Dark Theme</a>";
    // $sidebar .= "<a href='" . PROJECT_URL . "theme/5" . "' class='btn btn-primary'>Dark2 Theme</a>";
    // $sidebar .= "</div>";
    // for ($i = 1; $i < 14; $i++) {
    //   $sidebar .= "<a href='" . PROJECT_URL . "theme/". $i . "' class='btn btn-primary'>Theme ".$i."</a>";
    // }
    $sidebar .= "</div>";
    return $sidebar;
}
