@extends('layouts.dashboard')

@section('title', 'Professional AI Whiteboard - AI Study Assistant')

@push('styles')
<style>
/* Reset container padding if necessary to maximize space */
.whiteboard-container {
    height: calc(100vh - 140px); /* Adjust based on your navbar height */
    position: relative;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    overflow: hidden;
    background-color: #fff;
}

#excalidraw-wrapper {
    width: 100%;
    height: 100%;
}

/* Custom AI Toolbar Overlay */
.ai-toolbar {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border-radius: 50px;
    padding: 10px 20px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    z-index: 10;
    display: flex;
    gap: 12px;
    align-items: center;
    border: 1px solid rgba(255,255,255,0.4);
}

.ai-btn {
    border-radius: 30px;
    font-weight: 600;
    font-size: 0.85rem;
    padding: 8px 16px;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
}

.ai-btn:hover {
    transform: translateY(-2px);
}
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Professional AI Whiteboard 🎨</h2>
            <p class="text-muted mb-0">Infinite canvas, flowcharts, mind maps, and AI-powered diagram conversions.</p>
        </div>
        <div>
            <button class="btn btn-outline-secondary" id="loadBtn">
                <i class="bi bi-clock-history me-1"></i> History
            </button>
            <button class="btn btn-primary-custom ms-2" id="saveDbBtn">
                <i class="bi bi-cloud-arrow-up me-1"></i> Save to Cloud
            </button>
        </div>
    </div>

    <!-- AI Output Modal -->
    <div class="modal fade" id="aiResultModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold" id="aiModalTitle"><i class="bi bi-stars text-warning me-2"></i> AI Generation Result</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" id="aiModalBody">
                    <div class="text-center py-5" id="aiLoadingState" style="display: none;">
                        <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;"></div>
                        <h5 class="text-muted">Analyzing your drawing with Gemini AI...</h5>
                        <p class="fs-7 text-secondary">This might take a few seconds depending on diagram complexity.</p>
                    </div>
                    <div id="aiContentState"></div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="whiteboard-container shadow-sm">
        <!-- Skeleton Loading State -->
        <div id="skeletonLoading" class="w-100 h-100 d-flex align-items-center justify-content-center bg-light placeholder-glow position-absolute top-0 start-0 z-3">
            <div class="text-center">
                <div class="spinner-border text-secondary mb-3" role="status"></div>
                <div class="text-muted fw-bold">Loading Professional Whiteboard Engine...</div>
            </div>
        </div>

        <div id="excalidraw-wrapper"></div>

        <div class="ai-toolbar" id="aiToolbar" style="display: none;">
            <button class="btn btn-light border ai-btn shadow-sm text-dark" onclick="processAI('explain')">
                <i class="bi bi-chat-right-text text-primary"></i> Explain Drawing
            </button>
            <button class="btn btn-light border ai-btn shadow-sm text-dark" onclick="processAI('notes')">
                <i class="bi bi-file-earmark-text text-info"></i> Convert to Notes
            </button>
            <button class="btn btn-light border ai-btn shadow-sm text-dark" onclick="processAI('markdown')">
                <i class="bi bi-markdown text-dark"></i> to Markdown
            </button>
            <button class="btn btn-light border ai-btn shadow-sm text-dark" onclick="processAI('flashcards')">
                <i class="bi bi-card-heading text-success"></i> to Flashcards
            </button>
            <button class="btn btn-light border ai-btn shadow-sm text-dark" onclick="processAI('quiz')">
                <i class="bi bi-question-circle text-danger"></i> to Quiz
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- React & ReactDOM (Required for Excalidraw) -->
<script src="https://unpkg.com/react@18.2.0/umd/react.production.min.js"></script>
<script src="https://unpkg.com/react-dom@18.2.0/umd/react-dom.production.min.js"></script>
<!-- Excalidraw Standalone -->
<script src="https://unpkg.com/@excalidraw/excalidraw@0.17.3/dist/excalidraw.production.min.js"></script>

<script>
let excalidrawAPI = null;

document.addEventListener('DOMContentLoaded', function() {
    // Hide skeleton and show toolbar after a short delay to ensure React renders
    setTimeout(() => {
        document.getElementById('skeletonLoading').classList.add('d-none');
        document.getElementById('aiToolbar').style.display = 'flex';
    }, 1500);

    const App = () => {
        return React.createElement(
            React.Fragment,
            null,
            React.createElement(
                "div",
                { style: { height: "100%", width: "100%" } },
                React.createElement(ExcalidrawLib.Excalidraw, {
                    initialData: {
                        elements: JSON.parse(localStorage.getItem('excalidraw-elements') || '[]'),
                        appState: JSON.parse(localStorage.getItem('excalidraw-state') || '{}')
                    },
                    onChange: (elements, state) => {
                        localStorage.setItem('excalidraw-elements', JSON.stringify(elements));
                    },
                    excalidrawAPI: (api) => {
                        excalidrawAPI = api;
                    },
                    theme: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'dark' : 'light'
                })
            )
        );
    };

    const root = ReactDOM.createRoot(document.getElementById("excalidraw-wrapper"));
    root.render(React.createElement(App));
});

window.processAI = async function(actionType) {
    if(!excalidrawAPI) return;
    
    const elements = excalidrawAPI.getSceneElements();
    if (elements.length === 0) {
        alert("The whiteboard is empty. Draw something first!");
        return;
    }

    // Show Modal Loading
    const modal = new bootstrap.Modal(document.getElementById('aiResultModal'));
    document.getElementById('aiLoadingState').style.display = 'block';
    document.getElementById('aiContentState').innerHTML = '';
    
    // Set Title based on action
    const titles = {
        'explain': 'AI Diagram Explanation',
        'notes': 'Study Notes from Diagram',
        'markdown': 'Markdown Conversion',
        'flashcards': 'AI Smart Flashcards',
        'quiz': 'Interactive Diagram Quiz'
    };
    document.getElementById('aiModalTitle').innerHTML = `<i class="bi bi-stars text-warning me-2"></i> ${titles[actionType]}`;
    modal.show();

    try {
        // Export to SVG/PNG base64
        const canvas = await ExcalidrawLib.exportToCanvas({
            elements,
            appState: excalidrawAPI.getAppState(),
            files: excalidrawAPI.getFiles(),
            exportPadding: 20,
        });

        const base64Data = canvas.toDataURL("image/png");

        const response = await fetch("{{ route('whiteboard.ai-process') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ image: base64Data, action: actionType })
        });

        const result = await response.json();
        
        document.getElementById('aiLoadingState').style.display = 'none';
        
        if(response.ok) {
            if(actionType === 'explain' || actionType === 'notes' || actionType === 'markdown') {
                document.getElementById('aiContentState').innerHTML = `<div class="p-3 bg-light rounded border markdown-body">${result.content.replace(/\n/g, '<br>')}</div>`;
            } else if (actionType === 'flashcards' || actionType === 'quiz') {
                // If it's structured JSON
                let html = '<ul class="list-group list-group-flush">';
                if(result.items && Array.isArray(result.items)) {
                    result.items.forEach(item => {
                        html += `<li class="list-group-item bg-transparent"><strong>Q:</strong> ${item.q || item.question}<br><span class="text-muted"><strong>A:</strong> ${item.a || item.answer || item.correct_answer}</span></li>`;
                    });
                } else {
                    html += `<div class="p-3 bg-light rounded border">${result.content.replace(/\n/g, '<br>')}</div>`;
                }
                html += '</ul>';
                document.getElementById('aiContentState').innerHTML = html;
            }
        } else {
            document.getElementById('aiContentState').innerHTML = `<div class="alert alert-danger">Error: ${result.message || 'Failed to process image.'}</div>`;
        }

    } catch (e) {
        console.error(e);
        document.getElementById('aiLoadingState').style.display = 'none';
        document.getElementById('aiContentState').innerHTML = `<div class="alert alert-danger">An unexpected error occurred: ${e.message}</div>`;
    }
}
</script>
@endpush
