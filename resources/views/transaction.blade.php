@extends('layout.master')

@section('content')
    <div class="widget ">
        <div class="widget-header">
            <i class="icon-book"></i>
            <h3>Transaction</h3>
        </div>
        <div class="widget-content">
            @include('layout.error_status')
            <form class="form-horizontal" id="form" method="post" action="{{ action('TransactionController@store') }}"
                  autocomplete="off">
                <fieldset>
                    {{ csrf_field() }}
                    <div class="control-group">
                        <label class="control-label" for="barcode">Barcode</label>
                        <div class="controls">
                            <input type="text" id="barcode" class="span6" name="barcode" autofocus>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="title">Title</label>
                        <div class="controls">
                            <input type="text" id="title" disabled="disabled" class="span6" name="title"
                                   value="{{ old('title') }}">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="publisher">Publisher</label>
                        <div class="controls">
                            <input type="text" disabled="disabled" id="publisher" class="span6" name="publisher"
                                   value="{{ old('publisher') }}">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="quantity">Quantity</label>
                        <div class="controls">
                            <input type="number" id="quantity" class="span3" name="quantity"
                                   required value="{{ old('quantity') }}">
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="type" class="control-label"></label>
                        <div class="controls stock">
                            In Stock : 0
                        </div>
                    </div>
                    <div class="control-group" id="invalid_quantity" style="display: none;">
                        <label for="type" class="control-label"></label>
                        <div class="controls">
                            <span class="alert alert-danger span5" style="margin: 0;">
                                Invalid Quantity
                            </span>
                        </div>
                    </div>
                    <div class="control-group" id="sell_total">
                        <label class="control-label" for="selling_price">Selling Price (RM)</label>
                        <div class="controls">
                            <input type="number" disabled="disabled" id="selling_price" min="1" step="0.01"
                                   class="span3"
                                   name="selling_price"
                                   value="{{ old('selling_price') }}">
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="type" class="control-label"></label>
                        <div class="controls">
                            <input type="checkbox" id="type" value="1" checked>
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
@endsection

@section('scripts')
    <script>

        function clearInput() {
            $('#title').val('');
            $('#publisher').val('');
            $('#selling_price').val('');
            $('#quantity').val('');
        }
        $(document).ready(function () {
            var $type = $('#type');
            var $loader = $('.loader');
            var originalPrice = 0;
            var originalQuantity = 0;

            $type.bootstrapToggle({
                off: 'Transfer',
                on: 'Buy',
                offstyle: 'danger',
                onstyle: 'success',
                height: 20,
                width: 100
            });

            $('.alert').on('click', function () {
                $('#invalid_quantity').hide();
            });

            $type.on('change', function () {
                $toggle = $(this);

                if ($toggle.val() == 1) {
                    $toggle.val(2);
                } else {
                    $toggle.val(1);
                }

                $('#sell_total').toggle();
            });

            $('#barcode').on('keyup', function () {
                var $barcode = $(this);

                if ($barcode.val().length > 4) {
                    $loader.toggle();
                    $.get("/transaction/item/" + $barcode.val(), function (data) {
                        if (data) {
                            $('#title').val(data.title);
                            $('#publisher').val(data.publisher);
                            $('#selling_price').val(data.selling_price);
                            $('#quantity').prop('maxLength', data.quantity);

                            originalPrice = data.selling_price;
                            originalQuantity = data.quantity;

                            $('.stock').html('In Stock: ' + originalQuantity);
                        } else {
                            clearInput();
                        }
                    });
                    $loader.toggle();
                } else {
                    clearInput();
                }
            });

            $('#form').on('submit', function (event) {
                event.preventDefault();

                if (parseInt($('#quantity').val()) >= originalQuantity) {
                    $('#invalid_quantity').show();
                    return;
                }

                if ($('#title').val() == '') {
                    alert('Error occured when submitting transaction');
                    return;
                }
                var data = JSON.stringify({
                    'barcode': $('#barcode').val(),
                    'quantity': $('#quantity').val(),
                    'type': $('#type').val(),
                    'selling_price': $('#selling_price')
                });

                $.ajax({
                    type: "POST",
                    contentType: "application/json; charset=utf-8",
                    url: "/transaction/store",
                    data: data,
                    dataType: "json",
                    success: function (result) {
                        if (result.code === 200) {
                            $.notify({
                                // options
                                message: 'Transaction approved'
                            }, {
                                type: 'success'
                            });
                            clearInput();
                            $('#barcode').val('');
                            $('.stock').html('In Stock: 0');
                        } else {
                            $.notify({
                                // options
                                message: 'Error occurred'
                            }, {
                                type: 'success'
                            });
                        }
                    }
                });

                $("#barcode").focus();
            });

            $('#quantity').on('keyup', function () {
                var quantity = parseInt($('#quantity').val());
                var price = parseInt(originalPrice);
                var total = quantity * price;

                if ($(this).val() > 0) {
                    $('#selling_price').val(total);
                }
            });
        });
    </script>
@endsection