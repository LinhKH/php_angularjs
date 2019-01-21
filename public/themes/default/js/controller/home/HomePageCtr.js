bookApp.controller('HomePageCtr', ['$scope', '$rootScope', '$q', '$location', '$sce', 'commonService',
  function ($scope, $rootScope, $q, $location, $sce, commonService) {
    
    $scope.featureProduct = [];
    $scope.newProduct = [];

    $scope.totalItems = 0;
    $scope.currentPage = 1;
    $scope.pageSize = 4;

    $scope.totalItems1 = 0;
    $scope.currentPage1 = 1;
    $scope.pageSize1 = 4;

    $scope.getFeatureProduct = function () {
      $rootScope.startUi();
      var params = {};
      params.itemperpage = angular.copy($scope.pageSize);
      params.page = angular.copy($scope.currentPage);
      commonService.requestFunction('getFeatureProduct', params, function (res) {
        if (res.code === 200) {
          $scope.featureProduct = res.data.list;
          $scope.totalItems = res.data.total;
        }
        $rootScope.stopUi();
      });
    };

    $scope.getNewProduct = function () {
      $rootScope.startUi();
      var params = {};
      params.itemperpage = angular.copy($scope.pageSize1);
      params.page = angular.copy($scope.currentPage1);
      commonService.requestFunction('getNewProduct', params, function (res) {
        if (res.code === 200) {
          $scope.newProduct = res.data.list;
          $scope.totalItems1 = res.data.total;
        }
        $rootScope.stopUi();
      });
    };
    
    // Pagination Feature Product
    $scope.setPagination = function (page) {
      if (typeof page !== 'undefined' && typeof page.pageSize !== 'undefined' && page.pageSize !== $scope.pageSize) {
          $scope.pageSize = page.pageSize;
          if ($scope.currentPage == 1) {
              $scope.getFeatureProduct();
          } else {
              $scope.currentPage = 1;
          }
      } else {
          $scope.getFeatureProduct();
      }
    };

     // Pagination New Product
     $scope.setPagination1 = function (page) {
      if (typeof page !== 'undefined' && typeof page.pageSize1 !== 'undefined' && page.pageSize1 !== $scope.pageSize1) {
          $scope.pageSize1 = page.pageSize1;
          if ($scope.currentPage1 == 1) {
              $scope.getNewProduct();
          } else {
              $scope.currentPage1 = 1;
          }
      } else {
          $scope.getNewProduct();
      }
    };
   
    $scope.$on('$viewContentLoaded', function () {
      $scope.getFeatureProduct();
      $scope.getNewProduct();
    });
  }
]);

