
  var navbarBtn = document.getElementById("navbarBtn");
  var navbarElems = document.getElementById("navbarElems");



  function check_nav()
  {
      if (navbarBtn.getAttribute('aria-expanded') == "false")
      {
        navbarElems.classList.add("show");
        navbarBtn.setAttribute("aria-expanded", "true");
        navbarElems.classList.remove("collapsed");
      }else if  (navbarBtn.getAttribute('aria-expanded') == "true")
      {
        navbarElems.classList.remove("show");
        navbarBtn.setAttribute("aria-expanded", "false");
        navbarElems.classList.add("collapsed");
        //
      }
  }
