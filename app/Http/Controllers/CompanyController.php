<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = QueryBuilder::for(Company::class)
            ->allowedFilters(['id', 'name', 'vat_number'])
            ->allowedIncludes(['programs'])
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('store');

        $validated = $request->validate([
            'name' => 'required',
            'vat_number' => 'required',
        ]);

        $company = Company::create($validated);

        return response()->json($company);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Company $company)
    {
        return response()->json($company);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Company $company)
    {
        $this->authorize('update');

        $validated = $request->validate([
            'name' => 'required',
            'vat_number' => 'required',
        ]);

        $company->update(
            $validated
        );

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
