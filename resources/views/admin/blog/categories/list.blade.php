@extends("admin.main")

@section("content")
    <h1 class="title">Categories list</h1>

    @include("errors.list")
    {!! Form::open(["url" => action("Admin\CategoriesController@postStore"), 'class' => "col-xs-5"]) !!}
            <h3>Add category</h3>
        <!--- Name Field --->
        <div class="form-group">
            {!! Form::label('name', 'Name:') !!}
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>

        <button type="submit" class="btn btn-default">Add</button>
    {!! Form::close() !!}
    <div class="clearfix"></div>
    <hr />
    <table class="table table-bordered table-hover categories-list">
        <thead>
            <tr>
                <th>Name</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        @foreach($categories as $category)
            <tr>
                <td class="col-xs-9 name">
                    {{ $category->name }}
                </td>
                <td class="text-center">
                    <button class="btn btn-default action-edit-category" data-toggle="modal" data-target="#editCategory" data-action="{{ action("Admin\CategoriesController@postUpdate", $category->id) }}">Edit</button>
                    <button class="btn btn-danger action-remove-category" data-action="{{ action("Admin\CategoriesController@getDestroy", $category->id) }}">Remove</button>
                </td>
            </tr>
        @endforeach
    </table>
    <!-- Modal -->
    <div class="modal fade" id="editCategory" tabindex="-1" role="dialog" aria-labelledby="editCategoryLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="editCategoryLabel">Edit category</h4>
                </div>
                <div class="modal-body">
                    {!! Form::open() !!}
                    <div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <strong>Whoops!</strong> There were some problems with your input.
                        <br>
                        <ul>
                            <li></li>
                        </ul>
                    </div>
                    <!--- name Field --->
                    <div class="form-group">
                        {!! Form::label('name', 'Name:') !!}
                        {!! Form::text('name', null, ['class' => 'form-control']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary action-save-category">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("scripts")
    <script>
        $(document).ready(function() {
            var list = $(".categories-list");
            var modalForm = $("#editCategory form");
            list.on("click", ".action-edit-category", function() {
                modalForm.attr("action", $(this).data("action"));
                modalForm.find("[name=name]").val($(this).closest("tr").find(".name").html().trim());

            });

            $(".action-save-category").click(function() {
                save();
            });

            modalForm.submit(function(e) {
                e.preventDefault();
                save();
            });

            var alertHolder = modalForm.find(".alert");
            alertHolder.hide();
            function save() {
                alertHolder.hide();
                $.post(modalForm.attr("action"), modalForm.serialize(), function(data) {
                    if (data.errors)
                    {
                        alertHolder.show();
                        var errorList = alertHolder.find("ul");
                        errorList.html("");
                        for (var key in data.errors) {
                            var errors = data.errors[key];

                            for (var id in errors) {
                                errorList.append("<li>"+ errors[id] +"</li>");
                            }
                        }
                    }
                    else
                        location.reload();
                });
            }

            list.on("click", ".action-remove-category", function() {
                var tr = $(this).closest("tr");
                $.get($(this).data("action"), function(data) {

                    if (data.success) {
                        tr.remove();
                    }

                }, "json");
            });
        });
    </script>
@endsection