@extends('layouts.dashboard')

@section('title', 'AI Coding Playground - AI Study Assistant')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">AI Coding Playground 💻</h2>
            <p class="text-muted mb-0">Write code in C, C++, Java, Python, PHP, or JavaScript. AI explains, detects bugs, and optimizes performance.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Code Editor Input -->
        <div class="col-lg-6">
            <div class="card card-custom bg-white p-4 border h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <label for="language" class="form-label fw-bold text-dark mb-0">Select Language</label>
                    <select id="language" class="form-select form-select-sm w-auto">
                        <option value="python" selected>Python</option>
                        <option value="php">PHP</option>
                        <option value="javascript">JavaScript</option>
                        <option value="java">Java</option>
                        <option value="cpp">C++</option>
                        <option value="c">C</option>
                    </select>
                </div>

                <div class="mb-3">
                    <textarea id="codeEditor" rows="14" class="form-control font-monospace bg-dark text-white p-3" style="font-size: 0.9rem;" placeholder="# Write your code here...\ndef binary_search(arr, target):\n    low = 0\n    high = len(arr) - 1\n    while low <= high:\n        mid = (low + high) // 2\n        if arr[mid] == target:\n            return mid\n        elif arr[mid] < target:\n            low = mid + 1\n        else:\n            high = mid - 1\n    return -1"></textarea>
                </div>

                <button type="button" id="analyzeBtn" class="btn btn-primary-custom py-2.5 fw-bold">
                    <i class="bi bi-cpu me-1"></i> Analyze & Optimize Code with AI
                </button>
            </div>
        </div>

        <!-- AI Explanation Output -->
        <div class="col-lg-6">
            <div class="card card-custom bg-white p-4 border h-100">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-robot text-primary me-2"></i>AI Code Analysis</h5>
                <div class="p-3 bg-light rounded border font-monospace text-dark fs-7 lh-base" id="analysisOutput" style="min-height: 380px; white-space: pre-line;">
                    Click "Analyze & Optimize Code with AI" to view step-by-step logic explanations, bug detections, and test cases!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const analyzeBtn = document.getElementById('analyzeBtn');
    const codeEditor = document.getElementById('codeEditor');
    const language = document.getElementById('language');
    const analysisOutput = document.getElementById('analysisOutput');

    analyzeBtn.addEventListener('click', function() {
        analyzeBtn.disabled = true;
        analyzeBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Analyzing Code...';

        fetch('{{ route("playground.analyze") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                code: codeEditor.value,
                language: language.value
            })
        })
        .then(res => res.json())
        .then(data => {
            analyzeBtn.disabled = false;
            analyzeBtn.innerHTML = '<i class="bi bi-cpu me-1"></i> Analyze & Optimize Code with AI';
            if (data.success) {
                analysisOutput.textContent = data.analysis;
            }
        });
    });
});
</script>
@endpush
