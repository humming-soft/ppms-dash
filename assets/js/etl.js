

$(function() {
    toGeoJSON = (function() {
        'use strict';

        var removeSpace = (/\s*/g),
                trimSpace = (/^\s*|\s*$/g),
                splitSpace = (/\s+/);
        // generate a short, numeric hash of a string
        function okhash(x) {
            if (!x || !x.length)
                return 0;
            for (var i = 0, h = 0; i < x.length; i++) {
                h = ((h << 5) - h) + x.charCodeAt(i) | 0;
            }
            return h;
        }
        // all Y children of X
        function get(x, y) {
            return x.getElementsByTagName(y);
        }
        function attr(x, y) {
            return x.getAttribute(y);
        }
        function attrf(x, y) {
            return parseFloat(attr(x, y));
        }
        // one Y child of X, if any, otherwise null
        function get1(x, y) {
            var n = get(x, y);
            return n.length ? n[0] : null;
        }
        // https://developer.mozilla.org/en-US/docs/Web/API/Node.normalize
        function norm(el) {
            if (el.normalize) {
                el.normalize();
            }
            return el;
        }
        // cast array x into numbers
        function numarray(x) {
            for (var j = 0, o = []; j < x.length; j++)
                o[j] = parseFloat(x[j]);
            return o;
        }
        function clean(x) {
            var o = {};
            for (var i in x)
                if (x[i])
                    o[i] = x[i];
            return o;
        }
        // get the content of a text node, if any
        function nodeVal(x) {
            if (x) {
                norm(x);
            }
            return (x && x.firstChild && x.firstChild.nodeValue) || '';
        }
        // get one coordinate from a coordinate array, if any
        function coord1(v) {
            return numarray(v.replace(removeSpace, '').split(','));
        }
        // get all coordinates from a coordinate array as [[],[]]
        function coord(v) {
            var coords = v.replace(trimSpace, '').split(splitSpace),
                    o = [];
            for (var i = 0; i < coords.length; i++) {
                o.push(coord1(coords[i]));
            }
            return o;
        }
        function coordPair(x) {
            var ll = [attrf(x, 'lon'), attrf(x, 'lat')],
                    ele = get1(x, 'ele');
            if (ele)
                ll.push(parseFloat(nodeVal(ele)));
            return ll;
        }

        // create a new feature collection parent object
        function fc() {
            return {
                type: 'FeatureCollection',
                features: []
            };
        }

        var serializer;
        if (typeof XMLSerializer !== 'undefined') {
            serializer = new XMLSerializer();
            // only require xmldom in a node environment
        } else if (typeof exports === 'object' && typeof process === 'object' && !process.browser) {
            serializer = new (require('xmldom').XMLSerializer)();
        }
        function xml2str(str) {
            return serializer.serializeToString(str);
        }

        var t = {
            kml: function(doc, o) {
                o = o || {};

                var gj = fc(),
                        // styleindex keeps track of hashed styles in order to match features
                        styleIndex = {},
                        // atomic geospatial types supported by KML - MultiGeometry is
                        // handled separately
                        geotypes = ['Polygon', 'LineString', 'Point', 'Track'],
                        // all root placemarks in the file
                        placemarks = get(doc, 'Placemark'),
                        styles = get(doc, 'Style');

                for (var k = 0; k < styles.length; k++) {
                    styleIndex['#' + attr(styles[k], 'id')] = okhash(xml2str(styles[k])).toString(16);
                }
                for (var j = 0; j < placemarks.length; j++) {
                    gj.features = gj.features.concat(getPlacemark(placemarks[j]));
                }
                function kmlColor(v) {
                    var color, opacity;
                    v = v || "";
                    if (v.substr(0, 1) === "#")
                        v = v.substr(1);
                    if (v.length === 6 || v.length === 3)
                        color = v;
                    if (v.length === 8) {
                        opacity = parseInt(v.substr(0, 2), 16) / 255;
                        color = v.substr(2);
                    }
                    return [color, isNaN(opacity) ? undefined : opacity];
                }
                function gxCoord(v) {
                    return numarray(v.split(' '));
                }
                function gxCoords(root) {
                    var elems = get(root, 'coord', 'gx'), coords = [];
                    for (var i = 0; i < elems.length; i++)
                        coords.push(gxCoord(nodeVal(elems[i])));
                    return coords;
                }
                function getGeometry(root) {
                    var geomNode, geomNodes, i, j, k, geoms = [];
                    if (get1(root, 'MultiGeometry'))
                        return getGeometry(get1(root, 'MultiGeometry'));
                    if (get1(root, 'MultiTrack'))
                        return getGeometry(get1(root, 'MultiTrack'));
                    for (i = 0; i < geotypes.length; i++) {
                        geomNodes = get(root, geotypes[i]);
                        if (geomNodes) {
                            for (j = 0; j < geomNodes.length; j++) {
                                geomNode = geomNodes[j];
                                if (geotypes[i] == 'Point') {
                                    geoms.push({
                                        type: 'Point',
                                        coordinates: coord1(nodeVal(get1(geomNode, 'coordinates')))
                                    });
                                } else if (geotypes[i] == 'LineString') {
                                    geoms.push({
                                        type: 'LineString',
                                        coordinates: coord(nodeVal(get1(geomNode, 'coordinates')))
                                    });
                                } else if (geotypes[i] == 'Polygon') {
                                    var rings = get(geomNode, 'LinearRing'),
                                            coords = [];
                                    for (k = 0; k < rings.length; k++) {
                                        coords.push(coord(nodeVal(get1(rings[k], 'coordinates'))));
                                    }
                                    geoms.push({
                                        type: 'Polygon',
                                        coordinates: coords
                                    });
                                } else if (geotypes[i] == 'Track') {
                                    geoms.push({
                                        type: 'LineString',
                                        coordinates: gxCoords(geomNode)
                                    });
                                }
                            }
                        }
                    }
                    return geoms;
                }
                function getPlacemark(root) {
                    var geoms = getGeometry(root), i, properties = {},
                            name = nodeVal(get1(root, 'name')),
                            styleUrl = nodeVal(get1(root, 'styleUrl')),
                            description = nodeVal(get1(root, 'description')),
                            timeSpan = get1(root, 'TimeSpan'),
                            extendedData = get1(root, 'ExtendedData'),
                            lineStyle = get1(root, 'LineStyle'),
                            polyStyle = get1(root, 'PolyStyle');

                    if (!geoms.length)
                        return [];
                    if (name)
                        properties.name = name;
                    if (styleUrl && styleIndex[styleUrl]) {
                        properties.styleUrl = styleUrl;
                        properties.styleHash = styleIndex[styleUrl];
                    }
                    if (description)
                        properties.description = description;
                    if (timeSpan) {
                        var begin = nodeVal(get1(timeSpan, 'begin'));
                        var end = nodeVal(get1(timeSpan, 'end'));
                        properties.timespan = {begin: begin, end: end};
                    }
                    if (lineStyle) {
                        var linestyles = kmlColor(nodeVal(get1(lineStyle, 'color'))),
                                color = linestyles[0],
                                opacity = linestyles[1],
                                width = parseFloat(nodeVal(get1(lineStyle, 'width')));
                        if (color)
                            properties.stroke = color;
                        if (!isNaN(opacity))
                            properties['stroke-opacity'] = opacity;
                        if (!isNaN(width))
                            properties['stroke-width'] = width;
                    }
                    if (polyStyle) {
                        var polystyles = kmlColor(nodeVal(get1(polyStyle, 'color'))),
                                pcolor = polystyles[0],
                                popacity = polystyles[1],
                                fill = nodeVal(get1(polyStyle, 'fill')),
                                outline = nodeVal(get1(polyStyle, 'outline'));
                        if (pcolor)
                            properties.fill = pcolor;
                        if (!isNaN(popacity))
                            properties['fill-opacity'] = popacity;
                        if (fill)
                            properties['fill-opacity'] = fill === "1" ? 1 : 0;
                        if (outline)
                            properties['stroke-opacity'] = outline === "1" ? 1 : 0;
                    }
                    if (extendedData) {
                        var datas = get(extendedData, 'Data'),
                                simpleDatas = get(extendedData, 'SimpleData');

                        for (i = 0; i < datas.length; i++) {
                            properties[datas[i].getAttribute('name')] = nodeVal(get1(datas[i], 'value'));
                        }
                        for (i = 0; i < simpleDatas.length; i++) {
                            properties[simpleDatas[i].getAttribute('name')] = nodeVal(simpleDatas[i]);
                        }
                    }
                    return [{
                            type: 'Feature',
                            geometry: (geoms.length === 1) ? geoms[0] : {
                                type: 'GeometryCollection',
                                geometries: geoms
                            },
                            properties: properties
                        }];
                }
                return gj;
            },
            gpx: function(doc, o) {
                var i,
                        tracks = get(doc, 'trk'),
                        routes = get(doc, 'rte'),
                        waypoints = get(doc, 'wpt'),
                        // a feature collection
                        gj = fc();
                for (i = 0; i < tracks.length; i++) {
                    gj.features.push(getTrack(tracks[i]));
                }
                for (i = 0; i < routes.length; i++) {
                    gj.features.push(getRoute(routes[i]));
                }
                for (i = 0; i < waypoints.length; i++) {
                    gj.features.push(getPoint(waypoints[i]));
                }
                function getPoints(node, pointname) {
                    var pts = get(node, pointname), line = [];
                    for (var i = 0; i < pts.length; i++) {
                        line.push(coordPair(pts[i]));
                    }
                    return line;
                }
                function getTrack(node) {
                    var segments = get(node, 'trkseg'), track = [];
                    for (var i = 0; i < segments.length; i++) {
                        track.push(getPoints(segments[i], 'trkpt'));
                    }
                    return {
                        type: 'Feature',
                        properties: getProperties(node),
                        geometry: {
                            type: track.length === 1 ? 'LineString' : 'MultiLineString',
                            coordinates: track.length === 1 ? track[0] : track
                        }
                    };
                }
                function getRoute(node) {
                    return {
                        type: 'Feature',
                        properties: getProperties(node),
                        geometry: {
                            type: 'LineString',
                            coordinates: getPoints(node, 'rtept')
                        }
                    };
                }
                function getPoint(node) {
                    var prop = getProperties(node);
                    prop.sym = nodeVal(get1(node, 'sym'));
                    return {
                        type: 'Feature',
                        properties: prop,
                        geometry: {
                            type: 'Point',
                            coordinates: coordPair(node)
                        }
                    };
                }
                function getProperties(node) {
                    var meta = ['name', 'desc', 'author', 'copyright', 'link',
                        'time', 'keywords'],
                            prop = {},
                            k;
                    for (k = 0; k < meta.length; k++) {
                        prop[meta[k]] = nodeVal(get1(node, meta[k]));
                    }
                    return clean(prop);
                }
                return gj;
            }
        };
        return t;
    })();

    if (typeof module !== 'undefined')
        module.exports = toGeoJSON;

    $.ajax('/mpxd/assets/js/tunnel.gpx').done(function(xml) {
        abc = toGeoJSON.gpx(xml);
        a = [];
        $.each(abc.features, function(idx, i) {
            a.push(i.geometry.coordinates[0]);
            //console.log(i.geometry.coordinates.length)
            //if (idx > 1) { if (i.geometry.coordinates[0] != abc.features[idx-1].geometry.coordinates[1]) console.log('noo')}
            //a.push(i.geometry.coordinates)
        })

        //console.log(abc);
    });



	function testtest(){
	
		console.log("test");
	}



})
    //drawPortlets([{"col":1,"row":1,"size_x":6,"size_y":1,id:"1",type:"scurve"},{"col":7,"row":1,"size_x":6,"size_y":10,id:"2"},{"col":2,"row":2,"size_x":6,"size_y":1,id:"3"},{"col":3,"row":4,"size_x":6,"size_y":1,id:"4"}]);

    /*
     mpxd.modules.scurve.GenerateScurve({
     id: 1,
     title: "Elevated South (Civil/Systems)",
     actualData: [0.372827,0.620892,1.24215,1.55246,2.04931,2.29734,2.79418,3.22886,3.85019,4.34712,5.2796,5.71423,6.08667,6.77027,7.70274,8.63516,9.8166,11.8069,13.0506,14.7298,16.1601,17.8393,19.5807,21.3222,22.6902,24.3694,26.4843,28.2879,30.1538,32.3309],
     earlyData: [0.374583,0.624847,1.12453,1.87349,2.80969,3.49644,3.99613,4.18406,4.55909,4.93408,5.43376,6.05821,6.37083,6.80816,7.68193,8.431,9.74113,12.1113,13.6084,15.4797,17.1015,19.4715,21.7169,24.0869,25.8958,28.2659,30.823,33.8789,37.1216,40.0527,43.4203,46.7878,49.7812,52.5253,56.0176,59.2604,62.3785,65.8708,69.1137,71.7331,73.6042,76.2237,78.4689,80.5895,83.2091,85.0802,86.3903,87.8876,89.1979,90.0716,91.0699,92.0685,92.4435,93.0681,93.443,93.8181,93.9435,93.9444,94.0701,94.1956,94.5706,94.821,95.0087,96.5061,98.0032,98.9393,99.9374],
     delayedData: [0.248457,0.683119,1.05557,2.11253,2.98271,3.66627,3.97648,4.34894,4.65915,5.09377,5.6529,6.0876,6.64667,6.89466,7.76492,8.75962,9.94105,11.2468,11.9304,13.2985,13.9821,15.7858,17.4028,19.8909,21.1967,23.1871,24.9908,27.0433,28.6604,30.8374,32.8899,35.0671,37.6174,39.6701,41.9715,44.9575,46.9478,49.0938,51.5196,53.6656,55.8114,58.0507,60.5699,62.9959,64.9552,67.1011,69.807,72.233,74.7521,76.6181,79.3243,81.9056,84.5182,86.0108,87.6277,88.7469,89.3682,90.114,90.611,91.3566,91.7292,92.4751,93.221,94.7134,96.766,98.072,99.7515],
     currentEarly: "39.66%",
     currentLate: "30.60%",
     currentActual: "32.10%",
     varEarly: "-10.5w",
     varLate: "3.5w",
     trend: "up",
     chartType: "short",
     viewType: "2"
     });
     
    aaa = [
        ["1", "AW1 - Package A", "Dilapidation Survey Works for Semantan and Cochrane launching shaft", "3-Feb-2011", "42,150.00", "Cunningham Lindsey Adjusters (M) Sdn Bhd"],
        ["2", "AW2 - Package C1", "Construction and completion of working platform including Earthworks, Access Road, Drainage Works,TNB Substation, Retaining Walls, Utilities Protection and Ancillary Works for Semantan launching shaft", "6-Mar-2011", "12,256,534.91", "Menta Construction Sdn Bhd"],
        ["3", "AW3 - Package C2", "Construction and completion of working platform including demolition of existing government quarters and playground, site clearance, utilities protection, drainage works, TNB substation, tree relocation, traffic diversion & control and ancillary works for Cochrane launching shaft", "6-Mar-2011", "7,261,371.70", "Ragawang Corporation Sdn Bhd"],
        ["4", "AW4 - Package D1", "Relocation of existing telecommunication & power supply cables for Cochrane launching shaft", "27-May-2011", "12,837.00", "E.S.S. Engineering Sdn Bhd"],
        ["5", "AW5 - Package D2", "Relocation of existing sewerage and water mains for Cochrane launching shaft", "24-Jun-2011", "493,688.43", "Puncak Niaga (M) Sdn Bhd"],
        ["6", "AW6 - Package E", "Supply and installation of instrumentation and equipment for monitoring works including automated total station for Semantan and Cochrane launching shaft", "6-Sep-2011", "1,148,820.00", "Soil Instruments (M) Sdn Bhd"],
        ["7", "AW7- Package F1", "Construction and completion of contiguous bored piles including ground anchor for Semantan launching Shaft", "3-Jun-2012", "6,579,624.00", "Geohan Sdn Bhd"],
        ["8", "AW8 - Package F2", "Construction and completion of secant bored pile for Cochrane launching shaft", "15-Aug-2011", "10,588,000.00", "Bauer (M) Sdn Bhd"],
        ["9", "AW9", "Construction and completion of earthworks and associated works (Phase 1) at Sungai Buloh Depot", "6-Sep-2011", "23,910,226.60", "Gadang Engineering (M) Sdn Bhd"],
        ["10", "AW10", "Demolition of chiller room, pump houses and removal of piles obstructing TMB drive at IPPKKL", "17-Oct-2011", "2,645,950.00", "Pembinaan CW Yap Sdn Bhd"],
        ["11", "AW11 - Package GPL1", "Relocation of existing gas pipe line for KL Sentral", "3-Jun-2012", "917,186.00", "Misi Setia Oil and Gas Sdn Bhd"],
        ["12", "AW12 - Package SYB1", "Relocation of existing water pipe line and traffic management for utilities relocation for KL Sentral", "31-Jan-2012", "5,691,650.00", "Hatimuda Sdn Bhd"],
        ["13", "AW13 - Package TEL1", "The relocation of existing telecommunication lines for KL Sentral", "14-Dec-2011", "697,044.80", "Sri Communication Sdn Bhd"],
        ["14", "AW14 - Package TCO1", "The relocation of existing fibre optic lines for KL Sentral", "14-Dec-2011", "1,496,568.90", "Tenaga Nirwana (M) Sdn Bhd"],
        ["15", "AW15 - Package TNB1", "The relocation of existing power supply cables for KL Sentral and Merdeka", "14-Dec-2011", "1,723,085.00", "Worktime Engineering Sdn Bhd"],
        ["16", "AW16 - Package TEL2", "The relocation of existing telecommunication lines for Merdeka", "14-Dec-2011", "1,979,949.90", "Sri Communication Sdn Bhd"],
        ["17", "AW17 - Package SYB2", "Relocation of existing water pipe line for Maluri", "3-Aug-2012", "8,338,000.00", "MMC Gamuda KVMRT (PDP) Sdn Bhd"],
        ["18", "AW18 - Package TEL3", "Relocation of existing telecommunication lines for Maluri", "19-Jan-2012", "10,023,562.97", "Sri Communication Sdn Bhd"],
        ["19", "AW19 - Package TCO2", "Relocation of existing fibre optic lines for Maluri", "19-Jan-2012", "3,895,623.20", "Fastpro Sdn Bhd"],
        ["20", "AW20 - Package TNB2", "Relocation of existing power supply cables for Maluri", "3-Jun-2012", "37,888,400.00", "Huls Transmission Sdn Bhd"],
        ["21", "AW21", "Grouting works, underground excavation, ground anchors and rock bolts for Cochrane launching shaft & station", "3-Aug-2012", "24,016,411.50", "Keller (M) Sdn Bhd"],
        ["22", "AW22", "Demolition of superstructure for Klang bus stand, Plaza Warisan and UO superstore, supply and installation of 1MVA compact substation and all necessary associated works for Pasar Seni Station", "3-Jun-2012", "11,904,040.00", "Pembinaan C W Yap Sdn Bhd"],
        "PART B : GUIDEWAY (VIADUCT)",
        ["23", "Elevated Section V1 - Viaduct", "Construction and completion of viaduct guideway and other associated works from Sungai Buloh to Kota Damansara Station", "18-May-2012", "1,092,333,333.00", "Syarikat Muhibah & Perniagaan & Pembinaan Sdn Bhd"],
        ["24", "Elevated Section V2 - Viaduct", "Construction and completion of viaduct guideway and other associated works from Kota Damansara to Dataran Sunway Station", "7-Oct-2012", "863,393,908.00", "Gadang Engineering (M) Sdn Bhd"],
        ["25", "Elevated Section V3 - Viaduct", "Construction and completion of viaduct guideway and other associated works from Dataran Sunway to Section 17", "7-Oct-2012", "816,242,537.64", "Mudajaya Corporation Berhad"],
        ["26", "Elevated Section V4 - Viaduct", "Construction and completion of viaduct guideway and other associated works from Section 17 to Semantan Portal", "18-May-2012", "1,172,750,000.00", "Sunway Construction Sdn Bhd"],
        ["27", "Elevated Section V5 - Viaduct", "Construction and completion of viaduct guideway and other associated works from Maluri portal to Plaza Phoenix station", "16-Feb-2012", "974,780,000.00", "IJM Construction Sdn Bhd"],
        ["28", "Elevated Section V6 - Viaduct", "Construction and completion of viaduct guideway and other associated works from Plaza Phoenix station to Bandar Tun Hussein Onn Station", "16-Feb-2012", "764,907,527.28", "Ahmad Zaki Sdn Bhd"],
        ["29", "Elevated Section V7 - Viaduct", "Construction and completion of viaduct guideway and other associated works from Bandar Tun Hussein Onn to Taman Mesra", "18-May-2012", "499,980,104.78", "MTD Construction Sdn Bhd"],
        ["30", "Elevated Section V8 - Viaduct", "Construction and completion of viaduct guideway and other associated works from Taman Mesra to Kajang Station", "26-Sep-2012", "951,086,627.26", "UEM Construction Sdn Bhd"],
        "PART C : DEPOTS",
        ["31", "Package Dpt1", "Construction And Completion Of Sungai Buloh Maintenance Depot, Administration Building, External Works And Other Associated Works", "18-May-2012", "458,980,000.00", "Trans Resources Corporation Sdn Bhd"],
        ["32", "Package Dpt2", "Construction And Completion Of Kajang Maintenance Depot, External Works And Other Associated Works", "24-Jul-2012", "212,808,808.00", "TSR Bina Sdn Bhd"],
        "PART D : UNDERGROUND WORKS",
        ["33", "Underground works (Tunnel stations & associated structures) between Semantan north portal and Maluri south portal", "Package MSPR1 : Construction And Completion Of Multi Storey Car Park Building, External Works And Other Associated Works At Sungai Buloh Station", "30-Mar-12", "8,280,000,000.00", "MMC Gamuda KVMRT (T) Sdn Bhd"],
        "PART E : MULTISTOREY CAR PARKS",
        ["35", "Package MSPR4", "Construction And Completion Of Multi Storey Car Park Building, External Works And Other Associated Works at Section 16 Station", "30-Jun-14", "83,440,000.00", "Budaya Restu Sdn Bhd "],
        ["36", "Package MSPR6", "Construction And Completion Of Multi Storey Car Park Building, External Works And Other Associated Works At Taman Bukit Mewah Station", "30-Jun-14", "128,860,838.60", "Perkasa Sutera Sdn Bhd "],
        ["37", "Package MSPR8", "Construction and Completion of Multi Storey Car Oark Building, External Works And Other Associated Works At Taman Koperasi Station", "30-Jun-14", "115,975,441.93", "InnoSeven Sdn Bhd "],
        ["38", "Package MSPR11", "Construction And Completion Of Multi Storey Car Park Building, External Works And Other Associated Works At  Saujana Impian Station", "30-Jun-14", "123,200,000.00", "RD Resources Sdn Bhd "],
        ["39", "Package MSPR9", "Construction and Completion of Multi Storey Car Oark Building, External Works And Other Associated Works At Kajang Station", "8-Mar-2013", "50,701,461.46", "SMPP-IBWANI Joint Venture"],
        "PART F : SYSTEMS",
        "System Group 1",
        ["40", "Systems Work Package SBK-S-01", "Engineering, Procurement, Construction, Testing & Commissioning Of Electric Trains", "28-Sep-12", "1,365,083,284.00", "Consortium of Siemens Malaysia Sdn Bhd, Siemens AG and SMH Rail Sdn Bhd"],
        ["41", "Systems Work Package SBK-S-02", "Engineering, Procurement, Construction, Testing & Commissioning Of Depot Equipment & Maintenance Vehicles", "28-Sep-12", "418,813,910.00", "Consortium of Siemens Malaysia Sdn Bhd, Siemens AG, Hisniaga Sdn Bhd"],
        ["42", "Systems Work Package SBK-S-03", "Engineering, Procurement, Construction, Testing & Commissioning Of Signalling & Train Control System", "28-Sep-12", "281,314,655.00", "Bombardier (Malaysia) Sdn Bhd"],
        ["43", "System Works Package SBK-S-04", "Engineering, Procurement, Construction, Testing & Commissioning Of Platform Screen Doors and Automatic Platform Gates", "31-Jan-13", "78,089,833.00", "Singapore Technologies Electronics Limited"],
        "System Group 2",
        ["44", "Systems Work Package SBK-S-05", "Engineering, Procurement, Construction, Testing & Commissioning Of Power Supply & Distribution System", "28-Sep-12", "459,248,224.71", "Meidensha Corporation"],
        ["45", "Systems Work Package SBK-S-06", "Engineering, Procurement, Construction, Testing & Commissioning Of Track Works", "31-Oct-12", "855,000,000.00", "Mitsubishi Heavy Industries, Ltd"],
        "System Group 3",
        ["46", "Systems Works Package -SBK-S-07", "Engineering, Procurement, Construction, Testing & Commissioning Of Telecommunication System", "31-Oct-12", "319,941,634.00", "Apex Communication Sdn Bhd - LG CNS Consortium"],
        ["47", "System Works Package SBK-S-08", "Engineering, Procurement, Construction, Testing & Commissioning Of Facility Scada", "11-Aug-12", "23,241,459.00", "A.F.S. Engineering (M) Sdn Bhd - ST Electronics Ltd"],
        ["48", "System Works Package SBK-S-09", "Engineering, Procurement, Construction, Testing & Commissioning Of Automatic Fare Collection System", "22-Nov-12", "120,763,079.00", "Affiliated Computer Services Solutions France SAS"],
        ["49", "System Works Package SBK-S-10", "Engineering, Procurement, Construction, Testing & Commissioning Of Electronic Access Control System", "11-Aug-12", "41,014,958.79", "Apex Communication Sdn Bhd - Johnson Controls (M) Sdn Bhd"],
        ["50", "System Works SBK-S-11", "Engineering, Procurement, Construction, Testing & Commissioning Of Building Management System", "11-Aug-12", "43,042,826.00", "Metronic Engineering Sdn Bhd"],
        ["51", "System Works Package SBK-S-12", "Engineering, Procurement, Construction, Testing & Commissioning Of Government Integrated Radio Network", "6-Apr-13", "4,405,479.55", "Sapura Research Sdn Bhd"],
        ["52", "System Works Package SBK-S-13", "Design, Procurement, Configuration, Installation, Testing and Commisioning, Training and Documentation of IT System for All Elevated and Underground Stations And Depots", "23-Jul-14", "44,467,821.00", "EV-Dynamic Sdn Bhd "],
        "PART G: ELEVATED STATIONS",
        ["53", "Package S1", "Construction And Completion Of Elevated Stations And Other Associated Works At Sungai Buloh, Kg. Baru Sungai Buloh And Kota Damansara", "28-Aug-12", "283,668,000.00", "Trans Resources Corporation Sdn Bhd"],
        ["54", "Package S2", "Construction And Completion Of Elevated Stations And Other Associated Works At Taman Industrial Sungai Buloh, PJU 5 And Dataran Sunway", "10-Mar-12", "204,661,590.00", "Naim Engineering Sdn Bhd"],
        ["55", "Package S3", "Construction And Completion Of Elevated Stations And Other Associated Works At The Curve, One Utama And Tamn Tun Dr Ismail", "10-Mar-12", "275,773,408.38", "UEM Construction Sdn Bhd"],
        ["56", "Package S4", "Construction And Completion Of Elevated Stations And Other Associated Works At Section 16, Bandar Damansara And Semantan", "9-Jul-12", "208,153,000.00", "Naim Engineering Sdn Bhd"],
        ["57", "Package S5", "Construction And Completion Of Elevated Stations And Other Associated Works At Taman Bukit Ria, Taman Bukit Mewah, Leisure Mall And Plaza Phoenix", "24-Jul-12", "228,910,000.00", "IJM Construction Sdn Bhd"],
        ["58", "Package S6", "Construction And Completion Of Elevated Stations And Other Associated Works At Taman Suntex, Taman Cuepacs And Bandar Tun Hussein Onn", "28-Aug-12", "174,644,172.87", "Ahmad Zaki Sdn Bhd"],
        ["59", "Package S7", "Construction And Completion Of Elevated Stations And Other Associated Works At Balakong And Taman Koperasi", "28-Aug-12", "104,749,720.59", "Apex Communication Sdn Bhd"],
        ["60", "Package S8", "Construction And Completion Of Elevated Stations And Other Associated Works At Saujana Impian, Bandar Kajang And Kajang", "10-Mar-12", "251,743,307.09", "Apex Communication Sdn Bhd"],
        "PART H: CIVIL & STRUCTURAL AND OTHER WORKS",
        ["61", "SBG-Northern", "Supply And Delivery Of Segmental Box Girders (Sbg) For Proposed Projek Mass Rapid Transit Lembah Kelang: Jajaran Sg Buloh   Kajang (Northern Section)", "2-Feb-12", "223,180,030.00", "SPC Industries Sdn Bhd"],
        ["62", "SBG-Southern", "Supply And Delivery of Segmental Box Girders (Sbg) For Proposed Projek Mass Rapid Transit Lembah Kelang: Jajaran Sungai Buloh-Kajang 9 (Southern Section)", "2-Feb-12", "185,912,543.60", "Eastern Pretech (M) Sdn Bhd"],
        ["63", "Package V1 to V8", "Design, Supply, Installation, Testing And Commissioning Of Noise Barriers And Enclosures", "18-Mar-13", "201,989,570.00", "Muhibbah - SV 0 Samjung JV"],
        ["64", "Package ECS1", "Supply, Procurement, Installation, Testing And Commissioning Of Environmental Control System For Elevated Package V1 To V4 And Sungai Buloh Depot", "18-Mar-13", "44,180,000.00", "Kejuruteraan Astra Sdn Bhd"],
        ["65", "Package ECS2", "Supply, Procurement, Installation, Testing And Commissioning Of Environmental Control System For Elevated Package V5 To V8 And Kajang Depot", "18-Mar-13", "21,973,970.00", "FastColl Corporation Sdn Bhd"],
        ["66", "Package FD1", "Supply, Procurement, Installation, Testing and Commissioning of Fire Detection and Protection System for Elevated Package V1 to V2 and Sungai Buloh Depot", "4-Jun-13", "31,966,800.00", "P. J. Indah Sdn Bhd"],
        ["67", "Package FD2", "Supply, Procurement, Installation, Testing and Commissioning of Fire Detection and Protection System for Elevated Package V3 to V4", "16-Aug-13", "14,775,000.00", "Fitters-MPS Sdn Bhd"],
        ["68", "Package FD3", "Supply, Procurement, Installation, Testing And Commissioning Of Fire Detection and Protection System For Elevated Package V5 To V5", "18-Mar-13", "18,822,000.00", "Zulfan (M) Sdn Bhd"],
        ["69", "Package FD4", "Supply, Procurement, Installation, Testing And Commissioning Of Fire Detection And Protection System For Elevated Package V7 To V8 And Kajang Depot", "13-Jun-13", "22,283,800.00", "Mecomb Malaysia Sdn Bhd"],
        ["70", "Package SDDT-E", "Supply, Delivery And Supervision Of Installation, Testing And Commissioning Of Step Down Distribution Transformer For All Elevated Packages, Sungai Buloh and Kajang Depot", "18-Mar-13", "10,065,635.00", "PTIS Engineering Sdn Bhd"],
        ["71", "Package UPS-E", "Supply, Delivery And Supervision Of Installation, Testing And Commissioning Of Uninterruptible Power Supply For All Elevated Stations", "18-Mar-13", "6,688,320.00", "Info-Matic Power Sdn Bhd"],
        ["72", "Package LS-E", "Supply, Installation, Testing And Commissioning Of Lift System For All Elevated Packages, Sungai Buloh Depot And Multi Storey Carparks", "18-Mar-13", "42,450,000.00", "Otis Elevator Company (M) Sdn Bhd"],
        ["73", "Package ETS-E", "Supply, Procurement, Installation, Testing And Commissioning Of Escalator And Travelator System For All Elevated Stations", "31-Jan-13", "102,670,000.00", "MS Elevators Engineering Sdn Bhd"],
        ["74", "Package LED-E", "Design, Supply, Procurement, Installation, Testing And Commissioning Of LED Lighting and Lighting Boom Equipment For All Elevated Packages", "20-Dec-13", "39,900,812.00", "Norangkasa Enterprise Sdn Bhd"],
        ["75", "Package ETS-U", "Design, Supply, Installation, Testing and Commisioning of Escalator and Travelator System for All Underground Stations", "01-Jul-14", "68,000,000.00", "Kone Elevator (M) Sdn Bhd "],
        ["76", "Package LS-U (R1)", "Supply, Procurement, Installation, Testing and Commisioning of Lift System for All Underground Stations and Ancillary Buildings", "25-Jul-14", "15,300,000.00", "Eita Elevator  (Malaysia) Sdn Bhd "],
        ["77", "Package SDDT-U", "Supply, Delivery, Installation, Testing and Commisioning of Lift System for All Underground Stations and Ancillary Buildings", "26-Jul-14", "7,418,164.00", "PTIS Engineering Sdn Bhd "]
    ];

    /*
     aaa = [
     ["1","AW1 - Package A","3-Feb-2011"," 42,150.00 ","Cunningham Lindsey Adjusters (M) Sdn Bhd"  ],
     ["","Dilapidation Survey Works for Semantan and Cochrane launching shaft","","",""  ],
     ["2","AW2 - Package C1","6-Mar-2011"," 12,256,534.91 ","Menta Construction Sdn Bhd"  ],
     ["","Construction and completion of working platform including Earthworks, Access Road, Drainage Works,TNB Substation, Retaining Walls, Utilities Protection and Ancillary Works for Semantan launching shaft","","",""  ],
     ["3","AW3 - Package C2","6-Mar-2011"," 7,261,371.70 ","Ragawang Corporation Sdn Bhd"  ],
     ["","Construction and completion of working platform including demolition of existing government quarters and playground, site clearance, utilities protection, drainage works, TNB substation, tree relocation, traffic diversion & control and ancillary works for Cochrane launching shaft","","",""  ],
     ["4","AW4 - Package D1","27-May-2011"," 12,837.00 ","E.S.S. Engineering Sdn Bhd"  ],
     ["","Relocation of existing telecommunication & power supply cables for Cochrane launching shaft","","","LOA by PDP"  ],
     ["5","AW5 - Package D2","24-Jun-2011"," 493,688.43 ","Puncak Niaga (M) Sdn Bhd"  ],
     ["","Relocation of existing sewerage and water mains for Cochrane launching shaft","","",""  ],
     ["6","AW6 - Package E","6-Sep-2011"," 1,148,820.00 ","Soil Instruments (M) Sdn Bhd"  ],
     ["","Supply and installation of instrumentation and equipment for monitoring works including automated total station for Semantan and Cochrane launching shaft","","",""  ],
     ["7","AW7- Package F1","3-Jun-2012"," 6,579,624.00 ","Geohan Sdn Bhd"  ],
     ["","Construction and completion of contiguous bored piles including ground anchor for Semantan launching Shaft","","",""  ],
     ["8","AW8 - Package F2","15-Aug-2011"," 10,588,000.00 ","Bauer (M) Sdn Bhd"  ],
     ["","Construction and completion of secant bored pile for Cochrane launching shaft","","",""  ],
     ["9","AW9","6-Sep-2011"," 23,910,226.60 ","Gadang Engineering (M) Sdn Bhd"  ],
     ["","Construction and completion of earthworks and associated works (Phase 1) at Sungai Buloh Depot","","",""  ],
     ["10","AW10","17-Oct-2011"," 2,645,950.00 ","Pembinaan CW Yap Sdn Bhd"  ],
     ["","Demolition of chiller room, pump houses and removal of piles obstructing TMB drive at IPPKKL","","",""  ],
     ["11","AW11 - Package GPL1","3-Jun-2012"," 917,186.00 ","Misi Setia Oil and Gas Sdn Bhd"  ],
     ["","Relocation of existing gas pipe line for KL Sentral","","",""  ],
     ["12","AW12 - Package SYB1","31-Jan-2012"," 5,691,650.00 ","Hatimuda Sdn Bhd"  ],
     ["","Relocation of existing water pipe line and traffic management for utilities relocation for KL Sentral","","",""  ],
     ["13","AW13 - Package TEL1","14-Dec-2011"," 697,044.80 ","Sri Communication Sdn Bhd"  ],
     ["","The relocation of existing telecommunication lines for KL Sentral","","",""  ],
     ["14","AW14 - Package TCO1","14-Dec-2011"," 1,496,568.90 ","Tenaga Nirwana (M) Sdn Bhd"  ],
     ["","The relocation of existing fibre optic lines for KL Sentral","","",""  ],
     ["15","AW15 - Package TNB1","14-Dec-2011"," 1,723,085.00 ","Worktime Engineering Sdn Bhd"  ],
     ["","The relocation of existing power supply cables for KL Sentral and Merdeka","","",""  ],
     ["16","AW16 - Package TEL2","14-Dec-2011"," 1,979,949.90 ","Sri Communication Sdn Bhd"  ],
     ["","The relocation of existing telecommunication lines for Merdeka","","",""  ],
     ["17","AW17 - Package SYB2","3-Aug-2012"," 8,338,000.00 ","MMC Gamuda KVMRT (PDP) Sdn Bhd"  ],
     ["","Relocation of existing water pipe line for Maluri","","",""  ],
     ["18","AW18 - Package TEL3","19-Jan-2012"," 10,023,562.97 ","Sri Communication Sdn Bhd"  ],
     ["","Relocation of existing telecommunication lines for Maluri","","",""  ],
     ["19","AW19 - Package TCO2","19-Jan-2012"," 3,895,623.20 ","Fastpro Sdn Bhd"  ],
     ["","Relocation of existing fibre optic lines for Maluri","","",""  ],
     ["20","AW20 - Package TNB2","3-Jun-2012"," 37,888,400.00 ","Huls Transmission Sdn Bhd"  ],
     ["","Relocation of existing power supply cables for Maluri","","",""  ],
     ["21","AW21","3-Aug-2012"," 24,016,411.50 ","Keller (M) Sdn Bhd"  ],
     ["","Grouting works, underground excavation, ground anchors and rock bolts for Cochrane launching shaft & station","","",""  ],
     ["22","AW22","3-Jun-2012"," 11,904,040.00 ","Pembinaan C W Yap Sdn Bhd"  ],
     ["","Demolition of superstructure for Klang bus stand, Plaza Warisan and UO superstore, supply and installation of 1MVA compact substation and all necessary associated works for Pasar Seni Station","","",""  ],
     ["23","Elevated Section V1 - Viaduct","18-May-2012"," 1,092,333,333.00 ","Syarikat Muhibah & Perniagaan & Pembinaan Sdn Bhd"  ],
     ["","Construction and completion of viaduct guideway and other associated works from Sungai Buloh to Kota Damansara Station","","",""  ],
     ["24","Elevated Section V2 - Viaduct","7-Oct-2012"," 863,393,908.00 ","Gadang Engineering (M) Sdn Bhd"  ],
     ["","Construction and completion of viaduct guideway and other associated works from Kota Damansara to Dataran Sunway Station","","",""  ],
     ["25","Elevated Section V3 - Viaduct","7-Oct-2012"," 816,242,537.64 ","Mudajaya Corporation Berhad"  ],
     ["","Construction and completion of viaduct guideway and other associated works from Dataran Sunway to Section 17","","",""  ],
     ["26","Elevated Section V4 - Viaduct","18-May-2012"," 1,172,750,000.00 ","Sunway Construction Sdn Bhd"  ],
     ["","Construction and completion of viaduct guideway and other associated works from Section 17 to Semantan Portal","","",""  ],
     ["27","Elevated Section V5 - Viaduct","16-Feb-2012"," 974,780,000.00 ","IJM Construction Sdn Bhd"  ],
     ["","Construction and completion of viaduct guideway and other associated works from Maluri portal to Plaza Phoenix station","","",""  ],
     ["28","Elevated Section V6 - Viaduct","16-Feb-2012"," 764,907,527.28 ","Ahmad Zaki Sdn Bhd"  ],
     ["","Construction and completion of viaduct guideway and other associated works from Plaza Phoenix station to Bandar Tun Hussein Onn Station","","",""  ],
     ["29","Elevated Section V7 - Viaduct","18-May-2012"," 499,980,104.78 ","MTD Construction Sdn Bhd"  ],
     ["","Construction and completion of viaduct guideway and other associated works from Bandar Tun Hussein Onn to Taman Mesra","","",""  ],
     ["30","Elevated Section V8 - Viaduct","26-Sep-2012"," 951,086,627.26 ","UEM Construction Sdn Bhd"  ],
     ["","Construction and completion of viaduct guideway and other associated works from Taman Mesra to Kajang Station","","",""  ],
     ["31","Package Dpt1: Construction And Completion Of Sungai Buloh Maintenance Depot, Administration Building, External Works And Other Associated Works","18-May-2012"," 458,980,000.00 ","Trans Resources Corporation Sdn Bhd"  ],
     ["32","Package Dpt2: Construction And Completion Of Kajang Maintenance Depot, External Works And Other Associated Works","24-Jul-2012"," 212,808,808.00 ","TSR Bina Sdn Bhd"  ],
     ["33","Underground works (Tunnel stations & associated structures) between Semantan north portal and Maluri south portal","30-Mar-12"," 8,280,000,000.00 ","MMC Gamuda KVMRT (T) Sdn Bhd"  ],
     ["34","Package MSPR1 : Construction And Completion Of Multi Storey Car Park Building, External Works And Other Associated Works At Sungai Buloh Station","24-Jul-2012"," 117,109,017.34 ","TSR Bina Sdn Bhd"  ],
     ["35","Package MSPR4 : Construction And Completion Of Multi Storey Car Park Building, External Works And Other Associated Works at Section 16 Station","30-Jun-14"," 83,440,000.00 ","Budaya Restu Sdn Bhd "  ],
     ["36","Package MSPR6 : Construction And Completion Of Multi Storey Car Park Building, External Works And Other Associated Works At Taman Bukit Mewah Station","30-Jun-14"," 128,860,838.60 ","Perkasa Sutera Sdn Bhd "  ],
     ["37","Package MSPR8 : Construction and Completion of Multi Storey Car Oark Building, External Works And Other Associated Works At Taman Koperasi Station ","30-Jun-14"," 115,975,441.93 ","InnoSeven Sdn Bhd "  ],
     ["38","Package MSPR11 : Construction And Completion Of Multi Storey Car Park Building, External Works And Other Associated Works At  Saujana Impian Station","30-Jun-14"," 123,200,000.00 ","RD Resources Sdn Bhd "  ],
     ["39","Package MSPR9 : Construction and Completion of Multi Storey Car Oark Building, External Works And Other Associated Works At Kajang Station ","8-Mar-2013"," 50,701,461.46 ","SMPP-IBWANI Joint Venture"  ],
     ["40","Systems Work Package SBK-S-01 : Engineering, Procurement, Construction, Testing & Commissioning Of Electric Trains","28-Sep-12"," 1,365,083,284.00 ","Consortium of Siemens Malaysia Sdn Bhd, Siemens AG and SMH Rail Sdn Bhd"  ],
     ["41","Systems Work Package SBK-S-02 : Engineering, Procurement, Construction, Testing & Commissioning Of Depot Equipment & Maintenance Vehicles","28-Sep-12"," 418,813,910.00 ","Consortium of Siemens Malaysia Sdn Bhd, Siemens AG, Hisniaga Sdn Bhd"  ],
     ["42","Systems Work Package SBK-S-03 : Engineering, Procurement, Construction, Testing & Commissioning Of Signalling & Train Control System","28-Sep-12"," 281,314,655.00 ","Bombardier (Malaysia) Sdn Bhd"  ],
     ["43","System Works Package SBK-S-04 : Engineering, Procurement, Construction, Testing & Commissioning Of Platform Screen Doors and Automatic Platform Gates","31-Jan-13"," 78,089,833.00 ","Singapore Technologies Electronics Limited"  ],
     // ["System Group 2","","","",""  ],
     ["44","Systems Work Package SBK-S-05 : Engineering, Procurement, Construction, Testing & Commissioning Of Power Supply & Distribution System","28-Sep-12"," 459,248,224.71 ","Meidensha Corporation"  ],
     ["45","Systems Work Package SBK-S-06 : Engineering, Procurement, Construction, Testing & Commissioning Of Track Works","31-Oct-12"," 855,000,000.00 ","Mitsubishi Heavy Industries, Ltd"  ],
     // ["System Group 3","","","",""  ],
     ["46","Systems Works Package -SBK-S-07: Engineering, Procurement, Construction, Testing & Commissioning Of Telecommunication System","31-Oct-12"," 319,941,634.00 ","Apex Communication Sdn Bhd - LG CNS Consortium"  ],
     ["47","System Works Package SBK-S-08 : Engineering, Procurement, Construction, Testing & Commissioning Of Facility Scada ","11-Aug-12"," 23,241,459.00 ","A.F.S. Engineering (M) Sdn Bhd - ST Electronics Ltd"  ],
     ["48","System Works Package SBK-S-09 : Engineering, Procurement, Construction, Testing & Commissioning Of Automatic Fare Collection System","22-Nov-12"," 120,763,079.00 ","Affiliated Computer Services Solutions France SAS"  ],
     ["49","System Works Package SBK-S-10 : Engineering, Procurement, Construction, Testing & Commissioning Of Electronic Access Control System ","11-Aug-12"," 41,014,958.79 ","Apex Communication Sdn Bhd - Johnson Controls (M) Sdn Bhd"  ],
     ["50","System Works SBK-S-11 : Engineering, Procurement, Construction, Testing & Commissioning Of Building Management System ","11-Aug-12"," 43,042,826.00 ","Metronic Engineering Sdn Bhd"  ],
     ["51","System Works Package SBK-S-12 : Engineering, Procurement, Construction, Testing & Commissioning Of Government Integrated Radio Network","6-Apr-13"," 4,405,479.55 ","Sapura Research Sdn Bhd"  ],
     ["52","System Works Package SBK-S-13 : Design, Procurement, Configuration, Installation, Testing and Commisioning, Training and Documentation of IT System for All Elevated and Underground Stations And Depots","23-Jul-14"," 44,467,821.00 ","EV-Dynamic Sdn Bhd "  ],
     ["53","Package S1 : Construction And Completion Of Elevated Stations And Other Associated Works At Sungai Buloh, Kg. Baru Sungai Buloh And Kota Damansara ","28-Aug-12"," 283,668,000.00 ","Trans Resources Corporation Sdn Bhd"  ],
     ["54","Package S2 : Construction And Completion Of Elevated Stations And Other Associated Works At Taman Industrial Sungai Buloh, PJU 5 And Dataran Sunway ","10-Mar-12"," 204,661,590.00 ","Naim Engineering Sdn Bhd"  ],
     ["55","Package S3 : Construction And Completion Of Elevated Stations And Other Associated Works At The Curve, One Utama And Tamn Tun Dr Ismail ","10-Mar-12"," 275,773,408.38 ","UEM Construction Sdn Bhd"  ],
     ["56","Package S4 : Construction And Completion Of Elevated Stations And Other Associated Works At Section 16, Bandar Damansara And Semantan ","9-Jul-12"," 208,153,000.00 ","Naim Engineering Sdn Bhd"  ],
     ["57","Package S5 : Construction And Completion Of Elevated Stations And Other Associated Works At Taman Bukit Ria, Taman Bukit Mewah, Leisure Mall And Plaza Phoenix","24-Jul-12"," 228,910,000.00 ","IJM Construction Sdn Bhd"  ],
     ["58","Package S6 : Construction And Completion Of Elevated Stations And Other Associated Works At Taman Suntex, Taman Cuepacs And Bandar Tun Hussein Onn ","28-Aug-12"," 174,644,172.87 ","Ahmad Zaki Sdn Bhd"  ],
     ["59","Package S7 : Construction And Completion Of Elevated Stations And Other Associated Works At Balakong And Taman Koperasi ","28-Aug-12"," 104,749,720.59 ","Apex Communication Sdn Bhd"  ],
     ["60","Package S8 : Construction And Completion Of Elevated Stations And Other Associated Works At Saujana Impian, Bandar Kajang And Kajang ","10-Mar-12"," 251,743,307.09 ","Apex Communication Sdn Bhd"  ],
     ["61","SBG-Northern","2-Feb-12"," 223,180,030.00 ","SPC Industries Sdn Bhd"  ],
     ["","Supply And Delivery Of Segmental Box Girders (Sbg) For Proposed Projek Mass Rapid Transit Lembah Kelang: Jajaran Sg Buloh   Kajang (Northern Section)","","",""  ],
     ["62","SBG-Southern","2-Feb-12"," 185,912,543.60 ","Eastern Pretech (M) Sdn Bhd"  ],
     ["","Supply And Delivery of Segmental Box Girders (Sbg) For Proposed Projek Mass Rapid Transit Lembah Kelang: Jajaran Sungai Buloh-Kajang 9 (Southern Section)","","",""  ],
     ["63","Package V1 to V8 : Design, Supply, Installation, Testing And Commissioning Of Noise Barriers And Enclosures ","18-Mar-13"," 201,989,570.00 ","Muhibbah - SV 0 Samjung JV"  ],
     ["64","Package ECS1 : Supply, Procurement, Installation, Testing And Commissioning Of Environmental Control System For Elevated Package V1 To V4 And Sungai Buloh Depot ","18-Mar-13"," 44,180,000.00 ","Kejuruteraan Astra Sdn Bhd"  ],
     ["65","Package ECS2 : Supply, Procurement, Installation, Testing And Commissioning Of Environmental Control System For Elevated Package V5 To V8 And Kajang Depot","18-Mar-13"," 21,973,970.00 ","FastColl Corporation Sdn Bhd"  ],
     ["66","Package FD1 : Supply, Procurement, Installation, Testing and Commissioning of Fire Detection and Protection System for Elevated Package V1 to V2 and Sungai Buloh Depot","4-Jun-13"," 31,966,800.00 ","P. J. Indah Sdn Bhd"  ],
     ["67","Package FD2 : Supply, Procurement, Installation, Testing and Commissioning of Fire Detection and Protection System for Elevated Package V3 to V4 ","16-Aug-13"," 14,775,000.00 ","Fitters-MPS Sdn Bhd"  ],
     ["68","Package FD3 : Supply, Procurement, Installation, Testing And Commissioning Of Fire Detection and Protection System For Elevated Package V5 To V5 ","18-Mar-13"," 18,822,000.00 ","Zulfan (M) Sdn Bhd"  ],
     ["69","Package FD4 : Supply, Procurement, Installation, Testing And Commissioning Of Fire Detection And Protection System For Elevated Package V7 To V8 And Kajang Depot ","13-Jun-13"," 22,283,800.00 ","Mecomb Malaysia Sdn Bhd"  ],
     ["70","Package SDDT-E : Supply, Delivery And Supervision Of Installation, Testing And Commissioning Of Step Down Distribution Transformer For All Elevated Packages, Sungai Buloh and Kajang Depot ","18-Mar-13"," 10,065,635.00 ","PTIS Engineering Sdn Bhd"  ],
     ["71","Package UPS-E : Supply, Delivery And Supervision Of Installation, Testing And Commissioning Of Uninterruptible Power Supply For All Elevated Stations ","18-Mar-13"," 6,688,320.00 ","Info-Matic Power Sdn Bhd"  ],
     ["72","Package LS-E : Supply, Installation, Testing And Commissioning Of Lift System For All Elevated Packages, Sungai Buloh Depot And Multi Storey Carparks ","18-Mar-13"," 42,450,000.00 ","Otis Elevator Company (M) Sdn Bhd"  ],
     ["73","Package ETS-E : Supply, Procurement, Installation, Testing And Commissioning Of Escalator And Travelator System For All Elevated Stations ","31-Jan-13"," 102,670,000.00 ","MS Elevators Engineering Sdn Bhd"  ],
     ["74","Package LED-E : Design, Supply, Procurement, Installation, Testing And Commissioning Of LED Lighting and Lighting Boom Equipment For All Elevated Packages ","20-Dec-13"," 39,900,812.00 ","Norangkasa Enterprise Sdn Bhd"  ],
     ["75","Package ETS-U : Design, Supply, Installation, Testing and Commisioning of Escalator and Travelator System for All Underground Stations","01-Jul-14"," 68,000,000.00 ","Kone Elevator (M) Sdn Bhd "  ],
     ["76","Package LS-U (R1): Supply, Procurement, Installation, Testing and Commisioning of Lift System for All Underground Stations and Ancillary Buildings","25-Jul-14"," 15,300,000.00 ","Eita Elevator  (Malaysia) Sdn Bhd "  ],
     ["77","Package SDDT-U: Supply, Delivery, Installation, Testing and Commisioning of Lift System for All Underground Stations and Ancillary Buildings","26-Jul-14"," 7,418,164.00 ","PTIS Engineering Sdn Bhd "  ]
     ];
     
     bbb = [];
     t = aaa.shift();
     
     while (typeof t != "undefined") {
     if (!isNaN(t[0])) {
     id = t[0];
     loa = t[2];
     sum = t[3].trim();
     contractor = t[4];
     
     if (t[1].indexOf(":") != -1) {
     name = t[1].split(":")[0].trim();
     desc = t[1].split(":")[1].trim();
     } else {
     name = t[1]
     desc = aaa.shift()[1]
     }
     bbb.push([id,name,desc,loa,sum,contractor]);
     }
     t = aaa.shift();
     //if (t[0] == "undefined")
     }
     */