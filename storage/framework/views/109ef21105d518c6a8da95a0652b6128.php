<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice <?php echo e($invoice->invoice_number); ?></title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111; }
        h1 { font-size: 18px; margin: 0 0 4px; }
        .muted { color: #555; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ccc; padding: 4px 6px; text-align: left; }
        th { background: #eee; }
        .num { text-align: right; }
        .totals { width: 240px; margin-left: auto; margin-top: 12px; }
        .totals td { border: none; padding: 2px 4px; }
        .billto { margin-top: 10px; }
    </style>
</head>
<body>
    <h1><?php echo e(config('bargain.company_name', config('app.name'))); ?></h1>
    <div class="muted">Invoice</div>
    <p>
        <strong>Invoice #:</strong> <?php echo e($invoice->invoice_number); ?><br>
        <strong>Date:</strong> <?php echo e($invoice->invoice_date?->format('m/d/Y')); ?><br>
        <strong>Due:</strong> <?php echo e($invoice->due_date?->format('m/d/Y') ?: '—'); ?>

    </p>

    <div class="billto">
        <strong>Bill To</strong><br>
        <?php echo e($invoice->customer?->display_name); ?><br>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $invoice->customer?->billToLines() ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo e($line); ?><br>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Description</th>
                <th class="num">Qty</th>
                <th class="num">Rate</th>
                <th class="num">Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $invoice->lines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($line->item?->sku); ?></td>
                    <td><?php echo e($line->description); ?></td>
                    <td class="num"><?php echo e(number_format((float) $line->quantity, 2)); ?></td>
                    <td class="num"><?php echo e(number_format((float) $line->rate, 2)); ?></td>
                    <td class="num"><?php echo e(number_format((float) $line->amount, 2)); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>

    <table class="totals">
        <tr><td>Subtotal</td><td class="num"><?php echo e(number_format((float) $invoice->subtotal, 2)); ?></td></tr>
        <tr><td>Tax</td><td class="num"><?php echo e(number_format((float) $invoice->tax_total, 2)); ?></td></tr>
        <tr><td><strong>Total</strong></td><td class="num"><strong><?php echo e(number_format((float) $invoice->total, 2)); ?></strong></td></tr>
        <tr><td>Balance Due</td><td class="num"><?php echo e(number_format((float) $invoice->balance_due, 2)); ?></td></tr>
    </table>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoice->memo): ?>
        <p><strong>Memo:</strong> <?php echo e($invoice->memo); ?></p>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</body>
</html>
<?php /**PATH F:\laragon\www\bargain-enterprise\resources\views/pdf/invoice.blade.php ENDPATH**/ ?>