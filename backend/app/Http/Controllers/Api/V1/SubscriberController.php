<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Subscriber;
use App\Http\Requests\SubscriberRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    /**
     * Retrieve subscribers.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Subscriber::with(['fields'])->get();
    }

    /**
     * Retrieve a subscriber.
     *
     * @param Subscriber $subscriber
     * @return \Illuminate\Http\Response
     */
    public function show(Subscriber $subscriber)
    {
        $subscriber->load(['fields']);

        return $subscriber;
    }

    /**
     * Create new subscriber.
     *
     * @param SubscriberRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubscriberRequest $request)
    {
        $subscriber = new Subscriber;

        $subscriber->name = $request->name;
        $subscriber->email = $request->email;

        if($request->state) {
            $subscriber->state = $request->state;
        }

        $subscriber->save();
        $fieldsToSync = [];

        $fields = $request->fields ?? [];

        foreach($fields as $field) {
            $fieldsToSync[$field['id']] = [
                'value' => $field['value'],
            ];
        }

        $subscriber->fields()->sync($fieldsToSync);

        return response('OK', 201);
    }

    /**
     * Update a subscriber.
     *
     * @param SubscriberRequest $request
     * @param \App\Models\Subscriber $subscriber
     * @return \Illuminate\Http\Response
     */
    public function update(SubscriberRequest $request, Subscriber $subscriber)
    {
        $subscriber->name = $request->name;
        $subscriber->email = $request->email;

        if($request->state) {
            $subscriber->state = $request->state;
        }

        $subscriber->save();
        $fieldsToSync = [];

        $fields = $request->fields ?? [];

        foreach($fields as $field) {
            $fieldsToSync[$field['id']] = [
                'value' => $field['value'],
            ];
        }

        $subscriber->fields()->sync($fieldsToSync);

        return response('OK', 200);
    }

    /**
     * Remove a subscriber.
     *
     * @param Request $request
     * @param \App\Models\Subscriber $subscriber
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Subscriber $subscriber)
    {
        $subscriber->delete();
        
        return response('', 204);
    }
}
