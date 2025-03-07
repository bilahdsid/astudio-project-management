<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    // List projects with optional filtering on regular and dynamic attributes
    public function index(Request $request)
    {
        $query = Project::query();

         // Process static filters (e.g., name, status)
        if ($request->has('filters')) {
            $filters = $request->input('filters');

            foreach ($filters as $field => $criteria) {
                // Skip dynamic attributes handling below for known static fields
                if (in_array($field, ['name', 'status'])) {
                    // Determine operator and value:
                    if (is_array($criteria)) {
                        $operator = isset($criteria['op']) ? $criteria['op'] : '=';
                        $value = $criteria['value'] ?? null;
                    } else {
                        // Default operator for static fields
                        $operator = (is_numeric($criteria)) ? '=' : 'LIKE';
                        $value = $criteria;
                        if($operator === 'LIKE'){
                            $value = '%' . $value . '%';
                        }
                    }

                    $query->where($field, $operator, $value);
                }
            }
        }

        // Process dynamic filtering for EAV attributes.
        // Assume dynamic filters are any filters not handled above.
        // We'll loop through filters and apply them for attributes.
        foreach ($request->input('filters', []) as $key => $criteria) {
            if (in_array($key, ['name', 'status'])) {
                continue;
            }

            // Determine operator and value for dynamic filter
            if (is_array($criteria)) {
                $operator = $criteria['op'] ?? '=';
                $value = $criteria['value'] ?? null;
            } else {
                // default: use LIKE for strings and = for numbers (this logic can be refined)
                $operator = (is_numeric($criteria)) ? '=' : 'LIKE';
                $value = $criteria;
                if($operator === 'LIKE'){
                    $value = '%' . $value . '%';
                }
            }

            // Join on attribute values filtering by the attribute name and operator/value
            $query->whereHas('attributeValues', function($q) use ($key, $operator, $value) {
                $q->whereHas('attribute', function($q2) use ($key) {
                    $q2->where('name', $key);
                })->where('value', $operator, $value);
            });
        }
        $query->where('user_id', $request->user()->id);
        $projects = $query->with('attributeValues.attribute')->get();
        return response()->json($projects);
    }

    // Show a single project with its dynamic attributes
    public function show($id)
    {
        try{
        $project = Project::with('attributeValues.attribute')->findOrFail($id);
        return response()->json($project);
        }catch(\Exception $e){
            return response()->json($e->getMessage());
        }
    }

    // Create a new project with dynamic attribute values
    public function store(Request $request)
    {
        try{
            $validated = $request->validate([
                'name'   => 'required|string',
                'status' => 'required|string|in:active,completed,pending',
                // Dynamic attributes can be passed as an associative array
                'attributes' => 'array'
            ]);
            $user = $request->user();
            $project = $user->project()->create($validated);
    
            // If attributes are provided, create/update attribute values
            if (isset($validated['attributes'])) {
                foreach ($validated['attributes'] as $attributeName => $value) {
                    // Find attribute by name
                    $attribute = Attribute::where('name', $attributeName)->first();
                    if ($attribute) {
                        AttributeValue::create([
                            'attribute_id' => $attribute->id,
                            'entity_id'    => $project->id,
                            'value'        => $value,
                        ]);
                    }
                }
            }
    
            return response()->json($project, 201);
        }catch(\Exception $e){
            return response()->json($e->getMessage());
        }
        
    }

    // Update project and its dynamic attributes
    public function update(Request $request, $id)
    {
        try{
            $project = Project::findOrFail($id);
        $validated = $request->validate([
            'name'   => 'sometimes|required|string',
            'status' => 'sometimes|required|string',
            'attributes' => 'sometimes|array'
        ]);

        $project->update($validated);

        if (isset($validated['attributes'])) {
            foreach ($validated['attributes'] as $attributeName => $value) {
                $attribute = Attribute::where('name', $attributeName)->first();
                if ($attribute) {
                    // Update if exists or create new attribute value
                    AttributeValue::updateOrCreate(
                        [
                            'attribute_id' => $attribute->id,
                            'entity_id'    => $project->id,
                        ],
                        ['value' => $value]
                    );
                }
            }
        }

        return response()->json($project);
        }catch(\Exception $e){
            return response()->json($e->getMessage());
        }
        
    }

    // Delete a project along with its dynamic attributes
    public function destroy($id)
    {
        try{
        $project = Project::findOrFail($id);

        // Delete associated dynamic attributes (optional: or use cascading deletes if set up)
        $project->attributeValues()->delete();
        $project->delete();

        return response()->json(['message' => 'Project deleted successfully']);
        }catch(\Exception $e){
            return response()->json($e->getMessage());
        }
    }
}
