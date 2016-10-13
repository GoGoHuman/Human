<?php

if (!is_admin()) {
            return;
}

function human_admin_header() {

            if (isset($_GET['page'])) {
                        $page = str_replace(array('human-', '-settings'), '', sanitize_text_field($_GET['page']));
                        $human_header = '<div class="wrap human_wrapper" id="' . $page . '_wrapper_header">
                            <h2>
	                           ' . strtoupper(str_replace('-', ' ', $page)) . '
                            </h2>
                            <h2 class="nav-tab-wrapper">
						       ' . HUMAN_TABS . '
						    </h2>
                         </div>';

                        return $human_header;
            }
}
