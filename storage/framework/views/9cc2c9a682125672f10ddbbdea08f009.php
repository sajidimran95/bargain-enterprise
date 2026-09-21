<?php $__env->startSection('title', 'PO '.$order->number); ?>
<?php $__env->startSection('toolbar', 'Purchase Order '.$order->number.' — Letter Print'); ?>

<?php $__env->startSection('content'); ?>
    <h1><?php echo e(config('bargain.company_name', config('app.name'))); ?></h1>
    <div class="muted">Purchase Order</div>
    <p>
        <strong>PO #:</strong> <?php echo e($order->number); ?><br>
        <strong>Date:</strong> <?php echo e($order->order_date?->format('m/d/Y')); ?><br>
        <strong>Expected:</strong> <?php echo e($order->expected_date?->format('m/d/Y') ?: '—'); ?><br>
        <strong>Status:</strong> <?php echo e($order->status); ?>

    </p>

    <div class="party">
        <strong>Vendor</strong><br>
        <?php echo e($order->vendor?->display_name); ?><br>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $order->vendor?->billFromLines() ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo e($line); ?><br>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Description</th>
                <th class="num">Qty</th>
                <th class="num">Received</th>
                <th class="num">Rate</th>
                <th class="num">Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $order->lines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($line->item?->sku); ?></td>
                    <td><?php echo e($line->description); ?></td>
                    <td class="num"><?php echo e(number_format((float) $line->quantity, 2)); ?></td>
                    <td class="num"><?php echo e(number_format((float) $line->qty_received, 2)); ?></td>
                    <td class="num"><?php echo e(number_format((float) $line->rate, 2)); ?></td>
                    <td class="num"><?php echo e(number_format((float) $line->amount, 2)); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6">No lines.</td></tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>

    <table class="totals">
        <tr><td>Subtotal</td><td class="num"><?php echo e(number_format((float) $order->subtotal, 2)); ?></td></tr>
        <tr><td><strong>Total</strong></td><td class="num"><strong><?php echo e(number_format((float) $order->total, 2)); ?></strong></td></tr>
    </table>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->memo): ?>
        <p><strong>Memo:</strong> <?php echo e($order->memo); ?></p>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.print', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laragon\www\bargain-enterprise\resources\views/print/purchase-order.blade.php ENDPATH**/ ?>