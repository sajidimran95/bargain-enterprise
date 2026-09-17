<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo e($title); ?></title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #111; }
        .header { text-align: center; margin-bottom: 12px; }
        .company { font-size: 13px; font-weight: bold; }
        .title { font-size: 16px; font-weight: bold; }
        .subtitle { color: #555; margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border-bottom: 1px solid #ddd; padding: 3px 4px; text-align: left; }
        th { background: #eee; font-weight: bold; }
        .num { text-align: right; }
        .group { background: #eef5fb; font-weight: bold; }
        .total td { border-top: 2px solid #333; border-bottom: 2px solid #333; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company"><?php echo e(config('bargain.company_name', config('app.name'))); ?></div>
        <div class="title"><?php echo e($title); ?></div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($subtitle)): ?>
            <div class="subtitle"><?php echo e($subtitle); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php echo $bodyHtml; ?>

</body>
</html>
<?php /**PATH F:\laragon\www\bargain-enterprise\resources\views\pdf\report.blade.php ENDPATH**/ ?>