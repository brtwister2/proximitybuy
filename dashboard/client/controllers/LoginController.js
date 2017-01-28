var app = angular.module('login', []).run()
app.controller('LoginController', function ($scope, $http) {
    var self = this;

    $scope.requestLogin = function(){
        $http.get(LOGIN_URL).then(function(response){
            if(response.data.status){
                localStorage.clear();
                localStorage.setItem("loggedin", true);
                location.href = 'index.html';
            }
        })
    }

});