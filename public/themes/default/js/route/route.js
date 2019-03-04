bookApp.config(['$routeProvider', '$locationProvider',
    function ($routeProvider, $locationProvider) {
        //colorCustomizations
        var baseURL = $('base').attr('href');
        $routeProvider.when('/logout', {
            controller: 'LogoutCtrl',
            url: baseURL + 'logout'
        }).when('/', {
            templateUrl: 'themes/default/html/scart/index.html',
            controller: 'HomePageCtr',
            module: 'Scart',
            url: baseURL,
            title: 'Demo S-cart: Free open source - eCommerce Platform for Business',
            breadcrumbs: 'Home',
        })
        .when('/products', {
          templateUrl: 'themes/default/html/scart/products.html',
          controller: 'ProductPageCtr',
          module: 'Scart',
          url: baseURL,
          title: 'All products',
          breadcrumbs: 'All products',
        })        
        .when('/news', {
          templateUrl: 'themes/default/html/scart/news.html',
          controller: 'NewsPageCtr',
          module: 'Scart',
          url: baseURL,
          title: 'Blog',
          breadcrumbs: 'Blog'
        })        
        .when('/about', {
          templateUrl: 'themes/default/html/scart/about.html',
          controller: 'AboutPageCtr',
          module: 'Scart',
          url: baseURL,
          title: 'About',
          breadcrumbs: 'About'
        })        
        .when('/contact', {
          templateUrl: 'themes/default/html/scart/contact.html',
          controller: 'ContactPageCtr',
          module: 'Scart',
          url: baseURL,
          title: 'Contact',
          breadcrumbs: 'Contact'
        })        
        .otherwise({
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
        $rootScope.app.breadcrumbs = next.breadcrumbs;

        if (next.controller === 'LogoutCtrl') {
            event.preventDefault();
            location = next.url;
        } 
    });
});
