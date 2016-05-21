app.controller('MainController', function ($scope, Service) {

    $scope.Service = Service;
    var self = this;

    self.init = function () {
        Service.setView('home');
    }

});