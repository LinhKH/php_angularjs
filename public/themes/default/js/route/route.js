bookApp.config(['$routeProvider', '$locationProvider',
    function ($routeProvider, $locationProvider) {
        var baseURL = $('base').attr('href');
        $routeProvider.when('/logout', {
            controller: 'LogoutCtrl',
            url: baseURL + 'logout'
        }).when('/', {
            templateUrl: 'themes/default/html/bookstore/index.html',
            controller: 'HomePageCtr',
            module: 'Bookstore',
            url: baseURL,
            title: 'Trang Chủ'
        }).when('/product', {
          templateUrl: 'themes/default/html/bookstore/product.html',
          controller: 'ProductPageCtr',
          module: 'Bookstore',
          url: baseURL,
          title: 'Sản Phẩm'
        })
        .when('/topbrands', {
            templateUrl: 'themes/default/html/bookstore/topbrands.html',
            controller: 'BrandPageCtr',
            module: 'Bookstore',
            url: baseURL,
            title: 'Brand Page'
        })
        .when('/cart', {
            templateUrl: 'themes/default/html/bookstore/cart.html',
            controller: 'CartPageCtr',
            module: 'Bookstore',
            url: baseURL,
            title: 'Giỏ Hàng'
        })
        .when('/contact', {
            templateUrl: 'themes/default/html/bookstore/contact.html',
            controller: 'ContactPageCtr',
            module: 'Bookstore',
            url: baseURL,
            title: 'Liên Hệ'
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

        if (next.controller === 'LogoutCtrl') {
            event.preventDefault();
            location = next.url;
        } 
    });
});