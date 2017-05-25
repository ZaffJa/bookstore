@extends('layout.master')

@section('content')
    <div class="widget ">
        <div class="widget-header">
            <i class="icon-book"></i>
            <h3>New Book Information</h3>
        </div>
        <div class="widget-content">
            @include('layout.error_status')
            <form class="form-horizontal" method="post" action="{{ action('BookController@store') }}"
                  autocomplete="off">
                <fieldset>
                    {{ csrf_field() }}
                    <div class="control-group">
                        <label class="control-label" for="barcode">Barcode</label>
                        <div class="controls">
                            <input type="text" class="span6" name="barcode">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="title">Title</label>
                        <div class="controls">
                            <input type="text" class="span6" name="title" value="{{ old('title') }}">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="publisher">Publisher</label>
                        <div class="controls">
                            <input type="text" class="span6" name="publisher"
                                   value="{{ old('publisher') }}">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="quantity">Quantity</label>
                        <div class="controls">
                            <input type="number" class="span3" name="quantity"
                                   value="{{ old('quantity') }}">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="retail_price">Retail Price (RM)</label>
                        <div class="controls">
                            <input type="number" step="0.01" class="span3" name="retail_price"
                                   value="{{ old('retail_price') }}">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="sell_price">Selling Price (RM)</label>
                        <div class="controls">
                            <input type="number" step="0.01" class="span3" name="selling_price"
                                   value="{{ old('selling_price') }}">
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button class="btn" type="reset">Cancel</button>
                    </div> <!-- /form-actions -->
                </fieldset>
            </form>
        </div>
    </div>
    <div class="widget widget-table action-table">
        <div class="widget-header"><i class="icon-th-list"></i>
            <h3>Book Informations</h3>
        </div>
        <div class="widget-content" style="padding: 10px 20px 10px 20px;">
            <table id="books" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Barcode</th>
                    <th>Title</th>
                    <th>Publisher</th>
                    <th>Quantity</th>
                    <th>Retail Price (RM)</th>
                    <th>Selling Price (RM)</th>
                    <th>Added Date</th>
                    <th class="td-actions">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach(\App\Models\Book::all() as $book)
                    <tr>
                        <td>{{ $book->barcode }}</td>
                        <td>{{ $book->title }}</td>
                        <td>{{ $book->publisher }}</td>
                        <td>{{ $book->quantity }}</td>
                        <td>{{ $book->retail_price }}</td>
                        <td>{{ $book->selling_price }}</td>
                        <td>{{ $book->created_at }}</td>
                        <td class="td-actions">
                            <a href="javascript:"
                               class="btn btn-small btn-success edit"
                               data-book-barcode="{{ $book->barcode }}"
                               data-book-title="{{ $book->title }}"
                               data-book-publisher="{{ $book->publisher }}"
                               data-book-quantity="{{ $book->quantity }}"
                               data-book-retail_price="{{ $book->retail_price }}"
                               data-book-selling_price="{{ $book->selling_price }}">
                                <i class="btn-icon-only icon-edit"> </i>
                            </a>
                            <a href="{{ action('BookController@delete',$book->id) }}" onclick="return confirm('Are you sure you want to delete this book?');" class="btn btn-danger btn-small">
                                <i class="btn-icon-only icon-remove"> </i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{--<a href="#myModal" role="button" class="btn" data-toggle="modal">Launch demo modal</a>--}}
    <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Edit Book Information</h3>
        </div>
        <div class="modal-body" style="padding-right: 0;">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" method="post" action="{{ action('BookController@update') }}"
                          autocomplete="off">
                        <fieldset>
                            {{ csrf_field() }}
                            <div class="control-group">
                                <label class="control-label" for="barcode">Barcode</label>
                                <div class="controls">
                                    <input type="text" class="span3" id="barcode" name="barcode">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="title">Title {{ session('success') }}</label>
                                <div class="controls">
                                    <input type="text" class="span3" id="title" name="title">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="publisher">Publisher</label>
                                <div class="controls">
                                    <input type="text" class="span3" id="publisher" name="publisher">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="quantity">Quantity</label>
                                <div class="controls">
                                    <input type="number" class="span3" id="quantity" name="quantity">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="retail_price">Retail Price (RM)</label>
                                <div class="controls">
                                    <input type="number" step="0.01" class="span3" id="retail_price" name="retail_price">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="sell_price">Selling Price (RM)</label>
                                <div class="controls">
                                    <input type="number" step="0.01" class="span3" id="selling_price" name="selling_price">
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="button" class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                            </div> <!-- /form-actions -->
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#books').DataTable();

            $('.edit').on('click',function () {
                console.log('hello')
                var $this = $(this);
                $('#barcode').val($this.data('book-barcode'));
                $('#title').val($this.data('book-title'));
                $('#publisher').val($this.data('book-publisher'));
                $('#quantity').val($this.data('book-quantity'));
                $('#retail_price').val($this.data('book-retail_price'));
                $('#selling_price').val($this.data('book-selling_price'));

                $('#myModal').modal('show');
            });
        });
    </script>
@endsection