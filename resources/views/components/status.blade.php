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
