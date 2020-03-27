<?php

namespace App\Http\Controllers;

use App\Program;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $programs = QueryBuilder::for(Program::class)
            ->allowedFilters(['id', 'name'])
            ->allowedIncludes(['editions'])
            ->defaultSort('name')
            ->allowedSorts(['id'])
            ->paginate(20)
            ->appends(request()->query());

        if (request()->expectsJson()) {
            return $programs;
        } else {
            return view('program.index', [
                'programs' => $programs,
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
            'name' => 'required',
        ]);

        $program = Program::create($validated);

        return $this->show($program);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function show(Program $program)
    {
        return response()->json($program);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Program $program)
    {
        $this->authorize('update');

        $validated = $request->validate([
            'name' => 'required',
        ]);

        $program->update(
            $validated
        );

        return $this->show($program);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function destroy(Program $program)
    {
        $this->authorize('destroy');

        $program->delete();

        return response()->json();
    }
}
