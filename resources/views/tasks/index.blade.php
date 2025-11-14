<x-app-layout>

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">My Tasks</h2>

        <a href="{{ route('tasks.create') }}" class="btn btn-primary">
            + Add Task
        </a>
    </div>

    {{-- Success Flash Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Task List --}}
    <div class="card shadow-sm">
        <div class="card-body">

            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Due Date</th>
                        <th style="width: 200px;">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($tasks as $task)
                        <tr>
                            <td>{{ $task->title }}</td>

                            <td>
                                <span class="badge 
                                    @if($task->status === 'pending') bg-secondary
                                    @elseif($task->status === 'in-progress') bg-warning text-dark
                                    @else bg-success
                                    @endif">
                                    {{ ucfirst($task->status) }}
                                </span>
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($task->due_date)->format('d M, Y') }}
                            </td>

                            <td>
                                <a href="{{ route('tasks.edit', $task) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    Edit
                                </a>

                                <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this task?')">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-outline-danger">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                No tasks found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $tasks->links() }}
            </div>

        </div>
    </div>

</x-app-layout>
