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
  fData.push("</location></request>");
  return fData.join("");
}


function getItemAsXML() {
  var utilOptions = new Array("breakable", "climbable", "lightable", "openable", 
  "takeable");
  var stateOptions = new Array("open", "useable", "lit");
  var utilities = getCheckBoxes(utilOptions, "itemUtility");
  var states = getCheckBoxes(stateOptions, "itemState");
  var description =  $("#itemDescription").val().replace(/\r?\n/g, '<br />');
  var fData = new Array();
  fData.push("<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>");
  fData.push("<request>");
  fData.push("<op>SaveNewItem</op>");
  fData.push("<item>");
  fData.push("<name>" + $("#itemName").val() + "</name>");
  fData.push("<description>" + description + "</description>");
  fData.push("<image>" + $("#itemImage").val() + "</image>");
  fData.push("<utilities>" + utilities + "</utilities>");
  fData.push("<states>" + states + "</states>");
  fData.push("<location_y>" + $("#itemYVal").val() + "</location_y>");
  fData.push("<location_x>" + $("#itemXVal").val() + "</location_x>");
  fData.push("<location_storey>" + $("#itemStoreyVal").val() + "</location_storey>");
  fData.push("<uses_remaining>" + $("#itemUsesRemaining").val() + "</uses_remaining> ");
  fData.push("</item></request>");
  return fData.join("");
}


function getWeaponAsXML() {
  var description =  $("#weaponDescription").val().replace(/\r?\n/g, '<br />');
  var fData = new Array();
  fData.push("<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>");
  fData.push("<request>");
  fData.push("<op>SaveNewWeapon</op>");
  fData.push("<weapon>");
  fData.push("<name>" + $("#weaponName").val() + "</name>");
  fData.push("<description>" + description + "</description>");
  fData.push("<dmg1_type>" + $("#weaponDmg1Type").val() + "</dmg1_type>");
  fData.push("<dmg1_min>" + $("#weaponDmg1Min").val()  + "</dmg1_min>");
  fData.push("<dmg1_max>" + $("#weaponDmg1Max").val()  + "</dmg1_max>");
  fData.push("<dmg2_type>" + $("#weaponDmg2Type").val()  + "</dmg2_type>");
  fData.push("<dmg2_min>" + $("#weaponDmg2Min").val()  + "</dmg2_min>");
  fData.push("<dmg2_max>" + $("#weaponDmg2Max").val()  + "</dmg2_max>");
  fData.push("<bonus_status_type>" + $("input[name='weaponBonusStatusType']").val() + "</bonus_status_type>");
  fData.push("<bonus_status_val>" + $("#weaponBonusStatusVal").val() + "</bonus_status_val>");
  fData.push("<reqd_level>" + $("#weaponReqdLevel").val() + "</reqd_level>");
  fData.push("<reqd_class>" + $("#weaponReqdClass").val() + "</reqd_class>");
  fData.push("<equipped>" + $("input[name='weaponEquipped']").val() + "</equipped>");
  fData.push("<condtn>" + $("#weaponCondtn").val() + "</condtn>");
  fData.push("<deteriorates>" + $("input[name='weaponDeteriorates']").val() + "</deteriorates>");
  fData.push("<location_y>" + $("#weaponYVal").val() + "</location_y>");
  fData.push("<location_x>" + $("#weaponXVal").val() + "</location_x>");
  fData.push("<location_storey>" + $("#weaponStoreyVal").val() + "</location_storey>");
  fData.push("<image>" + $("#weaponImage").val() + "</image>");
  fData.push("</weapon></request>");
  return fData.join("");
}


function getBeingAsXML() {
  var description =  $("#beingDescription").val().replace(/\r?\n/g, '<br />');
  var fData = new Array();
  
  var envOptions = new Array("p", "b", "r", "m", "f", "p", "c");
  var resistances = getCheckBoxes(resistantOptions, "beingResistant");
  var vulnerabilities = getCheckBoxes(envOptions, "beingVulnerabilities");
  
  fData.push("<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>");
  fData.push("<request>");
  fData.push("<op>SaveNewBeing</op>");
  fData.push("<being>");
  
  fData.push("<name>" + $("#beingName").val() + "</name>");  
  fData.push("<race>" + $("#beingRace").val() + "</race>");
  fData.push("<hp><" + $("#beingHP").val() + "</hp>");
  fData.push("<level>" + $("#beingLevel").val() + "</level>");
  fData.push("<mp>" + $("#beingMP").val() + "</mp>");
  fData.push("<defence>" + $("#beingDefence").val() + "</defence>");
  fData.push("<image>" + $("#beingImage").val() + "</image>");
  fData.push("<str>" + $("#beingStr").val() + "</str>");
  fData.push("<dex>" + $("#beingDex").val() + "</dex>");
  fData.push("<con>" + $("#beingCon").val() + "</con>");
  fData.push("<wis>" + $("#beingWis").val() + "</wis>");
  fData.push("<itg>" + $("#beingItg").val() + "</itg>");
  fData.push("<cha>" + $("#beingCha").val() + "</cha>");
  
  fData.push("<resistant>" + resistances + "</resistant>");
  fData.push("<vulnerable>" + resistances + "</vulnerable>");
  
  fData.push("<mood>" + $("#beingMood").val() + "</mood>");
  fData.push("<location_y>" + $("#being").val() + "</locationy>");
  fData.push("<location_x>" + $("#being").val() + "</location_x>");
  fData.push("<location_storey>" + $("#being").val() + "</location_storey>");
  fData.push("<weapon_id1>" + $("#being").val() + "</weapon_id1>");
  fData.push("<item1_id>" + $("#being").val() + "</item1_id>");
  fData.push("<item2_id>" + $("#being").val() + "</item2_id>");
  fData.push("<gp>" + $("#being").val() + "</gp>");
  
  fData.push("<weapon_id2>" + $("#being").val() + "</weapon_id2>");
  fData.push("<weapon_id3>" + $("#being").val() + "</weapon_id3>");
  fData.push("<location_y>" + $("#being").val() + "</location_y>");
  fData.push("<location_x>" + $("#being").val() + "</location_x>");
  fData.push("<location_storey>" + $("#being").val() + "</location_storey>");
  
  
  fData.push("</being></request>");
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
  $("input[name='weaponEquipped'][value='0']").prop("checked", true);
  $("input[name='weaponDeteriorates'][value='1']").prop("checked", true);  

  //fillWithTestData();
  $("form").hide();
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
  
  
  $("#weaponSubmit").click(function() {
    var fData = getWeaponAsXML();
    var url = $("#frmWeapons").data("route");      
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
    //clearForm(frmSelected);
    //fillWithTestData();
  });
  

  
  
});
