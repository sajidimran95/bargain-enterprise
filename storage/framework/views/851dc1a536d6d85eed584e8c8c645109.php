<?php
    $isCredit = $bill->isCreditDocument();
    $isRtv = str_starts_with((string) $bill->bill_number, 'RTV-');
    $docLabel = $isRtv ? 'Return to Vendor (RTV)' : ($isCredit ? 'Vendor Credit' : 'Vendor Bill');
?>

<?php $__env->startSection('title', $docLabel.' '.$bill->bill_number); ?>
<?php $__env->startSection('toolbar', $docLabel.' '.$bill->bill_number.' — Letter Print'); ?>

<?php $__env->startSection('content'); ?>
    <h1><?php echo e(config('bargain.company_name', config('app.name'))); ?></h1>
    <div class="muted"><?php echo e($docLabel); ?></div>
    <p>
        <strong><?php echo e($isCredit ? 'Credit #' : 'Bill #'); ?>:</strong> <?php echo e($bill->bill_number); ?><br>
        <strong>Ref No.:</strong> <?php echo e($bill->ref_no ?: '—'); ?><br>
        <strong>Date:</strong> <?php echo e($bill->bill_date?->format('m/d/Y')); ?><br>
        <strong>Due:</strong> <?php echo e($bill->due_date?->format('m/d/Y') ?: '—'); ?>

    </p>

    <div class="party">
        <strong>Vendor</strong><br>
        <?php echo e($bill->vendor?->display_name); ?><br>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $bill->vendor?->billFromLines() ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo e($line); ?><br>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Description</th>
                <th class="num">Qty</th>
                <th class="num">Cost</th>
                <th class="num">Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $bill->lines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($line->item?->sku); ?></td>
                    <td><?php echo e($line->description); ?></td>
                    <td class="num"><?php echo e(number_format((float) $line->quantity, 2)); ?></td>
                    <td class="num"><?php echo e(number_format((float) $line->rate, 2)); ?></td>
                    <td class="num"><?php echo e(number_format((float) $line->amount, 2)); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5">No lines.</td></tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>

    <table class="totals">
        <tr><td>Subtotal</td><td class="num"><?php echo e(number_format((float) $bill->subtotal, 2)); ?></td></tr>
        <tr><td><strong>Total</strong></td><td class="num"><strong><?php echo e(number_format((float) $bill->total, 2)); ?></strong></td></tr>
        <tr><td>Balance Due</td><td class="num"><?php echo e(number_format((float) $bill->balance_due, 2)); ?></td></tr>
    </table>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bill->memo): ?>
        <p><strong>Memo:</strong> <?php echo e($bill->memo); ?></p>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.print', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laragon\www\bargain-enterprise\resources\views/print/vendor-bill.blade.php ENDPATH**/ ?>