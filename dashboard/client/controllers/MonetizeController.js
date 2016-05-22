app.controller('MonetizeController', function ($scope, $timeout, $http, Service) {

    $scope.Service = Service;
    var self = this;


    self.currentCampaign = {};
    self.campaigns = [];
    self.isEditing = false;

    self.changeStatus = function(){

    }

    self.removeCampaignImage = function(campaign){
        campaign.img = null;
    }
    self.getCurrentImage = function(campaign){
        var i = (campaign.img != null && campaign.img != '')
            ? campaign.img : 'images/no_image.png';
        return i;
    }

    $scope.setCampaignImage = function(img){
        if (img && img[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $timeout(function(){
                    self.currentCampaign.img = e.target.result;
                },0);

            }
            reader.readAsDataURL(img[0]);
        }

    }

    self.editCampaign = function(campaign){
        self.isEditing = true;
        self.currentCampaign = campaign;
    };
    self.newCampaign = function(){
        self.isEditing = true;
        self.currentCampaign = {};
    }

    self.cancelEditCampaign = function(campaign){
        self.isEditing = false
        self.currentCampaign = {};
    };

    self.removeCampaign = function (campaign, showdialog) {
        if(!showdialog || confirm("Are you sure you want to delete this item?")) {
            $http.delete(MONETIZE_URL + '/' + campaign.id)
                .then(function (response) {
                    if(response.data.status) {

                        for (n in self.campaigns) {
                            if (self.campaigns[n].id == campaign.id) {
                                self.campaigns.splice(n, 1);
                                break;
                            }
                        }

                        self.isEditing = false;
                        self.currentCampaign = {};

                    }else{
                        alert(response.data.msg);
                    }

                })
                .catch(function () {
                    if (confirm("An unexpected erro happened. Try Again?")) {
                        self.removeCampaign(campaign, false);
                    }
                });
        }

    }

    self.saveCurrentCampaign = function(campaign){

        if(isValid(campaign.appid) && isValid(campaign.status) ) {
            campaign.platform = parseInt(campaign.platform);
            campaign.category = parseInt(campaign.category);
            if(!campaign.id) {
                $http.post(MONETIZE_URL, campaign)
                    .then(function (response) {
                        if(response.data.status) {
                            self.requestCampaign();
                            self.currentCampaign = {};
                        }else{
                            alert(response.data.msg);
                        }

                    })
                    .catch(function () {
                        if (confirm("An unexpected erro happened. Try Again?")) {
                            self.saveCurrentCampaign(campaign);
                        }
                    });
            }else{
                $http.put(MONETIZE_URL + '/' + campaign.id, campaign)
                    .then(function (response) {
                        if(response.data.status) {
                            self.requestCampaign();
                            self.currentCampaign = {};
                        }else{
                            alert(response.data.msg);
                        }
                    })
                    .catch(function () {
                        if (confirm("An unexpected erro happened. Try Again?")) {
                            self.saveCurrentCampaign(campaign);
                        }
                    });
            }
        }
    };

    self.requestCampaign = function () {
        $http.get(MONETIZE_URL)
            .then(function(response){
                self.campaigns = response.data.list;
                NProgress.done();
            })
            .catch(function(){
                if(confirm("An unexpected erro happened. Try Again?")){
                    self.requestCampaign();
                }
            });
    };

    self.init = function () {
        Service.setView('monetize');
        NProgress.start();
        self.requestCampaign();
    };

});

function isValid(s){
    return s != '' && s != undefined && s != null;
}