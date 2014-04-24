/* Javascript document.
 * Requires:  -jQuery, 
 * author: Scott Davies 2014
 */
 

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


function clearForm(frmSelected) {
  $(frmSelected)[0].reset();
}
    
    
  /*
  $("#locnYVal").val("");
  $("#locnXVal").val("");
  $("#locnShortLbl").val("");
  $("#locnArea").val("");
  $("#locnDescription").val("");
  $("input[name='locnExits']").each(function() {
    $(this).prop("checked", false);
   });
   */
  //$("#locnStorey").val("1");
//}


function getLocnAsXML() {
  var exitsArr = new Array();
  $("input[name='locnExits']:checked").each(function() {
    exitsArr.push($(this).val());
  });
  var description =  $("#locnDescription").val().replace(/\r?\n/g, '<br />');
  var fData = new Array();
  fData.push("<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>");
  fData.push("<requestLocation>");
  fData.push("<op>SaveNew</op>");
  fData.push("<location>");
  fData.push("<y_val>" +  $("#locnYVal").val() + "</y_val>");
  fData.push("<x_val>" + $("#locnXVal").val() + "</x_val>");
  fData.push("<short_lbl>" + $("#locnShortLbl").val() + "</short_lbl>");
  fData.push("<area>" + $("#locnArea").val() + "</area>");
  fData.push("<description>" + description + "</description>");
  fData.push("<exits>" + exitsArr.join(",") + "</exits>");           
  fData.push("<storey_val>" + $("#locnStorey").val() + "</storey_val>");
  fData.push("<image>" + $("#locnImage").val() + "</image>");
  fData.push("<visited>0</visited>");
  fData.push("</location>");
  fData.push("</requestLocation>");
  return fData.join("");
}


$(document).ready(function() {

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
    fData = getLocnAsXML();
    //"": $("#").val(),
    //testSubmit(fData);
    var url = $("#frmLocation").data("route");      
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
  });
  
  
  $("#navMain ul li").click(function() {
    var frmSelected = "#frm" + $(this).text();
    $("#navMain ul li").each(function() {
      $(this).removeClass("navItemSelected");
    });
    $(this).addClass("navItemSelected");
    
    $("form").hide();
    $(frmSelected).show();
    
    clearForm(frmSelected);
    fillWithTestData();
    
  });
  
  
});
