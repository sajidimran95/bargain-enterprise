<div class="be-page be-invoice-page" x-data @be-focus-scan.window="$refs.scanInput?.focus()">
    <?php if (isset($component)) { $__componentOriginal51b2a34bf372287677637d382834dd3d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal51b2a34bf372287677637d382834dd3d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.document-ribbon','data' => ['findRouteName' => 'invoices.index','newRouteName' => 'invoices.create','activeTab' => $ribbonTab,'isPending' => $is_pending,'showApplyCredits' => true,'showReceivePayments' => true,'showRefundCredit' => true,'applyCreditsRouteName' => 'credit-memos.index','receivePaymentsRouteName' => 'payments.create','refundCreditRouteName' => 'credit-memos.create','attachmentCount' => count($pendingAttachments),'formattingFontSize' => $formattingFontSize,'formattingBold' => $formattingBold]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.document-ribbon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['find-route-name' => 'invoices.index','new-route-name' => 'invoices.create','active-tab' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($ribbonTab),'is-pending' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($is_pending),'show-apply-credits' => true,'show-receive-payments' => true,'show-refund-credit' => true,'apply-credits-route-name' => 'credit-memos.index','receive-payments-route-name' => 'payments.create','refund-credit-route-name' => 'credit-memos.create','attachment-count' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(count($pendingAttachments)),'formatting-font-size' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($formattingFontSize),'formatting-bold' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($formattingBold)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal51b2a34bf372287677637d382834dd3d)): ?>
<?php $attributes = $__attributesOriginal51b2a34bf372287677637d382834dd3d; ?>
<?php unset($__attributesOriginal51b2a34bf372287677637d382834dd3d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal51b2a34bf372287677637d382834dd3d)): ?>
<?php $component = $__componentOriginal51b2a34bf372287677637d382834dd3d; ?>
<?php unset($__componentOriginal51b2a34bf372287677637d382834dd3d); ?>
<?php endif; ?>

    <?php echo $__env->make('livewire.sales.partials.document-ribbon-modals', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($is_pending): ?>
        <div class="be-invoice-pending">Pending — will not post inventory or AR until cleared.</div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="be-invoice-layout <?php echo e($inspectorOpen ? '' : 'be-invoice-layout--inspector-collapsed'); ?>">
        <div class="be-invoice-main">
            <div class="be-invoice-doc">
                <div class="be-invoice-toprow">
                    <div class="be-field be-field--grow">
                        <label class="be-field__label be-field__label--caps be-field__label--on-blue">Customer:Job</label>
                        <select wire:model.live="customer_id" class="be-input be-input--combo be-input--customer">
                            <option value="">Select customer…</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($customer->id); ?>">
                                    <?php echo e($customer->customer_number
                                        ? $customer->customer_number.' ('.$customer->display_name.')'
                                        : $customer->display_name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['customer_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="be-field__error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="be-field be-field--class">
                        <label class="be-field__label be-field__label--caps be-field__label--on-blue">Class</label>
                        <?php if (isset($component)) { $__componentOriginal5ffc033813591e85e4508e27a2ee1612 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ffc033813591e85e4508e27a2ee1612 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.input','data' => ['wire:model' => 'class','class' => 'be-input--combo']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'class','class' => 'be-input--combo']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5ffc033813591e85e4508e27a2ee1612)): ?>
<?php $attributes = $__attributesOriginal5ffc033813591e85e4508e27a2ee1612; ?>
<?php unset($__attributesOriginal5ffc033813591e85e4508e27a2ee1612); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5ffc033813591e85e4508e27a2ee1612)): ?>
<?php $component = $__componentOriginal5ffc033813591e85e4508e27a2ee1612; ?>
<?php unset($__componentOriginal5ffc033813591e85e4508e27a2ee1612); ?>
<?php endif; ?>
                    </div>
                    <div class="be-field be-field--template">
                        <label class="be-field__label be-field__label--caps be-field__label--on-blue">Template</label>
                        <?php if (isset($component)) { $__componentOriginal847fd48de2d422593186c84a70c7291b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal847fd48de2d422593186c84a70c7291b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.select','data' => ['wire:model' => 'template','class' => 'be-input--combo','options' => [
                                'Intuit Product Invoice' => 'Intuit Product Invoice',
                                'Copy of Intuit Product Invoice' => 'Copy of Intuit Product Invoice',
                            ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'template','class' => 'be-input--combo','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                                'Intuit Product Invoice' => 'Intuit Product Invoice',
                                'Copy of Intuit Product Invoice' => 'Copy of Intuit Product Invoice',
                            ])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal847fd48de2d422593186c84a70c7291b)): ?>
<?php $attributes = $__attributesOriginal847fd48de2d422593186c84a70c7291b; ?>
<?php unset($__attributesOriginal847fd48de2d422593186c84a70c7291b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal847fd48de2d422593186c84a70c7291b)): ?>
<?php $component = $__componentOriginal847fd48de2d422593186c84a70c7291b; ?>
<?php unset($__componentOriginal847fd48de2d422593186c84a70c7291b); ?>
<?php endif; ?>
                    </div>
                </div>

                <div class="be-invoice-midrow">
                    <div class="be-invoice-midrow__left">
                        <h1 class="be-invoice-title">Invoice</h1>
                    </div>
                    <div class="be-invoice-midrow__right">
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Date</label>
                            <?php if (isset($component)) { $__componentOriginal5ffc033813591e85e4508e27a2ee1612 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ffc033813591e85e4508e27a2ee1612 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.input','data' => ['type' => 'date','wire:model' => 'invoice_date','class' => 'be-input--combo']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'date','wire:model' => 'invoice_date','class' => 'be-input--combo']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5ffc033813591e85e4508e27a2ee1612)): ?>
<?php $attributes = $__attributesOriginal5ffc033813591e85e4508e27a2ee1612; ?>
<?php unset($__attributesOriginal5ffc033813591e85e4508e27a2ee1612); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5ffc033813591e85e4508e27a2ee1612)): ?>
<?php $component = $__componentOriginal5ffc033813591e85e4508e27a2ee1612; ?>
<?php unset($__componentOriginal5ffc033813591e85e4508e27a2ee1612); ?>
<?php endif; ?>
                        </div>
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Invoice #</label>
                            <?php if (isset($component)) { $__componentOriginal5ffc033813591e85e4508e27a2ee1612 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ffc033813591e85e4508e27a2ee1612 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.input','data' => ['wire:model' => 'invoice_number','class' => 'be-input--combo']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'invoice_number','class' => 'be-input--combo']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5ffc033813591e85e4508e27a2ee1612)): ?>
<?php $attributes = $__attributesOriginal5ffc033813591e85e4508e27a2ee1612; ?>
<?php unset($__attributesOriginal5ffc033813591e85e4508e27a2ee1612); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5ffc033813591e85e4508e27a2ee1612)): ?>
<?php $component = $__componentOriginal5ffc033813591e85e4508e27a2ee1612; ?>
<?php unset($__componentOriginal5ffc033813591e85e4508e27a2ee1612); ?>
<?php endif; ?>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['invoice_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="be-field__error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($savedInvoice): ?>
                            <div class="be-invoice-audit text-[11px] text-gray-700 leading-snug mt-1">
                                <div>
                                    <strong>Created:</strong>
                                    <?php echo e($savedInvoice->created_at?->format('m/d/Y g:i A') ?? '—'); ?>

                                    by <?php echo e($savedInvoice->createdBy?->name ?? 'System'); ?>

                                </div>
                                <div>
                                    <strong>Last edit:</strong>
                                    <?php echo e($savedInvoice->updated_at?->format('m/d/Y g:i A') ?? '—'); ?>

                                    by <?php echo e($savedInvoice->updatedBy?->name ?? $savedInvoice->createdBy?->name ?? 'System'); ?>

                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <div class="be-field">
                            <label class="be-field__label be-field__label--caps">Bill To</label>
                            <textarea class="be-input be-invoice-billto" rows="5" readonly><?php echo e($selectedCustomer?->formattedBillingAddress() ?: ''); ?></textarea>
                        </div>
                    </div>
                </div>

                <?php if (isset($component)) { $__componentOriginalaea448eefd550171c3b0f9c5e0a89e4b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalaea448eefd550171c3b0f9c5e0a89e4b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.item-search-bar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.item-search-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalaea448eefd550171c3b0f9c5e0a89e4b)): ?>
<?php $attributes = $__attributesOriginalaea448eefd550171c3b0f9c5e0a89e4b; ?>
<?php unset($__attributesOriginalaea448eefd550171c3b0f9c5e0a89e4b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalaea448eefd550171c3b0f9c5e0a89e4b)): ?>
<?php $component = $__componentOriginalaea448eefd550171c3b0f9c5e0a89e4b; ?>
<?php unset($__componentOriginalaea448eefd550171c3b0f9c5e0a89e4b); ?>
<?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['lines'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="be-invoice-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div class="be-invoice-grid-wrap">
                    <table class="be-table be-invoice-grid">
                        <thead>
                            <tr>
                                <th style="width:13%">ITEM CODE</th>
                                <th style="width:7%" class="text-right">QTY</th>
                                <th>DESCRIPTION</th>
                                <th style="width:11%" class="text-right">PRICE EACH</th>
                                <th style="width:10%">CLASS</th>
                                <th style="width:11%" class="text-right">AMOUNT</th>
                                <th style="width:5%" class="text-center">TAX</th>
                                <th style="width:3%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $lines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr wire:key="inv-line-<?php echo e($index); ?>" class="<?php echo e($index % 2 ? 'be-row-alt' : ''); ?>">
                                    <td>
                                        <?php if (isset($component)) { $__componentOriginalc9e3310ebf61f49f9ecce963291c8b46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc9e3310ebf61f49f9ecce963291c8b46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.item-code-input','data' => ['wire:model.blur' => 'lines.'.e($index).'.item_code','placeholder' => 'Code…']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.item-code-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.blur' => 'lines.'.e($index).'.item_code','placeholder' => 'Code…']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc9e3310ebf61f49f9ecce963291c8b46)): ?>
<?php $attributes = $__attributesOriginalc9e3310ebf61f49f9ecce963291c8b46; ?>
<?php unset($__attributesOriginalc9e3310ebf61f49f9ecce963291c8b46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc9e3310ebf61f49f9ecce963291c8b46)): ?>
<?php $component = $__componentOriginalc9e3310ebf61f49f9ecce963291c8b46; ?>
<?php unset($__componentOriginalc9e3310ebf61f49f9ecce963291c8b46); ?>
<?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (isset($component)) { $__componentOriginal5ffc033813591e85e4508e27a2ee1612 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ffc033813591e85e4508e27a2ee1612 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.input','data' => ['type' => 'number','step' => '0.01','min' => '0','class' => 'text-right be-input--bare','wire:model.live' => 'lines.'.e($index).'.quantity']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'number','step' => '0.01','min' => '0','class' => 'text-right be-input--bare','wire:model.live' => 'lines.'.e($index).'.quantity']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5ffc033813591e85e4508e27a2ee1612)): ?>
<?php $attributes = $__attributesOriginal5ffc033813591e85e4508e27a2ee1612; ?>
<?php unset($__attributesOriginal5ffc033813591e85e4508e27a2ee1612); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5ffc033813591e85e4508e27a2ee1612)): ?>
<?php $component = $__componentOriginal5ffc033813591e85e4508e27a2ee1612; ?>
<?php unset($__componentOriginal5ffc033813591e85e4508e27a2ee1612); ?>
<?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (isset($component)) { $__componentOriginal5ffc033813591e85e4508e27a2ee1612 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ffc033813591e85e4508e27a2ee1612 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.input','data' => ['wire:model' => 'lines.'.e($index).'.description','class' => 'be-input--bare']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'lines.'.e($index).'.description','class' => 'be-input--bare']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5ffc033813591e85e4508e27a2ee1612)): ?>
<?php $attributes = $__attributesOriginal5ffc033813591e85e4508e27a2ee1612; ?>
<?php unset($__attributesOriginal5ffc033813591e85e4508e27a2ee1612); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5ffc033813591e85e4508e27a2ee1612)): ?>
<?php $component = $__componentOriginal5ffc033813591e85e4508e27a2ee1612; ?>
<?php unset($__componentOriginal5ffc033813591e85e4508e27a2ee1612); ?>
<?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (isset($component)) { $__componentOriginal5ffc033813591e85e4508e27a2ee1612 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ffc033813591e85e4508e27a2ee1612 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.input','data' => ['type' => 'number','step' => '0.01','min' => '0','class' => 'text-right be-input--bare','wire:model.live' => 'lines.'.e($index).'.rate']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'number','step' => '0.01','min' => '0','class' => 'text-right be-input--bare','wire:model.live' => 'lines.'.e($index).'.rate']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5ffc033813591e85e4508e27a2ee1612)): ?>
<?php $attributes = $__attributesOriginal5ffc033813591e85e4508e27a2ee1612; ?>
<?php unset($__attributesOriginal5ffc033813591e85e4508e27a2ee1612); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5ffc033813591e85e4508e27a2ee1612)): ?>
<?php $component = $__componentOriginal5ffc033813591e85e4508e27a2ee1612; ?>
<?php unset($__componentOriginal5ffc033813591e85e4508e27a2ee1612); ?>
<?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (isset($component)) { $__componentOriginal5ffc033813591e85e4508e27a2ee1612 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ffc033813591e85e4508e27a2ee1612 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.input','data' => ['wire:model' => 'lines.'.e($index).'.class','class' => 'be-input--bare']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'lines.'.e($index).'.class','class' => 'be-input--bare']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5ffc033813591e85e4508e27a2ee1612)): ?>
<?php $attributes = $__attributesOriginal5ffc033813591e85e4508e27a2ee1612; ?>
<?php unset($__attributesOriginal5ffc033813591e85e4508e27a2ee1612); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5ffc033813591e85e4508e27a2ee1612)): ?>
<?php $component = $__componentOriginal5ffc033813591e85e4508e27a2ee1612; ?>
<?php unset($__componentOriginal5ffc033813591e85e4508e27a2ee1612); ?>
<?php endif; ?>
                                    </td>
                                    <td class="num be-invoice-amount"><?php echo e(number_format((float) ($line['amount'] ?? 0), 2)); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" wire:model.live="lines.<?php echo e($index); ?>.taxable" class="be-invoice-tax">
                                    </td>
                                    <td class="text-center">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($line['item_id'] ?? null)): ?>
                                            <button type="button" class="be-link-btn" wire:click="removeLine(<?php echo e($index); ?>)">×</button>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="be-invoice-footer">
                    <div class="be-invoice-footer__left">
                        <div class="be-field">
                            <label class="be-field__label be-field__label--caps">Customer Message</label>
                            <?php if (isset($component)) { $__componentOriginal5ffc033813591e85e4508e27a2ee1612 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ffc033813591e85e4508e27a2ee1612 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.input','data' => ['wire:model' => 'customer_message','class' => 'be-input--combo '.e($formattingBold ? 'font-bold' : '').'','style' => 'font-size: '.e($formattingFontSize).'px']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'customer_message','class' => 'be-input--combo '.e($formattingBold ? 'font-bold' : '').'','style' => 'font-size: '.e($formattingFontSize).'px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5ffc033813591e85e4508e27a2ee1612)): ?>
<?php $attributes = $__attributesOriginal5ffc033813591e85e4508e27a2ee1612; ?>
<?php unset($__attributesOriginal5ffc033813591e85e4508e27a2ee1612); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5ffc033813591e85e4508e27a2ee1612)): ?>
<?php $component = $__componentOriginal5ffc033813591e85e4508e27a2ee1612; ?>
<?php unset($__componentOriginal5ffc033813591e85e4508e27a2ee1612); ?>
<?php endif; ?>
                        </div>
                        <div class="be-field">
                            <label class="be-field__label be-field__label--caps">Memo</label>
                            <?php if (isset($component)) { $__componentOriginal5ffc033813591e85e4508e27a2ee1612 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ffc033813591e85e4508e27a2ee1612 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.input','data' => ['wire:model' => 'memo','class' => 'be-input--combo '.e($formattingBold ? 'font-bold' : '').'','style' => 'font-size: '.e($formattingFontSize).'px']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'memo','class' => 'be-input--combo '.e($formattingBold ? 'font-bold' : '').'','style' => 'font-size: '.e($formattingFontSize).'px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5ffc033813591e85e4508e27a2ee1612)): ?>
<?php $attributes = $__attributesOriginal5ffc033813591e85e4508e27a2ee1612; ?>
<?php unset($__attributesOriginal5ffc033813591e85e4508e27a2ee1612); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5ffc033813591e85e4508e27a2ee1612)): ?>
<?php $component = $__componentOriginal5ffc033813591e85e4508e27a2ee1612; ?>
<?php unset($__componentOriginal5ffc033813591e85e4508e27a2ee1612); ?>
<?php endif; ?>
                        </div>
                        <div class="be-field be-field--taxcode">
                            <label class="be-field__label be-field__label--caps">Customer Tax Code</label>
                            <?php if (isset($component)) { $__componentOriginal847fd48de2d422593186c84a70c7291b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal847fd48de2d422593186c84a70c7291b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.select','data' => ['wire:model.live' => 'tax_code_id','class' => 'be-input--combo','options' => ['' => 'Non'] + $taxCodes]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'tax_code_id','class' => 'be-input--combo','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['' => 'Non'] + $taxCodes)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal847fd48de2d422593186c84a70c7291b)): ?>
<?php $attributes = $__attributesOriginal847fd48de2d422593186c84a70c7291b; ?>
<?php unset($__attributesOriginal847fd48de2d422593186c84a70c7291b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal847fd48de2d422593186c84a70c7291b)): ?>
<?php $component = $__componentOriginal847fd48de2d422593186c84a70c7291b; ?>
<?php unset($__componentOriginal847fd48de2d422593186c84a70c7291b); ?>
<?php endif; ?>
                        </div>
                    </div>
                    <div class="be-invoice-totals">
                        <div class="be-invoice-totals__row">
                            <span>Tax</span>
                            <span class="be-invoice-totals__tax">
                                <span><?php echo e(number_format((float) $taxRate, 2)); ?>%</span>
                                <strong><?php echo e(number_format((float) $taxTotal, 2)); ?></strong>
                            </span>
                        </div>
                        <div class="be-invoice-totals__row be-invoice-totals__row--total">
                            <span>Total</span>
                            <strong><?php echo e(number_format((float) $total, 2)); ?></strong>
                        </div>
                        <div class="be-invoice-totals__row">
                            <span>Payments Applied</span>
                            <strong><?php echo e($receive_payment_now && ! $is_pending ? number_format((float) ($payment_amount !== '' ? $payment_amount : $total), 2) : '0.00'); ?></strong>
                        </div>
                        <div class="be-invoice-totals__row be-invoice-totals__row--balance">
                            <span>Balance Due</span>
                            <strong>
                                <?php
                                    $appliedPreview = ($receive_payment_now && ! $is_pending)
                                        ? (float) ($payment_amount !== '' ? $payment_amount : $total)
                                        : 0;
                                    $balancePreview = max(0, (float) $total - $appliedPreview);
                                ?>
                                <?php echo e(number_format($balancePreview, 2)); ?>

                            </strong>
                        </div>
                    </div>
                </div>

                <div class="be-invoice-paynow border px-2 py-2 mb-2" style="border-color:#8aa3bc;background:#f3f7fb;">
                    <label class="inline-flex items-center gap-2 text-[12px] font-semibold">
                        <input type="checkbox" wire:model.live="receive_payment_now" <?php if($is_pending): echo 'disabled'; endif; ?>>
                        Receive payment now (pay with this invoice)
                    </label>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($receive_payment_now && ! $is_pending): ?>
                        <div class="mt-2 grid gap-2 md:grid-cols-3">
                            <div class="be-field">
                                <label class="be-field__label be-field__label--caps">Method</label>
                                <?php if (isset($component)) { $__componentOriginal847fd48de2d422593186c84a70c7291b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal847fd48de2d422593186c84a70c7291b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.select','data' => ['wire:model' => 'payment_method','class' => 'be-input--combo','options' => $paymentMethodOptions]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'payment_method','class' => 'be-input--combo','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($paymentMethodOptions)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal847fd48de2d422593186c84a70c7291b)): ?>
<?php $attributes = $__attributesOriginal847fd48de2d422593186c84a70c7291b; ?>
<?php unset($__attributesOriginal847fd48de2d422593186c84a70c7291b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal847fd48de2d422593186c84a70c7291b)): ?>
<?php $component = $__componentOriginal847fd48de2d422593186c84a70c7291b; ?>
<?php unset($__componentOriginal847fd48de2d422593186c84a70c7291b); ?>
<?php endif; ?>
                            </div>
                            <div class="be-field">
                                <label class="be-field__label be-field__label--caps">Amount</label>
                                <?php if (isset($component)) { $__componentOriginal5ffc033813591e85e4508e27a2ee1612 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ffc033813591e85e4508e27a2ee1612 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.input','data' => ['type' => 'number','step' => '0.01','min' => '0','wire:model.live' => 'payment_amount','class' => 'be-input--combo text-right']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'number','step' => '0.01','min' => '0','wire:model.live' => 'payment_amount','class' => 'be-input--combo text-right']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5ffc033813591e85e4508e27a2ee1612)): ?>
<?php $attributes = $__attributesOriginal5ffc033813591e85e4508e27a2ee1612; ?>
<?php unset($__attributesOriginal5ffc033813591e85e4508e27a2ee1612); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5ffc033813591e85e4508e27a2ee1612)): ?>
<?php $component = $__componentOriginal5ffc033813591e85e4508e27a2ee1612; ?>
<?php unset($__componentOriginal5ffc033813591e85e4508e27a2ee1612); ?>
<?php endif; ?>
                            </div>
                            <div class="be-field">
                                <label class="be-field__label be-field__label--caps">Reference #</label>
                                <?php if (isset($component)) { $__componentOriginal5ffc033813591e85e4508e27a2ee1612 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ffc033813591e85e4508e27a2ee1612 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.input','data' => ['wire:model' => 'payment_reference','class' => 'be-input--combo','placeholder' => 'Check / Ref #']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'payment_reference','class' => 'be-input--combo','placeholder' => 'Check / Ref #']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5ffc033813591e85e4508e27a2ee1612)): ?>
<?php $attributes = $__attributesOriginal5ffc033813591e85e4508e27a2ee1612; ?>
<?php unset($__attributesOriginal5ffc033813591e85e4508e27a2ee1612); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5ffc033813591e85e4508e27a2ee1612)): ?>
<?php $component = $__componentOriginal5ffc033813591e85e4508e27a2ee1612; ?>
<?php unset($__componentOriginal5ffc033813591e85e4508e27a2ee1612); ?>
<?php endif; ?>
                            </div>
                        </div>
                        <p class="mt-1 text-[11px] text-gray-600">Leave amount blank to pay the full invoice total (<?php echo e(number_format((float) $total, 2)); ?>).</p>
                    <?php elseif($is_pending): ?>
                        <p class="mt-1 text-[11px] text-gray-600">Clear Pending before receiving payment.</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="be-invoice-actions">
                    <button type="button" class="be-btn be-btn--primary" wire:click="saveAndClose">Save &amp; Close</button>
                    <button type="button" class="be-btn be-btn--primary" wire:click="saveAndNew">Save &amp; New</button>
                    <button type="button" class="be-btn" wire:click="clearForm">Clear</button>
                </div>
            </div>
        </div>

        <button type="button" class="be-invoice-inspector-toggle" wire:click="toggleInspector" title="<?php echo e($inspectorOpen ? 'Hide panel' : 'Show panel'); ?>">
            <?php echo e($inspectorOpen ? '›' : '‹'); ?>

        </button>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inspectorOpen): ?>
            <aside class="be-invoice-inspector">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedCustomer): ?>
                    <div class="be-inspector-head">
                        <div class="be-inspector-head__id"><?php echo e($selectedCustomer->customer_number ?: '—'); ?></div>
                        <div class="be-inspector-head__name"><?php echo e($selectedCustomer->display_name); ?></div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div class="be-inspector-tabs">
                    <button type="button" class="<?php echo e($inspectorTab === 'name' ? 'is-active' : ''); ?>" wire:click="$set('inspectorTab', 'name')">Customer</button>
                    <button type="button" class="<?php echo e($inspectorTab === 'transaction' ? 'is-active' : ''); ?>" wire:click="$set('inspectorTab', 'transaction')">Transaction</button>
                    <button type="button" class="<?php echo e($inspectorTab === 'history' ? 'is-active' : ''); ?>" wire:click="$set('inspectorTab', 'history')">History</button>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inspectorTab === 'history'): ?>
                    <div class="be-inspector-section">
                        <h3>Who / When</h3>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($savedInvoice): ?>
                            <dl class="be-inspector-summary text-[11px]">
                                <div>
                                    <dt>Invoice date</dt>
                                    <dd><?php echo e($savedInvoice->invoice_date?->format('m/d/Y')); ?></dd>
                                </div>
                                <div>
                                    <dt>Created</dt>
                                    <dd><?php echo e($savedInvoice->created_at?->format('m/d/Y g:i A')); ?><br><?php echo e($savedInvoice->createdBy?->name ?? 'System'); ?></dd>
                                </div>
                                <div>
                                    <dt>Last edit</dt>
                                    <dd><?php echo e($savedInvoice->updated_at?->format('m/d/Y g:i A')); ?><br><?php echo e($savedInvoice->updatedBy?->name ?? $savedInvoice->createdBy?->name ?? 'System'); ?></dd>
                                </div>
                                <div>
                                    <dt>Status</dt>
                                    <dd><?php echo e($savedInvoice->status); ?></dd>
                                </div>
                                <div>
                                    <dt>Paid / Balance</dt>
                                    <dd><?php echo e(number_format((float) $savedInvoice->amount_paid, 2)); ?> / <?php echo e(number_format((float) $savedInvoice->balance_due, 2)); ?></dd>
                                </div>
                            </dl>
                            <h3 class="mt-3">Activity</h3>
                            <div class="space-y-2 max-h-72 overflow-auto">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $auditTrail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="border px-1.5 py-1 text-[11px]" style="border-color: var(--be-border);" wire:key="inv-audit-<?php echo e($log->id); ?>">
                                        <div class="font-semibold"><?php echo e(ucfirst($log->action)); ?> · <?php echo e($log->user?->name ?? 'System'); ?></div>
                                        <div class="text-gray-500"><?php echo e($log->created_at?->format('m/d/Y g:i A')); ?></div>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(is_array($log->new_values) && $log->new_values !== []): ?>
                                            <div class="mt-0.5 text-gray-700">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = collect($log->new_values)->only(['invoice_number','status','total','amount_paid','balance_due','customer_id','method','allocated'])->filter(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div><?php echo e(str_replace('_', ' ', $key)); ?>: <?php echo e(is_scalar($value) ? $value : json_encode($value)); ?></div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <p class="be-inspector-empty">No activity logged yet. Save the invoice to start history.</p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php else: ?>
                            <p class="be-inspector-empty">Save or open an invoice (Prev/Next) to see who created/edited it and full activity.</p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php elseif($inspectorTab === 'transaction'): ?>
                    <div class="be-inspector-section">
                        <h3>Transaction</h3>
                        <dl>
                            <div><dt>Status</dt><dd><?php echo e($is_pending ? 'Pending' : 'Open'); ?></dd></div>
                            <div><dt>Template</dt><dd><?php echo e($template); ?></dd></div>
                            <div><dt>Print later</dt><dd><?php echo e($print_later ? 'Yes' : 'No'); ?></dd></div>
                            <div><dt>Email later</dt><dd><?php echo e($email_later ? 'Yes' : 'No'); ?></dd></div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($savedInvoice): ?>
                                <div><dt>Created</dt><dd><?php echo e($savedInvoice->created_at?->format('m/d/Y g:i A')); ?> · <?php echo e($savedInvoice->createdBy?->name ?? 'System'); ?></dd></div>
                                <div><dt>Last edit</dt><dd><?php echo e($savedInvoice->updated_at?->format('m/d/Y g:i A')); ?> · <?php echo e($savedInvoice->updatedBy?->name ?? $savedInvoice->createdBy?->name ?? 'System'); ?></dd></div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </dl>
                    </div>
                <?php elseif($selectedCustomer): ?>
                    <div class="be-inspector-section">
                        <div class="be-inspector-section__title">
                            <h3>Summary</h3>
                        </div>
                        <dl class="be-inspector-summary">
                            <div>
                                <dt>Preferred delivery method</dt>
                                <dd>None</dd>
                            </div>
                            <div>
                                <dt>Open balance</dt>
                                <dd class="be-inspector-balance <?php echo e((float) $selectedCustomer->balance > 0 ? 'is-due' : ''); ?>">
                                    <?php echo e(number_format((float) $selectedCustomer->balance, 2)); ?>

                                </dd>
                            </div>
                            <div>
                                <dt>Active estimates</dt>
                                <dd>0</dd>
                            </div>
                            <div>
                                <dt>Sales Orders to be Invoiced</dt>
                                <dd>0</dd>
                            </div>
                        </dl>
                    </div>
                    <div class="be-inspector-section">
                        <div class="be-inspector-section__title">
                            <h3>Customer Payment</h3>
                        </div>
                        <p class="be-inspector-note">
                            <?php echo e($selectedCustomer->online_payment_eligible
                                ? 'This customer can pay invoices online.'
                                : "Your customer can't pay this invoice online"); ?>

                        </p>
                    </div>
                    <div class="be-inspector-section">
                        <div class="be-inspector-section__title">
                            <h3>Recent Transactions</h3>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recentInvoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="be-inspector-txn">
                                <span><?php echo e($inv->invoice_date?->format('m/d/y')); ?> Invoice</span>
                                <span class="be-inspector-txn__amt"><?php echo e(number_format((float) $inv->total, 2)); ?></span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p class="be-inspector-empty">No recent invoices.</p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="be-inspector-section">
                        <div class="be-inspector-section__title">
                            <h3>Notes</h3>
                        </div>
                        <p class="be-inspector-notes"><?php echo e($selectedCustomer->pinned_note ?: ''); ?></p>
                    </div>
                <?php else: ?>
                    <div class="be-inspector-section">
                        <h3>Summary</h3>
                        <p class="be-inspector-empty">Select a customer to see balance, recent transactions, and notes.</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </aside>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php /**PATH F:\laragon\www\bargain-enterprise\resources\views/livewire/sales/invoice-form.blade.php ENDPATH**/ ?>