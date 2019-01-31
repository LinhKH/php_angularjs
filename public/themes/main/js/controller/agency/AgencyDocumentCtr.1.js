AgencyManagementApp.controller('AgencyDocumentCtr', ['$scope', '$rootScope', 'commonService', '$timeout', '$compile', 'fileUpload','blockUI',
    function ($scope, $rootScope, commonService, $timeout, $compile, fileUpload, blockUI) {
        $rootScope.app.class = '';
        $scope.$on('$viewContentLoaded', function () {
            $scope.clickSearch();
            
        });
        
        $scope.totalItems = 0;
        $scope.currentPage = 1;
        $scope.pageSize = 10;
        
        $scope.listDoc = [];
        $scope.searchInput = {};

        // Get data
        $scope.clickSearch = function() {
            $scope.searchInput.pageSize = angular.copy($scope.pageSize);
            $scope.searchInput.currentPage = angular.copy($scope.currentPage);
            commonService.requestFunction('listAgencyDoc', $scope.searchInput, function (res) {
                if(res.code === 200){
                    $scope.listDoc = res.data['list'];
                    $scope.totalItems = res.data['totalItems'];
                    $scope.currentPage = res.data['currentPage'];
                    $scope.pageSize = res.data['pageSize'];
                }
            });
        };


        
        // Pagination 
        $scope.setPagination = function (page) {
            if (typeof page !== 'undefined' && typeof page.pageSize !== 'undefined' && page.pageSize !== $scope.pageSize) {
                $scope.pageSize = page.pageSize;
                if ($scope.currentPage == 1) {
                    $scope.clickSearch();
                } else {
                    $scope.currentPage = 1;
                }
            } else {
                $scope.clickSearch();
            }
        };
    }
]);
