app.controller('HomeController', function ($scope, Service) {

    $scope.Service = Service;
    var self = this;

    self.init = function () {
        Service.setView('home')
        NProgress.start();
        jQuery.getScript('js/data.js', function(){ NProgress.done();}).fail(function(){
            if(arguments[0].readyState==0){
                //script failed to load
            }else{
                //script loaded but failed to parse
                alert(arguments[2].toString());
            }
        })
    }

});