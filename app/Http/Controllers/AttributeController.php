<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attribute;

class AttributeController extends Controller
{
    public function index()
    {
        $attributes = Attribute::all();
        return response()->json($attributes);
    }

    public function show($id)
    {
        try{
        $attribute = Attribute::findOrFail($id);
        return response()->json($attribute);}
        catch(\Exception $e){
            return response()->json($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try{
        $validated = $request->validate([
            'name' => 'required|string|unique:attributes,name',
            'type' => 'required|in:text,date,number,select'
        ]);

        $attribute = Attribute::create($validated);
        return response()->json($attribute, 201);}
        catch(\Exception $e){
            return response()->json($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
        $attribute = Attribute::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|required|string|unique:attributes,name,' . $id,
            'type' => 'sometimes|required|in:text,date,number,select'
        ]);

        $attribute->update($validated);
        return response()->json($attribute);}
        catch(\Exception $e){
            return response()->json($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try{
        $attribute = Attribute::findOrFail($id);
        $attribute->delete();
        return response()->json(['message' => 'Attribute deleted successfully']);}
        catch(\Exception $e){
            return response()->json($e->getMessage());
        }
    }
}
