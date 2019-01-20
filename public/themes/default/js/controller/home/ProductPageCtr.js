bookApp.controller('ProductPageCtr', ['$scope', '$rootScope', '$q', '$location', '$sce','commonService','$routeParams',
  function ($scope, $rootScope, $q, $location, $sce,commonService,$routeParams) {
    $scope.productId = $routeParams.id;

    $scope.allProduct = [];
    $scope.totalItems = 0;
    $scope.currentPage = 1;
    $scope.pageSize = 10;


    $scope.getAllProduct = function () {
      $rootScope.startUi();
      var params = {};
      params.itemperpage = angular.copy($scope.pageSize);
      params.page = angular.copy($scope.currentPage);
      commonService.requestFunction('getAllProduct', params, function (res) {
        if (res.code === 200) {
          $scope.allProduct = res.data.list;
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
              $scope.getAllProduct();
          } else {
              $scope.currentPage = 1;
          }
      } else {
          $scope.getAllProduct();
      }
    };

    
    if($scope.productId) {
      commonService.requestFunction('getProductById', {id:$scope.productId}, function (res) {
        if (res.code === 200) {
          $scope.productDetail = res.data;
          if(!$scope.productDetail) {
            window.location.href = baseURL+'product';
            

          }
          
        }        
      });
    }

    $scope.$on('$viewContentLoaded', function () {
      $scope.getAllProduct();
      console.log($routeParams.id);
      

    });
  }
]);