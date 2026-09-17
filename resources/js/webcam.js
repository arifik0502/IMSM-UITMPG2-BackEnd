/**
 * Handles opening the webcam, capturing a selfie, and submitting it to the
 * appropriate clock-in / clock-out form. Designed to degrade gracefully if
 * the modal isn't present on the current page, or if camera access fails.
 */

const modal = document.getElementById('camera-modal');

if (modal) {
    const video = document.getElementById('camera-video');
    const preview = document.getElementById('camera-preview');
    const canvas = document.getElementById('camera-canvas');
    const title = document.getElementById('camera-modal-title');
    const errorBox = document.getElementById('camera-error');

    const captureBtn = document.getElementById('camera-capture-btn');
    const retakeBtn = document.getElementById('camera-retake-btn');
    const submitBtn = document.getElementById('camera-submit-btn');
    const closeBtn = document.getElementById('camera-modal-close');

    let stream = null;
    let currentAction = null; // 'clock-in' | 'clock-out'
    let currentWorkLocation = null; // 'office' | 'home' (only relevant for clock-in)
    let capturedDataUrl = null;

    const titles = {
        'clock-in': 'Take a selfie to clock in',
        'clock-out': 'Take a selfie to clock out',
    };

    function showError(message) {
        errorBox.textContent = message;
        errorBox.classList.remove('hidden');
    }

    function clearError() {
        errorBox.textContent = '';
        errorBox.classList.add('hidden');
    }

    function resetToLiveView() {
        capturedDataUrl = null;
        preview.classList.add('hidden');
        video.classList.remove('hidden');
        captureBtn.classList.remove('hidden');
        retakeBtn.classList.add('hidden');
        submitBtn.classList.add('hidden');
    }

    async function openModal(action, workLocation) {
        currentAction = action;
        currentWorkLocation = workLocation ?? null;

        const locationSuffix = workLocation === 'home' ? ' (Work From Home)' : workLocation === 'office' ? ' (Office)' : '';
        title.textContent = (titles[action] ?? 'Take a selfie') + locationSuffix;

        clearError();
        resetToLiveView();
        modal.classList.remove('hidden');

        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'user' },
                audio: false,
            });
            video.srcObject = stream;
        } catch (err) {
            showError('Could not access your camera. Please allow camera permissions and try again.');
        }
    }

    function stopStream() {
        if (stream) {
            stream.getTracks().forEach((track) => track.stop());
            stream = null;
        }
    }

    function closeModal() {
        stopStream();
        modal.classList.add('hidden');
        currentAction = null;
    }

    function capturePhoto() {
        if (!stream) {
            showError('Camera is not ready yet.');
            return;
        }

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        capturedDataUrl = canvas.toDataURL('image/jpeg', 0.85);
        preview.src = capturedDataUrl;

        video.classList.add('hidden');
        preview.classList.remove('hidden');
        captureBtn.classList.add('hidden');
        retakeBtn.classList.remove('hidden');
        submitBtn.classList.remove('hidden');
    }

    function submitPhoto() {
        if (!capturedDataUrl || !currentAction) {
            return;
        }

        const formId = currentAction === 'clock-in' ? 'clock-in-form' : 'clock-out-form';
        const inputId = currentAction === 'clock-in' ? 'clock-in-photo' : 'clock-out-photo';

        document.getElementById(inputId).value = capturedDataUrl;

        if (currentAction === 'clock-in' && currentWorkLocation) {
            document.getElementById('clock-in-work-location').value = currentWorkLocation;
        }

        submitBtn.disabled = true;
        submitBtn.textContent = 'Submitting...';

        document.getElementById(formId).submit();
    }

    document.querySelectorAll('[data-open-camera]').forEach((button) => {
        button.addEventListener('click', () => openModal(button.dataset.openCamera, button.dataset.workLocation));
    });

    captureBtn.addEventListener('click', capturePhoto);
    retakeBtn.addEventListener('click', resetToLiveView);
    submitBtn.addEventListener('click', submitPhoto);
    closeBtn.addEventListener('click', closeModal);

    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });
}