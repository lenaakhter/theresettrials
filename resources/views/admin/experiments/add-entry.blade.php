@extends('layouts.app')

@section('content')
<section class="reader-auth" style="min-height: auto; padding: 2rem 1rem;">
    <div class="reader-auth__card">
        <h1 class="reader-auth__title">Add Experiment Entry</h1>
        
        <div style="margin: 1rem 0; padding: 1rem; background: #f0f0f0; border-radius: 8px;">
            <p style="margin: 0; color: #5A5A5A;"><strong>{{ $experiment->title }}</strong></p>
        </div>

        <form action="{{ route('admin.experiments.store-entry', $experiment) }}" method="POST" style="display: flex; flex-direction: column; gap: 1rem;">
            @csrf

            <div>
                <label style="display: block; font-weight: 700; color: #5A5A5A; margin-bottom: 0.5rem;">Entry Date</label>
                <input 
                    type="datetime-local" 
                    name="entry_date" 
                    value="{{ old('entry_date', now()->format('Y-m-d\TH:i')) }}"
                    style="width: 100%; padding: 0.65rem; border: 1px solid #d5c7cc; border-radius: 8px; font-family: 'Quicksand', sans-serif;"
                    required
                >
                @error('entry_date')
                    <span style="color: #d32f2f; font-size: 0.85rem;">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label style="display: block; font-weight: 700; color: #5A5A5A; margin-bottom: 0.5rem;">Entry Type</label>
                <select 
                    name="type" 
                    style="width: 100%; padding: 0.65rem; border: 1px solid #d5c7cc; border-radius: 8px; font-family: 'Quicksand', sans-serif;"
                    required
                >
                    <option value="observation" @selected(old('type') === 'observation')>Observation</option>
                    <option value="result" @selected(old('type') === 'result')>Result</option>
                    <option value="note" @selected(old('type') === 'note')>Note</option>
                    <option value="update" @selected(old('type', 'update') === 'update')>Update</option>
                </select>
                @error('type')
                    <span style="color: #d32f2f; font-size: 0.85rem;">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label style="display: block; font-weight: 700; color: #5A5A5A; margin-bottom: 0.5rem;">Content</label>
                <textarea 
                    name="content" 
                    placeholder="Write your entry..." 
                    style="width: 100%; min-height: 200px; padding: 0.75rem; border: 1px solid #d5c7cc; border-radius: 8px; font-family: 'Quicksand', sans-serif; font-size: 0.95rem; resize: vertical;"
                    required
                >{{ old('content') }}</textarea>
                @error('content')
                    <span style="color: #d32f2f; font-size: 0.85rem;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; gap: 1rem;">
                <button 
                    type="submit" 
                    style="flex: 1; padding: 0.65rem; background: #c56a7f; color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; font-family: 'Quicksand', sans-serif;"
                >
                    Add Entry
                </button>
                <a 
                    href="{{ route('experiments.show', $experiment) }}" 
                    style="flex: 1; padding: 0.65rem; background: #8C7B7F; color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; font-family: 'Quicksand', sans-serif; text-align: center; text-decoration: none; display: flex; align-items: center; justify-content: center;"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
