<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cluster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClusterApiController extends Controller
{
    /**
     * Display a listing of clusters.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $clusters = Cluster::select('id', 'name')->get();
            return response()->json([
                'success' => true,
                'message' => 'Clusters retrieved successfully.',
                'data' => $clusters,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve clusters: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve clusters.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}