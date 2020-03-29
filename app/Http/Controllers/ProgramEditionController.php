<?php

namespace App\Http\Controllers;

use App\ProgramEdition;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
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

        $validated = $this->validatedFields($request);

        $programEdition = DB::transaction(function () use ($validated, $request) {
            $programEdition = ProgramEdition::create(
                Arr::except(
                    $validated,
                    ['schedules']
                )
            );
            if ($request->get('schedules')) {
                $programEdition->schedules()->createMany($request->get('schedules'));
            }

            return $programEdition;
        });

        return $this->show($programEdition->fresh());
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

        $validated = $this->validatedFields($request);

        $programEdition->update(
            $validated
        );

        return $this->show($programEdition->fresh());
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

    private function validatedFields($request)
    {
        $validated = $request->validate([
            'program_id' => 'required|exists:programs,id',
            'name' => 'required|max:50',
            'company_id' => 'required|exists:companies,id',
            'cost' => 'required|min:0|max:999999',
            'supplier' => 'required',
            'teacher_name' => 'required',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'schedules.*' => 'nullable',
            'schedules.*.starts_at' => [
                'required_with:schedules.*',
                'date',
                'after_or_equal:starts_at',
                'before_or_equal:ends_at',
            ],
            'schedules.*.ends_at' => [
                'required_with:schedules.*',
                'date',
                'after_or_equal:schedules.*.starts_at',
            ],
        ]);
        $validated['created_by'] = auth()->user()->id;

        return $validated;
    }
}
