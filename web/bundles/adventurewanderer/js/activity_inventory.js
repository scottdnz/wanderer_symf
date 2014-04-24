/* Depends on jQuery */

function showInventoryArea(area) {
  $(".invArea").addClass("hidden");
  $("#" + area).removeClass("hidden");
}

$(document).ready(function() {
  var pageWid = $(window).width();
  var pageHgt = $(window).height();
  var chromeWid = $("#pMain").width();
  /*alert(pageWid);
  alert(pageHgt);*/
  /*alert(chromeWid);*/
  
  showInventoryArea("itemsArea");
  
  $("#navInventory ul li").each(function() {
    if ($(this).text() == "Regular Items") {
     $(this).addClass("navItemSelected");
    }
    else {
      $(this).removeClass("navItemSelected");
    }
  });

  if (pageWid > chromeWid) {
    var lrMargin = (pageWid - chromeWid) / 2;
    $("#pMain").css({"margin-left": lrMargin, "margin-right": lrMargin});
  }
  
  /*$("#regularItems").click(function() {
    showInventoryArea("regularArea");
    
  });
  
  $("#armourItems").click(function() {
    showInventoryArea("armourArea");
  });
  
  $("#weaponItems").click(function() {
    showInventoryArea("weaponArea");
  });
  */
  

  $("#navInventory ul li").click(function() {
    var areaSelected = $(this).text();
    $("#navInventory ul li").each(function() {
      $(this).removeClass("navItemSelected");
    });
    $(this).addClass("navItemSelected");
  

    switch(areaSelected) {
      case "Regular Items":
        areaSelected = "itemsArea";
        break;
      case "Armour":
        areaSelected = "armourArea";
        break;
      case "Weapons":
        areaSelected = "weaponsArea";
        break;
    }

    
    
    //$(".invArea").hide();
    //$(areaSelected).show();
    showInventoryArea(areaSelected);
  });
  
});
