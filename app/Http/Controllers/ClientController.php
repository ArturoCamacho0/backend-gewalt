<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ClientController extends Controller
{
    /**
     * Display a listing of the clients.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::all();

        return response()->json($clients);
    }

    /**
     * Store a newly created client in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'company_id' => 'required',
        ]);

        $client = Client::create($request->all());

        return response()->json($client, Response::HTTP_CREATED);
    }

    /**
     * Display the specified client.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        return response()->json($client);
    }

    /**
     * Update the specified client in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'company_id' => 'required',
        ]);

        $client->update($request->all());

        return response()->json($client);
    }

    /**
     * Remove the specified client from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Assign a project to a client.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function assignProject(Request $request)
    {
        $request->validate([
            'client_id' => 'required',
            'project_id' => 'required',
        ]);

        $client = Client::findOrFail($request->input('client_id'));
        $project = Project::findOrFail($request->input('project_id'));

        // Check if the project is already assigned to the client
        $existingAssignment = ClientProject::where('client_id', $client->id)
            ->where('project_id', $project->id)
            ->first();

        if ($existingAssignment) {
            return response()->json([
                'message' => 'El proyecto ya está asignado al cliente.',
            ], Response::HTTP_CONFLICT);
        }

        // Create a new assignment
        $assignment = ClientProject::create([
            'client_id' => $client->id,
            'project_id' => $project->id,
        ]);

        return response()->json($assignment, Response::HTTP_CREATED);
    }
}
