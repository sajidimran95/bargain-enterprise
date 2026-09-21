<?php $__env->startSection('title', 'Receipt '.$receipt->number); ?>
<?php $__env->startSection('toolbar', 'Receive Inventory '.$receipt->number.' — Letter Print'); ?>

<?php $__env->startSection('content'); ?>
    <h1><?php echo e(config('bargain.company_name', config('app.name'))); ?></h1>
    <div class="muted">Goods Receipt / Receive Inventory</div>
    <p>
        <strong>Receipt #:</strong> <?php echo e($receipt->number); ?><br>
        <strong>Date:</strong> <?php echo e($receipt->receipt_date?->format('m/d/Y')); ?><br>
        <strong>PO:</strong> <?php echo e($receipt->purchaseOrder?->number ?: '—'); ?>

    </p>

    <div class="party">
        <strong>Vendor</strong><br>
        <?php echo e($receipt->vendor?->display_name); ?><br>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $receipt->vendor?->billFromLines() ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo e($line); ?><br>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Description</th>
                <th class="num">Qty</th>
                <th class="num">Unit Cost</th>
                <th class="num">Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $receipt->lines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $amount = (float) bcmul((string) $line->quantity, (string) $line->unit_cost, 4);
                ?>
                <tr>
                    <td><?php echo e($line->item?->sku); ?></td>
                    <td><?php echo e($line->item?->purchase_description ?: $line->item?->name); ?></td>
                    <td class="num"><?php echo e(number_format((float) $line->quantity, 2)); ?></td>
                    <td class="num"><?php echo e(number_format((float) $line->unit_cost, 2)); ?></td>
                    <td class="num"><?php echo e(number_format($amount, 2)); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5">No lines.</td></tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($receipt->memo): ?>
        <p><strong>Memo:</strong> <?php echo e($receipt->memo); ?></p>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.print', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laragon\www\bargain-enterprise\resources\views/print/goods-receipt.blade.php ENDPATH**/ ?>