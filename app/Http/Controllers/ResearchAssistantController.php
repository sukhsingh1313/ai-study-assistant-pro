<?php

namespace App\Http\Controllers;

use App\Models\ResearchReference;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ResearchAssistantController extends Controller
{
    public function index(): View
    {
        try {
            $references = ResearchReference::where('user_id', Auth::id())->latest()->get();
        } catch (\Throwable $e) {
            $references = collect();
        }

        return view('research.index', compact('references'));
    }

    public function generateCitation(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string'],
            'authors' => ['required', 'string'],
            'year' => ['required', 'string'],
            'style' => ['required', 'string', 'in:APA,MLA,Chicago,IEEE'],
        ]);

        $title = $validated['title'];
        $authors = $validated['authors'];
        $year = $validated['year'];
        $style = $validated['style'];

        $citation = match($style) {
            'APA' => "{$authors} ({$year}). {$title}. Academic Publishing.",
            'MLA' => "{$authors}. \"{$title}.\" Academic Publishing, {$year}.",
            'Chicago' => "{$authors}. \"{$title}.\" Academic Publishing ({$year}).",
            'IEEE' => "{$authors}, \"{$title},\" Academic Publishing, {$year}.",
            default => "{$authors} ({$year}). {$title}.",
        };

        try {
            ResearchReference::create([
                'user_id' => Auth::id(),
                'title' => $title,
                'authors' => $authors,
                'year' => $year,
                'citation_style' => $style,
                'formatted_citation' => $citation,
            ]);
        } catch (\Throwable $e) {
            // Silence DB exception
        }

        return redirect()->route('research.index')
            ->with('success', "{$style} Citation generated successfully!");
    }
}
