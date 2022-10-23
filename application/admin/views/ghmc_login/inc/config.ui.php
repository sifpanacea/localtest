<?php

//CONFIGURATION for SmartAdmin UI

//ribbon breadcrumbs config
//array("Display Name" => "URL");
$breadcrumbs = array(
	"Home" => URL
);

/*navigation array config

ex:
"dashboard" => array(
	"title" => "Display Title",
	"url" => "http://yoururl.com",
	"icon" => "fa-home"
	"label_htm" => "<span>Add your custom label/badge html here</span>",
	"sub" => array() //contains array of sub items with the same format as the parent
)

*/
$page_nav = array(
	"dashboard" => array(
		"title" => "Dashboard",
		"url" => URL,
		"icon" => "fa-home"
	),
	"inbox" => array(
		"title" => "Inbox",
		"url" => URL."/inbox.php",
		"icon" => "fa-inbox",
		"label_htm" => '<span class="badge pull-right inbox-badge">14</span>'
	),
	"graphs" => array(
		"title" => "Graphs",
		"icon" => "fa-bar-chart-o",
		"sub" => array(
			"flot" => array(
				"title" => "Flot Chart",
				"url" => URL."/flot.php"
			),
			"morris" => array(
				"title" => "Morris Charts",
				"url" => URL."/morris.php"
			),
			"inline" => array(
				"title" => "Inline Charts",
				"url" => URL."/inline-charts.php"
			)
		)
	),
	"tables" => array(
		"title" => "Tables",
		"icon" => "fa-table",
		"sub" => array(
			"normal" => array(
				"title" => "Normal Tables",
				"url" => URL."/table.php"
			),
			"data" => array(
				"title" => "Data Tables",
				"url" => URL."/datatables.php"
			)
		)
	),
	"forms" => array(
		"title" => "Forms",
		"icon" => "fa-pencil-square-o",
		"sub" => array(
			"smart_elements" => array(
				"title" => "Smart Form Elements",
				"url" => URL."/form-elements.php"
			),
            "smart_layout" => array(
				"title" => "Smart Form Layouts",
				"url" => URL."/form-templates.php"
			),
            "smart_validation" => array(
				"title" => "Smart Form Validation",
				"url" => URL."/validation.php"
			),
            "bootstrap_forms" => array(
				"title" => "Bootstrap Form Elements",
				"url" => URL."/bootstrap-forms.php"
			),
            "form_plugins" => array(
				"title" => "Form Plugins",
				"url" => URL."/plugins.php"
			),
            "wizards" => array(
				"title" => "Wizards",
				"url" => URL."/wizard.php"
			),
            "bootstrap_editors" => array(
				"title" => "Bootstrap Editors",
				"url" => URL."/other-editors.php"
			),
            "dropzone" => array(
				"title" => "Dropzone",
				"url" => URL."/dropzone.php",
                "label_htm" => '<span class="badge pull-right inbox-badge bg-color-yellow">new</span>'
			)
		)
	),
    "ui_elements" => array(
        "title" => "UI Elements",
        "icon" => "fa-desktop",
        "sub" => array(
            "general" => array(
                "title" => "General Elements",
                "url" => URL."/general-elements.php"
            ),
            "buttons" => array(
                "title" => "Buttons",
                "url" => URL."/buttons.php"
            ),
            "icons" => array(
                "title" => "Icons",
                "sub" => array(
                    "fa" => array(
                        "title" => "Font Awesome",
                        "icon" => "fa-plane",
                        "url" => "fa.php"
                    ),
                    "glyph" => array(
                        "title" => "Glyph Icons",
                        "icon" => "fa-plane",
                        "url" => "glyph.php"
                    )
                ) 
            ),
            "grid" => array(
                "title" => "Grid",
                "url" => URL."/grid.php"
            ),
            "tree_view" => array(
                "title" => "Tree View",
                "url" => URL."/treeview.php"
            ),
            "nestable_lists" => array(
                "title" => "Nestable Lists",
                "url" => URL."/nestable-list.php"
            ),
            "jquery_ui" => array(
                "title" => "jQuery UI",
                "url" => URL."/jqui.php"
            )
        )
    ),
	"nav6" => array(
		"title" => "6 Level Navigation",
		"icon" => "fa-folder-open",
		"sub" => array(
			"second_lvl" => array(
				"title" => "2nd Level",
				"icon" => "fa-folder-open",
				"sub" => array(
					"third_lvl" => array(
						"title" => "3rd Level",
						"icon" => "fa-folder-open",
						"sub" => array(
							"file" => array(
								"title" => "File",
								"icon" => "fa-file-text"
							),
							"fourth_lvl" => array(
								"title" => "4th Level",
								"icon" => "fa-folder-open",
								"sub" => array(
									"file" => array(
										"title" => "File",
										"icon" => "fa-file-text"
									),
									"fifth_lvl" => array(
										"title" => "5th Level",
										"icon" => "fa-folder-open",
										"sub" => array(
											"file" => array(
												"title" => "File",
												"icon" => "fa-file-text"
											),
											"file" => array(
												"title" => "File",
												"icon" => "fa-file-text"
											)
										)
									)
								)
							)
						)
					)
				)
			),
			"folder" => array(
				"title" => "Folder",
				"icon" => "fa-folder-open",
				"sub" => array(
					"third_lvl" => array(
						"title" => "3rd Level",
						"icon" => "fa-folder-open",
						"sub" => array(
							"file1" => array(
								"title" => "File",
								"icon" => "fa-file-text"
							),
							"file2" => array(
								"title" => "File",
								"icon" => "fa-file-text"
							)
						)
					)
				)
			)
		)
	)
);

//configuration variables
$page_title = "";
$page_css = array();
$no_main_header = false; //set true for lock.php and login.php
$page_body_prop = array(); //optional properties for <body>
?>