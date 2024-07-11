<?php

namespace Modules\Contacts\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Contacts\Models\Contacts;
use Illuminate\Validation\ValidationException;

class ContactsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('contacts::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('contacts::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validar los datos de entrada
        $validatedData = $request->validate([
            'name' => 'required|string|max:60',
            'last_name' => 'required|string|max:60',
            'email' => 'nullable|email|max:150',
            'phone' => 'nullable|string|max:20',
            'workspace_id' => 'nullable|exists:workspaces,id',
            'custom_attributes' => 'nullable|json',
        ]);

        // Crear un nuevo contacto
        $contact = Contacts::create($validatedData);

        // Redirigir a la lista de contactos con un mensaje de éxito
        return redirect()->route('contacts.index')->with('success', 'Contact created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $contact = Contacts::findOrFail($id);
        return view('contacts::show', compact('contact'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $contact = Contacts::findOrFail($id);
        return view('contacts::edit', compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        // Validar los datos de entrada
        $validatedData = $request->validate([
            'name' => 'required|string|max:60',
            'last_name' => 'required|string|max:60',
            'email' => 'nullable|email|max:150',
            'phone' => 'nullable|string|max:20',
            'workspace_id' => 'nullable|exists:workspaces,id',
            'custom_attributes' => 'nullable|json',
        ]);

        // Buscar el contacto y actualizarlo
        $contact = Contacts::findOrFail($id);
        $contact->update($validatedData);

        // Redirigir a la lista de contactos con un mensaje de éxito
        return redirect()->route('contacts.index')->with('success', 'Contact updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        // Buscar y eliminar el contacto
        $contact = Contacts::findOrFail($id);
        $contact->delete();

        // Redirigir a la lista de contactos con un mensaje de éxito
        return redirect()->route('contacts.index')->with('success', 'Contact deleted successfully.');
    }
}
