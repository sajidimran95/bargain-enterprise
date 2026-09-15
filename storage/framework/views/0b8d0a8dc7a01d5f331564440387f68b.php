<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('bargain.company_name')); ?> — Sign In</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

</head>
<body class="be-body" style="overflow:auto">
    <div class="flex min-h-screen flex-col items-center justify-center px-4" style="background:#e8e8e8">
        <div class="mb-4 text-center">
            <div class="text-[11px] font-semibold uppercase tracking-wide text-white px-3 py-1" style="background:#1e3a5f">
                <?php echo e(config('bargain.company_name')); ?>

            </div>
            <p class="mt-2 text-[13px] font-semibold text-gray-800"><?php echo e(config('bargain.product_name')); ?></p>
            <p class="text-[11px] text-gray-500">Wholesale Distribution POS</p>
        </div>

        <div class="w-full max-w-md border bg-white" style="border-color:#c8c8c8">
            <div class="border-b px-3 py-2 text-[12px] font-semibold" style="border-color:#c8c8c8;background:#eee">
                Sign In
            </div>
            <div class="px-4 py-4">
                <?php echo e($slot); ?>

            </div>
        </div>
    </div>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

</body>
</html>
<?php /**PATH F:\laragon\www\bargain-enterprise\resources\views/layouts/guest.blade.php ENDPATH**/ ?>