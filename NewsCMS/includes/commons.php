<?php
	//users of the system.
	define("USER_BASIC", "0");
	define("USER_ADMIN", "1");
	
	//general task
	define("GENERAL_TASK_SEARCH", "search");
	define("GENERAL_TASK_SELECT_CATEGORY", "select_category");
	define("GENERAL_TASK_CHANGE_PASSWORD", "change_password");
	define("GENERAL_TASK_CREATE_CATEGORY", "create_category");
	define("GENERAL_TASK_SUB_CATEGORY_LISTING", "sub_category_listing");
	define("GENERAL_TASK_CREATE_SUB_CATEGORY", "create_sub_category");
	define("GENERAL_TASK_ACTIVE_CATEGORY", "active_category");
	
	define("GENERAL_TASK_PUBLISHED_ARTICLES", "published_articles");
	define("GENERAL_TASK_UNPUBLISHED_ARTICLES", "unpublished_articles");
	define("GENERAL_TASK_WRITE_ARTICLE", "write_article");
	define("GENERAL_TASK_EDIT_ARTICLE", "edit_article");
	define("GENERAL_TASK_RELATED_LINKS", "related_links");
	define("GENERAL_TASK_SET_RELATED_ARTICLES", "set_related_articles");
	define("GENERAL_TASK_PREVIEW_ARTICLE", "preview_article");
	
	define("GENERAL_TASK_VIEW_TOP_STORIES", "view_top_stories");
	
	define("GENERAL_TASK_VIEW_SUBCATEGORY_GROUP", "view_subcategory_group");
	define("GENERAL_TASK_VIEW_SUBCATEGORY_GROUP_ARTICLES", "view_subcategory_group_articles");
	
	//editor task.
	
	
	//admin task.
	define("ADMIN_TASK_POLL_BOOT", "poll_boot");
	define("ADMIN_TASK_POLL_BOOT_SUBTASK_EDIT", "edit_poll");
	define("ADMIN_TASK_SET_TAB_INDEX", "set_tab_index");
	
	define("ADMIN_TASK_ADVERTS", "adverts");
	define("ADMIN_TASK_ADVERTS_SUB_UPLOAD", "upload_adverts");
	define("ADMIN_TASK_ADVERTS_SUB_VIEW", "view_adverts");
	define("ADMIN_TASK_ADVERTS_SUB_REGISTER_CLIENT", "register_clients");
	
	define("ADMIN_TASK_ADD_USER", "add_new_user");
	
	//subtask of task
	define("ADMIN_TASK_POLL_BOOT_POST", "post_poll");
	define("ADMIN_TASK_POLL_BOOT_LISTING", "list_poll");
	
	//the about us editing task
	define("ADMIN_TASK_ABOUT_US", "about_us");
	define("ADMIN_TASK_HOW_TO_PLACE_ADS", "how_to_place_ads");
	
	define("ADMIN_TASK_SECTION_INDEX_TEMPLATE", "set_index_template");
	define("ADMIN_TASK_SELECT_TEMPLATE", "select_template");
	define("ADMIN_TASK_SELECTED_TEMPLATE_SET_SECTIONS", "selected_template_set_sections");
	define("ADMIN_TASK_VIEW_CATEGORY_TEMPLATE", "view_category_template");
	
	define("ADMIN_TASK_SELECT_TEMPLATE_SUBCATEGORY", "select_template_subcategory");
	define("ADMIN_TASK_SUBCATEGORY_SELECTED_TEMPLATE_SET_SECTIONS", "subcategory_selected_template_set_sections");
	define("ADMIN_TASK_VIEW_SUBCATEGORY_TEMPLATE", "view_subcategory_template");
	define("ADMIN_TASK_CREATE_SUBCATEGORY_GROUP", "create_subcategory_group");
	
	define("ADMIN_TASK_MAINTENANCE", "maintenance");
?>