<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudySessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $skillId = $this->route('skill')->id;

        return [
            'session_date' => 'required|date',
            'hours' => 'required|numeric|min:0.1',
            'topic_id' => [
                'nullable',
                'exists:topics,id',
                Rule::exists('topics', 'id')->where(function ($query) use ($skillId) {
                    return $query->where('skill_id', $skillId);
                }),
            ],
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'session_date.required' => 'Session date is required.',
            'session_date.date' => 'Session date must be a valid date.',
            'hours.required' => 'Hours are required.',
            'hours.numeric' => 'Hours must be a number.',
            'hours.min' => 'Hours must be at least 0.1.',
            'topic_id.exists' => 'Selected topic does not belong to this skill.',
            'notes.max' => 'Notes cannot exceed 1000 characters.',
        ];
    }
}

