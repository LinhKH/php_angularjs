AgencyManagementApp.controller('DashboardCtrl', ['$scope',
    function ($scope) {
        $scope.$parent.app.class = '';
        $scope.$on('$viewContentLoaded', function () {
            init_dashboard();
        });
    }
]);
