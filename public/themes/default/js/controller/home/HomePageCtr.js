bookApp.controller('HomePageCtr', ['$scope', '$rootScope', '$q', '$location', '$sce', 'commonService',
  function ($scope, $rootScope, $q, $location, $sce, commonService) {
    $scope.featureProduct = [];
    $scope.totalItems = 0;
    $scope.currentPage = 1;
    $scope.pageSize = 4;

    $scope.tblProduct = { current: {} };

    $scope.searchInput = {};
    $scope.des = "Lorem ipsum dolor sit amet, consectetur adipisicing elit 1";

    $scope.insertCommentOfOrderMng = function () {
      console.log('insertCommentOfOrderMng');
      
    };

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
    
    // Pagination 
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

    $scope.$on('$viewContentLoaded', function () {
      $scope.getFeatureProduct();
      

    });
  }
]);