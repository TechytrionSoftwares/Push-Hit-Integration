<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://https://techytrionsoftwares.com/
 * @since             1.0.0
 * @package           Push_Hit_Integration
 *
 * @wordpress-plugin
 * Plugin Name:       Push hit integration
 * Plugin URI:        https://https://techytrionsoftwares.com/wp-admin/plugins.php
 * Description:       Push Hit Integration
 * Version:           1.0.0
 * Author:            Techytrion
 * Author URI:        https://https://techytrionsoftwares.com//
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       push-hit-integration
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PUSH_HIT_INTEGRATION_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-push-hit-integration-activator.php
 */
function activate_push_hit_integration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-push-hit-integration-activator.php';
	Push_Hit_Integration_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-push-hit-integration-deactivator.php
 */
function deactivate_push_hit_integration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-push-hit-integration-deactivator.php';
	Push_Hit_Integration_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_push_hit_integration' );
register_deactivation_hook( __FILE__, 'deactivate_push_hit_integration' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-push-hit-integration.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_push_hit_integration() {

	$plugin = new Push_Hit_Integration();
	$plugin->run();

}
run_push_hit_integration();

function bbloomer_add_premium_support_endpoint() {
    add_rewrite_endpoint( 'pushit-book-clip', EP_ROOT | EP_PAGES );
}
  
add_action( 'init', 'bbloomer_add_premium_support_endpoint' );
  
function bbloomer_premium_support_query_vars( $vars ) {
    $vars[] = 'pushit-book-clip';
    return $vars;
}
  
add_filter( 'query_vars', 'bbloomer_premium_support_query_vars', 0 );
  
// function bbloomer_add_premium_support_link_my_account( $items ) {
//     $items['pushit-book-clip'] = 'Pushit Book Clip';
//     return $items;
// }
  
// add_filter( 'woocommerce_account_menu_items', 'bbloomer_add_premium_support_link_my_account' );

function bbloomer_premium_support_content() {
    ?>
<script>
  jQuery(document).ready(function ($) {
    $("#sessionForm").on("submit", function (e) {
        e.preventDefault();
        jQuery("#pshit_overlay").fadeIn(300);

        // Retrieve form data using FormData object
        const formData = new FormData(this);
        const selectedDate = formData.get("startDate"); 
        const selectedTimeSlot = formData.get("timeSlot");

        // Declare variables outside the if block
        let startDateFormatted = null;
        let endDateFormatted = null;

        if (selectedTimeSlot) {
            const [startTime, endTime] = selectedTimeSlot.split(" - ").map(time => time.trim());

            // Add seconds (:00) to the time and format the dates
            startDateFormatted = `${selectedDate} ${startTime}:00`;
            endDateFormatted = `${selectedDate} ${endTime}:00`;
        }

        // Prepare the data to be sent in AJAX
        const data = {
            startDate: startDateFormatted,
            endDate: endDateFormatted,
            pitchId: formData.get("pitchId"),
            private: formData.get("private"),
            name: formData.get("name") || null,
            emails: formData.get("emails") ? formData.get("emails").split(",") : [] 
        };

        // console.log("Formatted Data: ", data);

        // AJAX call to submit the data
        $.ajax({
            url: '<?php echo admin_url("admin-ajax.php"); ?>',
            method: "POST",
            data: {
                action: "submit_session_form",
                data: data
            },
            success: function (result) {
                if (result.success) {
                    $("#sessionForm").prepend(
                        '<div class="response-message success">' + result.data.message + "</div>"
                    );
                    $(".subt_btn_cstm_psh").prop("disabled", true);
                    setTimeout(function () {
                        jQuery("#pshit_overlay").fadeOut(300);
                    }, 500);
                    setTimeout(function () {
                        window.location.reload();
                    }, 5000);
                } else {
                    $("#sessionForm").prepend(
                        '<div class="response-message error">' + result.data.message + "</div>"
                    );
                    $(".subt_btn_cstm_psh").prop("disabled", false);
                    setTimeout(function () {
                        jQuery("#pshit_overlay").fadeOut(300);
                    }, 500);
                }
            },
            error: function (xhr, status, error) {
                console.error("Error Status:", status);
                console.error("XHR Object:", xhr);
                console.error("Error:", error);
                alert("An unexpected error occurred.");
                setTimeout(function () {
                    jQuery("#pshit_overlay").fadeOut(300);
                }, 500);
            }
        });
    });

     document.addEventListener('DOMContentLoaded', function () {
            const startDateInput = document.getElementById('startDate');
            if (startDateInput) {
                startDateInput.addEventListener('click', function () {
                    if (this.showPicker) {
                        this.showPicker(); 
                    }
                });
            }
        });
});
jQuery(document).ready(function ($) {
    $(".time-slots input[type='radio']").on("change", function () {
        $(".time-slots .time-slot").removeClass("active");
        $(this).closest(".time-slot").addClass("active");
    });
});
document.addEventListener("DOMContentLoaded", function () {
    const startDateInput = document.getElementById("startDate");
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    
    const minDate = `${year}-${month}-${day}`;
    
    // Set the min attribute to the current date
    if (startDateInput) {
        startDateInput.min = minDate;
    }
     startDateInput.addEventListener("click", function () {
                startDateInput.showPicker(); 
            });
});

</script>
    <form id="sessionForm" method="post">
    <div class="pushit_date">
        <div class="pushit_start_dt">
            <div class='lbl_psht_fm'>
                <label for="startDate">Start Date</label>
            </div>
            <input type="date" id="startDate" name="startDate" required>
        </div>
        <div class="pushit_end_dt">
            <div class='lbl_psht_fm'>
                <label for="endDate">Time Slots</label>
            </div>
            <div class="time-slots">
                <label class="time-slot">
                    <input type="radio" name="timeSlot" value="06:00 - 07:30" required>
                    06:00 - 07:30
                </label>
                <label class="time-slot">
                    <input type="radio" name="timeSlot" value="07:30 - 09:00" required>
                    07:30 - 09:00
                </label>
                <label class="time-slot">
                    <input type="radio" name="timeSlot" value="09:00 - 10:30" required>
                    09:00 - 10:30
                </label>
                <label class="time-slot">
                    <input type="radio" name="timeSlot" value="10:30 - 12:00" required>
                    10:30 - 12:00
                </label>
                <label class="time-slot">
                    <input type="radio" name="timeSlot" value="12:00 - 13:30" required>
                    12:00 - 13:30
                </label>
                <label class="time-slot">
                    <input type="radio" name="timeSlot" value="13:30 - 15:00" required>
                    13:30 - 15:00
                </label>
                <label class="time-slot">
                    <input type="radio" name="timeSlot" value="15:00 - 16:30" required>
                    15:00 - 16:30
                </label>
                <label class="time-slot">
                    <input type="radio" name="timeSlot" value="16:30 - 18:00" required>
                    16:30 - 18:00
                </label>
                <label class="time-slot">
                    <input type="radio" name="timeSlot" value="18:00 - 19:30" required>
                    18:00 - 19:30
                </label>
                <label class="time-slot">
                    <input type="radio" name="timeSlot" value="19:30 - 21:00" required>
                    19:30 - 21:00
                </label>
                <label class="time-slot">
                    <input type="radio" name="timeSlot" value="21:00 - 22:30" required>
                    21:00 - 22:30
                </label>
            </div>
        </div>
    </div>

    <label for="pitchId">Pitch ID</label>
    <!-- <input type="text" id="pitchId" name="pitchId" required> -->
    <select id="pitchId" name="pitchId" required style="width: 100%; border: 1px solid #cecece; padding: 8px 6px; border-radius: 4px;">
        <option value="Slipway Court 1">Slipway Court 1</option>
        <option value="Slipway Court 2">Slipway Court 2</option>
    </select>
    <br><br>

    <label for="name">Name (optional)</label>
    <input type="text" id="name" name="name">
    <br><br>

    <label>Private:</label>
    <input type="radio" id="privateTrue" name="private" value="true">
    <label for="privateTrue">Yes</label>

    <input type="radio" id="privateFalse" name="private" value="false" checked>
    <label for="privateFalse">No</label>
    <br><br>

    <label for="emails">Emails (optional)</label>
    <input type="text" id="emails" name="emails" value="<?php echo wp_get_current_user()->user_email; ?>">
    <br><br>

    <button type="submit" class="subt_btn_cstm_psh">Submit <pushit-icon _ngcontent-ng-c835490105="" name="logo" type="pushit" class="svg_cn_cstm" alt="contact-form-cover" _nghost-ng-c2144752284=""><span _ngcontent-ng-c2144752284="" nz-icon="" class="anticon anticon-icons:pushit/logo"><svg version="1.0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 862.000000 949.000000" preserveAspectRatio="xMidYMid meet" fill="currentColor" width="1em" height="1em" data-icon="icons:pushit/logo" aria-hidden="true">
        <g transform="translate(0.000000,949.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
        <path d="M3475 9472 c-68 -24 -1281 -726 -1323 -766 -82 -77 -102 -196 -48
        -294 16 -28 43 -63 60 -75 53 -40 1268 -739 1309 -753 81 -29 183 -5 247 57
        68 66 73 88 78 321 l4 207 57 -32 c31 -17 261 -150 511 -294 l455 -263 78 0
        c86 0 121 13 175 64 61 57 72 97 72 255 0 106 3 141 13 141 21 0 228 -104 326
        -164 484 -295 850 -775 999 -1312 63 -224 85 -383 85 -614 0 -192 -11 -305
        -50 -480 -184 -842 -826 -1503 -1662 -1715 -197 -50 -290 -60 -546 -60 -251 0
        -345 10 -527 56 l-38 10 0 1022 c0 1089 -2 1129 -50 1312 -45 170 -158 381
        -277 517 -252 286 -584 439 -952 439 -218 0 -389 -39 -571 -131 -364 -183
        -610 -511 -691 -921 -21 -106 -16 -354 10 -499 84 -471 246 -878 506 -1270
        225 -338 527 -642 860 -865 100 -67 272 -169 299 -178 8 -3 12 -393 14 -1390
        2 -939 6 -1387 13 -1387 20 0 161 51 212 76 150 75 302 202 406 339 103 136
        185 340 210 519 7 49 11 344 11 812 l0 734 23 -5 c154 -37 594 -56 814 -36
        615 57 1154 271 1653 657 139 107 386 352 495 489 390 489 618 1053 676 1668
        17 181 7 563 -19 732 -91 589 -326 1111 -700 1555 -161 192 -422 429 -617 560
        -255 172 -492 290 -785 389 l-135 46 -5 185 c-5 197 -10 216 -59 273 -65 73
        -195 100 -286 58 -22 -10 -255 -142 -519 -294 -263 -153 -480 -277 -482 -277
        -2 0 -4 82 -4 183 0 226 -10 270 -79 338 -33 34 -61 51 -100 63 -68 20 -84 20
        -146 -2z m-832 -3309 c88 -42 163 -117 208 -208 l34 -70 3 -843 2 -844 -37 30
        c-75 59 -271 269 -345 368 -246 332 -394 704 -437 1106 -18 158 -15 197 19
        264 49 100 134 175 241 214 43 16 73 20 149 17 82 -2 104 -7 163 -34z"></path>
        </g>
        </svg></span></pushit-icon></button>
</form>
     <div id="pshit_overlay">
            <div class="cv-spinner">
                <img src="https://padelcentretz.com/wp-content/uploads/2025/01/1483.gif" style="width: 120px;">
            </div>
        </div>
    <?php
}

add_action('woocommerce_account_pushit-book-clip_endpoint', 'bbloomer_premium_support_content');

function handle_submit_session_form() {
    // Check if the data is received
    if (!isset($_POST['data']) || empty($_POST['data'])) {
        wp_send_json_error(array('message' => 'No data received.'));
        return;
    }

    $data = $_POST['data'];

    // Validate required fields
    if (!$data || !isset($data['startDate']) || !isset($data['endDate']) || !isset($data['pitchId'])) {
        wp_send_json_error(array('message' => 'Missing required fields.'));
        return;
    }

    // Retrieve the Bearer token (optional, if needed)
    $bearer_token = isset($_COOKIE['pushit_refaccess_token']) ? $_COOKIE['pushit_refaccess_token'] : '';

    // If the token is required but not found, return an error
    if (!$bearer_token) {
        wp_send_json_error(array('message' => 'Bearer token is missing.'));
        return;
    }

    // Prepare cURL request to external API
    $session_data = $data;
    // PRINT_r($session_data); die();

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://pushitreplays.com/new-api/external/v1/sessions?fieldId=142',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode(array(
            'startDate' => $session_data['startDate'],
            'endDate' => $session_data['endDate'],
            'pitchId' =>  (int) $session_data['pitchId'],
            'name' => $session_data['name'],
            'private' => filter_var($session_data['private'], FILTER_VALIDATE_BOOLEAN),
            'emails' => $session_data['emails'],
        )),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $bearer_token,
        ),
    ));

    $response = curl_exec($curl);
    // print_r($response); die;
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        wp_send_json_error(array('message' => 'cURL Error: ' . $err));
    } else {
    // Decode the API response
    $decoded_response = json_decode($response, true);

    // Check if the token has expired
  if (isset($decoded_response['statusCode']) && $decoded_response['statusCode'] === 401) {
    wp_send_json_error(array('message' => 'Token is expired. Please refresh the page.'));
} elseif (isset($decoded_response['message']) && is_string($decoded_response['message']) && strtolower($decoded_response['message']) === 'token expired') {
    wp_send_json_error(array('message' => 'Token is expired. Please refresh the page.'));
} else {
    wp_send_json_success(array('message' => 'Session created successfully', 'data' => $decoded_response));
}

}
}

add_action('wp_ajax_submit_session_form', 'handle_submit_session_form');
add_action('wp_ajax_nopriv_submit_session_form', 'handle_submit_session_form');


add_filter( 'woocommerce_account_menu_items', 'bbloomer_add_link_my_account' );
 
function bbloomer_add_link_my_account( $items ) {
   $save_for_later = array( 'pushit-book-clip' => __( 'Pushit Book Clip', 'woocommerce' ) );
   unset( $items['pushit-book-clip'] ); // REMOVE TAB
   $items = array_merge( array_slice( $items, 0, 2 ), $save_for_later, array_slice( $items, 2 ) );
   return $items;
}


function call_pushit_api_on_reload() {
    // Check if we are on the specific page by comparing full URL
    if (is_page() && $_SERVER['REQUEST_URI'] === '/my-account/pushit-book-clip/') {
        // First API: login and get accessToken and refreshToken
        $url = 'https://api.pushitreplays.com/new-api/external/v1/auth/login';
        $data = json_encode(array(
            'secretToken' => 'Add_secret_token'
        ));
        
        // Make the POST request using WordPress HTTP API
        $response = wp_remote_post($url, array(
            'method'    => 'POST',
            'body'      => $data,
            'headers'   => array(
                'Content-Type' => 'application/json'
            ),
        ));

        // Check if the response was successful
        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            echo "Error: $error_message";
        } else {
            // Get the response body from the first API call
            $response_body = wp_remote_retrieve_body($response);
            $response_data = json_decode($response_body, true);
            $access_token = $response_data['accessToken'];
            $refresh_token = $response_data['refreshToken'];

            // Now, use the refresh token to make the second API request (Server-side)
            $refresh_url = 'https://pushitreplays.com/new-api/external/v1/auth/refresh';
            $refresh_data = json_encode(array(
                'refreshToken' => $refresh_token
            ));

            // Make the second POST request (Refresh token)
            $refresh_response = wp_remote_post($refresh_url, array(
                'method'    => 'POST',
                'body'      => $refresh_data,
                'headers'   => array(
                    'Content-Type' => 'application/json'
                ),
            ));

            // Check for errors in the second API call
            if (is_wp_error($refresh_response)) {
                $error_message = $refresh_response->get_error_message();
                echo "Error refreshing access token: $error_message";
            } else {
                // Get the response body from the second API call (refresh)
                $refresh_response_body = wp_remote_retrieve_body($refresh_response);
                $refresh_response_data = json_decode($refresh_response_body, true);

                // Get the new access token and store it in a cookie
                $refreshed_access_token = $refresh_response_data['accessToken'];

                // Set cookies for accessToken, refreshToken, and refreshed accessToken
                echo "
                    <script>
                        document.cookie = 'pushit_accesstoken=' + '" . $access_token . "' + '; path=/; max-age=86400'; // 1 day
                        document.cookie = 'pushit_refreshtoken=' + '" . $refresh_token . "' + '; path=/; max-age=86400'; // 1 day
                        document.cookie = 'pushit_refaccess_token=' + '" . $refreshed_access_token . "' + '; path=/; max-age=86400'; // 1 day
                    </script>
                ";
            }
        }
    }
}

add_action('wp_footer', 'call_pushit_api_on_reload');
