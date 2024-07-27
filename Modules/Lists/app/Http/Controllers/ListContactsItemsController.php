<?php

namespace Modules\Lists\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Lists\Models\Lists;
use Modules\Lists\Models\ListContactItem;
use Illuminate\Http\Response;

class ListContactsItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('lists::index');
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
    public function store(Request $request, $listId)
    {
        $request->validate([
            'contacts' => 'required|array',
            'contacts.*.contact_id' => 'required|exists:contacts,id',
        ]);

        $list = Lists::findOrFail($listId);

        $contacts = $request->input('contacts');

        foreach ($contacts as $contact) {
            ListContactItem::create([
                'list_id' => $list->id,
                'contact_id' => $contact['contact_id'],
            ]);
        }

        return response()->json(['message' => 'Contacts added to list successfully'], 200);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {

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
        //
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



    public function addContactToList(Request $request, $contactId)
{
    $request->validate([
        'list_id' => 'required|exists:lists,id',
    ]);

    $listId = $request->input('list_id');

    ListContactItem::create([
        'list_id' => $listId,
        'contact_id' => $contactId,
    ]);

    return response()->json(['message' => 'Contact added to list successfully'], 200);
}



}
