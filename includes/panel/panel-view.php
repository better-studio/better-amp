<?php
/**
 * @var Better_AMP_Panel $this
 */
?>
<div class="panel-wrapper">
    <div id="bf-panel" class="panel-better-translation panel-with-tab better-amp-panel">

        <header class="bf-page-header">
            <div class="bf-page-header-inner bf-clearfix">
                <h2 class="page-title"><?php _e( 'BetterAMP Translation Panel', 'better-amp' ) ?></h2>
                <div class="page-desc"><p><?php _e( 'Translate all strings of plugin.', 'better-amp' ) ?></p></div>
                <div class="reset-sec">
                    <div class="btn-sec">
                        <a class="fleft bf-button bf-reset-button" data-confirm="Are you sure to reset translation?">
							<?php _e( 'Reset Translation', 'better-amp' ) ?>
                        </a>
                    </div>
                </div>
                <div class="btn-sec">
                    <a class="fright bf-save-button bf-button bf-main-button" data-confirm="">
						<?php _e( 'Save Translation', 'better-amp' ) ?>
                    </a>
                </div>

                <div class="bf-options-change-notice" style="display: none;">
					<?php _e( 'Options Changed', 'better-amp' ) ?>
                </div>
            </div>
        </header>

        <div id="bf-main" class="bf-clearfix" data-show-on-id="show-on-28914">

            <div id="bf-nav">
                <ul>
                    <li class="" data-go="general_tab">
                        <a href="#" class="bf-tab-item-a active_tab" data-go="general_tab">
							<?php _e( 'Texts', 'better-amp' ) ?>
                        </a>
                    </li>
                    <li class="margin-top-30" data-go="backup_restore">
                        <a href="#" class="bf-tab-item-a" data-go="backup_restore">
							<?php _e( 'Backup &amp; Restore', 'better-amp' ) ?>
                        </a>
                    </li>
                </ul>
            </div>

            <div id="bf-content">
                <form id="bf_options_form">
                    <!-- Section -->
                    <div class="group" id="bf-group-general_tab" style="display: block;">
						<?php
						$render = new Better_AMP_Panel_Render(
							$this->panel_fields(),
							$this->panel_values()
						);

						$render->render();
						?>
                    </div>
                    <div class="group" id="bf-group-backup_restore" style="display: none;">
                        <div class="bf-section-container bf-admin-panel bf-clearfix"
                             data-param-name="backup_export_options">
                            <div class="bf-section bf-nonrepeater-section bf-section-export-option bf-clearfix"
                                 data-id="backup_export_options">
                                <div class="bf-heading bf-nonrepeater-heading bf-heading-export-option bf-clearfix">
                                    <h3><label><?php _e( 'Backup / Export', 'better-amp' ) ?></label></h3>
                                </div>
                                <div class="bf-controls bf-nonrepeater-controls bf-controls-export-option bf-clearfix">
                                    <div>
                                        <a class="bf-button bf-main-button" id="bf-download-export-btn">
											<?php _e( 'Download Backup', 'better-amp' ) ?>
                                        </a>
                                    </div>
                                </div>
                                <div class="bf-explain bf-nonrepeater-explain bf-explain-export-option bf-clearfix">
									<?php _e( 'This allows you to create a backup of your translation. Please note, it will not backup anything else.', 'better-amp' ) ?>
                                </div>
                            </div>
                        </div>
                        <div class="bf-section-container bf-admin-panel bf-clearfix"
                             data-param-name="import_restore_options">
                            <div class="bf-section bf-nonrepeater-section bf-section-import-option bf-clearfix"
                                 data-id="import_restore_options">

                                <div class="bf-heading bf-nonrepeater-heading bf-heading-import-option bf-clearfix">
                                    <h3><label> <?php _e( 'Restore / Import', 'better-amp' ) ?></label></h3>
                                </div>

                                <div class="bf-controls bf-nonrepeater-controls bf-controls-import-option bf-clearfix">

                                    <input type="file"
                                           name="bf-import-file-input" id="bf-import-file-input"
                                           class="bf-import-file-input">

                                    <a class="bf-import-upload-btn bf-button bf-main-button">
										<?php _e( 'Import', 'better-amp' ) ?>
                                    </a>
                                </div>

                                <div class="bf-explain bf-nonrepeater-explain bf-explain-import-option bf-clearfix">
									<?php _e( '<strong>It will override your current translation!</strong> Please make sure to select a valid translation file.', 'better-amp' ) ?>
                                </div>

                            </div>
                        </div>
                    </div>
                    <input type="hidden" value="better-amp-panel-save" name="action">
					<?php wp_nonce_field( 'save-panel' ) ?>
                </form>
            </div>
        </div>
    </div>
</div>