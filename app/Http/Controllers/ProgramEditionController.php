<?php

namespace App\Http\Controllers;

use App\ProgramEdition;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class ProgramEditionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $programEditions = QueryBuilder::for(ProgramEdition::class)
            ->withCount('students')
            ->allowedFilters(['supplier', 'teacher_name', 'starts_at', 'ends_at'])
            ->allowedIncludes(['program', 'company', 'schedules', 'manager', 'students'])
            ->allowedSorts(['starts_at', 'ends_at'])
            ->defaultSorts(['-starts_at', 'name'])
            ->paginate(20)
            ->appends(request()->query());

        if (request()->expectsJson()) {
            return $programEditions;
        } else {
            return view('program-edition.index', [
                'programEditions' => $programEditions,
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->authorize('store');

        $validated = $request->validate([
            'program_id' => 'required|exists:programs,id',
            'name' => 'required|max:50',
            'company_id' => 'required|exists:companies,id',
            'supplier' => 'required',
            'teacher_name' => 'required',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);
        $validated['created_by'] = auth()->user()->id;

        $programEdition = ProgramEdition::create($validated);

        return $this->show($programEdition);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProgramEdition  $programEdition
     * @return \Illuminate\Http\Response
     */
    public function show(ProgramEdition $programEdition)
    {
        return response()->json($programEdition);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProgramEdition  $programEdition
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProgramEdition $programEdition)
    {
        $this->authorize('update');

        $validated = $request->validate([
            'program_id' => 'required|exists:programs,id',
            'name' => 'required|max:50',
            'company_id' => 'required|exists:companies,id',
            'supplier' => 'required',
            'teacher_name' => 'required',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        $programEdition->update(
            $validated
        );

        return $this->show($programEdition);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProgramEdition  $programEdition
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProgramEdition $programEdition)
    {
        $this->authorize('destroy');

        $programEdition->delete();

        return response()->json();
    }
}
