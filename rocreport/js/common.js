$(function(){

    var map;
    var citymap = {};
    function initialize(){
        $("#reports_location").children().each(function(){
            var loc =  $(this).find("> span").text()
            var id = $(this).find("> span").attr('id');
            var lat = loc.substring(0,loc.indexOf(","));
            var lng = loc.substring(loc.indexOf(",")+1,loc.length);
            citymap[id+""] = { center: new google.maps.LatLng(lat,lng) };
        });
        console.log(citymap.size);

        var mapOptions = {
            zoom: 13,
            zoomControl: true,
            center: new google.maps.LatLng(43.0824944,-77.6708276)
        };

        this.map = new google.maps.Map(document.getElementById('map-canvas'),
            mapOptions);

        for (var city in citymap) {
            var populationOptions = {
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 1,
                fillColor: '#FF0000',
                fillOpacity: 0.35,
                map: this.map,
                center: citymap[city].center,
                radius: 200
            };
            // Add the circle for this city to the map.
            cityCircle = new google.maps.Circle(populationOptions);
        }
    }

    google.maps.event.addDomListener(window, 'load', initialize);




    function loadingMap(loc){
        initialize();
//        var zoom = 14;

        if(loc=="zoom_out"){
            return;
        }

        var lag = loc.substring(0,loc.indexOf(","));
        var lng = loc.substring(loc.indexOf(",")+1,loc.length);

//        this.map.setZoom(zoom);
        this.map.setCenter( new google.maps.LatLng(lag,lng));

        var populationOptions = {
            strokeColor: '#0000FF',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#FF0000',
            fillOpacity: 0.25,
            map: this.map,
            center:new google.maps.LatLng(lag,lng),
            radius: 200
        };
        cityCircle = new google.maps.Circle(populationOptions);
    }




    $("#reports_location").children().each(function(){
        var location =  $(this).find("> span").text();
            var id =  $(this).find("> span").attr("id");
            $(this).click(function(){
                loadingMap(location);
                deselectAllButThis(this);
                $.post("/rocreport/listupdates",{report_id:id},function(data){
                    console.log(data);
                    $("#item").html(data);
                    $("#details-item-wrapper").show(); //just to show the stuff
                    $("#details-item").show();                                        
                });                
            });
    });

    function deselectAllButThis(elem){
        $("#reports_location").children().each(function(){
            $(this).removeClass('active');
        });
        $(elem).addClass('active');
    }

    $("#details-item-wrapper").click(function(){
        $(this).hide();
        $("#details-item").hide();
    });

    $(".vote_up_button").on("click", function(){
        var id = $(this).attr("id");
        alert(id);
    })


});
