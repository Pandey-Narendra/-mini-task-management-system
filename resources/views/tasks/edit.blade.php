<x-app-layout>

    <h2 class="fw-bold mb-4">Edit Task</h2>

    <div class="card shadow-sm">
        <div class="card-body">

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Please fix the errors below:</strong>
                </div>
            @endif

            <form action="{{ route('tasks.update', $task) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- TITLE --}}
                <div class="mb-3">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control"
                           value="{{ old('title', $task->title) }}">

                    @error('title')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                {{-- DESCRIPTION --}}
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="3" class="form-control">
                        {{ old('description', $task->description) }}
                    </textarea>

                    @error('description')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                {{-- STATUS --}}
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="pending"
                            {{ old('status', $task->status) == 'pending' ? 'selected' : '' }}>Pending</option>

                        <option value="in-progress"
                            {{ old('status', $task->status) == 'in-progress' ? 'selected' : '' }}>In Progress</option>

                        <option value="completed"
                            {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>

                    @error('status')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                {{-- DUE DATE --}}
                <div class="mb-3">
                    <label class="form-label">Due Date <span class="text-danger">*</span></label>

                    <input type="date" name="due_date" class="form-control"
                          value="{{ old('due_date', \Carbon\Carbon::parse($task->due_date)->format('Y-m-d')) }}">

                    @error('due_date')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                {{-- BUTTONS --}}
                <button class="btn btn-primary">Update Task</button>
                <a href="{{ route('tasks.index') }}" class="btn btn-secondary ms-2">
                    Cancel
                </a>

            </form>

        </div>
    </div>

</x-app-layout>
