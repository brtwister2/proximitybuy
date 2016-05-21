app.controller('MonetizeController', function ($scope, $timeout, Service) {

    $scope.Service = Service;
    var self = this;

    self.init = function () {
        Service.setView('monetize');
        NProgress.start();
        $timeout(NProgress.done, 10);
    }

});