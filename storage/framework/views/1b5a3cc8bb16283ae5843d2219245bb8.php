<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('title', 'Print'); ?></title>
    <style>
        :root {
            --page-width: 8.5in;
            --page-min-height: 11in;
            --page-pad: 0.6in;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: #e8e8e8;
            font-family: "Segoe UI", Arial, Helvetica, sans-serif;
            color: #111;
        }
        .be-print-toolbar {
            position: sticky;
            top: 0;
            z-index: 20;
            display: flex;
            gap: 8px;
            align-items: center;
            padding: 10px 16px;
            background: #2f4050;
            color: #fff;
            box-shadow: 0 1px 4px rgba(0,0,0,.2);
        }
        .be-print-toolbar__title {
            margin-right: auto;
            font-size: 13px;
            font-weight: 600;
        }
        .be-print-toolbar button,
        .be-print-toolbar a {
            appearance: none;
            border: 1px solid #d0d7de;
            background: #fff;
            color: #111;
            border-radius: 4px;
            padding: 6px 12px;
            font-size: 12px;
            cursor: pointer;
            text-decoration: none;
        }
        .be-print-toolbar button.primary {
            background: #0b6bcb;
            border-color: #0b6bcb;
            color: #fff;
        }
        .be-print-sheet {
            width: var(--page-width);
            min-height: var(--page-min-height);
            margin: 18px auto;
            padding: var(--page-pad);
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,.18);
        }
        .be-print-doc h1 { font-size: 20px; margin: 0 0 4px; }
        .be-print-doc .muted { color: #555; font-size: 11px; margin-bottom: 10px; }
        .be-print-doc p { margin: 0 0 10px; font-size: 12px; line-height: 1.4; }
        .be-print-doc .party { margin: 12px 0; font-size: 12px; line-height: 1.4; }
        .be-print-doc table { width: 100%; border-collapse: collapse; margin-top: 14px; font-size: 12px; }
        .be-print-doc th, .be-print-doc td { border: 1px solid #bbb; padding: 5px 7px; text-align: left; vertical-align: top; }
        .be-print-doc th { background: #f0f0f0; }
        .be-print-doc .num { text-align: right; white-space: nowrap; }
        .be-print-doc .totals { width: 260px; margin-left: auto; margin-top: 14px; }
        .be-print-doc .totals td { border: none; padding: 3px 4px; }
        @page {
            size: letter;
            margin: 0.5in;
        }
        @media print {
            body { background: #fff; }
            .be-print-toolbar { display: none !important; }
            .be-print-sheet {
                width: auto;
                min-height: auto;
                margin: 0;
                padding: 0;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="be-print-toolbar">
        <div class="be-print-toolbar__title"><?php echo $__env->yieldContent('toolbar', 'Print Preview — Letter'); ?></div>
        <button type="button" class="primary" onclick="window.print()">Print</button>
        <button type="button" onclick="window.close()">Close</button>
    </div>

    <div class="be-print-sheet be-print-doc">
        <?php echo $__env->yieldContent('content'); ?>
    </div>

    <script>
        window.addEventListener('load', function () {
            const params = new URLSearchParams(window.location.search);
            if (params.get('autoprint') === '0') {
                return;
            }
            setTimeout(function () { window.print(); }, 250);
        });
    </script>
</body>
</html>
<?php /**PATH F:\laragon\www\bargain-enterprise\resources\views/layouts/print.blade.php ENDPATH**/ ?>