bookApp.controller('AboutPageCtr', ['$scope', '$rootScope', '$q', '$location', '$sce','commonService',
  function ($scope, $rootScope, $q, $location, $sce, commonService) {

    $scope.contact = {};

    $scope.sendMailContact = function() {
      commonService.requestFunction('sendMailContact', {data: angular.copy($scope.contact)}, function (res) {
            if (res.code === 200) {
                commonService.showAlert(res.message);
            }
        });       
    }

    $scope.$on('$viewContentLoaded', function () {
      console.log('AboutPageCtr');

    });
  }
]);