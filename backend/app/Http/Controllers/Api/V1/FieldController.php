<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Field;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    /**
     * Retrieve fields.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Field::all();
    }

    /**
     * Create a new field.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'type' => 'required|in:date,number,string,boolean',
        ]);

        $field = new Field;

        $field->title = $request->title;
        $field->type = $request->type;

        $field->save();

        return response('OK', 201);
    }

    /**
     * Remove a field.
     *
     * @param Request $request
     * @param \App\Models\Field $field
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Field $field)
    {
        $request->validate([
            'title' => 'required|string',
        ]);

        $field->title = $request->title;
        $field->save();

        return response('OK', 200);
    }

    /**
     * Remove a field.
     *
     * @param Request $request
     * @param \App\Models\Field $field
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Field $field)
    {
        $field->delete();
        
        return response('', 204);
    }
}
