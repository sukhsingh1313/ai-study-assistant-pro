<?php

namespace App\Http\Controllers;

use App\Models\KnowledgeNode;
use App\Models\Note;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class KnowledgeGraphController extends Controller
{
    public function index(): View
    {
        $userId = Auth::id();

        $nodes = KnowledgeNode::where('user_id', $userId)->get();

        if ($nodes->isEmpty()) {
            // Seed initial interactive tree nodes
            $nodes = collect([
                (object)[
                    'subject' => 'Computer Science',
                    'chapter' => 'Data Structures',
                    'topic' => 'Binary Trees',
                    'formula' => 'Height = log2(N)',
                    'definition' => 'Hierarchical tree structure with left and right subtrees.'
                ],
                (object)[
                    'subject' => 'Computer Science',
                    'chapter' => 'Algorithms',
                    'topic' => 'Sorting & Searching',
                    'formula' => 'Binary Search Time = O(log N)',
                    'definition' => 'Logarithmic search strategy on sorted arrays.'
                ],
                (object)[
                    'subject' => 'Mathematics',
                    'chapter' => 'Calculus',
                    'topic' => 'Derivatives',
                    'formula' => 'd/dx(x^n) = n*x^(n-1)',
                    'definition' => 'Rate of change of a function with respect to a variable.'
                ]
            ]);
        }

        return view('knowledgegraph.index', compact('nodes'));
    }
}
