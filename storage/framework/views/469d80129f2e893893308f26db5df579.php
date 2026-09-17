<div class="be-page be-report-center">
    <div class="be-report-center__header">
        <h1 class="be-report-center__title">Report Center</h1>
        <div class="be-report-center__search">
            <input
                type="search"
                class="be-input"
                placeholder="Search reports"
                wire:model.live.debounce.250ms="search"
                aria-label="Search reports"
            >
        </div>
    </div>

    <div class="be-report-center__tabs" role="tablist">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <button
                type="button"
                role="tab"
                class="be-report-center__tab <?php echo e($tab === $key ? 'is-active' : ''); ?>"
                wire:click="selectTab(<?php echo \Illuminate\Support\Js::from($key)->toHtml() ?>)"
                aria-selected="<?php echo e($tab === $key ? 'true' : 'false'); ?>"
            ><?php echo e($label); ?></button>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <div class="be-report-center__body">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tab === 'standard'): ?>
            <aside class="be-report-center__nav" aria-label="Report categories">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button
                        type="button"
                        class="be-report-center__nav-item <?php echo e($category === $cat['id'] ? 'is-active' : ''); ?>"
                        wire:click="selectCategory(<?php echo \Illuminate\Support\Js::from($cat['id'])->toHtml() ?>)"
                    >
                        <span><?php echo e($cat['short']); ?></span>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($category === $cat['id']): ?>
                            <span class="be-report-center__nav-arrow" aria-hidden="true">▸</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </aside>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="be-report-center__content">
            <h2 class="be-report-center__category-title">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tab === 'standard'): ?>
                    <?php echo e($activeCategory['short'] ?? $activeCategory['label']); ?>

                <?php else: ?>
                    <?php echo e($tabs[$tab]); ?>

                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </h2>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($group['title'])): ?>
                    <h3 class="be-report-center__group-title"><?php echo e($group['title']); ?></h3>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div class="be-report-center__grid">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $group['reports']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <article class="be-report-card" wire:key="report-<?php echo e($report['key']); ?>">
                            <h4 class="be-report-card__title"><?php echo e($report['label']); ?></h4>
                            <div class="be-report-card__preview">
                                <table class="be-report-card__mini">
                                    <thead>
                                        <tr>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $report['preview']['columns']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <th><?php echo e($column); ?></th>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $report['preview']['rows']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cell): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <td><?php echo e($cell); ?></td>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="be-report-card__dates">
                                <label>
                                    <span>Dates:</span>
                                    <select
                                        class="be-input be-input--sm be-report-card__dates-select"
                                        size="1"
                                        wire:change="setDatePreset(<?php echo e(\Illuminate\Support\Js::from($report['key'])); ?>, $event.target.value)"
                                    >
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $datePresetOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($value); ?>" <?php if($report['date_preset'] === $value): echo 'selected'; endif; ?>>
                                                <?php echo e($label); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </select>
                                </label>
                                <div class="be-report-card__date-range">
                                    <input
                                        type="date"
                                        class="be-input be-input--sm"
                                        value="<?php echo e($report['from']); ?>"
                                        title="<?php echo e($report['from_display']); ?>"
                                        wire:change="setCustomFrom(<?php echo e(\Illuminate\Support\Js::from($report['key'])); ?>, $event.target.value)"
                                        aria-label="From date"
                                    >
                                    <input
                                        type="date"
                                        class="be-input be-input--sm"
                                        value="<?php echo e($report['to']); ?>"
                                        title="<?php echo e($report['to_display']); ?>"
                                        wire:change="setCustomTo(<?php echo e(\Illuminate\Support\Js::from($report['key'])); ?>, $event.target.value)"
                                        aria-label="To date"
                                    >
                                </div>
                            </div>
                            <div class="be-report-card__actions">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($report['route'])): ?>
                                    <?php if (isset($component)) { $__componentOriginal5abd15ccddcad372df58dab78ed00d60 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5abd15ccddcad372df58dab78ed00d60 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.workspace-link','data' => ['route' => $report['route'],'class' => 'be-report-card__run','title' => 'Run Report','wire:click' => 'recordRecent('.e(\Illuminate\Support\Js::from($report['key'])).')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.workspace-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($report['route']),'class' => 'be-report-card__run','title' => 'Run Report','wire:click' => 'recordRecent('.e(\Illuminate\Support\Js::from($report['key'])).')']); ?>▶ <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5abd15ccddcad372df58dab78ed00d60)): ?>
<?php $attributes = $__attributesOriginal5abd15ccddcad372df58dab78ed00d60; ?>
<?php unset($__attributesOriginal5abd15ccddcad372df58dab78ed00d60); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5abd15ccddcad372df58dab78ed00d60)): ?>
<?php $component = $__componentOriginal5abd15ccddcad372df58dab78ed00d60; ?>
<?php unset($__componentOriginal5abd15ccddcad372df58dab78ed00d60); ?>
<?php endif; ?>
                                <?php else: ?>
                                    <button
                                        type="button"
                                        class="be-report-card__run"
                                        title="Unavailable"
                                        disabled
                                    >▶</button>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($report['route'])): ?>
                                    <?php if (isset($component)) { $__componentOriginal5abd15ccddcad372df58dab78ed00d60 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5abd15ccddcad372df58dab78ed00d60 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.workspace-link','data' => ['route' => $report['route'],'class' => 'be-report-card__icon','title' => 'Display Report','wire:click' => 'recordRecent('.e(\Illuminate\Support\Js::from($report['key'])).')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.workspace-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($report['route']),'class' => 'be-report-card__icon','title' => 'Display Report','wire:click' => 'recordRecent('.e(\Illuminate\Support\Js::from($report['key'])).')']); ?>🔍 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5abd15ccddcad372df58dab78ed00d60)): ?>
<?php $attributes = $__attributesOriginal5abd15ccddcad372df58dab78ed00d60; ?>
<?php unset($__attributesOriginal5abd15ccddcad372df58dab78ed00d60); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5abd15ccddcad372df58dab78ed00d60)): ?>
<?php $component = $__componentOriginal5abd15ccddcad372df58dab78ed00d60; ?>
<?php unset($__componentOriginal5abd15ccddcad372df58dab78ed00d60); ?>
<?php endif; ?>
                                <?php else: ?>
                                    <span class="be-report-card__icon" title="Preview">🔍</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <button
                                    type="button"
                                    class="be-report-card__icon be-report-card__icon--fav <?php echo e($report['is_favorite'] ? 'is-on' : ''); ?>"
                                    title="<?php echo e($report['is_favorite'] ? 'Remove Favorite' : 'Add to Favorites'); ?>"
                                    wire:click="toggleFavorite(<?php echo e(\Illuminate\Support\Js::from($report['key'])); ?>)"
                                >♥</button>
                                <button
                                    type="button"
                                    class="be-report-card__icon be-report-card__icon--help <?php echo e($report['is_memorized'] ? 'is-on' : ''); ?>"
                                    title="<?php echo e($report['is_memorized'] ? 'Forget Memorized Report' : 'Memorize Report'); ?>"
                                    wire:click="toggleMemorize(<?php echo e(\Illuminate\Support\Js::from($report['key'])); ?>)"
                                >?</button>
                            </div>
                        </article>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="be-report-center__empty-text">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tab === 'favorites'): ?>
                        No favorite reports yet. Click the heart on any Standard report card.
                    <?php elseif($tab === 'recent'): ?>
                        No recent reports yet. Run a report from Standard to populate this list.
                    <?php elseif($tab === 'memorized'): ?>
                        No memorized reports yet. Click ? on a report card to memorize it.
                    <?php else: ?>
                        No reports match your search.
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div><?php /**PATH F:\laragon\www\bargain-enterprise\resources\views\livewire\reports\report-center.blade.php ENDPATH**/ ?>