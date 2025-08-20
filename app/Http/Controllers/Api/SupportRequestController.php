<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\SupportRequestResolvedMail;
use App\Models\SupportRequest;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SupportRequestController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['nullable', Rule::in([SupportRequest::STATUS_ACTIVE, SupportRequest::STATUS_RESOLVED])],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $query = SupportRequest::query();

        if (!empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        if (!empty($validated['from'])) {
            $query->where('created_at', '>=', $validated['from']);
        }

        if (!empty($validated['to'])) {
            $query->where('created_at', '<=', $validated['to']);
        }

        $perPage = $validated['per_page'] ?? 15;

        return response()->json(
            $query->orderByDesc('created_at')->paginate($perPage)
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        $supportRequest = SupportRequest::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'message' => $validated['message'],
            'status' => SupportRequest::STATUS_ACTIVE,
        ]);

        return response()->json($supportRequest, 201);
    }

    public function update(int $id, Request $request): JsonResponse
    {
        $supportRequest = SupportRequest::findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', Rule::in([SupportRequest::STATUS_ACTIVE, SupportRequest::STATUS_RESOLVED])],
            'comment' => ['nullable', 'string'],
        ]);

        if ($validated['status'] === SupportRequest::STATUS_RESOLVED && empty($validated['comment'])) {
            throw ValidationException::withMessages([
                'comment' => 'Comment is required when resolving the request.',
            ]);
        }

        $supportRequest->fill([
            'status' => $validated['status'],
            'comment' => $validated['comment'] ?? null,
        ]);
        $supportRequest->save();

        if ($supportRequest->status === SupportRequest::STATUS_RESOLVED && $supportRequest->comment) {
            Mail::to($supportRequest->email)->queue(new SupportRequestResolvedMail($supportRequest));
        }

        return response()->json($supportRequest);
    }
}


