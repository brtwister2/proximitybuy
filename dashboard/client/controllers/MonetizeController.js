app.controller('MonetizeController', function ($scope, $timeout, $http, Service) {

    $scope.Service = Service;
    var self = this;


    self.currentMonetize = {};
    self.monetizes = [];
    self.isEditing = false;

    self.changeStatus = function(){

    }

    self.getCurrentIcon = function(monetize){
        var i = (monetize.icon != null && monetize.icon != '')
            ? monetize.icon : 'images/no_image.png';
        return i;
    }
    
    self.editMonetize = function(monetize){
        self.isEditing = true;
        self.currentMonetize = monetize;
    };
    self.newMonetize = function(){
        self.isEditing = true;
        self.currentMonetize = {};
    }

    self.cancelEditMonetize = function(monetize){
        self.isEditing = false
        self.currentMonetize = {};
    };

    self.removeMonetize = function (monetize, showdialog) {
        if(!showdialog || confirm("Are you sure you want to delete this item?")) {
            $http.delete(MONETIZE_URL + '/' + monetize.id)
                .then(function (response) {
                    if(response.data.status) {

                        for (n in self.monetizes) {
                            if (self.monetizes[n].id == monetize.id) {
                                self.monetizes.splice(n, 1);
                                break;
                            }
                        }

                        self.isEditing = false;
                        self.currentMonetize = {};

                    }else{
                        alert(response.data.msg);
                    }

                })
                .catch(function () {
                    if (confirm("An unexpected erro happened. Try Again?")) {
                        self.removeMonetize(monetize, false);
                    }
                });
        }

    }

    self.savecurrentMonetize = function(monetize){

        if(isValid(monetize.appid) && isValid(monetize.status) ) {
            monetize.platform = parseInt(monetize.platform);
            monetize.category = parseInt(monetize.category);
            if(!monetize.id) {
                $http.post(MONETIZE_URL, monetize)
                    .then(function (response) {
                        if(response.data.status) {
                            self.requestMonetize();
                            self.currentMonetize = {};
                        }else{
                            alert(response.data.msg);
                        }

                    })
                    .catch(function () {
                        if (confirm("An unexpected erro happened. Try Again?")) {
                            self.savecurrentMonetize(monetize);
                        }
                    });
            }else{
                $http.put(MONETIZE_URL + '/' + monetize.id, monetize)
                    .then(function (response) {
                        if(response.data.status) {
                            self.requestMonetize();
                            self.currentMonetize = {};
                        }else{
                            alert(response.data.msg);
                        }
                    })
                    .catch(function () {
                        if (confirm("An unexpected erro happened. Try Again?")) {
                            self.savecurrentMonetize(monetize);
                        }
                    });
            }
        }
    };

    self.requestMonetize = function () {
        $http.get(MONETIZE_URL)
            .then(function(response){
                self.monetizes = response.data.list;
                NProgress.done();
            })
            .catch(function(){
                if(confirm("An unexpected erro happened. Try Again?")){
                    self.requestMonetize();
                }
            });
    };

    self.init = function () {
        Service.setView('monetize');
        NProgress.start();
        self.requestMonetize();
    };

});

function isValid(s){
    return s != '' && s != undefined && s != null;
}