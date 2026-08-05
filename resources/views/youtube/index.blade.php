@extends('layouts.dashboard')

@section('title', 'YouTube AI Learning Workspace')

@push('styles')
<style>
.yt-workspace {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}
@media (min-width: 992px) {
    .yt-workspace {
        flex-direction: row;
        height: calc(100vh - 120px);
        overflow: hidden;
    }
    .yt-player-col {
        flex: 0 0 50%;
        display: flex;
        flex-direction: column;
        overflow-y: auto;
        padding-right: 1rem;
    }
    .yt-action-col {
        flex: 0 0 50%;
        display: flex;
        flex-direction: column;
        overflow-y: auto;
        background: #f8fafc;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 1.5rem;
    }
}
.video-container {
    position: relative;
    padding-bottom: 56.25%; /* 16:9 */
    height: 0;
    overflow: hidden;
    border-radius: 12px;
    background: #000;
}
.video-container iframe {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
}
.gen-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 10px;
}
.gen-btn {
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 600;
    text-align: left;
    padding: 12px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    transition: all 0.2s ease;
    border: 1px solid #e2e8f0;
    background: #fff;
    color: #334155;
}
.gen-btn:hover {
    border-color: #3b82f6;
    background: #eff6ff;
    transform: translateY(-2px);
}
.gen-btn i {
    font-size: 1.25rem;
}
.output-box {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1.5rem;
    min-height: 300px;
}
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    
    <!-- Input Section -->
    <div id="urlInputSection" class="card card-custom p-4 border mb-4">
        <h4 class="fw-bold mb-3"><i class="bi bi-youtube text-danger me-2"></i>YouTube AI Learning Workspace</h4>
        <form id="analyzeForm" onsubmit="analyzeVideo(event)">
            <div class="input-group input-group-lg">
                <input type="url" id="ytUrl" class="form-control" placeholder="Paste YouTube URL here..." required>
                <button type="submit" class="btn btn-primary-custom fw-bold px-4">
                    Analyze <i class="bi bi-arrow-right ms-1"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Workspace Section (Hidden Initially) -->
    <div id="workspaceSection" class="yt-workspace d-none">
        
        <!-- Left: Player & Metadata -->
        <div class="yt-player-col">
            <div class="video-container shadow-sm mb-3" id="playerContainer">
                <!-- iframe will be injected here -->
            </div>
            
            <div class="card card-custom p-3 border mb-3">
                <div class="d-flex align-items-center gap-3">
                    <img id="metaThumbnail" src="" class="rounded" style="width: 120px; object-fit: cover;">
                    <div>
                        <h5 class="fw-bold mb-1" id="metaTitle">Loading...</h5>
                        <p class="text-muted mb-0 small">
                            <i class="bi bi-person-video2"></i> <span id="metaChannel">...</span> • 
                            <i class="bi bi-clock"></i> <span id="metaDuration">...</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Transcript Preview / Export Tools -->
            <div class="d-flex gap-2 mb-3">
                <button class="btn btn-outline-secondary btn-sm" onclick="download('pdf')"><i class="bi bi-file-pdf text-danger"></i> PDF</button>
                <button class="btn btn-outline-secondary btn-sm" onclick="download('docx')"><i class="bi bi-file-word text-primary"></i> DOCX</button>
                <button class="btn btn-outline-secondary btn-sm" onclick="download('md')"><i class="bi bi-markdown"></i> MD</button>
            </div>
        </div>

        <!-- Right: AI Generation Hub -->
        <div class="yt-action-col shadow-sm">
            
            <div id="generationMenu">
                <h5 class="fw-bold mb-3">Generation Hub</h5>
                <div class="gen-grid">
                    <button class="gen-btn" onclick="generateContent('Detailed Notes')"><i class="bi bi-journal-text text-primary"></i> Detailed Notes</button>
                    <button class="gen-btn" onclick="generateContent('Chapter Wise Notes')"><i class="bi bi-bookmarks text-info"></i> Chapter Wise</button>
                    <button class="gen-btn" onclick="generateContent('Timestamp Wise Notes')"><i class="bi bi-clock-history text-warning"></i> Timestamp Notes</button>
                    <button class="gen-btn" onclick="generateContent('Bullet Summary')"><i class="bi bi-list-ul text-success"></i> Bullet Summary</button>
                    <button class="gen-btn" onclick="generateContent('Exam Notes')"><i class="bi bi-mortarboard text-danger"></i> Exam Notes</button>
                    <button class="gen-btn" onclick="generateContent('Definitions & Concepts')"><i class="bi bi-book text-secondary"></i> Definitions</button>
                    <button class="gen-btn" onclick="generateContent('Interview Questions')"><i class="bi bi-person-lines-fill text-dark"></i> Interview Qs</button>
                    <button class="gen-btn" onclick="generateContent('MCQ')"><i class="bi bi-ui-radios text-primary"></i> MCQ Quiz</button>
                    <button class="gen-btn" onclick="generateContent('Flashcards')"><i class="bi bi-card-heading text-success"></i> Flashcards</button>
                    <button class="gen-btn" onclick="generateContent('Mind Map')"><i class="bi bi-diagram-3 text-info"></i> Mind Map</button>
                    <button class="gen-btn" onclick="generateContent('Code Examples')"><i class="bi bi-code-slash text-dark"></i> Code Examples</button>
                    <button class="gen-btn" onclick="generateContent('Formula Sheet')"><i class="bi bi-calculator text-warning"></i> Formula Sheet</button>
                </div>
            </div>

            <div id="loadingState" class="d-none text-center py-5">
                <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;"></div>
                <h5 class="text-muted" id="loadingText">Processing video transcript...</h5>
            </div>

            <div id="resultView" class="d-none mt-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button class="btn btn-sm btn-light border" onclick="showMenu()"><i class="bi bi-arrow-left"></i> Back to Hub</button>
                    <button class="btn btn-sm btn-success" onclick="storeInNotes()"><i class="bi bi-cloud-arrow-up"></i> Store in Notes</button>
                </div>
                
                <div class="d-flex gap-3 mb-3 small">
                    <span class="badge bg-light text-dark border"><i class="bi bi-stopwatch text-primary"></i> <span id="resReadingTime"></span> min read</span>
                    <span class="badge bg-light text-dark border"><i class="bi bi-bar-chart text-warning"></i> <span id="resDifficulty"></span></span>
                    <span class="badge bg-light text-dark border"><i class="bi bi-clock text-info"></i> Study: <span id="resStudyTime"></span></span>
                    <span class="badge bg-light text-dark border"><i class="bi bi-check-circle text-success"></i> <span id="resConfidence"></span>% Match</span>
                </div>

                <div class="output-box shadow-sm markdown-body" id="generatedContentBox"></div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
let currentTranscript = null;
let currentMetadata = null;
let currentGeneratedContent = null;
let currentGeneratedType = null;

async function analyzeVideo(e) {
    e.preventDefault();
    const url = document.getElementById('ytUrl').value;
    if(!url) return;

    const btn = e.target.querySelector('button');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Analyzing...';
    btn.disabled = true;

    try {
        const response = await fetch("{{ route('youtube.analyze') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ url })
        });

        const res = await response.json();
        if(res.success) {
            currentMetadata = res.metadata;
            currentTranscript = res.transcript;
            
            // Setup Player
            document.getElementById('playerContainer').innerHTML = `<iframe src="https://www.youtube.com/embed/${res.metadata.video_id}?enablejsapi=1" id="ytPlayer" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
            
            // Setup Meta
            document.getElementById('metaTitle').innerText = res.metadata.title;
            document.getElementById('metaChannel').innerText = res.metadata.channel;
            document.getElementById('metaDuration').innerText = res.metadata.duration;
            document.getElementById('metaThumbnail').src = res.metadata.thumbnail;

            document.getElementById('urlInputSection').classList.add('d-none');
            document.getElementById('workspaceSection').classList.remove('d-none');
            showMenu();
        } else {
            alert(res.message);
        }
    } catch(err) {
        alert("Failed to analyze video.");
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}

async function generateContent(type) {
    document.getElementById('generationMenu').classList.add('d-none');
    document.getElementById('resultView').classList.add('d-none');
    document.getElementById('loadingState').classList.remove('d-none');
    document.getElementById('loadingText').innerText = `Generating ${type}...`;

    try {
        const response = await fetch("{{ route('youtube.generate') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ 
                transcript: currentTranscript,
                type: type 
            })
        });

        const res = await response.json();
        if(res.success) {
            const data = res.data;
            currentGeneratedContent = data.content;
            currentGeneratedType = type;

            document.getElementById('resReadingTime').innerText = data.reading_time;
            document.getElementById('resDifficulty').innerText = data.difficulty;
            document.getElementById('resStudyTime').innerText = data.study_time;
            document.getElementById('resConfidence').innerText = data.confidence;

            // Parse Markdown. Inject timestamp click handlers
            let html = marked.parse(data.content);
            
            // Replace [HH:MM:SS] with clickable links
            html = html.replace(/\[(\d{1,2}:\d{2}(:\d{2})?)\]/g, (match, time) => {
                return `<a href="#" onclick="seekTo('${time}'); return false;" class="badge bg-danger text-white text-decoration-none"><i class="bi bi-play-circle"></i> ${time}</a>`;
            });

            document.getElementById('generatedContentBox').innerHTML = html;

            document.getElementById('loadingState').classList.add('d-none');
            document.getElementById('resultView').classList.remove('d-none');
        } else {
            alert("Failed to generate content.");
            showMenu();
        }
    } catch(err) {
        alert("Error occurred.");
        showMenu();
    }
}

function showMenu() {
    document.getElementById('loadingState').classList.add('d-none');
    document.getElementById('resultView').classList.add('d-none');
    document.getElementById('generationMenu').classList.remove('d-none');
}

function seekTo(timeStr) {
    const parts = timeStr.split(':').reverse();
    let seconds = 0;
    for(let i=0; i<parts.length; i++) {
        seconds += parseInt(parts[i]) * Math.pow(60, i);
    }
    const player = document.getElementById('ytPlayer').contentWindow;
    player.postMessage(JSON.stringify({
        event: 'command',
        func: 'seekTo',
        args: [seconds, true]
    }), '*');
}

async function storeInNotes() {
    const btn = event.currentTarget;
    const originalHtml = btn.innerHTML;
    btn.innerHTML = 'Saving...';
    
    try {
        const response = await fetch("{{ route('youtube.export') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ 
                title: `${currentGeneratedType} - ${currentMetadata.title}`,
                content: currentGeneratedContent,
                metadata: {
                    reading_time: document.getElementById('resReadingTime').innerText,
                    difficulty: document.getElementById('resDifficulty').innerText,
                    study_time: document.getElementById('resStudyTime').innerText,
                    confidence: document.getElementById('resConfidence').innerText,
                }
            })
        });

        const res = await response.json();
        if(res.success) {
            btn.innerHTML = '<i class="bi bi-check2"></i> Saved!';
            btn.classList.replace('btn-success', 'btn-outline-success');
            setTimeout(() => {
                btn.innerHTML = originalHtml;
                btn.classList.replace('btn-outline-success', 'btn-success');
            }, 3000);
        }
    } catch(e) {
        alert("Failed to save note.");
        btn.innerHTML = originalHtml;
    }
}

function download(format) {
    if(!currentGeneratedContent) {
        alert("Please generate some content first!");
        return;
    }
    
    let blob;
    if(format === 'md') {
        blob = new Blob([currentGeneratedContent], {type: 'text/markdown'});
    } else {
        alert(format.toUpperCase() + " export requires a backend PDF/Word generator package (like DomPDF or PHPWord). Markdown export is native!");
        return;
    }

    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `${currentMetadata.title.substring(0, 20)}_${currentGeneratedType}.${format}`;
    a.click();
    window.URL.revokeObjectURL(url);
}
</script>
@endpush
