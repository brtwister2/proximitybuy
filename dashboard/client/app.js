//Sorry about that
if(localStorage.getItem("loggedin") !== 'true'){
    location.href = 'login.html';
}

var app = angular.module('dashboard', ['ngRoute','ngAnimate', 'ui.utils.masks']).run()
.config(function ($routeProvider, $locationProvider) {
    $routeProvider
        .when('/home', {
            templateUrl: 'views/home.html',
            controller: "HomeController as ctrl"
        })
        .when('/monetize', {
            templateUrl: 'views/monetize.html',
            controller: "MonetizeController as ctrl"
        })
        .when('/promote', {
            templateUrl: 'views/promote.html',
            controller: "PromoteController as ctrl"
        })
        .when('/beacon', {
            templateUrl: 'views/beacons.html',
            controller: "BeaconsController as ctrl"
        })
        .otherwise('/home');

});

app.factory("Service", function () {
    var view = 'home';

    function getView() {
        return view;
    }
    function setView(nview) {
        view = nview;
    }
    return {
        getView: getView,
        setView: setView,
    }
})

app.directive('formatCurrency', function($filter) {
    return {
        require: 'ngModel',
        scope: {
            format: "="
        },
        link: function(scope, element, attrs, ngModelController) {
            ngModelController.$parsers.push(function(data) {
                return $filter('currency')(data, scope.format)
            });

            ngModelController.$formatters.push(function(data) {
                return $filter('currency')(data, scope.format)
            });
        }
    }
});
