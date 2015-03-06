var snsCtrl = function ($scope, $resource) {
    $scope.getFacebookCount = function($url) {
        var FacebookCount = $resource('/api/facebook.php?url=:url');
        FacebookCount.get({ url: $url }, function(data) {
            $scope.fbCount = {
                url: data.url,
                count: data.count
            };
        });
    };
    $scope.getTwitterCount = function($url) {
        var twitterCount = $resource('/api/twitter.php?url=:url');
        twitterCount.get({ url: $url }, function(data) {
            $scope.twCount = {
                url: data.url,
                count: data.count
            };
        });
    };
    $scope.getHatebuCount = function($url) {
        var hatebuCount = $resource('/api/hatebu.php?url=:url');
        hatebuCount.get({ url: $url }, function(data) {
            $scope.hbCount = {
                url: data.url,
                count: data.count
            };
        });
    };
};