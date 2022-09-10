@extends('books.layout')

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif



    @isset($search)
        <div class="alert alert-success">
            <p> {{ $search }} </p>
        </div>
    @endisset

    <div class="card mt-5 mb-5">
        <div class="card-header bg-primary">
            <h4 class="text-white d-flex justify-content-center">Library Management System</h4>
        </div>
        <div class="card-body">
            <div class="card-body">

                <div class="float-start mb-3">
                    <div class="col">
                        <button id='createButton' class="btn btn-primary" onclick="createBook()">Create new book</button>
                    </div>
                </div>

                <div class="float-end mb-3">
                    <div class="col">
                        <input class="" id="searchbar" type="text" name="searchQuery" placeholder="Search books">
                    </div>
                </div>

                <table class="table table-hover responsive booktable" id="contentTable">
                    <thead>
                        <th>Published Date</th>
                        <th>Name</th>
                        <th>Author</th>
                        <th>Action</th>
                    </thead>
                    <tbody></tbody>
                </table>

                <h5 id='resultNotFound' class="text-center d-none">No such search result(s) were found.</h5>
            </div>
        </div>
    </div>

    <!-- All modals -->

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Create new book</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cancel"></button>
                </div>
                <div class="modal-body">
                    <div>
                        <strong>Published date:</strong>
                        <input id="createDate" type="date" name="date" class="form-control"
                            placeholder="Published Date">
                    </div>
                    <div>
                        <strong>Name:</strong>
                        <input id="createName" type="text" name="name" class="form-control" placeholder="Name">
                    </div>
                    <div>
                        <strong>Author:</strong>
                        <input id="createAuthor" type="text" name="author" class="form-control" placeholder="Author">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="createPress()" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-secondary"
                        onclick='function createModalClickEvent() { $("#createModal").modal("hide") }'
                        data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit book</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cancel"></button>
                </div>
                <div class="modal-body">
                    <div>
                        <strong>Published date:</strong>
                        <input id="editDate" type="date" name="date" class="form-control"
                            placeholder="Published Date">
                    </div>
                    <div>
                        <strong>Name:</strong>
                        <input id="editName" type="text" name="name" class="form-control" placeholder="Name">
                    </div>
                    <div>
                        <strong>Author:</strong>
                        <input id="editAuthor" type="text" name="author" class="form-control" placeholder="Author">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="editConfirmationPress" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-secondary"
                        onclick='function editModalClickEvent() { $("#editModal").modal("hide") }'
                        data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-script')
    <script>
        let deletePress = function(id) {

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary me-3'
                },
                buttonsStyling: false
            })

            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {

                    $.post('{{ route('api.books.remove') }}', {
                        id: id
                    }, function(response) {

                        if (response.status === 200) {
                            swalWithBootstrapButtons.fire(
                                'Deleted!',
                                'Book has been deleted successfully.',
                                'success'
                            );
                            loadTable();
                        } else {
                            swalWithBootstrapButtons.fire(
                                'Not deleted!',
                                'There was an issue trying to delete the book.',
                                'error'
                            );
                        }

                    });

                }
            });
        }

        let loadTable = function(filter = false) {

            try {

                if (!filter) {

                    try {
                        $.ajax({
                            url: "{{ route('api.books.all') }}",
                            success: function(result) {

                                if (result.success === 200) {

                                    $("#contentTable").find('tbody').html("");
                                    $.each(
                                        result.data,
                                        function(index, value) {
                                            $("#contentTable").find('tbody')
                                                .append(
                                                    '<tr> <td>' + value.date + '</td> <td>' + value
                                                    .name +
                                                    '</td> <td>' + value.author +
                                                    '</td> </td> <td> <button class = "btn btn-primary" onclick = "editPress(' +
                                                    value.id +
                                                    ')"> Edit </button> <button class = "btn btn-danger" onclick = "deletePress(' +
                                                    value.id + ')"> Delete </button> </td> </tr>'
                                                );
                                        }
                                    );
                                }

                            }
                        })
                    } catch (error) {
                        console.log(error.message);
                    }
                } else {

                    try {
                        $.ajax({
                            url: "{{ route('api.books.filter') }}",
                            data: {
                                name: $("#searchbar").val()
                            },
                            success: function(result) {

                                if (result.success === 200) {

                                    if (result.data.length === 0) {
                                        $("#resultNotFound").removeClass('d-none');
                                    } else {
                                        $("#resultNotFound").addClass('d-none');
                                    }

                                    $("#contentTable").find('tbody').html("");
                                    $.each(
                                        result.data,
                                        function(index, value) {

                                            $("#contentTable").find('tbody')
                                                .append(
                                                    '<tr> <td>' + value.date + '</td> <td>' + value
                                                    .name +
                                                    '</td> <td>' + value.author +
                                                    '</td> </td> <td> <button class = "btn btn-primary" onclick = "editPress(' +
                                                    value.id +
                                                    ')"> Edit </button> <button class = "btn btn-danger" onclick = "deletePress(' +
                                                    value.id + ')"> Delete </button> </td> </tr>'
                                                );
                                        }
                                    );
                                }
                            }
                        })
                    } catch (error) {
                        console.log('There is an error while searching.');
                    }
                }
            } catch (error) {
                console.log('ono error ' + error.message);
            }

        }

        let createBook = function() {
            $("#createModal").modal('show');
        }

        let createPress = function() {
            $.post('{{ route('api.books.create') }}', {
                date: $("#createDate").val(),
                name: $("#createName").val(),
                author: $("#createAuthor").val(),
            }, function(response) {

                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                });

                if (response.status === 200) {
                    swalWithBootstrapButtons.fire(
                        'Created!',
                        'Book has been created successfully.',
                        'success'
                    );
                    loadTable();
                    $("#createModal").modal('hide');
                } else {
                    swalWithBootstrapButtons.fire(
                        'Not created!',
                        'There was an issue trying to create the book.',
                        'error'
                    );
                }

            });

        }

        let editPress = function(id) {

            $("#editModal").modal('show');

            $('input').prop('disabled', true);
            $.get({
                url: "{{ route('api.books.individual') }}",
                data: {
                    "id": id
                },
                success: function(result) {

                    $("#editDate").val(result.date);
                    $("#editName").val(result.name);
                    $("#editAuthor").val(result.author);
                    $('input').prop('disabled', false);

                    $("#editConfirmationPress").off().on('click', function(e) {
                        $.post('{{ route('api.books.edit') }}', {
                            id: id,
                            date: $("#editDate").val(),
                            name: $("#editName").val(),
                            author: $("#editAuthor").val(),
                        }, function(response) {

                            const swalWithBootstrapButtons = Swal.mixin({
                                customClass: {
                                    confirmButton: 'btn btn-success',
                                    cancelButton: 'btn btn-danger'
                                },
                                buttonsStyling: false
                            });

                            if (response.status === 200) {
                                swalWithBootstrapButtons.fire(
                                    'Updated!',
                                    'Book has been updated successfully.',
                                    'success'
                                );
                                loadTable();
                                $("#editModal").modal('hide');
                            } else {
                                swalWithBootstrapButtons.fire(
                                    'Not updated!',
                                    'There was an issue trying to update the book.',
                                    'error'
                                );
                            }

                        });
                    });
                },
                error: function() {
                    $('input').prop('disabled', false);
                }
            })

        }

        let initSearchBar = function() {
            $('#searchbar').off().on('input', function(e) {
                loadTable(true);
            });
        }

        let init = function() {
            loadTable();
            initSearchBar();
        }

        $(document).ready(init);
    </script>
@endpush
