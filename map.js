// Start position for the map (hardcoded here for simplicity,
// but maybe you want to get this from the URL params)
var lat=32.020000
var lon=118.900000
var zoom=7
var map
var extents
var layergpx

function mapinit(gpx){
	//Set Language to Japanese
	if (gpx.length == 0)
		return;
	OpenLayers.Lang.setCode("ja");

	//Create Map object
	map = new OpenLayers.Map('canvas', {
        controls:[
            new OpenLayers.Control.Navigation(),
            new OpenLayers.Control.PanZoomBar(),
            new OpenLayers.Control.LayerSwitcher(),
            new OpenLayers.Control.Attribution(),
			new OpenLayers.Control.ScaleLine(),
			new OpenLayers.Control.MousePosition({
				prefix: "マウス座標 経度",
				separator: "度 緯度",
				suffix: "度",
				displayProjection: new OpenLayers.Projection("EPSG:4326")
			})
		],
        maxResolution: 156543.0399,	//something like to minimum zoom level
        numZoomLevels: 19,
        units: 'm',
	});

	// Define the map layer
	layerTransportMap = new OpenLayers.Layer.OSM.TransportMap("交通図");
	map.addLayer(layerTransportMap);
	var layerosm = new OpenLayers.Layer.OSM("オープンストリートマップ");
    map.addLayer(layerosm);
	layerCycleMap = new OpenLayers.Layer.OSM.CycleMap("サイクリングマップ");
	map.addLayer(layerCycleMap);
	
	map.setCenter(
        new OpenLayers.LonLat(lon, lat).transform(
            new OpenLayers.Projection("EPSG:4326"),
            map.getProjectionObject()
        ), zoom
    );

	// Add the Layer with the GPX Track
	layergpx = new Array();
	extents = new OpenLayers.Bounds(0,0,0,0);
	for (var i = 0; i < gpx.length; i++) {
		layergpx[i] = getGPXInstance(gpx[i].date, gpx[i].filename, "blue");
		map.addLayer(layergpx[i]);

		// Zoom to GPX Track
		layergpx[i].events.register("loadend", layergpx[i], 
			function () {
				this.map.zoomToExtent(layergpx[0].getDataExtent());
            		}
		);
	}
}

function gpx_loaded(layer) {
	var bounds = layergpx[layer].getDataExtent();
	extents.extend(layergpx[layer].getDataExtent());
	map.zoomToExtent(extents);
}

function getGPXInstance(layername, filename, strokecolor) {
    return new OpenLayers.Layer.Vector(layername, {
        strategies: [new OpenLayers.Strategy.Fixed()],
        protocol: new OpenLayers.Protocol.HTTP({
            url: filename,
            format: new OpenLayers.Format.GPX({
                extractStyles: true,
                extractAttributes: true,
                mapDepth: 2
            })
        }),
        style: { strokeColor: strokecolor, strokeWidth: 5, strokeOpacity: 0.5 },
    });
}
