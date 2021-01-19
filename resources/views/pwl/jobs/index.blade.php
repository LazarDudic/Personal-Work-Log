@extends('layouts.admin', ['title' => 'Jobs'])

@section('content')
    <div class="m-auto col-xl-5 col-lg-8 col-md-12 col-sm-12">

        <div class="card mb-4 m-auto">
            <div class="card-header">
                <i class="fas fa-table mr-1"></i>
                Jobs
                <a href="{{ route('jobs.create') }}" class="btn btn-primary btn-sm float-right">Add Job</a>
            </div>
            @include('partials.messages')
            <div class="card-body">
                @if($jobs->count())
                    <div class="table-responsive">
                        <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Current</th>
                                    <th width="30%">Action</th>
                                </tr>
                            </thead>
                            <tfoot class="text-center">
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Current</th>
                                    <th>Action</th>

                                </tr>
                            </tfoot>
                            <tbody>
                            @foreach($jobs as $job)
                                <tr>
                                    <th>{{ $loop->iteration }}</th>
                                    <td>{{ $job->title }}</td>
                                    <td>
                                        @if($job->current_job)
                                            <p class='text-success'>Current Job</p>
                                        @else
                                            <form action="{{ route('jobs.current.update', $job->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button class="btn"><i class="fas fa-check-circle fa-lg"></i></button>
                                            </form>

                                        @endif
                                    </td>
                                    <td class="d-flex">
                                        <a href="{{ route('jobs.edit', $job->id) }}" class="btn btn-info btn-sm mr-1">Edit</a>
                                        <form action="{{ route('jobs.destroy', $job->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-danger btn-sm flex-grow-1"
                                                    onclick="confirm('Are you sure you want to delete this job?') || event.stopImmediatePropagation()"
                                            >Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <h3>Please add your job.</h3>
                @endif
            </div>
        </div>
    </div>

@endsection
