<div
    x-data="{ toast: { show: false, message: '' } }"
    x-on:be-toast.window="toast.message = ($event.detail && $event.detail.message) || 'Saved'; toast.show = true; clearTimeout(window.__beToastTimer); window.__beToastTimer = setTimeout(() => { toast.show = false }, 2500)"
>
    <div x-show="toast.show" x-cloak class="be-toast" role="status" @click="toast.show = false">
        <span x-text="toast.message"></span>
    </div>
</div>
<script>
    window.addEventListener('be-toast', (event) => {
        if (window.beWorkspace) {
            window.beWorkspace.setDirty(false);
        }
        const message = (event.detail && event.detail.message) || 'Saved';
        if (window.parent && window.parent !== window) {
            window.parent.postMessage({ type: 'be-toast', message }, '*');
        }
    });
    window.addEventListener('be-company-saved', (event) => {
        const name = event.detail && event.detail.name;
        if (! name || ! window.parent || window.parent === window) {
            return;
        }
        window.parent.postMessage({ type: 'be-company-name', name }, '*');
    });
    document.addEventListener('keydown', (event) => {
        if (! (event.ctrlKey || event.metaKey) || event.key.toLowerCase() !== 's') {
            return;
        }
        const save = document.querySelector('[wire\\:click="save"]');
        if (! save) {
            return;
        }
        event.preventDefault();
        save.click();
    });
</script>
