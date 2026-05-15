@if(session('status'))
    <div id="status" class="status-info">
        <p>{{ session('status') }}</p>
    </div>
    <script>
        const status = document.getElementById('status');
        status.style.display = 'block';
        requestAnimationFrame(() => {
            requestAnimationFrame(() => status.classList.add('visible'));
        });
        setTimeout(() => {
            status.classList.remove('visible');
            status.addEventListener('transitionend', () => status.remove(), { once: true });
        }, 3500);
    </script>
@endif

@if(session('error'))
    <div id="status-error" class="status-info" style="background:#c0392b; border-left-color:#e74c3c;">
        <p>{{ session('error') }}</p>
    </div>
    <script>
        const statusError = document.getElementById('status-error');
        statusError.style.display = 'block';
        requestAnimationFrame(() => {
            requestAnimationFrame(() => statusError.classList.add('visible'));
        });
        setTimeout(() => {
            statusError.classList.remove('visible');
            statusError.addEventListener('transitionend', () => statusError.remove(), { once: true });
        }, 3500);
    </script>
@endif
