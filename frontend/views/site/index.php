<!doctype html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="mik.css" />
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
    <script src="./lib/leaflet-providers.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" crossorigin="" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster-src.js" integrity="sha384-N9K+COcUk7tr9O2uHZVp6jl7ueGhWsT+LUKUhd/VpA0svQrQMGArhY8r/u/Pkwih" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/lightgallery@2.4.0/lightgallery.umd.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightgallery@2.4.0/css/lightgallery.css" integrity="sha256-6ZVIl1fex+EsPhcppktzWH9QW3vJaGXiMgYB7I7E8+4=" crossorigin="anonymous">
    <title></title>
</head>

<body>
<div class="click-on-map-overlay"></div>
<div id="notifications"></div>
<div class="root">
    <script>
        $.ajax('http://map.lol/objects/15', {
            success: function (data) {
                let newDiv = document.createElement("span");
                newDiv.innerHTML = "<h1>" + data.title + "</h1>";
                document.getElementById('notifications').append(newDiv);
            }
        });
    </script>
    <div id="please-login-block"></div>
    <div class="add-new-object-popup">

        <div class="add-new-object-popup-inside">
            <div class="add-new-object-popup-overlay">
            </div>
            <div class="add-new-object-popup-content">
            </div>
        </div>
    </div>
    <div class="header">
        <h1 data-tlname="title"></h1>
        <div style="display: flex">
            <div class="add-object-button-wrapper">
                <button class="add-object-button" data-tlname="add-object">

                </button>
            </div>
            &nbsp;&nbsp;
            <div id="login-signup-block"></div>
        </div>
        <div class="please-select-point" data-tlname="please-click-on-map">
        </div>
    </div>
    <div class="map-wrapper">
        <div id="map"></div>
    </div>
</div>
<script src="./archimapa/func.js"></script>
<script src="./main.js"></script>
<script>
    const onPageLoad = async() => {
        await initPage();
        initMap();
        const pleaseLoginPopup = new GenericPopupComponent('#please-login-block', {
            contentComponent: PleaseLoginPopupComponent,
            data: {}
        });
        pleaseLoginPopup.render();
        attachAddNewObjectHandlers(pleaseLoginPopup.show.bind(pleaseLoginPopup));
        const signup = new SignupComponent('#login-signup-block');
        global_data.signupComponent = signup;
        signup.render();
    }

    onPageLoad();
</script>

</body>