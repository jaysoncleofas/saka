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

    $(window).scroll(function() {
        var nav = $('.navbar');
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
});
