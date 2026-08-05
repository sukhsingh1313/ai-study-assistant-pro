@extends('layouts.dashboard')

@section('title', 'Generate Practice Quiz - AI Study Assistant')

@section('content')
<div class="container-fluid px-0" style="max-width: 900px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Generate Practice Quiz</h2>
            <p class="text-muted mb-0">Create customized practice questions with adjustable counts, types, and difficulty ratings.</p>
        </div>
    </div>

    <div class="card card-custom bg-white p-4 p-md-5 border">
        <form method="POST" action="{{ route('quizzes.store') }}">
            @csrf

            <!-- Select Existing Note -->
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label for="note_id" class="form-label fw-bold text-dark mb-0">Select Saved Note (Optional)</label>
                    <button type="button" id="loadSampleBtn" class="btn btn-sm btn-outline-primary rounded-pill">
                        <i class="bi bi-file-earmark-code me-1"></i> Insert Sample Study Text
                    </button>
                </div>
                <select name="note_id" id="note_id" class="form-select">
                    <option value="">-- Choose from your saved notes or paste text below --</option>
                    @foreach($notes as $note)
                        <option value="{{ $note->id }}" data-content="{{ $note->content }}">{{ $note->title }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Quiz Controls: Questions Count, Type & Difficulty -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label for="total_questions" class="form-label fw-bold text-dark">Number of Questions</label>
                    <select name="total_questions" id="total_questions" class="form-select" required>
                        <option value="5" selected>5 Questions</option>
                        <option value="10">10 Questions</option>
                        <option value="15">15 Questions</option>
                        <option value="20">20 Questions</option>
                        <option value="25">25 Questions</option>
                        <option value="50">50 Questions</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="question_type" class="form-label fw-bold text-dark">Question Format</label>
                    <select name="question_type" id="question_type" class="form-select" required>
                        <option value="Mixed" selected>Mixed Format</option>
                        <option value="MCQ">Multiple Choice (MCQ)</option>
                        <option value="True/False">True / False</option>
                        <option value="Fill in the blanks">Fill in the Blanks</option>
                        <option value="Short Answer">Short Answer</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="difficulty" class="form-label fw-bold text-dark">Difficulty Level</label>
                    <select name="difficulty" id="difficulty" class="form-select" required>
                        <option value="Medium" selected>Medium</option>
                        <option value="Easy">Easy</option>
                        <option value="Hard">Hard</option>
                        <option value="Mixed">Mixed Difficulty</option>
                    </select>
                </div>
            </div>

            <!-- Content Input -->
            <div class="mb-4">
                <label for="content" class="form-label fw-bold text-dark">Study Content to Test From</label>
                <textarea name="content" id="content" rows="8" class="form-control font-monospace @error('content') is-invalid @enderror" placeholder="Is field mein apne lecture notes, textbook chapter text, ya PDF content paste karein (e.g. Computer Science, Science, History, Math concepts)..." required>{{ old('content') }}</textarea>
                <small class="text-muted fs-8 mt-1 d-block"><i class="bi bi-info-circle me-1"></i> AI is text ko read karke isme se Questions & Options generate karega.</small>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('quizzes.index') }}" class="btn btn-light px-4">Cancel</a>
                <button type="submit" class="btn btn-primary-custom px-5 py-2">
                    <i class="bi bi-patch-question me-1"></i> Generate Practice Quiz
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const noteSelect = document.getElementById('note_id');
    const contentTextarea = document.getElementById('content');
    const loadSampleBtn = document.getElementById('loadSampleBtn');

    noteSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const noteContent = selectedOption.getAttribute('data-content');
        if (noteContent) {
            contentTextarea.value = noteContent;
        }
    });

    loadSampleBtn.addEventListener('click', function() {
        contentTextarea.value = "Data Structures & Algorithms Overview:\nA Binary Search Tree (BST) is a node-based binary tree data structure which has the following properties:\n1. The left subtree of a node contains only nodes with keys lesser than the node's key.\n2. The right subtree of a node contains only nodes with keys greater than the node's key.\n3. The left and right subtree each must also be a binary search tree.\nSearch time complexity in a balanced BST is O(log n), whereas in an unbalanced tree it can degrade to O(n). Active recall and practice problems improve algorithmic problem solving skills.";
    });
});
</script>
@endpush
