// enable send button
$('.message').focus();
$('.message').on('keyup', e => {
    if ($('.message').val() != "") {
        $('.typing-area button').addClass("active");
    } else {
        $('.typing-area button').removeClass("active");
    }
})


// send message
$('.typing-area button').on('click', e => {
    e.preventDefault();
    let urlink = $('.typing-area').attr('action')
    let _token = $('input[name="_token"]').val();
    let message = $('.message').val();
    console.log(message);
    //post request
    $.ajax({
        url: urlink,
        type: "post",
        data: {
            _token: _token,
            message: message
        },
        success: function(response) {
            $('.message').val('');
            scrollToBottom();
        }
    });

});


//get available convo
setInterval(() => {
    let urlink = $('.typing-area').attr('urlink')
    let _token = $('input[name="_token"]').val();
    // console.log(urlink);

    //post request
    $.ajax({
        url: urlink,
        type: "post",
        data: {
            _token: _token
        },
        success: function(response) {
            $('.chat-box').html(response.convo);
            if (response.status.includes('second')) {
                $('.status').text('Offline')
            } else {
                $('.status').text(response.status)
            }
            if (!$('.chat-box').hasClass("active")) {
                scrollToBottom();
            }
        }
    });
}, 500);

// setInterval(() => {

// });
chatBox = document.querySelector(".chat-box");

chatBox.onmouseenter = () => {
    chatBox.classList.add("active");
}

chatBox.onmouseleave = () => {
    chatBox.classList.remove("active");
}

function scrollToBottom() {
    chatBox.scrollTop = chatBox.scrollHeight;
}