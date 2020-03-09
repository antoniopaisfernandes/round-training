<div class="container">
    @foreach ($programs as $program)
        {{ $program->name }}
    @endforeach
</div>

{{ $programs->links() }}
