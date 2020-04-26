
/**
 * Button 1 - Alert
 */
function showAlert()
{
    alert('Clicked!');
}

jQuery(document).ready(function($) {    
    /**
     * Button 2 - Background Color Change from Input
     */
    $('#button-2').on('click', function() {
        let backgroundColor = $('#container-background-color').val();
        $('#card-1 .card').css('backgroundColor', '#' + backgroundColor);
    });
    /**
     * Button 3 - Fade Div 3
     */
    $('#button-3').on('click', function() {
        $('#card-3').fadeToggle();
    });
});
