<!DOCTYPE html>
<html lang="en" ng-app="AgencyManagementApp" ng-controller="AgencyManagementCtrl">
    <head>
        <base href="<?php echo \Uri::base(false); ?>" />
        <title>{{app.title}}</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php echo $partials['head']; ?>
        
    </head>
    <body>
        <div id="wrapper" class="toggled">
            <?php echo $partials['left_menu']; ?>
            <?php echo $partials['top_menu']; ?>
            <div class="mainWrapper" ng-view=""></div>    
        </div>

        <?php echo $partials['modal']; ?>

        <?php echo $partials['script']; ?>
        
    </body>
</html>