<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('title', null, []); ?> Company Snapshot <?php $__env->endSlot(); ?>
     <?php $__env->slot('windowTitle', null, []); ?> Company <?php $__env->endSlot(); ?>

    <div class="be-page">
        <div class="be-page__tabs" role="tablist">
            <span class="be-page__tab is-active" role="tab" aria-selected="true">Company</span>
            <span class="be-page__tab" role="tab">Payments</span>
            <span class="be-page__tab" role="tab">Customer</span>
        </div>

        <div class="be-toolbar">
            <button type="button" class="be-link-btn">Add Content &gt;</button>
            <button type="button" class="be-link-btn">Restore Default</button>
            <span class="ml-auto text-[11px] text-gray-500">Widget data arrives in Phase 10</span>
        </div>

        <div class="be-widget-grid">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                'Income and Expense Trend',
                'Prev Year Income Comparison',
                'Customers Who Owe Money',
                'Account Balances',
                'Top Customers by Sales',
                'Best-Selling Items',
                'Expense Breakdown',
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $widget): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <section class="be-widget" aria-label="<?php echo e($widget); ?>">
                    <header class="be-widget__header">
                        <span><?php echo e($widget); ?></span>
                        <select class="be-input w-auto py-0.5 text-[11px]" aria-label="Period for <?php echo e($widget); ?>">
                            <option>This year</option>
                            <option>Last year</option>
                        </select>
                    </header>
                    <div class="be-widget__body">
                        There is no data for this graph.
                    </div>
                </section>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH F:\laragon\www\bargain-enterprise\resources\views\dashboard\snapshots.blade.php ENDPATH**/ ?>