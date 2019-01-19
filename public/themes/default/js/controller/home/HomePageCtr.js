bookApp.controller('HomePageCtr', ['$scope', '$rootScope', '$q', '$location', '$sce', 'commonService',
  function ($scope, $rootScope, $q, $location, $sce, commonService) {
    
    $scope.totalItems = 0;
    $scope.currentPage = 1;
    $scope.pageSize = 10;

    $scope.searchInput = {};
    $scope.des = "Lorem ipsum dolor sit amet, consectetur adipisicing elit 1";
    $scope.insertCommentOfOrderMng = function () {
      console.log('insertCommentOfOrderMng');
      
     
    };

    $scope.$on('$viewContentLoaded', function () {
      

    });
  }
]);