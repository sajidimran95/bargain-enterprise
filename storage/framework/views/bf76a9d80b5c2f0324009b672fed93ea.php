<div class="be-page be-home-page">
    <?php if (isset($component)) { $__componentOriginal00d054dba68bdcc1c55fe1e4603ea575 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal00d054dba68bdcc1c55fe1e4603ea575 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.home-insights-tabs','data' => ['active' => 'home']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.home-insights-tabs'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['active' => 'home']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal00d054dba68bdcc1c55fe1e4603ea575)): ?>
<?php $attributes = $__attributesOriginal00d054dba68bdcc1c55fe1e4603ea575; ?>
<?php unset($__attributesOriginal00d054dba68bdcc1c55fe1e4603ea575); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal00d054dba68bdcc1c55fe1e4603ea575)): ?>
<?php $component = $__componentOriginal00d054dba68bdcc1c55fe1e4603ea575; ?>
<?php unset($__componentOriginal00d054dba68bdcc1c55fe1e4603ea575); ?>
<?php endif; ?>

    <div class="be-home">
        <div class="be-home__main">
            <section class="be-home-band be-home-band--vendors" aria-labelledby="vendors-band">
                <h2 id="vendors-band" class="be-home-band__title">Vendors</h2>

                <div class="be-home-vendors">
                    <div class="be-home-vendors__row1">
                        <?php if (isset($component)) { $__componentOriginalc19b0eb78905fcee5b378041fd965211 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc19b0eb78905fcee5b378041fd965211 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.home-tile','data' => ['route' => 'purchase-orders.create','title' => 'Purchase Orders','icon' => 'clipboard','tone' => 'po','badge' => $counts['purchase_orders'] ?: null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.home-tile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'purchase-orders.create','title' => 'Purchase Orders','icon' => 'clipboard','tone' => 'po','badge' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($counts['purchase_orders'] ?: null)]); ?>Purchase Orders <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $attributes = $__attributesOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__attributesOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $component = $__componentOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__componentOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
                        <span class="be-home-arrow" aria-hidden="true"></span>
                        <?php if (isset($component)) { $__componentOriginalc19b0eb78905fcee5b378041fd965211 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc19b0eb78905fcee5b378041fd965211 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.home-tile','data' => ['route' => 'goods-receipts.create','title' => 'Receive Inventory','icon' => 'truck','tone' => 'recv']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.home-tile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'goods-receipts.create','title' => 'Receive Inventory','icon' => 'truck','tone' => 'recv']); ?>Receive Inventory <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $attributes = $__attributesOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__attributesOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $component = $__componentOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__componentOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
                        <span class="be-home-arrow" aria-hidden="true"></span>
                        <?php if (isset($component)) { $__componentOriginalc19b0eb78905fcee5b378041fd965211 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc19b0eb78905fcee5b378041fd965211 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.home-tile','data' => ['route' => 'vendor-bills.create','title' => 'Enter Bills Against Inventory','icon' => 'ledger','tone' => 'bill']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.home-tile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'vendor-bills.create','title' => 'Enter Bills Against Inventory','icon' => 'ledger','tone' => 'bill']); ?>Enter Bills Against Inventory <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $attributes = $__attributesOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__attributesOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $component = $__componentOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__componentOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
                        <span class="be-home-arrow" aria-hidden="true"></span>
                        <?php if (isset($component)) { $__componentOriginalc19b0eb78905fcee5b378041fd965211 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc19b0eb78905fcee5b378041fd965211 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.home-tile','data' => ['route' => 'vendor-payments.create','title' => 'Pay Bills','icon' => 'cash','tone' => 'pay','badge' => $counts['vendor_bills'] ?: null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.home-tile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'vendor-payments.create','title' => 'Pay Bills','icon' => 'cash','tone' => 'pay','badge' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($counts['vendor_bills'] ?: null)]); ?>Pay Bills <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $attributes = $__attributesOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__attributesOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $component = $__componentOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__componentOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
                    </div>

                    <div class="be-home-vendors__row2">
                        <?php if (isset($component)) { $__componentOriginalc19b0eb78905fcee5b378041fd965211 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc19b0eb78905fcee5b378041fd965211 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.home-tile','data' => ['route' => 'vendor-bills.create','title' => 'Enter Bills','icon' => 'receipt','tone' => 'enter']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.home-tile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'vendor-bills.create','title' => 'Enter Bills','icon' => 'receipt','tone' => 'enter']); ?>Enter Bills <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $attributes = $__attributesOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__attributesOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $component = $__componentOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__componentOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
                        <span class="be-home-arrow be-home-arrow--long" aria-hidden="true"></span>
                    </div>

                    <div class="be-home-vendors__extras">
                        <?php if (isset($component)) { $__componentOriginalc19b0eb78905fcee5b378041fd965211 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc19b0eb78905fcee5b378041fd965211 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.home-tile','data' => ['route' => 'banking.create','title' => 'New Business Loans','icon' => 'bank','tone' => 'loan']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.home-tile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'banking.create','title' => 'New Business Loans','icon' => 'bank','tone' => 'loan']); ?>New Business Loans <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $attributes = $__attributesOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__attributesOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $component = $__componentOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__componentOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginalc19b0eb78905fcee5b378041fd965211 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc19b0eb78905fcee5b378041fd965211 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.home-tile','data' => ['route' => 'settings.index','title' => 'Manage Sales Tax','icon' => 'gear','tone' => 'tax']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.home-tile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'settings.index','title' => 'Manage Sales Tax','icon' => 'gear','tone' => 'tax']); ?>Manage Sales Tax <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $attributes = $__attributesOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__attributesOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $component = $__componentOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__componentOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
                    </div>
                </div>
            </section>

            <section class="be-home-band be-home-band--customers" aria-labelledby="customers-band">
                <h2 id="customers-band" class="be-home-band__title">Customers</h2>

                <div class="be-home-customers">
                    <div class="be-home-customers__grid">
                        <div class="be-home-customers__quote">
                            <?php if (isset($component)) { $__componentOriginalc19b0eb78905fcee5b378041fd965211 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc19b0eb78905fcee5b378041fd965211 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.home-tile','data' => ['route' => 'quotes.create','title' => 'Quotes (Estimates)','icon' => 'file','tone' => 'quote']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.home-tile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'quotes.create','title' => 'Quotes (Estimates)','icon' => 'file','tone' => 'quote']); ?>Quotes (Estimates) <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $attributes = $__attributesOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__attributesOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $component = $__componentOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__componentOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
                            <span class="be-home-arrow" aria-hidden="true"></span>
                        </div>

                        <div class="be-home-customers__so">
                            <?php if (isset($component)) { $__componentOriginalc19b0eb78905fcee5b378041fd965211 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc19b0eb78905fcee5b378041fd965211 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.home-tile','data' => ['route' => 'sales-orders.create','title' => 'Sales Orders','icon' => 'cart','tone' => 'so','badge' => $counts['sales_orders'] ?: null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.home-tile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'sales-orders.create','title' => 'Sales Orders','icon' => 'cart','tone' => 'so','badge' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($counts['sales_orders'] ?: null)]); ?>Sales Orders <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $attributes = $__attributesOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__attributesOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $component = $__componentOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__componentOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
                            <span class="be-home-arrow" aria-hidden="true"></span>
                        </div>

                        <div class="be-home-customers__invoice">
                            <?php if (isset($component)) { $__componentOriginalc19b0eb78905fcee5b378041fd965211 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc19b0eb78905fcee5b378041fd965211 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.home-tile','data' => ['route' => 'invoices.create','title' => 'Create Invoices','icon' => 'ledger','tone' => 'invoice','badge' => $counts['invoices'] ?: null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.home-tile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'invoices.create','title' => 'Create Invoices','icon' => 'ledger','tone' => 'invoice','badge' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($counts['invoices'] ?: null)]); ?>Create Invoices <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $attributes = $__attributesOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__attributesOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $component = $__componentOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__componentOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
                            <span class="be-home-arrow" aria-hidden="true"></span>
                            <?php if (isset($component)) { $__componentOriginalc19b0eb78905fcee5b378041fd965211 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc19b0eb78905fcee5b378041fd965211 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.home-tile','data' => ['route' => 'payments.create','title' => 'Receive Payments','icon' => 'cash','tone' => 'payment']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.home-tile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'payments.create','title' => 'Receive Payments','icon' => 'cash','tone' => 'payment']); ?>Receive Payments <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $attributes = $__attributesOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__attributesOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $component = $__componentOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__componentOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
                            <span class="be-home-arrow be-home-arrow--out" aria-hidden="true"></span>
                        </div>
                    </div>

                    <div class="be-home-customers__side">
                        <?php if (isset($component)) { $__componentOriginalc19b0eb78905fcee5b378041fd965211 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc19b0eb78905fcee5b378041fd965211 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.home-tile','data' => ['route' => 'payments.create','title' => 'Accept Credit Cards','icon' => 'cash','tone' => 'cc']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.home-tile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'payments.create','title' => 'Accept Credit Cards','icon' => 'cash','tone' => 'cc']); ?>Accept Credit Cards <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $attributes = $__attributesOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__attributesOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $component = $__componentOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__componentOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginalc19b0eb78905fcee5b378041fd965211 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc19b0eb78905fcee5b378041fd965211 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.home-tile','data' => ['route' => 'sales-receipts.create','title' => 'Create Sales Receipts','icon' => 'receipt','tone' => 'receipt']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.home-tile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'sales-receipts.create','title' => 'Create Sales Receipts','icon' => 'receipt','tone' => 'receipt']); ?>Create Sales Receipts <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $attributes = $__attributesOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__attributesOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $component = $__componentOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__componentOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
                        <div class="be-home-customers__statements">
                            <?php if (isset($component)) { $__componentOriginalc19b0eb78905fcee5b378041fd965211 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc19b0eb78905fcee5b378041fd965211 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.home-tile','data' => ['route' => 'invoices.create','title' => 'Statement Charges','icon' => 'list','tone' => 'stmt']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.home-tile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'invoices.create','title' => 'Statement Charges','icon' => 'list','tone' => 'stmt']); ?>Statement Charges <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $attributes = $__attributesOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__attributesOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $component = $__componentOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__componentOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
                            <span class="be-home-arrow" aria-hidden="true"></span>
                            <?php if (isset($component)) { $__componentOriginalc19b0eb78905fcee5b378041fd965211 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc19b0eb78905fcee5b378041fd965211 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.home-tile','data' => ['route' => 'customers.statements','title' => 'Statements','icon' => 'file','tone' => 'stmt']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.home-tile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'customers.statements','title' => 'Statements','icon' => 'file','tone' => 'stmt']); ?>Statements <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $attributes = $__attributesOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__attributesOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $component = $__componentOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__componentOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
                        </div>
                        <?php if (isset($component)) { $__componentOriginalc19b0eb78905fcee5b378041fd965211 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc19b0eb78905fcee5b378041fd965211 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.home-tile','data' => ['route' => 'credit-memos.create','title' => 'Refunds & Credits','icon' => 'credit','tone' => 'credit']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.home-tile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'credit-memos.create','title' => 'Refunds & Credits','icon' => 'credit','tone' => 'credit']); ?>Refunds &amp; Credits <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $attributes = $__attributesOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__attributesOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $component = $__componentOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__componentOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
                    </div>
                </div>
            </section>

            <section class="be-home-band be-home-band--employees" aria-labelledby="employees-band">
                <h2 id="employees-band" class="be-home-band__title">Employees</h2>
                <div class="be-home-employees">
                    <?php if (isset($component)) { $__componentOriginalc19b0eb78905fcee5b378041fd965211 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc19b0eb78905fcee5b378041fd965211 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.home-tile','data' => ['route' => 'employees.time','title' => 'Enter Time','icon' => 'clock','tone' => 'time']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.home-tile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'employees.time','title' => 'Enter Time','icon' => 'clock','tone' => 'time']); ?>Enter Time <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $attributes = $__attributesOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__attributesOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $component = $__componentOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__componentOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginalc19b0eb78905fcee5b378041fd965211 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc19b0eb78905fcee5b378041fd965211 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.home-tile','data' => ['route' => 'employees.payroll','title' => 'Turn On Payroll','icon' => 'users','tone' => 'payroll']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.home-tile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'employees.payroll','title' => 'Turn On Payroll','icon' => 'users','tone' => 'payroll']); ?>Turn On Payroll <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $attributes = $__attributesOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__attributesOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $component = $__componentOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__componentOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
                </div>
            </section>
        </div>

        <aside class="be-home__rail">
            <section class="be-home-band be-home-band--rail" aria-labelledby="company-panel">
                <h2 id="company-panel" class="be-home-band__title">Company</h2>
                <div class="be-home-rail__list">
                    <?php if (isset($component)) { $__componentOriginalc19b0eb78905fcee5b378041fd965211 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc19b0eb78905fcee5b378041fd965211 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.home-tile','data' => ['route' => 'accounting.chart','title' => 'Chart of Accounts','icon' => 'chart','tone' => 'coa']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.home-tile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'accounting.chart','title' => 'Chart of Accounts','icon' => 'chart','tone' => 'coa']); ?>Chart of Accounts <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $attributes = $__attributesOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__attributesOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $component = $__componentOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__componentOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginalc19b0eb78905fcee5b378041fd965211 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc19b0eb78905fcee5b378041fd965211 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.home-tile','data' => ['route' => 'inventory.index','title' => 'Inventory Activities','icon' => 'layers','tone' => 'inv']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.home-tile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'inventory.index','title' => 'Inventory Activities','icon' => 'layers','tone' => 'inv']); ?>Inventory Activities <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $attributes = $__attributesOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__attributesOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $component = $__componentOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__componentOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginalc19b0eb78905fcee5b378041fd965211 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc19b0eb78905fcee5b378041fd965211 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.home-tile','data' => ['route' => 'items.index','title' => 'Items & Services','icon' => 'box','tone' => 'items']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.home-tile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'items.index','title' => 'Items & Services','icon' => 'box','tone' => 'items']); ?>Items &amp; Services <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $attributes = $__attributesOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__attributesOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $component = $__componentOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__componentOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginalc19b0eb78905fcee5b378041fd965211 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc19b0eb78905fcee5b378041fd965211 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.home-tile','data' => ['route' => 'checks.create','title' => 'Order Checks','icon' => 'checkbook','tone' => 'check']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.home-tile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'checks.create','title' => 'Order Checks','icon' => 'checkbook','tone' => 'check']); ?>Order Checks <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $attributes = $__attributesOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__attributesOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $component = $__componentOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__componentOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginalc19b0eb78905fcee5b378041fd965211 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc19b0eb78905fcee5b378041fd965211 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.home-tile','data' => ['route' => 'dashboard.snapshots','title' => 'Calendar','icon' => 'camera','tone' => 'calendar']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.home-tile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'dashboard.snapshots','title' => 'Calendar','icon' => 'camera','tone' => 'calendar']); ?>Calendar <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $attributes = $__attributesOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__attributesOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $component = $__componentOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__componentOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
                </div>
            </section>

            <section class="be-home-band be-home-band--rail" aria-labelledby="banking-panel">
                <h2 id="banking-panel" class="be-home-band__title">Banking</h2>
                <div class="be-home-rail__list">
                    <?php if (isset($component)) { $__componentOriginalc19b0eb78905fcee5b378041fd965211 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc19b0eb78905fcee5b378041fd965211 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.home-tile','data' => ['route' => 'deposits.create','title' => 'Record Deposits','icon' => 'cash','tone' => 'deposit']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.home-tile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'deposits.create','title' => 'Record Deposits','icon' => 'cash','tone' => 'deposit']); ?>Record Deposits <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $attributes = $__attributesOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__attributesOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $component = $__componentOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__componentOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginalc19b0eb78905fcee5b378041fd965211 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc19b0eb78905fcee5b378041fd965211 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.home-tile','data' => ['route' => 'reconciliation.index','title' => 'Reconcile','icon' => 'check','tone' => 'reconcile']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.home-tile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'reconciliation.index','title' => 'Reconcile','icon' => 'check','tone' => 'reconcile']); ?>Reconcile <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $attributes = $__attributesOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__attributesOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $component = $__componentOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__componentOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginalc19b0eb78905fcee5b378041fd965211 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc19b0eb78905fcee5b378041fd965211 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.home-tile','data' => ['route' => 'checks.create','title' => 'Write Checks','icon' => 'checkbook','tone' => 'check']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.home-tile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'checks.create','title' => 'Write Checks','icon' => 'checkbook','tone' => 'check']); ?>Write Checks <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $attributes = $__attributesOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__attributesOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $component = $__componentOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__componentOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginalc19b0eb78905fcee5b378041fd965211 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc19b0eb78905fcee5b378041fd965211 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.home-tile','data' => ['route' => 'banking.index','title' => 'Check Register','icon' => 'bank','tone' => 'register']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.home-tile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'banking.index','title' => 'Check Register','icon' => 'bank','tone' => 'register']); ?>Check Register <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $attributes = $__attributesOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__attributesOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $component = $__componentOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__componentOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginalc19b0eb78905fcee5b378041fd965211 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc19b0eb78905fcee5b378041fd965211 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.home-tile','data' => ['route' => 'checks.index','title' => 'Print Checks','icon' => 'print','tone' => 'print']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.home-tile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'checks.index','title' => 'Print Checks','icon' => 'print','tone' => 'print']); ?>Print Checks <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $attributes = $__attributesOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__attributesOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc19b0eb78905fcee5b378041fd965211)): ?>
<?php $component = $__componentOriginalc19b0eb78905fcee5b378041fd965211; ?>
<?php unset($__componentOriginalc19b0eb78905fcee5b378041fd965211); ?>
<?php endif; ?>
                </div>
            </section>
        </aside>
    </div>
</div><?php /**PATH F:\laragon\www\bargain-enterprise\resources\views\livewire\dashboard\home-placeholder.blade.php ENDPATH**/ ?>