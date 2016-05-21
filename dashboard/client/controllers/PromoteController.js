app.controller('PromoteController', function ($scope, $timeout, $http, Service) {

    $scope.Service = Service;
    var self = this;

    self.campaigns = [];

    self.changeStatus = function(){

    }
    
    self.requestCampaign = function () {
        $http.get('mocks/campaign.json')
            .then(function(response){
                console.log(response);
                self.campaigns = response.data;
                NProgress.done();
            })
            .catch(function(){
                if(confirm("An unexpected erro happened. Try Again?")){
                    self.requestCampaign();
                }
            });
    }
    self.init = function () {
        Service.setView('promote');
        NProgress.start();
        self.requestCampaign();
    }

});