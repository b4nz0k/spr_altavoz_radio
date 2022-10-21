<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        
        <title><?php echo e(config('app.name', 'Laravel')); ?></title>
        <!-- Scripts -->
        <script src="<?php echo e(asset('js/app.js')); ?>" defer></script>
        <!-- Styles -->
        <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <style>
            .theme--light.v-application {
                background-color: #f5f5f5 !important;
            }
        </style>
    </head>
    <body>
        <?php echo $__env->yieldContent('content'); ?>
    </body>
</html><?php /**PATH C:\wamp64\www\spr\cms-radio\resources\views/layouts/login.blade.php ENDPATH**/ ?>