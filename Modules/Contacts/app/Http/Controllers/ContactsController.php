<?php

namespace Modules\Contacts\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Contacts\Models\Contacts;
use Modules\Contacts\Models\DynamicAttribute;
use Illuminate\Http\Response;

class ContactsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts = Contacts::all();
        return response()->json($contacts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:contacts,email',
            'phone' => 'nullable|string|max:20'
        ]);

        // Crear el contacto
        $contact = Contacts::create($validated);

        // Obtener todos los atributos dinámicos existentes
        $dynamicAttributes = DynamicAttribute::all();

        // Crear la estructura de custom_attributes
        $customAttributes = ['custom_attributes' => []];

        foreach ($dynamicAttributes as $dynamicAttribute) {
            $customAttributes['custom_attributes'][] = [
                'id' => $dynamicAttribute->id,
                'name' => $dynamicAttribute->name,
                'type' => $dynamicAttribute->type,
                'value' => $dynamicAttribute->default_value,
                'default' => $dynamicAttribute->default_value,
                'group_attribute_id' => $dynamicAttribute->group_attribute_id,
                'required_flag' => $dynamicAttribute->required_flag,
                'visible_flag' => $dynamicAttribute->visible_flag,
            ];
        }

        // Asignar custom_attributes al contacto y guardar
        $contact->custom_attributes = json_encode($customAttributes);
        $contact->save();

        return response()->json($contact, Response::HTTP_CREATED);
    }
    

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $contact = Contacts::findOrFail($id);
        return response()->json($contact);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $contact = Contacts::findOrFail($id);
    
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:contacts,email,' . $contact->id,
            'phone' => 'sometimes|nullable|string|max:20',
            'custom_attributes' => 'nullable|array',
            'custom_attributes.*.id' => 'required|integer|exists:dynamic_attributes,id',
            'custom_attributes.*.value' => 'nullable|array',
        ]);
    
        $contact->update($validated);
    
        if (isset($validated['custom_attributes'])) {
            $customAttributes = $contact->custom_attributes ?? ['custom_attributes' => []];
    
            foreach ($validated['custom_attributes'] as $attribute) {
                $index = array_search($attribute['id'], array_column($customAttributes['custom_attributes'], 'id'));
    
                $dynamicAttribute = DynamicAttribute::find($attribute['id']);
                $value = $attribute['value'];
    
                switch ($dynamicAttribute->type) {
                    case 'multiselect':
                        $value = json_decode(json_encode($value), true);
                        break;
                    default:
                        $value = $attribute['value'];
                }
    
                if ($index !== false) {
                    $customAttributes['custom_attributes'][$index]['value'] = $value;
                } else {
                    $customAttributes['custom_attributes'][] = [
                        'id' => $dynamicAttribute->id,
                        'name' => $dynamicAttribute->name,
                        'type' => $dynamicAttribute->type,
                        'value' => $value,
                        'default' => $dynamicAttribute->default_value,
                        'group_attribute_id' => $dynamicAttribute->group_attribute_id,
                        'required_flag' => $dynamicAttribute->required_flag,
                        'visible_flag' => $dynamicAttribute->visible_flag,
                    ];
                }
            }
    
            $contact->custom_attributes = $customAttributes;
            $contact->save();
        }
    
        return response()->json($contact);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id = null)
    {
        if ($id) {
            // Eliminar un solo contacto
            $contact = Contacts::findOrFail($id);
            $contact->delete();
        } else {
            // Eliminar múltiples contactos
            $ids = $request->input('ids');
            if ($ids) {
                Contacts::whereIn('id', $ids)->delete();
            }
        }

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }



    
    //funcion para editar los atributos dinamicos de un contacto en especifico por IDs
    public function updateDynamicAttributeValue(Request $request, $contactId, $attributeId)
    {
        $validated = $request->validate([
            'value' => 'required', // Aquí puedes añadir más validaciones según el tipo de valor esperado
        ]);

        $contact = Contacts::findOrFail($contactId);

        // Decodificar el JSON a un array
        $customAttributes = json_decode($contact->custom_attributes, true) ?? ['custom_attributes' => []];

        // Encontrar y actualizar el valor del atributo dinámico
        foreach ($customAttributes['custom_attributes'] as &$attribute) {
            if ($attribute['id'] == $attributeId) {
                $attribute['value'] = $validated['value'];
                break;
            }
        }

        // Codificar el array de nuevo a JSON y guardar en el modelo
        $contact->custom_attributes = json_encode($customAttributes);
        $contact->save();

        return response()->json($contact);
    }



    public function updateDynamicAttribute(Request $request, $contactId, $attributeId)
    {
        $contact = Contacts::findOrFail($contactId);
        $attributes = json_decode($contact->custom_attributes, true);
    
        foreach ($attributes['custom_attributes'] as &$attribute) {
            if ($attribute['id'] == $attributeId) {
                $attribute['value'] = $request->input('value');
                break;
            }
        }
    
        $contact->custom_attributes = json_encode($attributes);
        $contact->save();
    
        return response()->json(['message' => 'Dynamic attribute updated successfully']);
    }


}