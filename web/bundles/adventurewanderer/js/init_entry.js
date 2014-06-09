/* Javascript document.
 * Requires:  -jQuery, 
 * author: Scott Davies 2014
 */
 
 
/*
function testSubmit(fData) {
  var testMsg = "<code>";
  for (var i = 0; i < fData.length; i++ ) {
    testMsg += fData[i] + "<br>";
  }
  testMsg += "</code>";
  $("#testArea").html(testMsg);
}


function fillWithTestData() {
  $("#locnYVal").val("0");
  $("#locnXVal").val("0");
  $("#locnShortLbl").val("Short");
  $("#locnArea").val("area");
  $("#locnDescription").val("desc");
  $("input[name='locnExits']").each(function() {
    if ( ($(this).val() == "n") || ($(this).val() == "ne") || 
      ($(this).val() == "e")) {
      $(this).prop("checked", true);  
    }
  });
  //$("#locnImage").val("inn.jpg");
}
*/


function clearForm(frmSelected) {
  $(frmSelected)[0].reset();
  $("#respMsgArea").empty();
}


function getCheckBoxes(options, cbGroupName) {
  var elems_arr = new Array();
  for (var i = 0; i < options.length; i++) {
    var checked = $("input[name='" + cbGroupName + "'][value='" + options[i] + "']").prop("checked");
    if (checked) {
      var check_val = 1;
    }
    else {
      var check_val = 0;
    }   
    elems_arr.push("<" + options[i] + ">" + check_val + "</" + options[i] + ">");
  }
  return elems_arr.join("");
}


function getLocnAsXML() {
  var exit_options = new Array("n", "ne", "e", "se", "s", "sw", "w", "nw", "up", "down");
  var exits = getCheckBoxes(exit_options, "locnExits");
  var description =  $("#locnDescription").val().replace(/\r?\n/g, '<br />');
  var fData = new Array();
  fData.push("<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>");
  fData.push("<request>");
  fData.push("<op>SaveNewLocation</op>");
  fData.push("<location>");
  fData.push("<y_val>" +  $("#locnYVal").val() + "</y_val>");
  fData.push("<x_val>" + $("#locnXVal").val() + "</x_val>");
  fData.push("<short_lbl>" + $("#locnShortLbl").val() + "</short_lbl>");
  fData.push("<area>" + $("#locnArea").val() + "</area>");
  fData.push("<description>" + description + "</description>");
  fData.push("<exits>" + exits + "</exits>");           
  fData.push("<image>" + $("#locnImage").val() + "</image>");
  fData.push("<storey_val>" + $("#locnStorey").val() + "</storey_val>");
  fData.push("<visited>0</visited>");
  fData.push("</location>");
  fData.push("</request>");
  return fData.join("");
}


function getItemAsXML() {
  var util_options = new Array("breakable", "climbable", "lightable", "openable", 
  "takeable");
  var state_options = new Array("open", "useable", "lit");
  var utilities = getCheckBoxes(util_options, "itemUtility");
  var states = getCheckBoxes(state_options, "itemState");
  var description =  $("#itemDescription").val().replace(/\r?\n/g, '<br />');
  var fData = new Array();
  fData.push("<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>");
  fData.push("<request>");
  fData.push("<op>SaveNewItem</op>");
  fData.push("<item>");
  fData.push("<name>" + $("#itemName").val() + "</name>");
  fData.push("<description>" + description + "</description>");
  fData.push("<image>" + $("#item").val() + "</image>");
  fData.push("<utilities>" + utilities + "</utilities>");
  fData.push("<states>" + states + "</states>");
  fData.push("<location_y>" + $("#item").val() + "</location_y>");
  fData.push("<location_x>" + $("#item").val() + "</location_x>");
  fData.push("<uses_remaining>" + $("#item").val() + "</uses_remaining> ");
  fData.push("</item>");
  fData.push("</request>");
  return fData.join("");
}


function postAdd(url, fData) {
  //url, data, success, datatype
  $.post(url, 
    fData, 
    function(data) {
      //console.log(data);
      $respMsg = "<p>";
      $xml = $(data);
      $conf = $xml.find("conf").text();
      if ($conf.length > 0) {
        $respMsg += "Confirmation(s): " + $conf;
        $("#respMsgArea").css("color", "green");  
      }
      else {
        $error = $xml.find("error").text();
        $respMsg += "Error(s): " + $error;
        $("#respMsgArea").css("color", "red");
      }
      $("#respMsgArea").html($respMsg + "</p>");
    }, // End of post success function
    "xml"
  ); // End of post call
}


$(document).ready(function() {
  
  //Initialising
  $("form").hide();
  //fillWithTestData();
  $("#frmLocations").show();


  $("#navMain ul li").each(function() {
    if ($(this).text() == "Locations") {
     $(this).addClass("navItemSelected");
    }
    else {
      $(this).removeClass("navItemSelected");
    }
  });

    
  $("#locnReset").click(function() {
    clearForm("#frmLocations");
  });
  
  
  $("#itemReset").click(function() {
    clearForm("#frmItems");
  });
  
  
  $(".cBox").hover(function() {
    $(this).css("cursor", "pointer")
  }, function() {
    $(this).css("cursor", "default")
  });
  
  
  $(".cBox").click(function() {
    $curCBox = $(this).siblings("input[name='locnExits']").first();
    if ($curCBox.prop("checked") == true) {
      $curCBox.prop("checked", false);
    }
    else {
      $curCBox.prop("checked", true);
    }
  });
    
  
  $("#locnSubmit").click(function() {
    var fData = getLocnAsXML();
    var url = $("#frmLocations").data("route");      
    postAdd(url, fData);
  });
  
  
  $("#itemSubmit").click(function() {
    var fData = getItemAsXML();
    var url = $("#frmItems").data("route");      
    postAdd(url, fData);
  });
  
  
  /* Someone has clicked a menu item */
  $("#navMain ul li").click(function() {
    var frmSelected = "#frm" + $(this).text();
    $("#navMain ul li").each(function() {
      $(this).removeClass("navItemSelected");
    });
    $(this).addClass("navItemSelected");
    $("form").hide();
    $(frmSelected).show();
    clearForm(frmSelected);
    //fillWithTestData();
  });
  

  
  
});
