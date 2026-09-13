{{-- Hidden forms that actually submit the captured selfie --}}
<form id="clock-in-form" method="POST" action="{{ route('attendance.clock-in') }}" class="hidden">
    @csrf
    <input type="hidden" name="photo" id="clock-in-photo">
    <input type="hidden" name="work_location" id="clock-in-work-location">
</form>

<form id="clock-out-form" method="POST" action="{{ route('attendance.clock-out') }}" class="hidden">
    @csrf
    <input type="hidden" name="photo" id="clock-out-photo">
</form>

{{-- Camera modal --}}
<div id="camera-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
        <div class="card w-full max-w-md">
        <div class="flex items-center justify-between mb-4">
                     <h3 id="camera-modal-title" class="font-bold uppercase tracking-wide text-gray-900">Take a selfie to clock in</h3>
            <button type="button" id="camera-modal-close" class="text-gray-400 hover:text-gray-600" aria-label="Close">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div id="camera-error" class="hidden mb-3 text-sm text-red-600"></div>

        <div class="relative rounded-lg overflow-hidden bg-gray-900 aspect-video">
            <video id="camera-video" class="w-full h-full object-cover" autoplay playsinline muted></video>
            <img id="camera-preview" class="w-full h-full object-cover hidden" alt="Captured selfie preview">
        </div>
        <canvas id="camera-canvas" class="hidden"></canvas>

        <div class="mt-4 flex gap-3">
            <button type="button" id="camera-capture-btn" class="btn-primary flex-1">Capture</button>
            <button type="button" id="camera-retake-btn" class="btn-secondary hidden flex-1">Retake</button>
            <button type="button" id="camera-submit-btn" class="btn-primary hidden flex-1">Confirm &amp; Submit</button>
        </div>
    </div>
</div>
