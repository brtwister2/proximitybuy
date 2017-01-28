app.controller('BeaconsController', function ($scope, $timeout, $http, Service) {

    $scope.Service = Service;
    var self = this;


    self.currentBeacon = {};
    self.beacons = [];
    self.isEditing = false;

    
    self.editBeacon = function(monetize){
        self.isEditing = true;
        self.currentBeacon = monetize;
    };
    self.newBeacon = function(){
        self.isEditing = true;
        self.currentBeacon = {};
    }

    self.cancelEditBeacon = function(beacon){
        self.isEditing = false
        self.currentBeacon = {};
    };

    self.removeBeacon = function (beacon, showdialog) {
        if(!showdialog || confirm("Are you sure you want to delete this item?")) {
            $http.delete(BEACON_URL + '/' + beacon.id)
                .then(function (response) {
                    if(response.data.status) {

                        for (n in self.beacons) {
                            if (self.beacons[n].id == beacon.id) {
                                self.beacons.splice(n, 1);
                                break;
                            }
                        }

                        self.isEditing = false;
                        self.currentBeacon = {};

                    }else{
                        alert(response.data.msg);
                    }

                })
                .catch(function () {
                    if (confirm("An unexpected erro happened. Try Again?")) {
                        self.removeBeacon(beacon, false);
                    }
                });
        }

    }

    self.savecurrentBeacon = function(beacon){

        if(isValid(beacon.bid) && isValid(beacon.description) ) {

            if(!beacon.id) {
                $http.post(BEACON_URL, beacon)
                    .then(function (response) {
                        if(response.data.status) {
                            self.requestBeacon();
                            self.currentBeacon = {};
                        }else{
                            alert(response.data.msg);
                        }

                    })
                    .catch(function () {
                        if (confirm("An unexpected erro happened. Try Again?")) {
                            self.savecurrentBeacon(beacon);
                        }
                    });
            }else{
                $http.put(BEACON_URL + '/' + beacon.id, beacon)
                    .then(function (response) {
                        if(response.data.status) {
                            self.requestBeacon();
                            self.currentBeacon = {};
                        }else{
                            alert(response.data.msg);
                        }
                    })
                    .catch(function () {
                        if (confirm("An unexpected erro happened. Try Again?")) {
                            self.savecurrentBeacon(beacon);
                        }
                    });
            }
        }
    };

    self.requestBeacon = function () {
        $http.get(BEACON_URL)
            .then(function(response){
                self.beacons = response.data.objects;
                NProgress.done();
            })
            .catch(function(){
                if(confirm("An unexpected erro happened. Try Again?")){
                    self.requestBeacon();
                }
            });
    };

    self.init = function () {
        Service.setView('beacon');
        NProgress.start();
        self.requestBeacon();
    };

});

function isValid(s){
    return s != '' && s != undefined && s != null;
}