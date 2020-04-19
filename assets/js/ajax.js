function showAjaxResult(page, query, elemId, type) {
  if (query.length == 0) {
    document.getElementById(elemId).innerHTML = "";
    return;
  } else {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById(elemId).innerHTML = this.responseText;
      }
    };
    xmlhttp.open("GET", page + "/" + query + "/" + type, true);
    xmlhttp.send();
  }
}
var load_index = 2;