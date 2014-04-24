/* Depends on jQuery */

function getText() {
  var locnTxt = "<p>You are standing outside the Inn of the Drunken Soldier, in the middle of the town. </p>";
  var descTxt ="<p>A street goes in two directions. You can see various townsfolk going about their business.</p>";
  var dirTxt = "<p>You can go East or West.</p>";
  return locnTxt + descTxt + dirTxt;
}


$(document).ready(function() {
  var pageWid = $(window).width();
  var pageHgt = $(window).height();
  var chromeWid = $("#pMain").width();
  /*alert(pageWid);
  alert(pageHgt);*/
  /*alert(chromeWid);*/

  if (pageWid > chromeWid) {
    var lrMargin = (pageWid - chromeWid) / 2;
    $("#pMain").css({"margin-left": lrMargin, "margin-right": lrMargin});
  }
  
  //$("#sceneImg").attr("src", "img/inn.png");
  
  $("#curDate").text("Day 1");
  $("#curTime").text("Morning");
  $("#curArea").text("Renfyrd Town")
  
  $("#descSpiel").html(getText());
  
  $("#imgChar1").attr("src", "img/face.png");
  $("#imgChar2").attr("src", "img/face.png");
  $("#imgChar3").attr("src", "img/face.png");
  
  $("#imgEnemy1").attr("src", "img/enemy.png");
  $("#imgEnemy2").attr("src", "img/enemy.png");
  $("#imgEnemy3").attr("src", "img/enemy.png");
  
  $("#mainInput").focus(); 
  
});
