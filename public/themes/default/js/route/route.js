bookApp.config(['$routeProvider', '$locationProvider',
    function ($routeProvider, $locationProvider) {
        //colorCustomizations
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
        .when('/product/detail/:id', {
            templateUrl: 'themes/default/html/bookstore/detail.html',
            controller: 'ProductPageCtr',
            module: 'Bookstore',
            url: baseURL,
            title: 'Chi Tiết Sản Phẩm'
        })
        .when('/product-by-category/:catname/:catid', {
            templateUrl: 'themes/default/html/bookstore/productbycat.html',
            controller: 'CategoryPageCtr',
            module: 'Bookstore',
            url: baseURL,
            title: 'Sản Phẩm Theo Danh Mục'
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
        .when('/signin', {
            templateUrl: 'themes/default/html/bookstore/signin.html',
            controller: 'SignPageCtr',
            module: 'Bookstore',
            url: baseURL,
            title: 'Đăng Ký/Đăng Nhập'
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
