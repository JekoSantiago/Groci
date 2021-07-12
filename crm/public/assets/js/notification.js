/* ------------------------------------------------------------------------------
*
*  # Sound notification
*
*  Specific JS code additions for notifying new orders and customer
*
*  Version: 1.0
*  Latest update: Nov. 24, 2020
*
* ---------------------------------------------------------------------------- */

$(function() {

    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    setInterval(checkCustomer, 5000);
    setInterval(checkOrders, 5000);
    
    function checkCustomer()
    {
        $.ajax({
            url: webURL + '/customer/checker',
            type: 'GET',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                if(response.customerCount > 0)
                {
                    if(response.tagCount != response.customerCount) 
                    {
                        var sourceFile = $('#customer_notification').val();
                        playSound(sourceFile);
                        
                        tagCustomer(response.ids, response.customerCount);
                        ('#customerTable').load(location.href + " #customerTable");
                    }
                }
            }
        });
    }

    function tagCustomer(customerID, count)
    {
        var form_data = new FormData();
        form_data.append('ids', customerID);

        $.ajax({
            url: webURL + '/customer/tag',
            type: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (response) {
                if(response.status == 'ok')
                {
                    $('#custCount').html(count);
                }
            }
        });
    }

    function checkOrders()
    {
        $.ajax({
            url: webURL + '/orders/checker',
            type: 'GET',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                if(response.orderCount > 0)
                {
                    if(response.tagCount != response.orderCount) 
                    {
                        var sourceFile = $('#order_notification').val();
                        playSound(sourceFile);

                        new PNotify({
                            title: 'New Orders',
                            text: 'You have <strong>'+response.orderCount+'</strong> new order to receive.',
                            addclass: 'bg-danger',
                        });

                        tagOrders(response.ids, response.orderCount);
                        $('#ordersTable').load(location.href + " #ordersTable");
                    }    
                }
            }
        });
    }

    function tagOrders(orderID, count)
    {
        var form_data = new FormData();
        form_data.append('ids', orderID);

        $.ajax({
            url: webURL + '/orders/tag',
            type: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (response) {
                if(response.status == 'ok')
                {
                    $('#orderCount').html(count);
                }
            }
        });
    }

    function playSound(file)
    {
        let audioCtx = new (window.AudioContext || window.webkitAudioContext)();
		let xhr = new XMLHttpRequest();
		xhr.open('GET', file);
		xhr.responseType = 'arraybuffer';
		xhr.addEventListener('load', () => {
		    let playsound = (audioBuffer) => {
                let source = audioCtx.createBufferSource();
                source.buffer = audioBuffer;
                source.connect(audioCtx.destination);
                source.loop = false;
                source.start();
            };

			audioCtx.decodeAudioData(xhr.response).then(playsound);
		});
        
        xhr.send();
    }

});

