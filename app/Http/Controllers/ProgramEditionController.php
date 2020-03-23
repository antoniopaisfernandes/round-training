<?php

namespace App\Http\Controllers;

use App\ProgramEdition;
use Illuminate\Http\Request;

class ProgramEditionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('program-edition.index', [
            'programEditions' => ProgramEdition::orderBy('starts_at', 'desc')
                ->orderBy('name', 'asc')
                ->paginate(20),
        ]);
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
            'name' => 'required',
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
