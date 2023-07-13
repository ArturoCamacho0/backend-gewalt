<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;

class ProjectController extends Controller
{
    /**
     * Display a listing of the projects.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $page = $request->input('page', 1);

        $projects = Project::with(['user'])
            ->withCount(['tasks' => function ($query) {
                $query->whereIn('status', ['in_progress', 'pending']);
            }])
            ->paginate($perPage, ['*'], 'page', $page);

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
            'status' => 'required|in:in_progress,finished,canceled',
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
            'status' => 'required|in:in_progress,finished,canceled',
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
        $count = Project::whereIn('status', ['pending', 'in_progress'])->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Get the number of inactive projects.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInactiveProjectsCount()
    {
        $count = Project::whereIn('status', ['finished','canceled'])->count();

        return response()->json(['count' => $count]);
    }

    public function deleteTasks(Request $request, $projectId)
    {
        $taskIds = $request->input('taskIds');

        try {
            Task::whereIn('id', $taskIds)->delete();

            return response()->json(['message' => 'Tasks deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete tasks'], 500);
        }
    }

    public function getUpcomingProjects()
    {
        $today = now()->startOfDay();
        $endOfWeek = now()->endOfWeek();

        $projects = Project::where('end_date', '>=', $today)
            ->where('end_date', '<=', $endOfWeek)
            ->get();

        return response()->json($projects);
    }
}
