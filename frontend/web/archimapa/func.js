var site_specific_funcs;

(function() {

    var buildingIcon = L.icon({
        iconUrl: '/archimapa/building.png',
        iconSize: [30, 30],
        iconAnchor: [10, 10],
        //popupAnchor: [5, 10],
        //shadowSize: [30, 95],
        //shadowAnchor: [22, 94]
    });
    var industryIcon = L.icon({
        iconUrl: '/archimapa/factory.png',
        iconSize: [30, 30],
        iconAnchor: [10, 10],
        //popupAnchor: [5, 10],
        //shadowSize: [30, 95],
        //shadowAnchor: [22, 94]
    });


    site_specific_funcs = {
        getMarkerIcon: (obj_data) => {
            //console.log('.........../././', obj_data);
            return obj_data.fields.building_type == 1 ? buildingIcon : industryIcon;
        }
    }
})();