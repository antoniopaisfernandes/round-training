<?php

namespace App\Http\Controllers;

use App\Company;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $companies = QueryBuilder::for(Company::class)
            ->with(['budgets'])
            ->allowedFilters(['id', 'name', 'vat_number'])
            ->allowedIncludes(['programs', 'budgets'])
            ->defaultSort('name')
            ->allowedSorts(['id', 'vat_number'])
            ->get();

        if (request()->expectsJson()) {
            return $companies;
        } else {
            return view('company.index', [
                'companies' => $companies,
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreCompanyRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCompanyRequest $request)
    {
        $company = DB::transaction(function () use ($request) {
            $company = Company::create($request->only('name', 'short_name', 'vat_number', 'coordinator_id'));
            $company->budgets()->createMany($request->get('budgets') ?: []);
            return $company;
        });

        return $this->show($company->fresh());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Company $company)
    {
        return response()->json($company->load(['budgets']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateCompanyRequest  $request
     * @param  \App\Company  $company
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $company = DB::transaction(function () use ($request, $company) {
            $company->update($request->only('name', 'short_name', 'vat_number', 'coordinator_id'));
            $company->budgets()->sync($request->get('budgets') ?: []);
            return $company;
        });

        return $this->show($company);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Company $company)
    {
        $this->authorize('destroy');

        $company->delete();

        return response()->json();
    }
}
