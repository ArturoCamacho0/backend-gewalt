<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ClientProject;

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
            'project_ids' => 'required|array',
            'project_ids.*' => 'exists:projects,project_id', // Validar que todos los IDs de proyectos existan en la tabla "projects"
        ]);

        $client = Client::findOrFail($request->input('client_id'));
        $projectIds = $request->input('project_ids');

        // Retrieve existing assignments for the client
        $existingAssignments = ClientProject::where('client_id', $client->client_id)
            ->whereIn('project_id', $projectIds)
            ->pluck('project_id')
            ->toArray();

        $newAssignments = [];

        foreach ($projectIds as $projectId) {
            if (!in_array($projectId, $existingAssignments)) {
                $newAssignments[] = [
                    'client_id' => $client->client_id,
                    'project_id' => $projectId,
                ];
            }
        }

        if (empty($newAssignments)) {
            return response()->json([
                'message' => 'Los proyectos ya están asignados al cliente.',
            ], Response::HTTP_CONFLICT);
        }

        // Create new assignments
        $assignments = ClientProject::insert($newAssignments);

        return response()->json($assignments, Response::HTTP_CREATED);
    }

    /**
     * Get the projects assigned to a client.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAssignedProjects($clientId)
    {
        $client = Client::findOrFail($clientId);

        $assignedProjects = ClientProject::where('client_id', $client->client_id)
            ->with('project')
            ->get();

        return response()->json($assignedProjects);
    }

    /**
     * Get the clients assigned to a project.
     *
     * @param  int  $projectId
     * @return \Illuminate\Http\Response
     */
    public function getAssignedClients($projectId)
    {
        $project = Project::findOrFail($projectId);

        $assignedClients = ClientProject::where('project_id', $project->project_id)
            ->with('client')
            ->get();

        return response()->json($assignedClients);
    }
}
