bookApp.controller('ContactPageCtr', ['$scope', '$rootScope', '$q', '$location', '$sce',
  function ($scope, $rootScope, $q, $location, $sce) {
    
    $scope.totalItems = 0;
    $scope.currentPage = 1;
    $scope.pageSize = 10;

    $scope.searchInput = {};



    $scope.$on('$viewContentLoaded', function () {
      console.log('ContactPageCtr');

    });
  }
]);