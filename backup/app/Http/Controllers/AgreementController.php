<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agreement;
use App\Models\AgreementOption;
use App\Http\Resources\AgreementResource;

class AgreementController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->only(['create', 'store', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\IndexAgreement  $request
     * @return \Illuminate\Http\Response
     */
    public function index(\App\Http\Requests\IndexAgreement $request)
    {
        $filters = $request->validated();
        $agreements = auth()->user()->agreements();
        $real_total = $agreements->count();

        $perPage = intval($filters['perPage'] ?? 5);
        $search = strtolower($filters['search'] ?? '');
        $filterBySeen = intval($filters['filterBySeen'] ?? -1);
        $filterByAccepted = intval($filters['filterByAccepted'] ?? -1);
        $sortField = $filters['sortField'] ?? 'id';
        $sortOrder = $filters['sortOrder'] ?? 'desc';

        if ($filterBySeen > -1) {
            $agreements = ($filterBySeen === 1) ? $agreements->whereNotNull('seen_at') : $agreements->whereNull('seen_at');
        }

        if ($filterByAccepted > -1) {
            $agreements = ($filterByAccepted === 1) ? $agreements->whereNotNull('accepted_at') : $agreements->whereNull('accepted_at');
        }

        if (!empty($search)) {
            $agreements = $agreements->whereRaw('LOWER(client_name) LIKE (?)', ["%{$search}%"])
                ->orWhereRaw('LOWER(subject) LIKE (?)', ["%{$search}%"])
                ->orWhereRaw('LOWER(notes) LIKE (?)', ["%{$search}%"]);
        }

        $agreements->orderBy($sortField, $sortOrder);

        return AgreementResource::collection($agreements->paginate($perPage))
            ->additional([
                'meta' => [
                    'real_total' => $real_total,
                    'create' => route('agreements.create')
                ]
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('agreements.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAgreement  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\StoreAgreement $request)
    {
        $validated = $request->validated();
        $options = $validated['options'];
        unset($validated['options']);

        $agreement = new Agreement($validated);
        auth()->user()->agreements()->save($agreement);

        foreach ($options as $option) {
            $agreement->options()->save(new AgreementOption(['prompt' => $option]));
        }

        session()->flash('token', $agreement->token);

        return redirect()->route('home');
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Agreement  $agreement
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Agreement $agreement)
    {
        if (!is_null($agreement->accepted_at)) {
            return view('agreements.expired');
        }

        if (!$request->has('nomark')) {
            $agreement->seen_at = now();
            $agreement->save();
        }

        return view('agreements.show', ['agreement' => new AgreementResource($agreement)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Agreement  $agreement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Agreement $agreement)
    {
        if (!is_null($agreement->accepted_at)) {
            return view('agreements.expired');
        }

        $options = array_keys($request->option ?? []);
        $agreement->options()->whereIn('id', $options)->update(['value' => true]);
        $agreement->options()->whereNotIn('id', $options)->update(['value' => false]);
        $agreement->notes = $request->notes;
        $agreement->accepted_at = now();
        $agreement->save();

        return view('agreements.thanks', ['agreement' => new AgreementResource($agreement)]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Agreement  $agreement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Agreement $agreement)
    {
        if (!$agreement->owner->is(auth()->user())) {
            return response(404);
        }

        if (!is_null($agreement->accepted_at)) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete an agreement which has been accepted.'
            ]);
        }

        $agreement->options()->delete();
        $agreement->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
