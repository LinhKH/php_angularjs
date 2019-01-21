bookApp.controller('CategoryPageCtr', ['$scope', '$rootScope', '$q', '$location', '$sce','commonService','$routeParams',
  function ($scope, $rootScope, $q, $location, $sce,commonService,$routeParams) {
    $scope.catId = $routeParams.catid;
    $scope.catName = $routeParams.catname;
    $scope.productByCate = [];
    
   // get Product By ID
  if($scope.catId) {
    commonService.requestFunction('getProductByCatId', {catid:$scope.catId}, function (res) {
      if (res.code === 200) {
        $scope.productByCate = res.data;
        if(!$scope.productByCate) {
          window.location.href = baseURL+'product';
        }          
      }        
    });
  }

    $scope.$on('$viewContentLoaded', function () {
      

    });
  }
]);