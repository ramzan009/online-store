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

$('.banner').each(function () {
    let block = $(this);
    let url = block.data('url');
    let format = block.data('format');
    let category = block.data('category');
    let region = block.data('region');

    axios
        .get(url, {params: {
                format: format,
                category: category,
                region: region
            }})
        .then(function (response) {
            block.html(response.data);
        })
        .catch(function (error) {
            console.error(error);
        });
});
