AgencyManagementApp.config(['$routeProvider', '$locationProvider',
    function ($routeProvider, $locationProvider) {
        var baseURL = $('base').attr('href');
        $routeProvider.when('/logout', {
            controller: 'LogoutCtrl',
            url: baseURL + 'logout'
        }).when('/', {
            templateUrl: 'themes/main/html/management/index.html',
            controller: 'AgencyManagementCtrl',
            module: 'Management',
            url: baseURL,
            title: '案件一覧'
        }).when('/management', {
            templateUrl: 'themes/main/html/management/ichiran.html',
            controller: 'AgencyIchiranCtr',
            module: 'Management',
            url: baseURL,
            title: '案件一覧'
        })
        .when('/management/detail/:id', {
            templateUrl: 'themes/main/html/management/detail.html',
            controller: 'AgencyDetailCtr',
            module: 'Management',
            url: baseURL,
            title: '案件詳細'
        }).when('/management/document', {
            templateUrl: 'themes/main/html/management/document.html',
            controller: 'AgencyDocumentCtr',
            module: 'Management',
            url: baseURL,
            title: '案件一覧'
        }).when('/master', {
            redirectTo: '/master/common'
        }).when('/master/common', {
            templateUrl: 'themes/main/html/master/base.html',
            controller: 'MasterCommonCtrl',
            module: 'Master',
            url: baseURL,
            title: '案件一覧'
        }).when('/master/agency', {
            templateUrl: 'themes/main/html/master/base.html',
            controller: 'MasterAgencyCtrl',
            module: 'Master',
            url: baseURL,
            title: '案件一覧'
        }).when('/master/employee', {
            templateUrl: 'themes/main/html/master/base.html',
            controller: 'MasterEmployeeCtrl',
            module: 'Master',
            url: baseURL,
            title: '案件一覧'
        }).when('/master/oamachine', {
            templateUrl: 'themes/main/html/master/base.html',
            controller: 'MasterOamachineCtrl',
            module: 'Master',
            url: baseURL,
            title: '案件一覧'
        }).when('/master/mail', {
            templateUrl: 'themes/main/html/master/base.html',
            controller: 'MasterMailCtrl',
            module: 'Master',
            url: baseURL,
            title: '案件一覧'
        }).when('/master/document', {
            templateUrl: 'themes/main/html/master/base.html',
            controller: 'MasterDocumentCtrl',
            module: 'Master',
            url: baseURL,
            title: '案件一覧'
        }).otherwise({
            redirectTo: '/'
        });
        $locationProvider.html5Mode(true);
    }
]).run(function ($rootScope) {
    $rootScope.$on('$routeChangeStart', function (event, next, current) {
        
        $rootScope.app.module = next.module;
        $rootScope.app.controller = next.controller;
        $rootScope.app.url = next.url;
        $rootScope.app.title = next.title;

        if (next.controller === 'LogoutCtrl') {
            event.preventDefault();
            location = next.url;
        } 
    });
});
