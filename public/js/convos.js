// search button 
$('.search button').on('click', e => {
    if ($('#search-icon').attr('data-icon') == 'ant-design:search-outlined') {
        $('#search-icon').attr('data-icon', 'iconoir:cancel');
    } else {
        $('#search-icon').attr('data-icon', 'ant-design:search-outlined')
    }
    $('.search input').toggleClass('show');
    $('.search button').toggleClass('active');
    $('.search input').focus();
    if ($('.search input').hasClass('active')) {
        $('.search input').val(' ');
        $('.search input').removeClass('active');
    }

});


// search for chats
$('.search input').on('keyup', e => {
    let search = $('.search input').val();
    urlink = $('.search').attr('urlink');
    let _token = $('input[name="_token"]').val();
    if (search != '') {
        $('.search input').addClass('active');
    } else {
        $('.search input').removeClass('active');
    }

    //post request
    $.ajax({
        url: urlink,
        type: "post",
        data: {
            _token: _token,
            search: search
        },
        success: function(response) {
            // console.log(response);
            $('.users-list').html(response);
        }
    });
})



// get available chats
setInterval(() => {
    let urlink = $('#getchat').attr('action')
    let _token = $('input[name="_token"]').val();
    let search = $('.search input');

    //post request
    $.ajax({
        url: urlink,
        type: "post",
        data: {
            _token: _token
        },
        success: function(response) {
            if (!search.hasClass('active')) {
                $('.users-list').html(response);
            }
        }
    });

}, 500);