/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 *  @author    eKomi
 *  @copyright 2017 eKomi
 *  @license   LICENSE.txt
 */

$(document).ready(function () {

    $('#rnr_interactive_screen_welcome').hide();
    $('.ekomiratingsandreviews').hide();
    $('#rnr_interactive_screen_top').hide();

    // saving users feedback on reviews
    $('body').on('click', '#rnr_signin', function () {
        $('#rnr_interactive_screen_bottom').hide();
        $('#rnr_interactive_screen_welcome').show();
        $('.ekomiratingsandreviews').show();
        $('#rnr_interactive_screen_top').show();
    });
});
