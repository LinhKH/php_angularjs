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

    $scope.subTotal = function(){
      $scope.total = 0;
      for(var i = 0; i<$scope.allCart.length; i++) {
        var item = $scope.allCart[i];
        $scope.total = $scope.total + (item.quantity * item.price);
      }
      return $scope.total;
    };
    $scope.grandTotal = function() {
      return Math.round($scope.total + $scope.total*10/100)
    }

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