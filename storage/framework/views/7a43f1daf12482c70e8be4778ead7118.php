<div
    class="be-page be-item-page"
    x-data
    @be-focus-barcode.window="$refs.barcodeInput?.focus(); $refs.barcodeInput?.select()"
>
    <div class="be-item-dialog">
        <div class="be-item-dialog__main">
            <div class="be-item-dialog__type-row">
                <div class="be-field be-item-dialog__type">
                    <label class="be-field__label">TYPE</label>
                    <?php if (isset($component)) { $__componentOriginal847fd48de2d422593186c84a70c7291b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal847fd48de2d422593186c84a70c7291b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.select','data' => ['wire:model.live' => 'type','options' => [
                            'inventory_part' => 'Inventory Part',
                            'inventory_assembly' => 'Inventory Assembly',
                            'non_inventory' => 'Non-inventory Part',
                            'service' => 'Service',
                        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'type','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                            'inventory_part' => 'Inventory Part',
                            'inventory_assembly' => 'Inventory Assembly',
                            'non_inventory' => 'Non-inventory Part',
                            'service' => 'Service',
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
                <p class="be-item-dialog__type-help"><?php echo e($typeHelp); ?></p>
            </div>

            <div class="be-item-dialog__identity">
                <div class="be-field be-item-dialog__sku">
                    <label class="be-field__label">Item Name/Number</label>
                    <?php if (isset($component)) { $__componentOriginal5ffc033813591e85e4508e27a2ee1612 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ffc033813591e85e4508e27a2ee1612 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.input','data' => ['wire:model' => 'sku']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'sku']); ?>
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
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['sku'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="be-field__error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="be-field be-item-dialog__subitem">
                    <label class="be-field__label">Subitem of</label>
                    <div class="be-item-dialog__subitem-row">
                        <input type="checkbox" wire:model.live="isSubitem" class="be-item-dialog__check">
                        <select wire:model="parent_id" class="be-input" <?php if(! $isSubitem): echo 'disabled'; endif; ?>>
                            <option value="">—</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $parents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($parent->id); ?>"><?php echo e($parent->sku); ?> — <?php echo e($parent->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="be-field be-item-dialog__mpn">
                    <label class="be-field__label">Manufacturer's Part Number</label>
                    <?php if (isset($component)) { $__componentOriginal5ffc033813591e85e4508e27a2ee1612 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ffc033813591e85e4508e27a2ee1612 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.input','data' => ['wire:model' => 'manufacturer_part_number']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'manufacturer_part_number']); ?>
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

            <div class="be-item-dialog__barcode">
                <div class="be-scan-bar be-scan-bar--compact">
                    <label class="be-field__label mb-0 shrink-0" for="item-barcode-input">Barcode / UPC</label>
                    <input
                        id="item-barcode-input"
                        type="text"
                        x-ref="barcodeInput"
                        class="be-input be-scan-input"
                        wire:model="barcode"
                        wire:keydown.enter.prevent="acceptBarcode"
                        autocomplete="off"
                        autocorrect="off"
                        autocapitalize="off"
                        spellcheck="false"
                        inputmode="numeric"
                        placeholder="Scan with scanner or type UPC, then Enter"
                    />
                    <?php if (isset($component)) { $__componentOriginal1533802996c09e398453c7a7b321bf25 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1533802996c09e398453c7a7b321bf25 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.button','data' => ['type' => 'button','variant' => 'primary','wire:click' => 'acceptBarcode']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'primary','wire:click' => 'acceptBarcode']); ?>Accept <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $attributes = $__attributesOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__attributesOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $component = $__componentOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__componentOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal1533802996c09e398453c7a7b321bf25 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1533802996c09e398453c7a7b321bf25 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.button','data' => ['type' => 'button','wire:click' => 'useSkuAsBarcode','title' => 'Copy Item Name/Number into barcode']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','wire:click' => 'useSkuAsBarcode','title' => 'Copy Item Name/Number into barcode']); ?>Use SKU <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $attributes = $__attributesOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__attributesOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $component = $__componentOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__componentOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal1533802996c09e398453c7a7b321bf25 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1533802996c09e398453c7a7b321bf25 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.button','data' => ['type' => 'button','wire:click' => 'clearBarcode','title' => 'Clear barcode']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','wire:click' => 'clearBarcode','title' => 'Clear barcode']); ?>Clear <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $attributes = $__attributesOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__attributesOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $component = $__componentOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__componentOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['barcode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="be-field__error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <p class="be-item-dialog__barcode-hint">Scanner and keyboard both work. Leave blank to default to Item Name/Number on save.</p>
            </div>

            <div class="be-item-dialog__uom">
                <span class="be-item-dialog__uom-label">UNIT OF MEASURE</span>
                <div class="be-item-dialog__uom-row">
                    <select wire:model="unit_of_measure_id" class="be-input be-item-dialog__uom-select">
                        <option value="">—</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($unit->id); ?>"><?php echo e($unit->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                    <?php if (isset($component)) { $__componentOriginal5abd15ccddcad372df58dab78ed00d60 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5abd15ccddcad372df58dab78ed00d60 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.workspace-link','data' => ['route' => 'lookups.index','class' => 'be-btn be-item-dialog__uom-btn','title' => 'Lookups']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.workspace-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'lookups.index','class' => 'be-btn be-item-dialog__uom-btn','title' => 'Lookups']); ?>Enable… <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5abd15ccddcad372df58dab78ed00d60)): ?>
<?php $attributes = $__attributesOriginal5abd15ccddcad372df58dab78ed00d60; ?>
<?php unset($__attributesOriginal5abd15ccddcad372df58dab78ed00d60); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5abd15ccddcad372df58dab78ed00d60)): ?>
<?php $component = $__componentOriginal5abd15ccddcad372df58dab78ed00d60; ?>
<?php unset($__componentOriginal5abd15ccddcad372df58dab78ed00d60); ?>
<?php endif; ?>
                </div>
            </div>

            <div class="be-item-dialog__columns">
                <section class="be-item-dialog__col" aria-labelledby="purchase-info">
                    <h2 id="purchase-info" class="be-item-dialog__section">PURCHASE INFORMATION</h2>
                    <div class="be-field">
                        <label class="be-field__label">Description on Purchase Transactions</label>
                        <textarea wire:model="purchase_description" class="be-input be-item-dialog__desc" rows="4" spellcheck="true"></textarea>
                    </div>
                    <div class="be-field be-item-dialog__narrow">
                        <label class="be-field__label">Cost</label>
                        <?php if (isset($component)) { $__componentOriginal5ffc033813591e85e4508e27a2ee1612 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ffc033813591e85e4508e27a2ee1612 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.input','data' => ['wire:model' => 'purchase_cost','class' => 'be-item-dialog__money']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'purchase_cost','class' => 'be-item-dialog__money']); ?>
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
                        <label class="be-field__label">COGS Account</label>
                        <select wire:model="cogs_account" class="be-input">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['Cost of Goods Sold', 'Purchases']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($account); ?>" <?php if($cogs_account === $account): echo 'selected'; endif; ?>><?php echo e($account); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cogs_account && ! in_array($cogs_account, ['Cost of Goods Sold', 'Purchases'], true)): ?>
                                <option value="<?php echo e($cogs_account); ?>" selected><?php echo e($cogs_account); ?></option>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>
                    <div class="be-field">
                        <label class="be-field__label">Preferred Vendor</label>
                        <select wire:model="preferred_vendor_id" class="be-input">
                            <option value="">—</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($vendor->id); ?>"><?php echo e($vendor->display_name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>
                </section>

                <section class="be-item-dialog__col" aria-labelledby="sales-info">
                    <h2 id="sales-info" class="be-item-dialog__section">SALES INFORMATION</h2>
                    <div class="be-field">
                        <label class="be-field__label">Description on Sales Transactions</label>
                        <textarea wire:model="sales_description" class="be-input be-item-dialog__desc" rows="4" spellcheck="true"></textarea>
                    </div>
                    <div class="be-field be-item-dialog__narrow">
                        <label class="be-field__label">Sales Price</label>
                        <?php if (isset($component)) { $__componentOriginal5ffc033813591e85e4508e27a2ee1612 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ffc033813591e85e4508e27a2ee1612 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.input','data' => ['wire:model' => 'sales_price','class' => 'be-item-dialog__money']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'sales_price','class' => 'be-item-dialog__money']); ?>
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
                    <div class="be-field be-item-dialog__narrow">
                        <label class="be-field__label">Tax Code</label>
                        <select wire:model="tax_code_id" class="be-input">
                            <option value="">—</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $taxCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($code->id); ?>"><?php echo e($code->code); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>
                    <div class="be-field">
                        <label class="be-field__label">Income Account</label>
                        <select wire:model="income_account" class="be-input">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['Sales', 'Merchandise Sales']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($account); ?>" <?php if($income_account === $account): echo 'selected'; endif; ?>><?php echo e($account); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($income_account && ! in_array($income_account, ['Sales', 'Merchandise Sales'], true)): ?>
                                <option value="<?php echo e($income_account); ?>" selected><?php echo e($income_account); ?></option>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>
                </section>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($type === 'inventory_part' || $type === 'inventory_assembly'): ?>
                <section class="be-item-dialog__inventory" aria-labelledby="inventory-info">
                    <h2 id="inventory-info" class="be-item-dialog__section">INVENTORY INFORMATION</h2>
                    <div class="be-item-dialog__inventory-grid">
                        <div class="be-field">
                            <label class="be-field__label">Asset Account</label>
                            <select wire:model="asset_account" class="be-input">
                                <option value="Inventory Asset" <?php if($asset_account === 'Inventory Asset'): echo 'selected'; endif; ?>>Inventory Asset</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($asset_account && $asset_account !== 'Inventory Asset'): ?>
                                    <option value="<?php echo e($asset_account); ?>" selected><?php echo e($asset_account); ?></option>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                        </div>
                        <div class="be-field be-item-dialog__narrow">
                            <label class="be-field__label">Reorder Point (Min)</label>
                            <?php if (isset($component)) { $__componentOriginal5ffc033813591e85e4508e27a2ee1612 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ffc033813591e85e4508e27a2ee1612 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.input','data' => ['wire:model' => 'reorder_min']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'reorder_min']); ?>
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
                        <div class="be-field be-item-dialog__narrow">
                            <label class="be-field__label">Max</label>
                            <?php if (isset($component)) { $__componentOriginal5ffc033813591e85e4508e27a2ee1612 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ffc033813591e85e4508e27a2ee1612 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.input','data' => ['wire:model' => 'reorder_max']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'reorder_max']); ?>
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

                    <div class="be-item-dialog__stock">
                        <div class="be-item-dialog__stock-item">
                            <span class="be-item-dialog__stock-label">On Hand</span>
                            <span class="be-item-dialog__stock-value"><?php echo e($item ? number_format((float) $item->on_hand, 0) : '0'); ?></span>
                        </div>
                        <div class="be-item-dialog__stock-item">
                            <span class="be-item-dialog__stock-label">Average Cost</span>
                            <span class="be-item-dialog__stock-value"><?php echo e($item ? number_format((float) $item->average_cost, 2) : '0.00'); ?></span>
                        </div>
                        <div class="be-item-dialog__stock-item">
                            <span class="be-item-dialog__stock-label">On P.O.</span>
                            <span class="be-item-dialog__stock-value"><?php echo e($item ? number_format((float) $item->on_po_qty, 0) : '0'); ?></span>
                        </div>
                        <div class="be-item-dialog__stock-item">
                            <span class="be-item-dialog__stock-label">On Sales Order</span>
                            <span class="be-item-dialog__stock-value"><?php echo e($item ? number_format((float) $item->on_so_qty, 0) : '0'); ?></span>
                        </div>
                    </div>
                </section>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <aside class="be-item-dialog__actions">
            <?php if (isset($component)) { $__componentOriginal1533802996c09e398453c7a7b321bf25 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1533802996c09e398453c7a7b321bf25 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.button','data' => ['variant' => 'primary','type' => 'button','wire:click' => 'save','class' => 'be-item-dialog__ok']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary','type' => 'button','wire:click' => 'save','class' => 'be-item-dialog__ok']); ?>OK <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $attributes = $__attributesOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__attributesOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $component = $__componentOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__componentOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal5abd15ccddcad372df58dab78ed00d60 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5abd15ccddcad372df58dab78ed00d60 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.workspace-link','data' => ['route' => 'items.index','class' => 'be-btn be-item-dialog__side-btn']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.workspace-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'items.index','class' => 'be-btn be-item-dialog__side-btn']); ?>Cancel <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5abd15ccddcad372df58dab78ed00d60)): ?>
<?php $attributes = $__attributesOriginal5abd15ccddcad372df58dab78ed00d60; ?>
<?php unset($__attributesOriginal5abd15ccddcad372df58dab78ed00d60); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5abd15ccddcad372df58dab78ed00d60)): ?>
<?php $component = $__componentOriginal5abd15ccddcad372df58dab78ed00d60; ?>
<?php unset($__componentOriginal5abd15ccddcad372df58dab78ed00d60); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal1533802996c09e398453c7a7b321bf25 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1533802996c09e398453c7a7b321bf25 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.button','data' => ['type' => 'button','wire:click' => 'openNotes','class' => 'be-item-dialog__side-btn']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','wire:click' => 'openNotes','class' => 'be-item-dialog__side-btn']); ?>
                <?php echo e($noteCount > 0 ? 'Notes ('.$noteCount.')' : 'New Note'); ?>

             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $attributes = $__attributesOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__attributesOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $component = $__componentOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__componentOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal1533802996c09e398453c7a7b321bf25 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1533802996c09e398453c7a7b321bf25 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.button','data' => ['type' => 'button','wire:click' => 'openCustomFields','class' => 'be-item-dialog__side-btn']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','wire:click' => 'openCustomFields','class' => 'be-item-dialog__side-btn']); ?>Custom Fields <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $attributes = $__attributesOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__attributesOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $component = $__componentOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__componentOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal1533802996c09e398453c7a7b321bf25 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1533802996c09e398453c7a7b321bf25 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.button','data' => ['type' => 'button','wire:click' => 'checkSpelling','class' => 'be-item-dialog__side-btn']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','wire:click' => 'checkSpelling','class' => 'be-item-dialog__side-btn']); ?>Spelling <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $attributes = $__attributesOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__attributesOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $component = $__componentOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__componentOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>

            <label class="be-item-dialog__inactive">
                <input type="checkbox" wire:model.live="itemInactive">
                Item is inactive
            </label>
        </aside>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showNotes): ?>
        <div class="be-modal" role="dialog" aria-modal="true" aria-labelledby="item-notes-title">
            <div class="be-modal__backdrop" wire:click="closeNotes"></div>
            <div class="be-modal__panel be-modal__panel--md" @click.stop>
                <div class="be-modal__header">
                    <h2 id="item-notes-title" class="be-modal__title">Item Notes</h2>
                    <button type="button" class="be-modal__close" wire:click="closeNotes" aria-label="Close">&times;</button>
                </div>
                <div class="be-modal__body">
                    <div class="be-field mb-3">
                        <label class="be-field__label"><?php echo e($editingNoteId ? 'Edit Note' : 'New Note'); ?></label>
                        <textarea wire:model="noteBody" class="be-input" rows="4" spellcheck="true" placeholder="Type a note…"></textarea>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['noteBody'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="be-field__error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="mb-3 flex gap-2">
                        <?php if (isset($component)) { $__componentOriginal1533802996c09e398453c7a7b321bf25 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1533802996c09e398453c7a7b321bf25 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.button','data' => ['type' => 'button','variant' => 'primary','wire:click' => 'saveNote']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'primary','wire:click' => 'saveNote']); ?><?php echo e($editingNoteId ? 'Update Note' : 'Add Note'); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $attributes = $__attributesOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__attributesOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $component = $__componentOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__componentOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($editingNoteId): ?>
                            <?php if (isset($component)) { $__componentOriginal1533802996c09e398453c7a7b321bf25 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1533802996c09e398453c7a7b321bf25 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.button','data' => ['type' => 'button','wire:click' => 'cancelNoteEdit']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','wire:click' => 'cancelNoteEdit']); ?>Cancel Edit <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $attributes = $__attributesOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__attributesOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $component = $__componentOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__componentOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="space-y-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $notes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="border px-2 py-1.5" style="border-color: var(--be-border);" wire:key="item-note-<?php echo e($note->id); ?>">
                                <div class="mb-1 flex items-center justify-between gap-2 text-[11px] text-gray-500">
                                    <span><?php echo e($note->user?->name ?? 'System'); ?> · <?php echo e($note->created_at?->format('m/d/y g:i A')); ?></span>
                                    <span class="flex gap-1">
                                        <button type="button" class="be-link-btn" wire:click="editNote(<?php echo e($note->id); ?>)">Edit</button>
                                        <button type="button" class="be-link-btn" wire:click="deleteNote(<?php echo e($note->id); ?>)" wire:confirm="Delete this note?">Delete</button>
                                    </span>
                                </div>
                                <div class="whitespace-pre-wrap text-[12px]"><?php echo e($note->body); ?></div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p class="text-[12px] text-gray-500">No notes yet.</p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
                <div class="be-modal__footer">
                    <?php if (isset($component)) { $__componentOriginal1533802996c09e398453c7a7b321bf25 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1533802996c09e398453c7a7b321bf25 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.button','data' => ['type' => 'button','wire:click' => 'closeNotes']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','wire:click' => 'closeNotes']); ?>Close <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $attributes = $__attributesOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__attributesOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $component = $__componentOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__componentOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showSpelling): ?>
        <div class="be-modal" role="dialog" aria-modal="true" aria-labelledby="item-spelling-title">
            <div class="be-modal__backdrop" wire:click="closeSpelling"></div>
            <div class="be-modal__panel be-modal__panel--md" @click.stop>
                <div class="be-modal__header">
                    <h2 id="item-spelling-title" class="be-modal__title">Spelling &amp; Formatting</h2>
                    <button type="button" class="be-modal__close" wire:click="closeSpelling" aria-label="Close">&times;</button>
                </div>
                <div class="be-modal__body">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($spellingIssues === []): ?>
                        <p class="text-[12px] text-gray-700">No issues found in name, descriptions, promotion, or MPN.</p>
                    <?php else: ?>
                        <ul class="space-y-2 text-[12px]">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $spellingIssues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $issue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="border px-2 py-1.5" style="border-color: var(--be-border);">
                                    <div class="font-semibold"><?php echo e($issue['label']); ?> — <?php echo e($issue['issue']); ?></div>
                                    <div class="text-red-700 line-through"><?php echo e($issue['original']); ?></div>
                                    <div class="text-green-700"><?php echo e($issue['suggestion']); ?></div>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </ul>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="be-modal__footer">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($spellingIssues !== []): ?>
                        <?php if (isset($component)) { $__componentOriginal1533802996c09e398453c7a7b321bf25 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1533802996c09e398453c7a7b321bf25 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.button','data' => ['type' => 'button','variant' => 'primary','wire:click' => 'applySpellingFixes']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'primary','wire:click' => 'applySpellingFixes']); ?>Apply Fixes <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $attributes = $__attributesOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__attributesOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $component = $__componentOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__componentOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal1533802996c09e398453c7a7b321bf25 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1533802996c09e398453c7a7b321bf25 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.button','data' => ['type' => 'button','wire:click' => 'closeSpelling']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','wire:click' => 'closeSpelling']); ?>Close <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $attributes = $__attributesOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__attributesOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $component = $__componentOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__componentOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showCustomFields): ?>
        <div class="be-modal" role="dialog" aria-modal="true" aria-labelledby="item-custom-fields-title">
            <div class="be-modal__backdrop" wire:click="closeCustomFields"></div>
            <div class="be-modal__panel be-modal__panel--md" @click.stop>
                <div class="be-modal__header">
                    <h2 id="item-custom-fields-title" class="be-modal__title">Custom Fields</h2>
                    <button type="button" class="be-modal__close" wire:click="closeCustomFields" aria-label="Close">&times;</button>
                </div>
                <div class="be-modal__body">
                    <p class="mb-3 text-[11px] text-gray-600">Tobacco / vape and other item attributes.</p>
                    <div class="be-form-grid">
                        <div class="be-field">
                            <label class="be-field__label">Category Code</label>
                            <select wire:model="item_category_id" class="be-input">
                                <option value="">—</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category->id); ?>"><?php echo e($category->code); ?> — <?php echo e($category->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                        </div>
                        <div class="be-field">
                            <label class="be-field__label">Item Type</label>
                            <select wire:model="item_type_id" class="be-input">
                                <option value="">—</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $itemTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($itemType->id); ?>"><?php echo e($itemType->label); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                        </div>
                        <div class="be-field">
                            <label class="be-field__label">Items Per Container</label>
                            <?php if (isset($component)) { $__componentOriginal5ffc033813591e85e4508e27a2ee1612 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ffc033813591e85e4508e27a2ee1612 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.input','data' => ['wire:model' => 'items_per_container']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'items_per_container']); ?>
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
                            <label class="be-field__label">Promotion</label>
                            <?php if (isset($component)) { $__componentOriginal5ffc033813591e85e4508e27a2ee1612 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ffc033813591e85e4508e27a2ee1612 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.input','data' => ['wire:model' => 'promotion']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'promotion']); ?>
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
                        <div class="be-field md:col-span-2">
                            <label class="be-field__label">Name / Title</label>
                            <?php if (isset($component)) { $__componentOriginal5ffc033813591e85e4508e27a2ee1612 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ffc033813591e85e4508e27a2ee1612 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.input','data' => ['wire:model' => 'name','placeholder' => 'Defaults to sales description or SKU']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'name','placeholder' => 'Defaults to sales description or SKU']); ?>
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
                </div>
                <div class="be-modal__footer">
                    <?php if (isset($component)) { $__componentOriginal1533802996c09e398453c7a7b321bf25 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1533802996c09e398453c7a7b321bf25 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.button','data' => ['type' => 'button','variant' => 'primary','wire:click' => 'closeCustomFields']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'primary','wire:click' => 'closeCustomFields']); ?>OK <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $attributes = $__attributesOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__attributesOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $component = $__componentOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__componentOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal1533802996c09e398453c7a7b321bf25 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1533802996c09e398453c7a7b321bf25 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.button','data' => ['type' => 'button','wire:click' => 'closeCustomFields']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','wire:click' => 'closeCustomFields']); ?>Cancel <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $attributes = $__attributesOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__attributesOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $component = $__componentOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__componentOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div><?php /**PATH F:\laragon\www\bargain-enterprise\resources\views\livewire\items\item-form.blade.php ENDPATH**/ ?>