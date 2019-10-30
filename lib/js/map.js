if( $('#mapid').length ) {
    var mymap = L.map('mapid').setView([44.8377067, -0.5657672], 15);

    var theKub = L.icon({
        iconUrl: '/img/Ellipse.png',

        iconSize: [25, 25], // size of the icon
        iconAnchor: [20, 20], // point of the icon which will correspond to marker's location
        shadowAnchor: [4, 62],  // the same for the shadow
        popupAnchor: [-10, -30] // point from which the popup should open relative to the iconAnchor
    });
    var marker = L.marker([44.837547, -0.569379], {icon: theKub}, 15).addTo(mymap);
    L.tileLayer('https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}.png', {
        maxZoom: 18,
    }).addTo(mymap);
}

