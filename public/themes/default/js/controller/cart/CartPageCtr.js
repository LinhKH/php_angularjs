bookApp.controller('CartPageCtr', ['$scope', '$rootScope', '$q', '$location', '$sce','commonService',
  function ($scope, $rootScope, $q, $location, $sce, commonService) {
    $scope.allCart = [];

    $scope.currentPage = 1;
    $scope.pageSize = 10;


    $scope.getAllCart = function () {
      $rootScope.startUi();
      var params = {};
      commonService.requestFunction('getAllCart', params, function (res) {
        if (res.code === 200) {
          $scope.allCart = res.data;
        }
        $rootScope.stopUi();
      });
    };

    $scope.updateCart = function(index) {
      commonService.requestFunction('updateCart', {data: $scope.allCart[index]}, function (res) {
        if (res.code === 200) {
          // $scope.allCart = res.data;
        }
        $rootScope.stopUi();
      });
    }

    $scope.$on('$viewContentLoaded', function () {
      $scope.getAllCart();

    });
  }
]);