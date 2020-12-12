$(document).ready(function () {
    $(document).on('click', '.trigger-delete', function () {
        var _this = $(this);
        var _url = _this.data('action');
        var _model = _this.data('model');
        swal({
                title: 'Are you sure?',
                text: 'Once deleted, you will not be able to recover this ' +_model+ '!',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: 'delete',
                        url: _url,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (result) {
                            if (result == 'success') {
                                // swal('Poof! The '+_model+' has been deleted!', {
                                //     icon: 'success',
                                // });
                                location.reload();
                            }
                        }
                    });
                } else {
                    swal('The '+_model+' is safe!');
                }
            });
    });

    $(document).on('click', '.trigger-pay', function () {
        var _this = $(this);
        var _url = _this.data('action');
        swal({
                title: 'Are you sure?',
                text: 'You want to change the status to paid!',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: 'post',
                        url: _url,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (result) {
                            if (result == 'success') {
                                location.reload();
                            }
                        }
                    });
                } else {
                    swal('The '+_model+' was cancelled!');
                }
            });
    });

    $(document).on('click', '.trigger-unpaid', function () {
        var _this = $(this);
        var _url = _this.data('action');
        swal({
                title: 'Are you sure?',
                text: 'You want to change the status to unpaid!',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: 'post',
                        url: _url,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (result) {
                            if (result == 'success') {
                                location.reload();
                            }
                        }
                    });
                } else {
                    swal('The '+_model+' was cancelled!');
                }
            });
    });

    $(window).scroll(function() {
        var nav = $('.navbar.nav1');
        var top = 200;
        if ($(window).scrollTop() >= top) {
    
            nav.addClass('top-nav-collapse');
            nav.addClass('bg-light');

            // nav.addClass('navbar-dark');
    
        } else {
            nav.removeClass('top-nav-collapse');
            nav.removeClass('bg-light');
            // nav.removeClass('navbar-dark');
            // nav.addClass('navbar-light');
        }
    });

    $('.digit_only').mask('000', {reverse: true});
    $('.digit_only2').mask('00000', {reverse: true});
});
