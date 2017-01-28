app.controller('MainController', function ($scope, Service) {
    $scope.Service = Service;
    var self = this;

    self.init = function () {
        Service.setView('home');
    }

    $scope.logout = function(){
        localStorage.clear();
        location.href = 'login.html';
    }

});