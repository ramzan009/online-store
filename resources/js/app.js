import './bootstrap';
import axios from "axios";


$(document).on('click', '.phone-button', function() {
    let button = $(this);
    axios.post(button.data('source')).then(function(response) {
        button.find('.number').html(response.data);
    }).catch(function(error) {
        console.error(error);
    })
})
