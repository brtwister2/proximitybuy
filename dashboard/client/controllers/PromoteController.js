app.controller('PromoteController', function ($scope, $timeout, $http, Service) {

    $scope.Service = Service;
    var self = this;


    self.currentCampaign = {};
    self.campaigns = [];
    self.beacons = [];
    self.isEditing = false;

    self.changeStatus = function(){

    }

    self.removeCampaignImage = function(campaign){
        campaign.img = null;
    };

    self.ViewCampaignImage = function(campaign){
        window.open(campaign.img,'target=_blank');
    };

    self.getCurrentImage = function(campaign){
        var i = (campaign.img != null && campaign.img != '')
            ? campaign.img : 'images/no_image.png';
        return i;
    };

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

    };

    self.isNoEndDate = function(campaign){
      return (!campaign.enddate || campaign.enddate == null || campaign.enddate == "0000-00-00 00:00:00");
    };

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
            $http.delete(CAMPAIGN_URL + '/' + campaign.id)
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

        if(!isValid(campaign.beaconid)) {  alert('Please select a valid beacon'); return; }

        if(isValid(campaign.name)  && isValid(campaign.description) &&
            isValid(campaign.status) ) {

            if(campaign.img != null && campaign.img != '' && campaign.img.indexOf('base64,') > -1) {
                campaign.img = campaign.img.substr(campaign.img.indexOf('base64,') + 7, campaign.img.length - 1);
            }

            if(!campaign.id) {
                $http.post(CAMPAIGN_URL, campaign)
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
                $http.put(CAMPAIGN_URL + '/' + campaign.id, campaign)
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

    self.requestCampaign = function () {
        $http.get(CAMPAIGN_URL)
            .then(function(response){
                self.campaigns = response.data.campaigns;
                NProgress.done();
            })
            .catch(function(){
                if(confirm("An unexpected erro happened. Try Again?")){
                    self.requestCampaign();
                }
            });
    };

    self.init = function () {
        Service.setView('promote');
        NProgress.start();
        self.requestCampaign();
        self.requestBeacon();
        document.getElementsByClassName('img-upload')[0]
            .addEventListener('click', function(){
                document.getElementById('imgupload').click();
            })
    };

});

function isValid(s){
    return s != '' && s != undefined && s != null;
}