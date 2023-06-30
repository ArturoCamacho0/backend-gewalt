<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    /**
     * Display a listing of the projects.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::with(['user', 'tasks'])->get();
        $projects = Project::withCount('tasks')->get();

        return response()->json($projects);
    }

    /**
     * Store a newly created project in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'status' => 'boolean',
            'user_id' => 'required',
        ]);

        $project = Project::create($request->all());

        return response()->json($project, Response::HTTP_CREATED);
    }

    /**
     * Display the specified project.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        $project->load(['user', 'tasks']);
        $project->loadCount('tasks');

        return response()->json($project);
    }

    /**
     * Update the specified project in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'status' => 'boolean',
            'user_id' => 'required',
        ]);

        $project->update($request->all());

        return response()->json($project);
    }

    /**
     * Remove the specified project from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Get all active projects.
     *
     * @return \Illuminate\Http\Response
     */
    public function getActiveProjects()
    {
        $projects = Project::where('status', true)->get();

        return response()->json($projects);
    }

    /**
     * Get all inactive projects.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInactiveProjects()
    {
        $projects = Project::where('status', false)->get();

        return response()->json($projects);
    }


    /**
     * Get the number of active projects.
     *
     * @return \Illuminate\Http\Response
     */
    public function getActiveProjectsCount()
    {
        $count = Project::where('status', true)->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Get the number of inactive projects.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInactiveProjectsCount()
    {
        $count = Project::where('status', false)->count();

        return response()->json(['count' => $count]);
    }
}
