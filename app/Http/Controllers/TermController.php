<?php

namespace App\Http\Controllers;

use App\Models\Term;
use Illuminate\Http\Request;

class TermController extends Controller
{
    // GET all terms
    public function index()
    {
        $data = Term::orderBy('id')->get();
        return response()->json($data);
    }

    // GET single term
    public function show($id)
    {
        $data = Term::findOrFail($id);
        return response()->json($data);
    }

    // POST create term
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
        ]);

      

        $term = Term::create($data);

        return response()->json($term, 201);
    }

    // PUT update term
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
        ]);

        //   dd($data);

        $term = Term::findOrFail($id);
        $term->update($data);

        return response()->json($term);
    }

    // DELETE term
    public function destroy($id)
    {
        Term::findOrFail($id)->delete();

        return response()->json(['message' => 'Term deleted']);
    }
}
