<?php

namespace Modules\Lists\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Lists\Models\Lists;
use Modules\Lists\Models\ListContactItem;

class ListsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lists = Lists::all();
        return response()->json($lists);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('lists::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'list_type' => 'required|string|max:50',
        ]);
    
        $list = Lists::create($validated);
    
        return response()->json($list, Response::HTTP_CREATED);
    }
    



    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $list = Lists::findOrFail($id);
        return response()->json($list);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('lists::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'list_type' => 'required|string|max:50',
        ]);

        $list = Lists::findOrFail($id);
        $list->update($request->all());
        return response()->json($list);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $listId)
    {
        $request->validate([
            'contacts' => 'required|array',
            'contacts.*.contact_id' => 'required|exists:list_contacts_items,contact_id',
        ]);
    
        $list = Lists::findOrFail($listId);
    
        $contacts = $request->input('contacts');
    
        foreach ($contacts as $contact) {
            ListContactItem::where('list_id', $list->id)
                ->where('contact_id', $contact['contact_id'])
                ->delete();
        }
    
        return response()->json(['message' => 'Contacts removed from list successfully'], 200);
    }


    /**
     * Get items for a specific list.
     */
    public function getListItems($listId)
    {
        // Obtén la lista específica
        $list = Lists::findOrFail($listId);

        // Verifica el tipo de lista y obtén los ítems correspondientes
        if ($list->list_type === 'contacts') {
            $items = ListContactItem::where('list_id', $listId)->with('contact')->get();
        }
        // Si en el futuro agregas soporte para companies o deals, puedes agregar más condiciones aquí
        // elseif ($list->list_type === 'companies') {
        //     $items = ListCompanyItem::where('list_id', $listId)->with('company')->get();
        // } elseif ($list->list_type === 'deals') {
        //     $items = ListDealItem::where('list_id', $listId)->with('deal')->get();
        // }

        return response()->json([
            'list' => $list,
            'items' => $items
        ]);
    }
}
