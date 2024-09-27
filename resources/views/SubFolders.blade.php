@extends('Layout')

@section('title', 'Folder Details')

@section('content')
<section class="h-100 h-custom">
    <div class="container py-2 h-80">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-lg-12 col-xl-12">
                <div class="card rounded-3">
                    <div class="card-body p-4 p-md-5">
                        <h3 class="mb-4"></h3>

                        <a  class="btn btn-primary mb-3">Create Subfolder</a>

                        <h5>Subfolders</h5>
                        <ul class="list-group">
                          
                                <li class="list-group-item">
                                
                                    <form  method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="width: 100px; height: 40px; padding: 0; line-height: 40px; border: none;" class="btn btn-danger btn-sm float-right">Delete</button>
                                    </form>
                                </li>
                          
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
