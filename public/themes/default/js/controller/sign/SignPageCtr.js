bookApp.controller('SignPageCtr', ['$scope', '$rootScope', '$q', '$location', '$sce','commonService',
  function ($scope, $rootScope, $q, $location, $sce, commonService) {
    $scope.allCart = [];

    $scope.currentPage = 1;
    $scope.pageSize = 10;
    

    $scope.$on('$viewContentLoaded', function () {

    });
  }
]);