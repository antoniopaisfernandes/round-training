<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProgramEditionRequest;
use App\Http\Requests\UpdateProgramEditionRequest;
use App\Http\Resources\ProgramEditionResource;
use App\ProgramEdition;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProgramEditionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $programEditions = ProgramEditionResource::collection(
                QueryBuilder::for(ProgramEdition::class)
                ->allowedFilters([
                    'supplier',
                    'teacher_name',
                    'starts_at',
                    'ends_at',
                    AllowedFilter::scope('status'),
                ])
                ->allowedIncludes(['program', 'company', 'schedules', 'manager', 'students'])
                ->allowedSorts(['starts_at', 'ends_at'])
                ->defaultSorts(['-starts_at', 'name'])
                ->paginate(20)
                ->appends(request()->query())
        );

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
     * @param  StoreProgramEditionRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreProgramEditionRequest $request)
    {
        $programEdition = DB::transaction(static function () use ($request) {
            $programEdition = ProgramEdition::create($request->validated());

            if ($request->get('schedules')) {
                $programEdition->schedules()->createMany($request->get('schedules'));
            }
            if ($request->has('enrollments')) {
                $programEdition->enrollments()->createMany($request->get('enrollments'));
            }

            return $programEdition;
        });

        return $this->show($programEdition->fresh());
    }

    /**
     * Display the specified resource.
     *
     * @param  ProgramEdition  $programEdition
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ProgramEdition $programEdition)
    {
        return response()->json($programEdition);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateProgramEditionRequest  $request
     * @param  ProgramEdition  $programEdition
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateProgramEditionRequest $request, ProgramEdition $programEdition)
    {
        $programEdition = DB::transaction(static function () use ($request, $programEdition) {
            $programEdition->update($request->validated());

            if ($request->has('schedules')) {
                $programEdition->schedules()->sync($request->get('schedules') ?: []);
            }
            if ($request->has('enrollments')) {
                $programEdition->enrollments()->sync($request->get('enrollments'));
            }

            return $programEdition;
        });

        return $this->show($programEdition->fresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  ProgramEdition  $programEdition
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(ProgramEdition $programEdition)
    {
        $this->authorize('destroy');

        $programEdition->delete();

        return response()->json();
    }
}
