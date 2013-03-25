var map;
        function initMap(){
            var map = new OpenLayers.Map('map');
            var proj4326 = new OpenLayers.Projection("EPSG:4326");
            var projmerc = new OpenLayers.Projection("EPSG:900913");
            var layerOSM = new OpenLayers.Layer.OSM("Street Map");
            map.addLayers([layerOSM]);
            if (!map.getCenter()) map.zoomToMaxExtent();
            map.events.register("mousemove", map, function(e) { 
                var position = this.events.getMousePosition(e);
                OpenLayers.Util.getElement("coords").innerHTML = 'MOUSE POSITION '+position;
                var lonlat = map.getLonLatFromPixel( this.events.getMousePosition(e) );
                OpenLayers.Util.getElement("lonlatTG").innerHTML = 'lonlat => '+lonlat;
                var lonlatTransf = lonlat.transform(map.getProjectionObject(), proj4326);
                OpenLayers.Util.getElement("lonlatTrans").innerHTML = 'lonlatTransf => '+lonlatTransf;
                OpenLayers.Util.getElement("lonlatDouble").innerHTML = 'lonlat => '+lonlat;
            });

            map.events.register("click", map, function(e) {
                var position = this.events.getMousePosition(e);
                var icon = new OpenLayers.Icon('http://maps.google.com/mapfiles/ms/icons/red-pushpin.png');   
                var lonlat = map.getLonLatFromPixel(position);
                var lonlatTransf = lonlat.transform(map.getProjectionObject(), proj4326);
                alert ('lonlatTrans=> lat='+lonlatTransf.lat+' lon='+lonlatTransf.lon+'\nlonlat=>'+lonlat+'\nposition=>'+position);
                var lonlat = lonlatTransf.transform(proj4326, map.getProjectionObject());
                var markerslayer = new OpenLayers.Layer.Markers( "Markers" );
                markerslayer.addMarker(new OpenLayers.Marker(lonlat, icon));
                map.addLayer(markerslayer);
            });
            map.addControl(new OpenLayers.Control.LayerSwitcher());
        }
