<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNameRequest;
use App\Http\Requests\UpdateNameRequest;
use App\Models\Name;

class NameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNameRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Name $name)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Name $name)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNameRequest $request, Name $name)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Name $name)
    {
        //
    }
}
