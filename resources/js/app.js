document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('sera-form');
    if (!form) {
        return;
    }

    const statusArea = document.getElementById('status-area');
    const statusText = document.getElementById('status-text');
    const timerEl = document.getElementById('timer');
    const resultsArea = document.getElementById('results-area');
    const formArea = document.getElementById('form-area');
    const submitBtn = document.getElementById('submit-btn');

    let seconds = 0;
    let minutes = 0;
    let timerId = null;

    const pad = (value) => String(value).padStart(2, '0');

    const updateTimer = () => {
        seconds += 1;
        if (seconds === 60) {
            seconds = 0;
            minutes += 1;
        }
        timerEl.textContent = `${pad(minutes)} : ${pad(seconds)}`;
    };

    const startTimer = () => {
        seconds = 0;
        minutes = 0;
        timerEl.textContent = '00 : 00';
        timerId = window.setInterval(updateTimer, 1000);
    };

    const stopTimer = () => {
        if (timerId !== null) {
            window.clearInterval(timerId);
            timerId = null;
        }
    };

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        submitBtn.disabled = true;
        formArea.classList.add('hidden');
        statusArea.classList.remove('hidden');
        resultsArea.innerHTML = '';
        statusText.textContent = 'Performing requested search…';
        document.title = 'Performing requested search';
        startTimer();

        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: formData,
            });

            stopTimer();
            const elapsed = timerEl.textContent;

            if (!response.ok) {
                throw new Error('Report generation failed.');
            }

            const html = await response.text();
            statusArea.classList.add('hidden');
            formArea.classList.remove('hidden');
            resultsArea.innerHTML = `<div class="sera-results-header"><p>Total search time: ${elapsed}</p></div>${html}`;
            document.title = 'Report Generated';
        } catch (error) {
            stopTimer();
            statusArea.classList.add('hidden');
            formArea.classList.remove('hidden');
            resultsArea.innerHTML = `<p class="sera-error">${error.message}</p>`;
            document.title = 'CM Sera Tool';
        } finally {
            submitBtn.disabled = false;
        }
    });
});
