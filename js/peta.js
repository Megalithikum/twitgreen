 var map = null;
    var geocoder = null;
    var point = null;
    var marker = null;
    var inputSplit = null;
    var bounds = null;
  var countOther = 1;
  var numGeocoded = 0;
	var inputSplit = [];

function roundT(n,d)
    {
      return Math.round(n*Math.pow(10,d))/Math.pow(10,d);
    }

function initiz(){  
    map = new GMap2(document.getElementById("Map-div"));
        map.setCenter(new GLatLng(-6.72, 109.97), 4);
        map.addControl(new GLargeMapControl());
        geocoder = new GClientGeocoder();
        GEvent.addListener(map,"click", function(overlay, latlng) 
        {     
          if (latlng) 
            { 
			   var myMarker = new GMarker(latlng);
			   var myInfo = "<div style=\"font-family: Tahoma, sans-serif; font-size: 12px;\"><b>other " + countOther + " | " 
                            + roundT(latlng.lat(),5) + " ; "  + roundT(latlng.lng(),5) + "</b><br>has been added to the results box.</div>";
			   map.addOverlay(myMarker);
		       GEvent.addListener(myMarker, "click", function() { myMarker.openInfoWindowHtml(myInfo); });
			   document.getElementById("results").value += "Point" + countOther + " : "
                                                         + roundT(latlng.lat(),5) + " ; "
												         + roundT(latlng.lng(),5)
                                                         + "\n"   ;
			   countOther++;
            }
        });
    
    
    
} 
