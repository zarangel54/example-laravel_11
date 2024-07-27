<?php

namespace Modules\Contacts\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Contacts\Models\DynamicAttribute;
use Modules\Contacts\Models\Contacts;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DynamicAttributeController extends Controller
{
    public function index()
    {
        $attributes = DynamicAttribute::all();
        return response()->json($attributes);
    }


    public function show($id)
    {
        $dynamicAttribute = DynamicAttribute::findOrFail($id);

        return response()->json($dynamicAttribute, Response::HTTP_OK);
    }




    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'default_value' => 'nullable|string',
            'group_attribute_id' => 'nullable|integer',
            'required_flag' => 'required|boolean',
            'visible_flag' => 'required|boolean',
        ]);
    
        $dynamicAttribute = DynamicAttribute::create($validated);
    
        $contacts = Contacts::all();
    
        foreach ($contacts as $contact) {
            // Obtener y decodificar los atributos personalizados
            $customAttributes = json_decode($contact->custom_attributes, true) ?? ['custom_attributes' => []];
    
            // Manejar diferentes tipos de atributos dinámicos
            switch ($validated['type']) {
                case 'multiselect':
                    $value = json_decode($validated['default_value'], true);
                    break;
                default:
                    $value = $validated['default_value'];
            }
    
            // Agregar el nuevo atributo dinámico al array
            $customAttributes['custom_attributes'][] = [
                'id' => $dynamicAttribute->id,
                'name' => $validated['name'],
                'type' => $validated['type'],
                'value' => $value,
                'default' => $validated['default_value'],
                'group_attribute_id' => $validated['group_attribute_id'],
                'required_flag' => $validated['required_flag'],
                'visible_flag' => $validated['visible_flag'],
            ];
    
            // Codificar el array de nuevo a JSON y guardar en el modelo
            $contact->custom_attributes = json_encode($customAttributes);
            $contact->save();
        }
    
        return response()->json($dynamicAttribute, Response::HTTP_CREATED);
    }
    


    //Funcion para actualizar el atributo dinamico y que ese cambio se refleje en los contactos 


    public function update(Request $request, $id)
    {
        $dynamicAttribute = DynamicAttribute::findOrFail($id);
    
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'default_value' => 'nullable|string',
            'group_attribute_id' => 'nullable|integer',
            'required_flag' => 'required|boolean',
            'visible_flag' => 'required|boolean',
        ]);
    
        $dynamicAttribute->update($validated);
    
        // Actualizar el atributo dinámico en los contactos
        $contacts = Contacts::all();
        foreach ($contacts as $contact) {
            $customAttributes = $contact->custom_attributes ?? ['custom_attributes' => []];
    
            // Convertir el JSON en un array si es necesario
            if (is_string($customAttributes)) {
                $customAttributes = json_decode($customAttributes, true);
            }
    
            foreach ($customAttributes['custom_attributes'] as &$attribute) {
                if ($attribute['id'] == $dynamicAttribute->id) {
                    $attribute['name'] = $validated['name'];
                    $attribute['type'] = $validated['type'];
                    $attribute['default'] = $validated['default_value'];
                    $attribute['group_attribute_id'] = $validated['group_attribute_id'];
                    $attribute['required_flag'] = $validated['required_flag'];
                    $attribute['visible_flag'] = $validated['visible_flag'];
                }
            }
    
            // Reasignar el array modificado de nuevo a la propiedad
            $contact->custom_attributes = json_encode($customAttributes);
            $contact->save();
        }
    
        return response()->json($dynamicAttribute);
    }


        //funcion para eliminar un atribyo dinamico 

        public function destroy($id)
        {
            $dynamicAttribute = DynamicAttribute::findOrFail($id);
        
            // Eliminar el atributo dinámico de los contactos
            $contacts = Contacts::all();
            foreach ($contacts as $contact) {
                // Decodificar el JSON a un array
                $customAttributes = json_decode($contact->custom_attributes, true) ?? ['custom_attributes' => []];
        
                // Filtrar y eliminar el atributo dinámico
                $filteredAttributes = array_filter($customAttributes['custom_attributes'], function ($attribute) use ($dynamicAttribute) {
                    return $attribute['id'] != $dynamicAttribute->id;
                });
        
                $customAttributes['custom_attributes'] = array_values($filteredAttributes);
        
                // Codificar el array de nuevo a JSON y guardar en el modelo
                $contact->custom_attributes = json_encode($customAttributes);
                $contact->save();
            }
        
            $dynamicAttribute->delete();
        
            return response()->json(null, Response::HTTP_NO_CONTENT);
        }
        
}
