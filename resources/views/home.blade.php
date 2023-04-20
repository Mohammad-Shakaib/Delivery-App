@extends('layout.app')
@section("content")

    <div class="container">
        <div class="row">
            <div class="col-sm">    <!-- Button trigger modal -->
                <div class="text-right">
                    <button type="button" class="btn btn-success create_parcel" data-toggle="modal" data-target="#exampleModal">
                        Create Parcel
                    </button>
                </div>
                <table class="table table-hover pt-3">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Pick Up</th>
                        <th scope="col">Drop Of</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="actions d-none">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="parcels">
{{--                    <tr>--}}
{{--                        <th scope="row">3</th>--}}
{{--                        <td colspan="2">Larry the Bird</td>--}}
{{--                        <td>@twitter</td>--}}
{{--                    </tr>--}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Parcel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Parcel Name</label>
                        <input type="text" name="name" class="form-control name"  placeholder="Parcel Name">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Pick Up</label>
                        <input type="text" name="pick_up" class="form-control pick_up"  placeholder="Pick Up">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Drop Off</label>
                        <input type="text" name="drop_off" class="form-control drop_off"  placeholder="Drop Off">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary parcel_saved" onclick="createParcel()">Parcel Saved</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        getParcels();
    </script>
@endsection







