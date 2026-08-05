@extends('layouts.dashboard')

@section('title', 'AI Tutor & Learning Tools - AI Study Assistant')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Interactive AI Tutor & Learning Suite</h2>
            <p class="text-muted mb-0">Ask questions, simplify complex topics, generate mind maps, or test your viva readiness.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 fs-7 rounded-pill">
                <i class="bi bi-cpu me-1"></i> Tokens Used: {{ number_format($totalTokens) }}
            </span>
        </div>
    </div>

    <!-- AI Tool Selector Pills -->
    <div class="card card-custom p-3 bg-white mb-4 border">
        <div class="d-flex flex-wrap gap-2" id="promptTypeContainer">
            <button type="button" class="btn btn-primary-custom btn-sm rounded-pill tool-pill active" data-type="chat">
                <i class="bi bi-chat-left-dots me-1"></i> General AI Chat
            </button>
            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill tool-pill" data-type="beginner">
                <i class="bi bi-emoji-smile me-1"></i> Explain Like Beginner
            </button>
            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill tool-pill" data-type="teacher">
                <i class="bi bi-mortarboard me-1"></i> Professor Level
            </button>
            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill tool-pill" data-type="rewrite">
                <i class="bi bi-pencil-square me-1"></i> Grammar & Rewrite
            </button>
            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill tool-pill" data-type="mindmap">
                <i class="bi bi-diagram-3 me-1"></i> Generate Mind Map
            </button>
            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill tool-pill" data-type="viva">
                <i class="bi bi-patch-question me-1"></i> Viva Questions
            </button>
            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill tool-pill" data-type="exercises">
                <i class="bi bi-list-task me-1"></i> Practice Exercises
            </button>
        </div>
    </div>

    <div class="row g-4">
        <!-- Input & Response Workspace -->
        <div class="col-lg-7">
            <div class="card card-custom bg-white p-4 border mb-4">
                <form id="tutorForm" method="POST" action="{{ route('tutor.ask') }}">
                    @csrf
                    <input type="hidden" name="prompt_type" id="selectedPromptType" value="chat">

                    <div class="mb-3">
                        <label for="promptInput" class="form-label fw-bold text-dark" id="promptLabel">Ask AI Tutor a Question</label>
                        <textarea name="prompt" id="promptInput" rows="4" class="form-control" placeholder="Type your question or paste text to rewrite..." required></textarea>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted fs-8"><i class="bi bi-info-circle me-1"></i>Press Submit or Shift+Enter to send</small>
                        <button type="submit" id="sendBtn" class="btn btn-primary-custom px-4 py-2">
                            <i class="bi bi-send-fill me-1"></i> Ask AI Tutor
                        </button>
                    </div>
                </form>
            </div>

            <!-- Latest Response Card -->
            <div class="card card-custom bg-white p-4 border d-none" id="responseCard">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <span class="badge bg-primary text-white rounded-pill px-3 py-1 fs-8" id="responseTag">AI Tutor</span>
                    <div class="d-flex gap-2">
                        <button type="button" id="copyBtn" class="btn btn-light btn-sm border text-secondary" title="Copy Response">
                            <i class="bi bi-clipboard me-1"></i> Copy
                        </button>
                        <button type="button" id="speakBtn" class="btn btn-light btn-sm border text-secondary" title="Speech Output">
                            <i class="bi bi-volume-up me-1"></i> Listen
                        </button>
                    </div>
                </div>

                <div class="lh-base text-dark fs-6 font-monospace" id="responseText" style="white-space: pre-line;"></div>
            </div>
        </div>

        <!-- History Sidebar -->
        <div class="col-lg-5">
            <div class="card card-custom bg-white p-4 border h-100">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-clock-history text-primary me-2"></i>Recent AI Conversations</h5>

                <div class="d-flex flex-column gap-3" id="historyList">
                    @forelse($history as $item)
                        <div class="p-3 bg-light rounded border">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle text-uppercase fs-8">
                                    {{ $item->prompt_type }}
                                </span>
                                <small class="text-muted fs-8">{{ $item->created_at->diffForHumans() }}</small>
                            </div>
                            <h6 class="fw-bold text-dark mb-1 fs-7">{{ $item->title }}</h6>
                            <p class="text-muted fs-8 mb-0">{{ Str::limit($item->response, 90) }}</p>
                        </div>
                    @empty
                        <div class="p-4 text-center text-muted fs-7">
                            No recent AI tutor conversations. Ask a question to get started!
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const promptPills = document.querySelectorAll('.tool-pill');
    const selectedPromptType = document.getElementById('selectedPromptType');
    const promptLabel = document.getElementById('promptLabel');
    const tutorForm = document.getElementById('tutorForm');
    const responseCard = document.getElementById('responseCard');
    const responseText = document.getElementById('responseText');
    const responseTag = document.getElementById('responseTag');
    const sendBtn = document.getElementById('sendBtn');
    const copyBtn = document.getElementById('copyBtn');
    const speakBtn = document.getElementById('speakBtn');

    const promptPlaceholders = {
        'chat': 'Ask any study question or request an explanation...',
        'beginner': 'Enter a complex concept to explain in simple everyday terms...',
        'teacher': 'Enter a topic to generate a university-level lecture overview...',
        'rewrite': 'Paste text here to proofread, fix grammar, and improve phrasing...',
        'mindmap': 'Enter a subject to build a structured hierarchical ASCII mind map...',
        'viva': 'Enter course topic to generate 5 oral examination (viva) questions with answers...',
        'exercises': 'Enter a chapter name to generate practice study problems with solutions...'
    };

    promptPills.forEach(pill => {
        pill.addEventListener('click', function() {
            promptPills.forEach(p => {
                p.classList.remove('btn-primary-custom', 'text-white');
                p.classList.add('btn-outline-primary');
            });
            this.classList.remove('btn-outline-primary');
            this.classList.add('btn-primary-custom', 'text-white');

            const type = this.getAttribute('data-type');
            selectedPromptType.value = type;
            document.getElementById('promptInput').placeholder = promptPlaceholders[type] || 'Type your question...';
        });
    });

    tutorForm.addEventListener('submit', function(e) {
        e.preventDefault();
        sendBtn.disabled = true;
        sendBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Thinking...';

        fetch('{{ route("tutor.ask") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                prompt: document.getElementById('promptInput').value,
                prompt_type: selectedPromptType.value
            })
        })
        .then(res => res.json())
        .then(data => {
            sendBtn.disabled = false;
            sendBtn.innerHTML = '<i class="bi bi-send-fill me-1"></i> Ask AI Tutor';

            if (data.success) {
                responseCard.classList.remove('d-none');
                responseTag.textContent = data.conversation.prompt_type.toUpperCase();
                
                // Typing animation effect
                let text = data.conversation.response;
                responseText.textContent = '';
                let index = 0;

                function typeEffect() {
                    if (index < text.length) {
                        responseText.textContent += text.charAt(index);
                        index++;
                        setTimeout(typeEffect, 8);
                    }
                }
                typeEffect();
            }
        });
    });

    // Copy to clipboard
    copyBtn.addEventListener('click', function() {
        navigator.clipboard.writeText(responseText.textContent);
        this.innerHTML = '<i class="bi bi-check-lg me-1"></i> Copied!';
        setTimeout(() => this.innerHTML = '<i class="bi bi-clipboard me-1"></i> Copy', 2000);
    });

    // Web Speech Synthesis Output
    speakBtn.addEventListener('click', function() {
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(responseText.textContent);
            window.speechSynthesis.speak(utterance);
        }
    });
});
</script>
@endpush
